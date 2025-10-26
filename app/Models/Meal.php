<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Meal extends Model
{
    /** @use HasFactory<\Database\Factories\MealFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'calories',
        'cost',
        'cuisine_type',
        'difficulty',
        'image_path',
        'is_featured',
    ];

    protected function casts(): array
    {
        return [
            'cost' => 'decimal:2',
            'is_featured' => 'boolean',
        ];
    }

    /**
     * Get the public URL for the meal image if stored on the public disk.
     */
    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image_path) {
            return null;
        }

        // If already a full URL (external or storage symlink resolved), return as-is
        if (str_starts_with($this->image_path, 'http://') || str_starts_with($this->image_path, 'https://')) {
            return $this->image_path;
        }

        // Check if file exists before generating URL
        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($this->image_path)) {
            // Use current request host to ensure correct URL generation
            $currentHost = request()->getHost();
            $currentPort = request()->getPort();
            $scheme = request()->getScheme();
            
            if ($currentHost && ($currentHost === '127.0.0.1' || $currentHost === 'localhost')) {
                $baseUrl = $scheme . '://' . $currentHost;
                if ($currentPort && $currentPort != 80 && $currentPort != 443) {
                    $baseUrl .= ':' . $currentPort;
                }
                return $baseUrl . '/storage/' . $this->image_path;
            }
            
            // Fallback to config URL
            $baseUrl = config('app.url');
            if ($baseUrl === 'http://localhost') {
                $baseUrl = 'http://localhost:8000';
            }
            return $baseUrl . '/storage/' . $this->image_path;
        }

        return null;
    }

    /**
     * Get the recipe for the meal.
     */
    public function recipe(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Recipe::class);
    }

    /**
     * Get the nutritional information for the meal.
     */
    public function nutritionalInfo(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(NutritionalInfo::class);
    }

    /**
     * Get the meal plans that include this meal.
     */
    public function mealPlans(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(MealPlan::class);
    }

    /**
     * Scope to get Filipino cuisine meals.
     */
    public function scopeFilipino($query)
    {
        return $query->where('cuisine_type', 'Filipino');
    }

    /**
     * Scope to get meals within budget.
     */
    public function scopeWithinBudget($query, float $budget)
    {
        return $query->where('cost', '<=', $budget);
    }

    /**
     * Scope to get meals by difficulty.
     */
    public function scopeByDifficulty($query, string $difficulty)
    {
        return $query->where('difficulty', $difficulty);
    }

    /**
     * Scope to get featured meals.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Get the cooking time from the related recipe.
     */
    public function getCookingTimeAttribute(): int
    {
        return $this->recipe ? $this->recipe->total_time : 0;
    }

    /**
     * Get the servings from the related recipe.
     */
    public function getServingsAttribute(): int
    {
        return $this->recipe ? $this->recipe->servings : 1;
    }
    
    /**
     * Get calculated cost based on current ingredient prices.
     *
     * @param string $regionCode Region code for pricing
     * @return float|null Calculated cost or null if no recipe/ingredients
     */
    public function getCalculatedCost(string $regionCode = 'NCR'): ?float
    {
        if (!$this->recipe) {
            return null;
        }

        return $this->recipe->calculateTotalCost($regionCode);
    }

    /**
     * Get cost per serving based on current ingredient prices.
     *
     * @param string $regionCode Region code for pricing
     * @return float|null Cost per serving or null if no recipe
     */
    public function getCalculatedCostPerServing(string $regionCode = 'NCR'): ?float
    {
        if (!$this->recipe) {
            return null;
        }

        return $this->recipe->calculateCostPerServing($regionCode);
    }

    /**
     * Get the cost to display (calculated or fallback to static cost).
     *
     * @param string $regionCode Region code for pricing
     * @return float Display cost
     */
    public function getDisplayCost(string $regionCode = 'NCR'): float
    {
        $calculatedCost = $this->getCalculatedCostPerServing($regionCode);
        
        // Fall back to static cost if calculation not available or returns 0
        if ($calculatedCost === null || $calculatedCost <= 0) {
            return floatval($this->cost ?? 0);
        }
        
        return $calculatedCost;
    }

    /**
     * Check if meal has real-time pricing available.
     *
     * @param string $regionCode Region code for pricing
     * @return bool
     */
    public function hasRealTimePricing(string $regionCode = 'NCR'): bool
    {
        if (!$this->recipe || !$this->recipe->ingredientRelations()->exists()) {
            return false;
        }
        
        // Check if any ingredient has recent price data for the region
        return $this->recipe->ingredientRelations()
            ->whereHas('priceHistory', function($query) use ($regionCode) {
                $query->where('region_code', $regionCode)
                    ->where('recorded_at', '>=', now()->subDays(7)); // Price is recent (last 7 days)
            })
            ->exists();
    }
    
    /**
     * Get adjusted calories for a specific user based on their BMI.
     */
    public function getAdjustedCaloriesForUser(User $user): int
    {
        $baseCalories = $this->nutritionalInfo->calories ?? $this->calories ?? 0;
        return $user->getAdjustedMealCalories($baseCalories);
    }
    
    /**
     * Get adjusted serving size for a specific user based on their BMI.
     */
    public function getAdjustedServingForUser(User $user): float
    {
        $multiplier = $user->getCalorieMultiplier();
        $baseServing = $this->getServingsAttribute();
        return round($baseServing * $multiplier, 1);
    }
    
    /**
     * Get meal information adjusted for a specific user's BMI.
     */
    public function getPersonalizedMealInfo(User $user): array
    {
        $baseCalories = $this->nutritionalInfo->calories ?? $this->calories ?? 0;
        $adjustedCalories = $this->getAdjustedCaloriesForUser($user);
        $adjustedServing = $this->getAdjustedServingForUser($user);
        $bmiStatus = $user->getBMIStatus();
        
        return [
            'original_calories' => $baseCalories,
            'adjusted_calories' => $adjustedCalories,
            'calorie_difference' => $adjustedCalories - $baseCalories,
            'original_serving' => $this->getServingsAttribute(),
            'adjusted_serving' => $adjustedServing,
            'serving_multiplier' => $user->getCalorieMultiplier(),
            'bmi_category' => $bmiStatus['category'],
            'recommendation' => $this->getBMIRecommendationText($bmiStatus['category'])
        ];
    }
    
    /**
     * Get BMI-specific recommendation text for this meal.
     */
    private function getBMIRecommendationText(string $bmiCategory): string
    {
        return match($bmiCategory) {
            'underweight' => 'Larger portion recommended for healthy weight gain.',
            'normal' => 'Standard portion size for maintenance.',
            'overweight' => 'Reduced portion recommended for gradual weight loss.',
            'obese' => 'Smaller portion strongly recommended for weight management.',
            default => 'Standard portion size.'
        };
    }
    
    /**
     * Scope to get meals suitable for a user's BMI category.
     */
    public function scopeForBMICategory($query, string $bmiCategory)
    {
        // Adjust meal selection based on BMI category
        return match($bmiCategory) {
            'underweight' => $query->whereHas('nutritionalInfo', function($q) {
                $q->where('calories', '>=', 400); // Higher calorie meals
            }),
            'obese' => $query->whereHas('nutritionalInfo', function($q) {
                $q->where('calories', '<=', 350); // Lower calorie meals
            }),
            'overweight' => $query->whereHas('nutritionalInfo', function($q) {
                $q->where('calories', '<=', 400); // Moderate calorie meals
            }),
            default => $query // Normal weight - all meals
        };
    }
}
