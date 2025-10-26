<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NutritionController extends Controller
{
    // Comprehensive nutrition database for common Filipino and international ingredients
    private $nutritionDatabase = [
        // Proteins - per 100g
        'chicken breast' => ['protein' => 31.0, 'carbs' => 0, 'fats' => 3.6, 'fiber' => 0, 'sugar' => 0, 'sodium' => 74],
        'chicken thigh' => ['protein' => 24.0, 'carbs' => 0, 'fats' => 10.0, 'fiber' => 0, 'sugar' => 0, 'sodium' => 82],
        'pork' => ['protein' => 27.0, 'carbs' => 0, 'fats' => 14.0, 'fiber' => 0, 'sugar' => 0, 'sodium' => 62],
        'beef' => ['protein' => 26.0, 'carbs' => 0, 'fats' => 15.0, 'fiber' => 0, 'sugar' => 0, 'sodium' => 72],
        'fish' => ['protein' => 22.0, 'carbs' => 0, 'fats' => 5.0, 'fiber' => 0, 'sugar' => 0, 'sodium' => 90],
        'tilapia' => ['protein' => 26.0, 'carbs' => 0, 'fats' => 2.7, 'fiber' => 0, 'sugar' => 0, 'sodium' => 52],
        'bangus' => ['protein' => 18.0, 'carbs' => 0, 'fats' => 12.0, 'fiber' => 0, 'sugar' => 0, 'sodium' => 78],
        'egg' => ['protein' => 13.0, 'carbs' => 1.1, 'fats' => 11.0, 'fiber' => 0, 'sugar' => 1.1, 'sodium' => 124],
        'tofu' => ['protein' => 8.0, 'carbs' => 1.9, 'fats' => 4.8, 'fiber' => 0.3, 'sugar' => 0.7, 'sodium' => 7],
        
        // Carbohydrates - per 100g
        'rice' => ['protein' => 2.7, 'carbs' => 28.0, 'fats' => 0.3, 'fiber' => 0.4, 'sugar' => 0.1, 'sodium' => 1],
        'brown rice' => ['protein' => 2.6, 'carbs' => 23.0, 'fats' => 0.9, 'fiber' => 1.8, 'sugar' => 0.4, 'sodium' => 5],
        'pasta' => ['protein' => 5.0, 'carbs' => 25.0, 'fats' => 0.9, 'fiber' => 1.8, 'sugar' => 0.6, 'sodium' => 6],
        'bread' => ['protein' => 9.0, 'carbs' => 49.0, 'fats' => 3.2, 'fiber' => 2.7, 'sugar' => 5.0, 'sodium' => 491],
        'potato' => ['protein' => 2.0, 'carbs' => 17.0, 'fats' => 0.1, 'fiber' => 2.2, 'sugar' => 0.8, 'sodium' => 6],
        'sweet potato' => ['protein' => 1.6, 'carbs' => 20.0, 'fats' => 0.1, 'fiber' => 3.0, 'sugar' => 4.2, 'sodium' => 55],
        'kamote' => ['protein' => 1.6, 'carbs' => 20.0, 'fats' => 0.1, 'fiber' => 3.0, 'sugar' => 4.2, 'sodium' => 55],
        
        // Vegetables - per 100g
        'broccoli' => ['protein' => 2.8, 'carbs' => 7.0, 'fats' => 0.4, 'fiber' => 2.6, 'sugar' => 1.7, 'sodium' => 33],
        'carrot' => ['protein' => 0.9, 'carbs' => 10.0, 'fats' => 0.2, 'fiber' => 2.8, 'sugar' => 4.7, 'sodium' => 69],
        'cabbage' => ['protein' => 1.3, 'carbs' => 6.0, 'fats' => 0.1, 'fiber' => 2.5, 'sugar' => 3.2, 'sodium' => 18],
        'spinach' => ['protein' => 2.9, 'carbs' => 3.6, 'fats' => 0.4, 'fiber' => 2.2, 'sugar' => 0.4, 'sodium' => 79],
        'tomato' => ['protein' => 0.9, 'carbs' => 3.9, 'fats' => 0.2, 'fiber' => 1.2, 'sugar' => 2.6, 'sodium' => 5],
        'onion' => ['protein' => 1.1, 'carbs' => 9.3, 'fats' => 0.1, 'fiber' => 1.7, 'sugar' => 4.2, 'sodium' => 4],
        'garlic' => ['protein' => 6.4, 'carbs' => 33.0, 'fats' => 0.5, 'fiber' => 2.1, 'sugar' => 1.0, 'sodium' => 17],
        'bell pepper' => ['protein' => 1.0, 'carbs' => 6.0, 'fats' => 0.3, 'fiber' => 2.1, 'sugar' => 4.2, 'sodium' => 4],
        'eggplant' => ['protein' => 1.0, 'carbs' => 6.0, 'fats' => 0.2, 'fiber' => 3.0, 'sugar' => 3.5, 'sodium' => 2],
        'talong' => ['protein' => 1.0, 'carbs' => 6.0, 'fats' => 0.2, 'fiber' => 3.0, 'sugar' => 3.5, 'sodium' => 2],
        
        // Fruits - per 100g
        'banana' => ['protein' => 1.1, 'carbs' => 23.0, 'fats' => 0.3, 'fiber' => 2.6, 'sugar' => 12.0, 'sodium' => 1],
        'saging' => ['protein' => 1.1, 'carbs' => 23.0, 'fats' => 0.3, 'fiber' => 2.6, 'sugar' => 12.0, 'sodium' => 1],
        'mango' => ['protein' => 0.8, 'carbs' => 15.0, 'fats' => 0.4, 'fiber' => 1.6, 'sugar' => 13.7, 'sodium' => 1],
        'apple' => ['protein' => 0.3, 'carbs' => 14.0, 'fats' => 0.2, 'fiber' => 2.4, 'sugar' => 10.0, 'sodium' => 1],
        'orange' => ['protein' => 0.9, 'carbs' => 12.0, 'fats' => 0.1, 'fiber' => 2.4, 'sugar' => 9.0, 'sodium' => 0],
        
        // Condiments & Others - per 100g
        'soy sauce' => ['protein' => 10.0, 'carbs' => 8.0, 'fats' => 0.1, 'fiber' => 0.8, 'sugar' => 1.7, 'sodium' => 5586],
        'oil' => ['protein' => 0, 'carbs' => 0, 'fats' => 100.0, 'fiber' => 0, 'sugar' => 0, 'sodium' => 0],
        'olive oil' => ['protein' => 0, 'carbs' => 0, 'fats' => 100.0, 'fiber' => 0, 'sugar' => 0, 'sodium' => 2],
        'coconut milk' => ['protein' => 2.3, 'carbs' => 6.0, 'fats' => 24.0, 'fiber' => 2.2, 'sugar' => 3.3, 'sodium' => 15],
        'gata' => ['protein' => 2.3, 'carbs' => 6.0, 'fats' => 24.0, 'fiber' => 2.2, 'sugar' => 3.3, 'sodium' => 15],
        'milk' => ['protein' => 3.4, 'carbs' => 5.0, 'fats' => 3.3, 'fiber' => 0, 'sugar' => 5.0, 'sodium' => 44],
        'cheese' => ['protein' => 25.0, 'carbs' => 1.3, 'fats' => 33.0, 'fiber' => 0, 'sugar' => 0.5, 'sodium' => 621],
        'butter' => ['protein' => 0.9, 'carbs' => 0.1, 'fats' => 81.0, 'fiber' => 0, 'sugar' => 0.1, 'sodium' => 714],
    ];

    /**
     * Calculate nutrition from ingredients
     */
    public function calculate(Request $request)
    {
        try {
            $validated = $request->validate([
                'ingredients' => 'required|array|min:1',
                'ingredients.*.name' => 'required|string',
                'ingredients.*.quantity' => 'nullable|numeric|min:0',
                'ingredients.*.unit' => 'nullable|string',
                'servings' => 'required|integer|min:1|max:100'
            ]);

            Log::info('Nutrition calculation request', [
                'ingredients_count' => count($validated['ingredients']),
                'servings' => $validated['servings']
            ]);

            $nutrition = $this->calculateFromIngredients(
                $validated['ingredients'],
                $validated['servings']
            );

            return response()->json([
                'success' => true,
                'nutrition' => $nutrition,
                'message' => 'Nutrition calculated successfully',
                'servings' => $validated['servings']
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid input data',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Nutrition calculation error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to calculate nutrition',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Calculate nutrition from ingredients array
     */
    private function calculateFromIngredients(array $ingredients, int $servings): array
    {
        $total = [
            'protein' => 0,
            'carbs' => 0,
            'fats' => 0,
            'fiber' => 0,
            'sugar' => 0,
            'sodium' => 0
        ];

        $matchedIngredients = 0;
        $totalIngredients = count($ingredients);

        foreach ($ingredients as $ingredient) {
            $name = strtolower(trim($ingredient['name']));
            $quantity = floatval($ingredient['quantity'] ?? 100);
            $unit = strtolower(trim($ingredient['unit'] ?? 'g'));

            // Convert to grams if needed
            $quantityInGrams = $this->convertToGrams($quantity, $unit);

            // Try to find nutrition data
            $nutritionData = $this->findNutritionData($name);

            if ($nutritionData) {
                $matchedIngredients++;
                
                // Calculate nutrition based on quantity (nutrition data is per 100g)
                $multiplier = $quantityInGrams / 100;

                foreach ($nutritionData as $nutrient => $value) {
                    $total[$nutrient] += $value * $multiplier;
                }

                Log::debug("Matched ingredient: {$name}", [
                    'quantity' => $quantityInGrams,
                    'multiplier' => $multiplier,
                    'nutrition' => $nutritionData
                ]);
            } else {
                Log::warning("Ingredient not found in database: {$name}");
            }
        }

        // Divide by servings to get per-serving values
        $perServing = array_map(function($value) use ($servings) {
            return round($value / $servings, 1);
        }, $total);

        Log::info('Nutrition calculation completed', [
            'matched' => $matchedIngredients,
            'total' => $totalIngredients,
            'servings' => $servings,
            'result' => $perServing
        ]);

        return $perServing;
    }

    /**
     * Find nutrition data for an ingredient
     */
    private function findNutritionData(string $name): ?array
    {
        // Direct match
        if (isset($this->nutritionDatabase[$name])) {
            return $this->nutritionDatabase[$name];
        }

        // Fuzzy matching - check if ingredient contains key words
        foreach ($this->nutritionDatabase as $key => $data) {
            if (str_contains($name, $key) || str_contains($key, $name)) {
                return $data;
            }
        }

        // No match found
        return null;
    }

    /**
     * Convert various units to grams
     */
    private function convertToGrams(float $quantity, string $unit): float
    {
        $conversions = [
            'g' => 1,
            'kg' => 1000,
            'mg' => 0.001,
            'ml' => 1, // Assuming water density (1ml = 1g)
            'l' => 1000,
            'cup' => 240, // Standard US cup
            'tbsp' => 15,
            'tsp' => 5,
            'tablespoon' => 15,
            'teaspoon' => 5,
            'oz' => 28.35,
            'lb' => 453.59,
            'piece' => 100, // Estimated average
            'slice' => 30, // Estimated average
            'clove' => 3, // For garlic
        ];

        $unit = strtolower($unit);
        
        if (isset($conversions[$unit])) {
            return $quantity * $conversions[$unit];
        }

        // Default to quantity as-is if unit not recognized
        return $quantity;
    }

    /**
     * Get available ingredients (for autocomplete/reference)
     */
    public function getIngredients()
    {
        $ingredients = array_map(function($key) {
            return [
                'name' => ucfirst($key),
                'key' => $key
            ];
        }, array_keys($this->nutritionDatabase));

        return response()->json([
            'success' => true,
            'ingredients' => array_values($ingredients),
            'count' => count($ingredients)
        ]);
    }
}
