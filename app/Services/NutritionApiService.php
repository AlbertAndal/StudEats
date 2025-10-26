<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class NutritionApiService
{
    /**
     * API Configuration
     * Replace with your actual nutrition API credentials
     * Recommended APIs:
     * - USDA FoodData Central API: https://fdc.nal.usda.gov/api-guide.html
     * - Edamam Nutrition API: https://developer.edamam.com/
     * - Nutritionix API: https://www.nutritionix.com/business/api
     */
    private $apiUrl;
    private $apiKey;
    
    public function __construct()
    {
        // Configure your nutrition API credentials
        // Example for USDA FoodData Central API
        $this->apiUrl = env('NUTRITION_API_URL', 'https://api.nal.usda.gov/fdc/v1');
        $this->apiKey = env('NUTRITION_API_KEY', 'YOUR_API_KEY_HERE');
    }
    
    /**
     * Search for a food item in the nutrition database
     * 
     * @param string $foodName The name of the ingredient
     * @return array|null Food data or null if not found
     */
    public function searchFood($foodName)
    {
        try {
            $cacheKey = 'nutrition_search_' . md5($foodName);
            
            return Cache::remember($cacheKey, 3600, function () use ($foodName) {
                $response = Http::timeout(30)->retry(3, 2000)->get("{$this->apiUrl}/foods/search", [
                    'api_key' => $this->apiKey,
                    'query' => $foodName,
                    'pageSize' => 1,
                    'dataType' => ['Foundation', 'SR Legacy'],
                ]);
                
                if ($response->successful()) {
                    $data = $response->json();
                    
                    if (isset($data['foods']) && count($data['foods']) > 0) {
                        return $data['foods'][0];
                    }
                }
                
                return null;
            });
        } catch (\Exception $e) {
            Log::error('Nutrition API search error: ' . $e->getMessage(), [
                'food_name' => $foodName,
                'error_type' => get_class($e)
            ]);
            
            // Check if it's a timeout error
            if (str_contains($e->getMessage(), 'timeout') || str_contains($e->getMessage(), 'timed out')) {
                Log::warning('Nutrition API timeout for food search: ' . $foodName);
            }
            
            return null;
        }
    }
    
    /**
     * Get detailed nutrition information for a specific food item
     * 
     * @param int $fdcId FoodData Central ID
     * @return array|null Nutrition data or null
     */
    public function getFoodDetails($fdcId)
    {
        try {
            $cacheKey = 'nutrition_details_' . $fdcId;
            
            return Cache::remember($cacheKey, 3600, function () use ($fdcId) {
                $response = Http::timeout(30)->retry(3, 2000)->get("{$this->apiUrl}/food/{$fdcId}", [
                    'api_key' => $this->apiKey,
                ]);
                
                if ($response->successful()) {
                    return $response->json();
                }
                
                return null;
            });
        } catch (\Exception $e) {
            Log::error('Nutrition API details error: ' . $e->getMessage(), [
                'fdc_id' => $fdcId,
                'error_type' => get_class($e)
            ]);
            
            // Check if it's a timeout error
            if (str_contains($e->getMessage(), 'timeout') || str_contains($e->getMessage(), 'timed out')) {
                Log::warning('Nutrition API timeout for FDC ID: ' . $fdcId);
            }
            
            return null;
        }
    }
    
    /**
     * Calculate nutrients for a given ingredient with quantity and unit
     * 
     * @param string $ingredientName Name of the ingredient
     * @param float $quantity Quantity of the ingredient
     * @param string $unit Unit of measurement (kg, g, lb, oz, cup, etc.)
     * @return array Calculated nutritional values
     */
    public function calculateNutrients($ingredientName, $quantity, $unit)
    {
        try {
            // Search for the food item
            $foodItem = $this->searchFood($ingredientName);
            
            if (!$foodItem) {
                return $this->getDefaultNutritionData();
            }
            
            // Get detailed nutrition data
            $fdcId = $foodItem['fdcId'] ?? null;
            if (!$fdcId) {
                return $this->getDefaultNutritionData();
            }
            
            $details = $this->getFoodDetails($fdcId);
            if (!$details) {
                return $this->getDefaultNutritionData();
            }
            
            // Convert quantity to grams (base unit for calculations)
            $quantityInGrams = $this->convertToGrams($quantity, $unit);
            
            // Extract nutritional values per 100g
            $nutrients = $this->extractNutrients($details);
            
            // Calculate nutrients for the specified quantity
            $multiplier = $quantityInGrams / 100;
            
            return [
                'success' => true,
                'ingredient' => $ingredientName,
                'quantity' => $quantity,
                'unit' => $unit,
                'weight_grams' => $quantityInGrams,
                'calories' => round($nutrients['calories'] * $multiplier, 2),
                'protein' => round($nutrients['protein'] * $multiplier, 2),
                'carbs' => round($nutrients['carbs'] * $multiplier, 2),
                'fats' => round($nutrients['fats'] * $multiplier, 2),
                'fiber' => round($nutrients['fiber'] * $multiplier, 2),
                'sugar' => round($nutrients['sugar'] * $multiplier, 2),
                'sodium' => round($nutrients['sodium'] * $multiplier, 2),
                'api_source' => 'USDA FoodData Central',
                'fdc_id' => $fdcId,
            ];
            
        } catch (\Exception $e) {
            Log::error('Nutrition calculation error: ' . $e->getMessage(), [
                'ingredient' => $ingredientName,
                'quantity' => $quantity,
                'unit' => $unit
            ]);
            
            return $this->getDefaultNutritionData();
        }
    }
    
    /**
     * Calculate total nutrition for multiple ingredients
     * 
     * @param array $ingredients Array of ingredients with name, quantity, and unit
     * @return array Total nutritional values
     */
    public function calculateTotalNutrients($ingredients)
    {
        $totals = [
            'calories' => 0,
            'protein' => 0,
            'carbs' => 0,
            'fats' => 0,
            'fiber' => 0,
            'sugar' => 0,
            'sodium' => 0,
        ];
        
        $ingredientDetails = [];
        
        foreach ($ingredients as $ingredient) {
            $name = $ingredient['name'] ?? '';
            $quantity = $ingredient['quantity'] ?? 0;
            $unit = $ingredient['unit'] ?? 'g';
            
            if (empty($name) || $quantity <= 0) {
                continue;
            }
            
            $nutrients = $this->calculateNutrients($name, $quantity, $unit);
            
            if ($nutrients['success']) {
                $totals['calories'] += $nutrients['calories'];
                $totals['protein'] += $nutrients['protein'];
                $totals['carbs'] += $nutrients['carbs'];
                $totals['fats'] += $nutrients['fats'];
                $totals['fiber'] += $nutrients['fiber'];
                $totals['sugar'] += $nutrients['sugar'];
                $totals['sodium'] += $nutrients['sodium'];
                
                $ingredientDetails[] = $nutrients;
            }
        }
        
        return [
            'success' => true,
            'totals' => [
                'calories' => round($totals['calories'], 2),
                'protein' => round($totals['protein'], 2),
                'carbs' => round($totals['carbs'], 2),
                'fats' => round($totals['fats'], 2),
                'fiber' => round($totals['fiber'], 2),
                'sugar' => round($totals['sugar'], 2),
                'sodium' => round($totals['sodium'], 2),
            ],
            'ingredients' => $ingredientDetails,
            'ingredient_count' => count($ingredientDetails),
        ];
    }
    
    /**
     * Extract nutritional values from API response
     * 
     * @param array $foodDetails Food details from API
     * @return array Extracted nutritional values per 100g
     */
    private function extractNutrients($foodDetails)
    {
        $nutrients = [
            'calories' => 0,
            'protein' => 0,
            'carbs' => 0,
            'fats' => 0,
            'fiber' => 0,
            'sugar' => 0,
            'sodium' => 0, // Kept for database compatibility, display saturated fat on frontend
        ];
        
        if (!isset($foodDetails['foodNutrients'])) {
            return $nutrients;
        }
        
        // Map of nutrient IDs to our nutrition fields (USDA FoodData Central)
        $nutrientMapping = [
            1008 => 'calories',    // Energy (kcal)
            1003 => 'protein',     // Protein
            1005 => 'carbs',       // Carbohydrate
            1004 => 'fats',        // Total lipid (fat)
            1079 => 'fiber',       // Fiber, total dietary
            2000 => 'sugar',       // Sugars, total
            1258 => 'sodium',      // Saturated fatty acids (stored in sodium field for compatibility)
        ];
        
        foreach ($foodDetails['foodNutrients'] as $nutrient) {
            $nutrientId = $nutrient['nutrient']['id'] ?? null;
            $amount = $nutrient['amount'] ?? 0;
            
            if (isset($nutrientMapping[$nutrientId])) {
                $field = $nutrientMapping[$nutrientId];
                $nutrients[$field] = (float) $amount;
            }
        }
        
        return $nutrients;
    }
    
    /**
     * Convert various units to grams
     * 
     * @param float $quantity Quantity to convert
     * @param string $unit Unit to convert from
     * @return float Quantity in grams
     */
    private function convertToGrams($quantity, $unit)
    {
        $unit = strtolower(trim($unit));
        
        // Conversion factors to grams
        $conversions = [
            'kg' => 1000,
            'kilogram' => 1000,
            'kilograms' => 1000,
            'g' => 1,
            'gram' => 1,
            'grams' => 1,
            'lb' => 453.592,
            'pound' => 453.592,
            'pounds' => 453.592,
            'oz' => 28.3495,
            'ounce' => 28.3495,
            'ounces' => 28.3495,
            'cup' => 240,      // Approximate for water/liquid
            'cups' => 240,
            'tbsp' => 15,      // Tablespoon
            'tablespoon' => 15,
            'tablespoons' => 15,
            'tsp' => 5,        // Teaspoon
            'teaspoon' => 5,
            'teaspoons' => 5,
            'ml' => 1,         // Approximate (1ml ≈ 1g for water)
            'milliliter' => 1,
            'milliliters' => 1,
            'l' => 1000,       // Liter
            'liter' => 1000,
            'liters' => 1000,
            'pcs' => 100,      // Approximate per piece
            'piece' => 100,
            'pieces' => 100,
        ];
        
        $factor = $conversions[$unit] ?? 1;
        
        return $quantity * $factor;
    }
    
    /**
     * Get default nutrition data when API fails
     * 
     * @return array Default nutrition values
     */
    private function getDefaultNutritionData()
    {
        return [
            'success' => false,
            'calories' => 0,
            'protein' => 0,
            'carbs' => 0,
            'fats' => 0,
            'fiber' => 0,
            'sugar' => 0,
            'sodium' => 0,
            'error' => 'Unable to fetch nutrition data',
        ];
    }
}
