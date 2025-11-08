<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\NutritionApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NutritionApiController extends Controller
{
    protected $nutritionService;
    
    public function __construct(NutritionApiService $nutritionService)
    {
        $this->nutritionService = $nutritionService;
    }
    
    /**
     * Calculate nutrients for a single ingredient
     * 
     * POST /api/calculate-ingredient-nutrition
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function calculateIngredient(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:200',
                'quantity' => 'required|numeric|min:0',
                'unit' => 'required|string|max:50',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Invalid request data',
                'message' => 'Please check your input and try again.',
                'data' => $this->getDefaultNutritionResponse()
            ], 422);
        }
        
        try {
            $nutrients = $this->nutritionService->calculateNutrients(
                $validated['name'],
                $validated['quantity'],
                $validated['unit']
            );
            
            // Ensure we always return a valid response
            if (!$nutrients || !is_array($nutrients)) {
                return $this->getDefaultNutritionResponse();
            }
            
            return response()->json($nutrients);
            
        } catch (\Exception $e) {
            Log::error('Ingredient nutrition calculation failed: ' . $e->getMessage(), [
                'ingredient' => $validated['name'] ?? 'unknown'
            ]);
            
            // Return default nutrition data instead of error
            return $this->getDefaultNutritionResponse();
        }
    }
    
    /**
     * Calculate total nutrients for multiple ingredients
     * 
     * POST /api/calculate-recipe-nutrition
     * 
     * Request body example:
     * {
     *   "ingredients": [
     *     {"name": "Chicken breast", "quantity": 500, "unit": "g"},
     *     {"name": "Rice", "quantity": 2, "unit": "cup"}
     *   ],
     *   "servings": 4
     * }
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function calculateRecipe(Request $request)
    {
        // Set execution time limit for API to prevent timeouts
        set_time_limit(30);
        
        // Ultimate safety net to ensure we ALWAYS return valid JSON
        try {
            try {
                $validated = $request->validate([
                    'ingredients' => 'required|array|min:1',
                    'ingredients.*.name' => 'required|string|max:200',
                    'ingredients.*.quantity' => 'required|numeric|min:0',
                    'ingredients.*.unit' => 'required|string|max:50',
                    'servings' => 'nullable|integer|min:1',
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'error' => 'Invalid request data',
                    'message' => 'Please check your ingredients and try again.',
                    'data' => $this->getDefaultRecipeResponse()
                ], 422);
            }
            
            try {
                $servings = $validated['servings'] ?? 1;
                
                // Calculate total nutrients for all ingredients
                $totals = $this->nutritionService->calculateTotalNutrients($validated['ingredients']);
                
                if (!$totals || !isset($totals['success']) || !$totals['success']) {
                    return $this->getDefaultRecipeResponse($servings);
                }
                
                // Calculate per-serving values
                $perServing = [];
                foreach ($totals['totals'] as $nutrient => $value) {
                    $perServing[$nutrient] = round($value / $servings, 2);
                }
                
                return response()->json([
                    'success' => true,
                    'total' => $totals['totals'],
                    'per_serving' => $perServing,
                    'servings' => $servings,
                    'ingredients' => $totals['ingredients'] ?? [],
                    'ingredient_count' => $totals['ingredient_count'] ?? 0,
                ]);
                
            } catch (\Exception $e) {
                Log::error('Recipe nutrition calculation failed: ' . $e->getMessage());
                
                // Return default nutrition data instead of error
                return $this->getDefaultRecipeResponse($validated['servings'] ?? 1);
            }
            
        } catch (\Throwable $e) {
            // Final safety net - log critical error and return safe JSON
            Log::error('Critical error in nutrition API: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => true,
                'total' => [
                    'calories' => 0, 'protein' => 0, 'carbs' => 0, 'fats' => 0,
                    'fiber' => 0, 'sugar' => 0, 'sodium' => 0
                ],
                'per_serving' => [
                    'calories' => 0, 'protein' => 0, 'carbs' => 0, 'fats' => 0,
                    'fiber' => 0, 'sugar' => 0, 'sodium' => 0
                ],
                'servings' => 1,
                'ingredients' => [],
                'ingredient_count' => 0,
                'error_note' => 'Nutrition data temporarily unavailable'
            ], 200);
        }
    }
    
    /**
     * Search for food items in the nutrition database
     * 
     * GET /api/search-food?query=chicken
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchFood(Request $request)
    {
        $validated = $request->validate([
            'query' => 'required|string|min:2|max:200',
        ]);
        
        try {
            $result = $this->nutritionService->searchFood($validated['query']);
            
            if ($result) {
                return response()->json([
                    'success' => true,
                    'food' => $result,
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'No food items found'
            ], 404);
            
        } catch (\Exception $e) {
            Log::error('Food search failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => 'Food search failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get default nutrition response for a single ingredient
     */
    private function getDefaultNutritionResponse()
    {
        return response()->json([
            'success' => true,
            'ingredient' => 'Unknown ingredient',
            'quantity' => 0,
            'unit' => 'g',
            'weight_grams' => 0,
            'calories' => 0,
            'protein' => 0,
            'carbs' => 0,
            'fats' => 0,
            'fiber' => 0,
            'sugar' => 0,
            'sodium' => 0,
            'api_source' => 'Default (API unavailable)',
            'message' => 'Using default nutrition values - API unavailable'
        ]);
    }
    
    /**
     * Get default recipe nutrition response
     */
    private function getDefaultRecipeResponse($servings = 1)
    {
        return response()->json([
            'success' => true,
            'total' => [
                'calories' => 0,
                'protein' => 0,
                'carbs' => 0,
                'fats' => 0,
                'fiber' => 0,
                'sugar' => 0,
                'sodium' => 0,
            ],
            'per_serving' => [
                'calories' => 0,
                'protein' => 0,
                'carbs' => 0,
                'fats' => 0,
                'fiber' => 0,
                'sugar' => 0,
                'sodium' => 0,
            ],
            'servings' => $servings,
            'ingredients' => [],
            'ingredient_count' => 0,
            'message' => 'Using default nutrition values - API unavailable'
        ]);
    }
}
