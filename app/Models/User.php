<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property string|null $timezone
 *
 * @method \Illuminate\Database\Eloquent\Relations\HasMany mealPlans()
 * @method \Illuminate\Database\Eloquent\Relations\HasMany mealPlansForDate(string $date)
 * @method \Illuminate\Database\Eloquent\Relations\HasMany weeklyMealPlans(\Carbon\Carbon $startDate)
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'dietary_preferences',
        'daily_budget',
        'age',
        'gender',
        'activity_level',
        'height',
        'height_unit',
        'weight',
        'weight_unit',
        'role',
        'is_active',
        'suspended_at',
        'suspended_reason',
        'timezone',
        'profile_photo',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'dietary_preferences' => 'array',
            'daily_budget' => 'decimal:2',
            'is_active' => 'boolean',
            'suspended_at' => 'datetime',
        ];
    }

    /**
     * Get the meal plans for the user.
     */
    public function mealPlans(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(MealPlan::class);
    }

    /**
     * Get the user's meal plans for a specific date.
     */
    public function mealPlansForDate(string $date): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->mealPlans()->where('scheduled_date', $date);
    }

    /**
     * Get the user's weekly meal plans.
     */
    public function weeklyMealPlans(\Carbon\Carbon $startDate): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->mealPlans()
            ->whereBetween('scheduled_date', [
                $startDate->format('Y-m-d'),
                $startDate->copy()->addDays(6)->format('Y-m-d'),
            ])
            ->with(['meal.nutritionalInfo']);
    }

    /**
     * Check if user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is a super admin.
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    /**
     * Suspend the user account.
     */
    public function suspend(?string $reason = null): void
    {
        $this->update([
            'is_active' => false,
            'suspended_at' => now(),
            'suspended_reason' => $reason,
        ]);
    }

    /**
     * Activate the user account.
     */
    public function activate(): void
    {
        $this->update([
            'is_active' => true,
            'suspended_at' => null,
            'suspended_reason' => null,
        ]);
    }

    /**
     * Get admin logs performed by this user.
     */
    public function adminLogs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(AdminLog::class, 'admin_user_id');
    }

    /**
     * Get current time in user's timezone.
     */
    public function getCurrentTimeInTimezone(): \Carbon\Carbon
    {
        $timezone = $this->timezone ?? config('app.timezone');

        return now()->setTimezone($timezone);
    }

    /**
     * Get current date in user's timezone.
     */
    public function getCurrentDateInTimezone(): string
    {
        return $this->getCurrentTimeInTimezone()->toDateString();
    }

    /**
     * Calculate user's BMI based on height and weight.
     */
    public function calculateBMI(): ?float
    {
        if (! $this->height || ! $this->weight) {
            return null;
        }

        // Convert height to meters
        $heightInMeters = $this->height_unit === 'ft'
            ? $this->height * 0.3048  // feet to meters
            : $this->height / 100;    // cm to meters

        // Convert weight to kg
        $weightInKg = $this->weight_unit === 'lbs'
            ? $this->weight * 0.453592  // pounds to kg
            : $this->weight;           // already in kg

        return round($weightInKg / ($heightInMeters * $heightInMeters), 1);
    }

    /**
     * Get BMI category based on calculated BMI.
     */
    public function getBMICategory(): string
    {
        $bmi = $this->calculateBMI();

        if ($bmi === null) {
            return 'unknown';
        }

        if ($bmi < 18.5) {
            return 'underweight';
        } elseif ($bmi >= 18.5 && $bmi < 25) {
            return 'normal';
        } elseif ($bmi >= 25 && $bmi < 30) {
            return 'overweight';
        } else {
            return 'obese';
        }
    }

    /**
     * Get calorie adjustment multiplier based on BMI category.
     */
    public function getCalorieMultiplier(): float
    {
        $category = $this->getBMICategory();

        return match ($category) {
            'underweight' => 1.3,  // 30% more calories
            'normal' => 1.0,       // standard calories
            'overweight' => 0.85,  // 15% fewer calories
            'obese' => 0.7,        // 30% fewer calories
            default => 1.0
        };
    }

    /**
     * Get recommended daily calorie intake based on BMI and other factors.
     */
    public function getRecommendedDailyCalories(): int
    {
        // Base calorie calculation using Mifflin-St Jeor Equation
        $age = (int) $this->age;
        $weightInKg = $this->weight_unit === 'lbs' ? $this->weight * 0.453592 : $this->weight;
        $heightInCm = $this->height_unit === 'ft' ? $this->height * 30.48 : $this->height;

        if ($this->gender === 'male') {
            $bmr = (10 * $weightInKg) + (6.25 * $heightInCm) - (5 * $age) + 5;
        } else {
            $bmr = (10 * $weightInKg) + (6.25 * $heightInCm) - (5 * $age) - 161;
        }

        // Activity level multipliers
        $activityMultiplier = match ($this->activity_level) {
            'sedentary' => 1.2,
            'lightly_active' => 1.375,
            'moderately_active' => 1.55,
            'very_active' => 1.725,
            'extra_active' => 1.9,
            default => 1.375
        };

        $dailyCalories = $bmr * $activityMultiplier;

        // Apply BMI-based adjustment
        $bmiMultiplier = $this->getCalorieMultiplier();

        return round($dailyCalories * $bmiMultiplier);
    }

    /**
     * Get adjusted calories for a specific meal.
     */
    public function getAdjustedMealCalories(int $originalCalories): int
    {
        $multiplier = $this->getCalorieMultiplier();

        return round($originalCalories * $multiplier);
    }

    /**
     * Get BMI status information for display.
     */
    public function getBMIStatus(): array
    {
        $bmi = $this->calculateBMI();
        $category = $this->getBMICategory();

        $statusColors = [
            'underweight' => ['bg-blue-100', 'text-blue-800', 'border-blue-200'],
            'normal' => ['bg-green-100', 'text-green-800', 'border-green-200'],
            'overweight' => ['bg-yellow-100', 'text-yellow-800', 'border-yellow-200'],
            'obese' => ['bg-red-100', 'text-red-800', 'border-red-200'],
            'unknown' => ['bg-gray-100', 'text-gray-800', 'border-gray-200'],
        ];

        $recommendations = [
            'underweight' => 'Higher calorie meals recommended for healthy weight gain.',
            'normal' => 'Maintain current healthy eating habits.',
            'overweight' => 'Reduced calorie meals recommended for gradual weight loss.',
            'obese' => 'Lower calorie meals strongly recommended for weight management.',
            'unknown' => 'Please update your height and weight for personalized recommendations.',
        ];

        return [
            'bmi' => $bmi,
            'category' => $category,
            'category_label' => ucfirst(str_replace('_', ' ', $category)),
            'colors' => $statusColors[$category],
            'recommendation' => $recommendations[$category],
            'calorie_multiplier' => $this->getCalorieMultiplier(),
            'daily_calories' => $this->getRecommendedDailyCalories(),
        ];
    }

    /**
     * Get the user's profile photo URL.
     */
    public function getProfilePhotoUrlAttribute(): ?string
    {
        if (!$this->profile_photo) {
            return null;
        }

        // If it's already a full URL (for external images)
        if (str_starts_with($this->profile_photo, 'http')) {
            return $this->profile_photo;
        }

        // For local storage
        return asset('storage/' . $this->profile_photo);
    }

    /**
     * Get profile photo or default avatar.
     */
    public function getAvatarUrl(): string
    {
        return $this->getProfilePhotoUrlAttribute() ?? 
               'https://ui-avatars.com/api/?name=' . urlencode($this->name) . 
               '&color=ffffff&background=10b981&size=200&font-size=0.6&bold=true';
    }

    /**
     * Check if user has a profile photo.
     */
    public function hasProfilePhoto(): bool
    {
        return !empty($this->profile_photo);
    }

    /**
     * Delete the user's profile photo from storage.
     */
    public function deleteProfilePhoto(): bool
    {
        if (!$this->profile_photo) {
            return true;
        }

        // Don't delete external URLs
        if (str_starts_with($this->profile_photo, 'http')) {
            $this->update(['profile_photo' => null]);
            return true;
        }

        $path = storage_path('app/public/' . $this->profile_photo);
        
        if (file_exists($path)) {
            unlink($path);
        }

        $this->update(['profile_photo' => null]);
        return true;
    }

    /**
     * Get comprehensive dietary preference configuration.
     */
    public static function getDietaryPreferenceConfig(): array
    {
        return [
            // Diet Types
            'vegetarian' => [
                'label' => 'Vegetarian',
                'icon' => '🥬',
                'description' => 'No meat, fish, or poultry',
                'category' => 'Diet Types',
                'color' => 'bg-green-100 text-green-800 border-green-200'
            ],
            'vegan' => [
                'label' => 'Vegan',
                'icon' => '🌱',
                'description' => 'No animal products',
                'category' => 'Diet Types',
                'color' => 'bg-emerald-100 text-emerald-800 border-emerald-200'
            ],
            'pescatarian' => [
                'label' => 'Pescatarian',
                'icon' => '🐟',
                'description' => 'Fish but no meat',
                'category' => 'Diet Types',
                'color' => 'bg-teal-100 text-teal-800 border-teal-200'
            ],
            'keto' => [
                'label' => 'Keto',
                'icon' => '🥑',
                'description' => 'Very low carb, high fat',
                'category' => 'Diet Types',
                'color' => 'bg-indigo-100 text-indigo-800 border-indigo-200'
            ],
            'paleo' => [
                'label' => 'Paleo',
                'icon' => '🥩',
                'description' => 'Whole foods, no processed',
                'category' => 'Diet Types',
                'color' => 'bg-amber-100 text-amber-800 border-amber-200'
            ],
            'mediterranean' => [
                'label' => 'Mediterranean',
                'icon' => '🫒',
                'description' => 'Heart-healthy, olive oil based',
                'category' => 'Diet Types',
                'color' => 'bg-cyan-100 text-cyan-800 border-cyan-200'
            ],
            
            // Food Restrictions
            'gluten_free' => [
                'label' => 'Gluten Free',
                'icon' => '🌾',
                'description' => 'No wheat, barley, rye',
                'category' => 'Restrictions',
                'color' => 'bg-yellow-100 text-yellow-800 border-yellow-200'
            ],
            'dairy_free' => [
                'label' => 'Dairy Free',
                'icon' => '🥛',
                'description' => 'No milk products',
                'category' => 'Restrictions',
                'color' => 'bg-blue-100 text-blue-800 border-blue-200'
            ],
            'nut_free' => [
                'label' => 'Nut Free',
                'icon' => '🥜',
                'description' => 'No tree nuts or peanuts',
                'category' => 'Restrictions',
                'color' => 'bg-orange-100 text-orange-800 border-orange-200'
            ],
            'shellfish_free' => [
                'label' => 'Shellfish Free',
                'icon' => '🦐',
                'description' => 'No shellfish',
                'category' => 'Restrictions',
                'color' => 'bg-red-100 text-red-800 border-red-200'
            ],
            'soy_free' => [
                'label' => 'Soy Free',
                'icon' => '🫘',
                'description' => 'No soy products',
                'category' => 'Restrictions',
                'color' => 'bg-lime-100 text-lime-800 border-lime-200'
            ],
            'egg_free' => [
                'label' => 'Egg Free',
                'icon' => '🥚',
                'description' => 'No eggs or egg products',
                'category' => 'Restrictions',
                'color' => 'bg-rose-100 text-rose-800 border-rose-200'
            ],
            
            // Nutritional Goals
            'low_carb' => [
                'label' => 'Low Carb',
                'icon' => '⚡',
                'description' => 'Reduced carbohydrates',
                'category' => 'Goals',
                'color' => 'bg-purple-100 text-purple-800 border-purple-200'
            ],
            'high_protein' => [
                'label' => 'High Protein',
                'icon' => '💪',
                'description' => 'Extra protein for fitness',
                'category' => 'Goals',
                'color' => 'bg-pink-100 text-pink-800 border-pink-200'
            ],
            'low_sodium' => [
                'label' => 'Low Sodium',
                'icon' => '🧂',
                'description' => 'Reduced salt intake',
                'category' => 'Goals',
                'color' => 'bg-slate-100 text-slate-800 border-slate-200'
            ],
            'heart_healthy' => [
                'label' => 'Heart Healthy',
                'icon' => '❤️',
                'description' => 'Good for cardiovascular health',
                'category' => 'Goals',
                'color' => 'bg-red-100 text-red-800 border-red-200'
            ],
            'diabetic_friendly' => [
                'label' => 'Diabetic Friendly',
                'icon' => '🩺',
                'description' => 'Low glycemic index',
                'category' => 'Goals',
                'color' => 'bg-violet-100 text-violet-800 border-violet-200'
            ],
            'weight_loss' => [
                'label' => 'Weight Loss',
                'icon' => '📉',
                'description' => 'Calorie-controlled portions',
                'category' => 'Goals',
                'color' => 'bg-sky-100 text-sky-800 border-sky-200'
            ],
        ];
    }

    /**
     * Get formatted dietary preferences with configuration.
     */
    public function getFormattedDietaryPreferences(): array
    {
        if (!$this->dietary_preferences || empty($this->dietary_preferences)) {
            return [];
        }

        $config = self::getDietaryPreferenceConfig();
        $formatted = [];

        foreach ($this->dietary_preferences as $preference) {
            $formatted[] = $config[$preference] ?? [
                'label' => ucfirst(str_replace('_', ' ', $preference)),
                'icon' => '🍽️',
                'description' => 'Diet preference',
                'category' => 'Other',
                'color' => 'bg-gray-100 text-gray-800 border-gray-200'
            ];
        }

        return $formatted;
    }

    /**
     * Get dietary preferences grouped by category.
     */
    public function getGroupedDietaryPreferences(): array
    {
        $formatted = $this->getFormattedDietaryPreferences();
        $grouped = [];

        foreach ($formatted as $preference) {
            $category = $preference['category'];
            if (!isset($grouped[$category])) {
                $grouped[$category] = [];
            }
            $grouped[$category][] = $preference;
        }

        return $grouped;
    }

    /**
     * Check if user has specific dietary preference.
     */
    public function hasDietaryPreference(string $preference): bool
    {
        return in_array($preference, $this->dietary_preferences ?? []);
    }

    /**
     * Add dietary preference if not already present.
     */
    public function addDietaryPreference(string $preference): bool
    {
        $preferences = $this->dietary_preferences ?? [];
        
        if (!in_array($preference, $preferences)) {
            $preferences[] = $preference;
            $this->update(['dietary_preferences' => $preferences]);
            return true;
        }
        
        return false;
    }

    /**
     * Remove dietary preference if present.
     */
    public function removeDietaryPreference(string $preference): bool
    {
        $preferences = $this->dietary_preferences ?? [];
        $key = array_search($preference, $preferences);
        
        if ($key !== false) {
            unset($preferences[$key]);
            $this->update(['dietary_preferences' => array_values($preferences)]);
            return true;
        }
        
        return false;
    }

    /**
     * Get dietary preference summary for display.
     */
    public function getDietaryPreferenceSummary(): array
    {
        $preferences = $this->dietary_preferences ?? [];
        $count = count($preferences);
        $config = self::getDietaryPreferenceConfig();
        
        $summary = [
            'count' => $count,
            'has_preferences' => $count > 0,
            'display_preferences' => [],
            'categories' => []
        ];

        if ($count > 0) {
            // Get first 3 preferences for display
            $displayPrefs = array_slice($preferences, 0, 3);
            foreach ($displayPrefs as $pref) {
                if (isset($config[$pref])) {
                    $summary['display_preferences'][] = [
                        'key' => $pref,
                        'label' => $config[$pref]['label'],
                        'icon' => $config[$pref]['icon']
                    ];
                }
            }

            // Count by category
            foreach ($preferences as $pref) {
                if (isset($config[$pref])) {
                    $category = $config[$pref]['category'];
                    if (!isset($summary['categories'][$category])) {
                        $summary['categories'][$category] = 0;
                    }
                    $summary['categories'][$category]++;
                }
            }
        }

        return $summary;
    }
}
