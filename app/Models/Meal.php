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

        // Leverage Storage facade for public disk
        return asset('storage/' . ltrim($this->image_path, '/'));
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
}
