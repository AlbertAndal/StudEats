<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    /** @use HasFactory<\Database\Factories\RecipeFactory> */
    use HasFactory;

    protected $fillable = [
        'meal_id',
        'ingredients',
        'instructions',
        'prep_time',
        'cook_time',
        'servings',
        'local_alternatives',
    ];

    protected function casts(): array
    {
        return [
            'ingredients' => 'array',
            'local_alternatives' => 'array',
        ];
    }

    /**
     * Get the meal that owns the recipe.
     */
    public function meal(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Meal::class);
    }

    /**
     * Get the total cooking time.
     */
    public function getTotalTimeAttribute(): int
    {
        return $this->prep_time + $this->cook_time;
    }

    /**
     * Get the cooking time (alias for total_time for compatibility).
     */
    public function getCookingTimeAttribute(): int
    {
        return $this->getTotalTimeAttribute();
    }

    /**
     * Get formatted cooking instructions as steps.
     */
    public function getFormattedInstructionsAttribute(): array
    {
        return explode("\n", $this->instructions);
    }
}
