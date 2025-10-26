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
     * Get the ingredients for this recipe.
     */
    public function ingredientRelations(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Ingredient::class, 'recipe_ingredients')
            ->withPivot('quantity', 'unit', 'estimated_cost', 'notes')
            ->withTimestamps();
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

    /**
     * Calculate the total cost of the recipe based on current ingredient prices.
     *
     * @param string $regionCode Region code for regional pricing
     * @return float Total estimated cost
     */
    public function calculateTotalCost(string $regionCode = 'NCR'): float
    {
        $totalCost = 0.0;

        // Load ingredients with their pivot data
        $ingredients = $this->ingredientRelations;

        foreach ($ingredients as $ingredient) {
            $quantity = $ingredient->pivot->quantity;
            $price = $ingredient->getPriceForRegion($regionCode);

            // Fallback to estimated_cost if no price available
            if (!$price && $ingredient->pivot->estimated_cost) {
                $totalCost += $ingredient->pivot->estimated_cost;
            } elseif ($price) {
                // Calculate cost based on quantity and price per kg
                // Assuming unit is 'kg' - you might need unit conversion logic
                $totalCost += $quantity * $price;
            }
        }

        return round($totalCost, 2);
    }

    /**
     * Calculate cost per serving.
     *
     * @param string $regionCode Region code for regional pricing
     * @return float Cost per serving
     */
    public function calculateCostPerServing(string $regionCode = 'NCR'): float
    {
        $totalCost = $this->calculateTotalCost($regionCode);
        $servings = $this->servings > 0 ? $this->servings : 1;

        return round($totalCost / $servings, 2);
    }

    /**
     * Update estimated costs for all ingredients in this recipe.
     *
     * @param string $regionCode Region code for regional pricing
     * @return int Number of ingredients updated
     */
    public function updateIngredientCosts(string $regionCode = 'NCR'): int
    {
        $updated = 0;

        foreach ($this->ingredientRelations as $ingredient) {
            $quantity = $ingredient->pivot->quantity;
            $price = $ingredient->getPriceForRegion($regionCode);

            if ($price) {
                $estimatedCost = round($quantity * $price, 2);
                
                $this->ingredientRelations()->updateExistingPivot($ingredient->id, [
                    'estimated_cost' => $estimatedCost,
                ]);

                $updated++;
            }
        }

        return $updated;
    }
}
