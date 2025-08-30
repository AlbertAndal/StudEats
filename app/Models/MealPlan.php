<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MealPlan extends Model
{
    /** @use HasFactory<\Database\Factories\MealPlanFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'meal_id',
        'scheduled_date',
        'meal_type',
        'is_completed',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_date' => 'date',
            'is_completed' => 'boolean',
        ];
    }

    /**
     * Get the user that owns the meal plan.
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the meal for this plan.
     */
    public function meal(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Meal::class);
    }

    /**
     * Scope to get plans for a specific date.
     */
    public function scopeForDate($query, string $date)
    {
        return $query->where('scheduled_date', $date);
    }

    /**
     * Scope to get plans for a specific meal type.
     */
    public function scopeForMealType($query, string $mealType)
    {
        return $query->where('meal_type', $mealType);
    }

    /**
     * Scope to get completed meals.
     */
    public function scopeCompleted($query)
    {
        return $query->where('is_completed', true);
    }
}
