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
        $validated = $request->validate([
            'name' => 'required|string|max:200',
            'quantity' => 'required|numeric|min:0',
            'unit' => 'required|string|max:50',
        ]);
        
        try {
            $nutrients = $this->nutritionService->calculateNutrients(
                $validated['name'],
                $validated['quantity'],
                $validated['unit']
            );
            
            return response()->json($nutrients);
            
        } catch (\Exception $e) {
            Log::error('Ingredient nutrition calculation failed: ' . $e->getMessage(), [
                'ingredient' => $validated['name']
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Failed to calculate nutrition for ingredient',
                'message' => $e->getMessage()
            ], 500);
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
        $validated = $request->validate([
            'ingredients' => 'required|array|min:1',
            'ingredients.*.name' => 'required|string|max:200',
            'ingredients.*.quantity' => 'required|numeric|min:0',
            'ingredients.*.unit' => 'required|string|max:50',
            'servings' => 'nullable|integer|min:1',
        ]);
        
        try {
            $servings = $validated['servings'] ?? 1;
            
            // Calculate total nutrients for all ingredients
            $totals = $this->nutritionService->calculateTotalNutrients($validated['ingredients']);
            
            if (!$totals['success']) {
                return response()->json([
                    'success' => false,
                    'error' => 'Failed to calculate nutrition data'
                ], 500);
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
                'ingredients' => $totals['ingredients'],
                'ingredient_count' => $totals['ingredient_count'],
            ]);
            
        } catch (\Exception $e) {
            Log::error('Recipe nutrition calculation failed: ' . $e->getMessage());
            
            // Check for timeout errors and provide user-friendly message
            $message = $e->getMessage();
            if (str_contains($message, 'timeout') || str_contains($message, 'timed out')) {
                $message = 'The nutrition database is currently slow to respond. Please try again in a moment.';
            } else if (str_contains($message, 'Connection') || str_contains($message, 'cURL')) {
                $message = 'Unable to connect to nutrition database. Please check your internet connection and try again.';
            }
            
            return response()->json([
                'success' => false,
                'error' => 'Failed to calculate recipe nutrition',
                'message' => $message
            ], 500);
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
}
