<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NutritionalInfo extends Model
{
    /** @use HasFactory<\Database\Factories\NutritionalInfoFactory> */
    use HasFactory;

    protected $table = 'nutritional_info';

    protected $fillable = [
        'meal_id',
        'calories',
        'protein',
        'carbs',
        'fats',
        'fiber',
        'sugar',
        'sodium',
    ];

    protected function casts(): array
    {
        return [
            'calories' => 'decimal:2',
            'protein' => 'decimal:2',
            'carbs' => 'decimal:2',
            'fats' => 'decimal:2',
            'fiber' => 'decimal:2',
            'sugar' => 'decimal:2',
            'sodium' => 'decimal:2',
        ];
    }

    /**
     * Get the meal that owns the nutritional info.
     */
    public function meal(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Meal::class);
    }

    /**
     * Calculate total macronutrients in grams.
     */
    public function getTotalMacronutrientsAttribute(): float
    {
        return $this->protein + $this->carbs + $this->fats;
    }

    /**
     * Get protein percentage of total calories.
     */
    public function getProteinPercentageAttribute(): float
    {
        if ($this->calories == 0) return 0;
        return round(($this->protein * 4 / $this->calories) * 100, 1);
    }

    /**
     * Get carbs percentage of total calories.
     */
    public function getCarbsPercentageAttribute(): float
    {
        if ($this->calories == 0) return 0;
        return round(($this->carbs * 4 / $this->calories) * 100, 1);
    }

    /**
     * Get fats percentage of total calories.
     */
    public function getFatsPercentageAttribute(): float
    {
        if ($this->calories == 0) return 0;
        return round(($this->fats * 9 / $this->calories) * 100, 1);
    }
}
