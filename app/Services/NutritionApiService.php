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
        
        // Log API configuration status for debugging
        Log::info('NutritionApiService initialized', [
            'api_url' => $this->apiUrl,
            'api_key_configured' => $this->apiKey && $this->apiKey !== 'YOUR_API_KEY_HERE',
            'api_key_length' => strlen($this->apiKey),
            'environment' => app()->environment()
        ]);
        
        // Warn if API key is not properly configured
        if (!$this->apiKey || $this->apiKey === 'YOUR_API_KEY_HERE') {
            Log::warning('Nutrition API key not configured properly', [
                'api_key_value' => $this->apiKey,
                'environment' => app()->environment(),
                'config_cached' => config('app.name') ? 'YES' : 'NO'
            ]);
        }
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
                Log::info('Making nutrition API request', [
                    'food_name' => $foodName,
                    'api_url' => $this->apiUrl,
                    'api_key_configured' => $this->apiKey && $this->apiKey !== 'YOUR_API_KEY_HERE',
                    'api_key_length' => strlen($this->apiKey)
                ]);
                
                $response = Http::timeout(10)->retry(2, 1000)->get("{$this->apiUrl}/foods/search", [
                    'api_key' => $this->apiKey,
                    'query' => $foodName,
                    'pageSize' => 5,
                    'dataType' => ['Foundation', 'SR Legacy'],
                ]);
                
                Log::info('Nutrition API response received', [
                    'food_name' => $foodName,
                    'status_code' => $response->status(),
                    'successful' => $response->successful(),
                    'response_size' => strlen($response->body())
                ]);
                
                if ($response->successful()) {
                    $data = $response->json();
                    
                    if (isset($data['foods']) && count($data['foods']) > 0) {
                        Log::info('Nutrition API search successful', [
                            'food_name' => $foodName,
                            'results_count' => count($data['foods'])
                        ]);
                        return $data;
                    } else {
                        Log::warning('Nutrition API returned no results', [
                            'food_name' => $foodName,
                            'response_data' => $data
                        ]);
                    }
                } else {
                    Log::error('Nutrition API request failed', [
                        'food_name' => $foodName,
                        'status_code' => $response->status(),
                        'response_body' => $response->body(),
                        'headers' => $response->headers()
                    ]);
                }
                
                return null;
            });
        } catch (\Exception $e) {
            Log::error('Nutrition API search error: ' . $e->getMessage(), [
                'food_name' => $foodName,
                'error_type' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'api_key_configured' => $this->apiKey && $this->apiKey !== 'YOUR_API_KEY_HERE'
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
                $response = Http::timeout(3)->retry(1, 500)->get("{$this->apiUrl}/food/{$fdcId}", [
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
                Log::info('Food item not found in USDA database', ['ingredient' => $ingredientName]);
                return $this->getDefaultNutritionData($ingredientName, $quantity, $unit);
            }
            
            // Get detailed nutrition data
            $fdcId = $foodItem['fdcId'] ?? null;
            if (!$fdcId) {
                Log::info('No FDC ID found for food item', ['ingredient' => $ingredientName]);
                return $this->getDefaultNutritionData($ingredientName, $quantity, $unit);
            }
            
            $details = $this->getFoodDetails($fdcId);
            if (!$details) {
                Log::info('Food details not available', ['ingredient' => $ingredientName, 'fdc_id' => $fdcId]);
                return $this->getDefaultNutritionData($ingredientName, $quantity, $unit);
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
                'unit' => $unit,
                'error_type' => get_class($e)
            ]);
            
            return $this->getDefaultNutritionData($ingredientName, $quantity, $unit);
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
        
        // Standard conversion factors to grams
        // Only using units that don't require food-specific estimation
        $conversions = [
            // Weight units (exact conversions)
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
            
            // Volume units (standard conversions)
            'cup' => 240,      // Standard US cup (240ml)
            'cups' => 240,
            'tbsp' => 15,      // Tablespoon (15ml)
            'tablespoon' => 15,
            'tablespoons' => 15,
            'tsp' => 5,        // Teaspoon (5ml)
            'teaspoon' => 5,
            'teaspoons' => 5,
            'ml' => 1,         // Milliliter (≈1g for water)
            'milliliter' => 1,
            'milliliters' => 1,
            'l' => 1000,       // Liter
            'liter' => 1000,
            'liters' => 1000,
        ];
        
        $factor = $conversions[$unit] ?? 1; // Default to 1:1 ratio if unit unknown
        
        $result = $quantity * $factor;
        
        // Log conversion for debugging
        Log::debug('Unit conversion', [
            'original' => $quantity . ' ' . $unit,
            'factor' => $factor,
            'grams' => $result
        ]);
        
        return $result;
    }
    
    /**
     * Get default nutrition data when API fails
     * 
     * @param string $ingredientName Optional ingredient name
     * @param float $quantity Optional quantity 
     * @param string $unit Optional unit
     * @return array Default nutrition values
     */
    private function getDefaultNutritionData($ingredientName = '', $quantity = 0, $unit = 'g')
    {
        return [
            'success' => true, // Return success true to prevent errors
            'ingredient' => $ingredientName ?: 'Unknown ingredient',
            'quantity' => $quantity,
            'unit' => $unit,
            'weight_grams' => $this->convertToGrams($quantity, $unit),
            'calories' => 0,
            'protein' => 0,
            'carbs' => 0,
            'fats' => 0,
            'fiber' => 0,
            'sugar' => 0,
            'sodium' => 0,
            'api_source' => 'Default (USDA API unavailable)',
            'message' => 'Nutrition data unavailable - using default values',
        ];
    }
}
