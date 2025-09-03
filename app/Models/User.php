<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property string|null $timezone
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
                $startDate->copy()->addDays(6)->format('Y-m-d')
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
    public function suspend(string $reason = null): void
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
        if (!$this->height || !$this->weight) {
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
        
        return match($category) {
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
        $activityMultiplier = match($this->activity_level) {
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
            'unknown' => ['bg-gray-100', 'text-gray-800', 'border-gray-200']
        ];
        
        $recommendations = [
            'underweight' => 'Higher calorie meals recommended for healthy weight gain.',
            'normal' => 'Maintain current healthy eating habits.',
            'overweight' => 'Reduced calorie meals recommended for gradual weight loss.',
            'obese' => 'Lower calorie meals strongly recommended for weight management.',
            'unknown' => 'Please update your height and weight for personalized recommendations.'
        ];
        
        return [
            'bmi' => $bmi,
            'category' => $category,
            'category_label' => ucfirst(str_replace('_', ' ', $category)),
            'colors' => $statusColors[$category],
            'recommendation' => $recommendations[$category],
            'calorie_multiplier' => $this->getCalorieMultiplier(),
            'daily_calories' => $this->getRecommendedDailyCalories()
        ];
    }
}
