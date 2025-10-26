<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ingredient;
use App\Services\BantayPresyoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class IngredientPriceController extends Controller
{
    protected $bantayPresyoService;

    public function __construct(BantayPresyoService $bantayPresyoService)
    {
        $this->bantayPresyoService = $bantayPresyoService;
    }

    /**
     * Get market price for a specific ingredient
     */
    public function getPrice(Request $request)
    {
        $request->validate([
            'ingredient_name' => 'required|string|max:100',
            'region' => 'string|max:20'
        ]);

        $ingredientName = trim($request->ingredient_name);
        $region = $request->region ?? 'NCR';

        try {
            // First, try to find exact match in our ingredients database
            $ingredient = Ingredient::where('name', 'LIKE', "%{$ingredientName}%")
                ->whereNotNull('current_price')
                ->where('price_source', 'bantay_presyo')
                ->first();

            if ($ingredient) {
                // Get regional price if available
                $price = $ingredient->getPriceForRegion($region);
                
                if ($price) {
                    return response()->json([
                        'success' => true,
                        'price' => number_format($price, 2, '.', ''),
                        'ingredient_id' => $ingredient->id,
                        'ingredient_name' => $ingredient->name,
                        'region' => $region,
                        'source' => 'Bantay Presyo',
                        'updated_at' => $ingredient->price_updated_at?->format('Y-m-d H:i:s'),
                        'is_stale' => $ingredient->isPriceStale(),
                        'common_unit' => $ingredient->common_unit
                    ]);
                }
            }

            // If no exact match, try fuzzy search
            $fuzzyIngredient = Ingredient::where(function($query) use ($ingredientName) {
                $words = explode(' ', $ingredientName);
                foreach ($words as $word) {
                    if (strlen($word) > 2) {
                        $query->orWhere('name', 'LIKE', "%{$word}%")
                              ->orWhere('bantay_presyo_name', 'LIKE', "%{$word}%");
                    }
                }
            })
            ->whereNotNull('current_price')
            ->where('price_source', 'bantay_presyo')
            ->first();

            if ($fuzzyIngredient) {
                $price = $fuzzyIngredient->getPriceForRegion($region);
                
                if ($price) {
                    return response()->json([
                        'success' => true,
                        'price' => number_format($price, 2, '.', ''),
                        'ingredient_id' => $fuzzyIngredient->id,
                        'ingredient_name' => $fuzzyIngredient->name,
                        'matched_name' => $ingredientName,
                        'region' => $region,
                        'source' => 'Bantay Presyo (fuzzy match)',
                        'updated_at' => $fuzzyIngredient->price_updated_at?->format('Y-m-d H:i:s'),
                        'is_stale' => $fuzzyIngredient->isPriceStale(),
                        'common_unit' => $fuzzyIngredient->common_unit
                    ]);
                }
            }

            // No price found
            return response()->json([
                'success' => false,
                'message' => 'No market price found for this ingredient',
                'ingredient_name' => $ingredientName,
                'region' => $region,
                'suggestions' => $this->getSuggestions($ingredientName)
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching ingredient price', [
                'ingredient' => $ingredientName,
                'region' => $region,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error fetching market price',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get list of available ingredients for autocomplete
     */
    public function getIngredientsList()
    {
        try {
            $ingredients = Ingredient::select('id', 'name', 'current_price', 'common_unit', 'price_updated_at')
                ->whereNotNull('current_price')
                ->where('price_source', 'bantay_presyo')
                ->orderBy('name')
                ->get()
                ->map(function ($ingredient) {
                    return [
                        'id' => $ingredient->id,
                        'name' => $ingredient->name,
                        'current_price' => $ingredient->current_price,
                        'common_unit' => $ingredient->common_unit ?? 'kg',
                        'is_stale' => $ingredient->isPriceStale(),
                        'updated_at' => $ingredient->price_updated_at?->format('Y-m-d H:i:s')
                    ];
                });

            return response()->json([
                'success' => true,
                'ingredients' => $ingredients,
                'count' => $ingredients->count()
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching ingredients list', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error fetching ingredients list',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
                'ingredients' => []
            ], 500);
        }
    }

    /**
     * Bulk update prices for multiple ingredients
     */
    public function bulkUpdatePrices(Request $request)
    {
        $request->validate([
            'ingredients' => 'required|array|min:1',
            'ingredients.*.name' => 'required|string|max:100',
            'region' => 'string|max:20'
        ]);

        $region = $request->region ?? 'NCR';
        $results = [];
        $successCount = 0;

        try {
            foreach ($request->ingredients as $ingredientData) {
                $ingredientName = trim($ingredientData['name']);
                
                $ingredient = Ingredient::where('name', 'LIKE', "%{$ingredientName}%")
                    ->whereNotNull('current_price')
                    ->where('price_source', 'bantay_presyo')
                    ->first();

                if ($ingredient) {
                    $price = $ingredient->getPriceForRegion($region);
                    
                    if ($price) {
                        $results[] = [
                            'ingredient_name' => $ingredientName,
                            'success' => true,
                            'price' => number_format($price, 2, '.', ''),
                            'ingredient_id' => $ingredient->id,
                            'matched_name' => $ingredient->name
                        ];
                        $successCount++;
                    } else {
                        $results[] = [
                            'ingredient_name' => $ingredientName,
                            'success' => false,
                            'message' => 'No price available for region'
                        ];
                    }
                } else {
                    $results[] = [
                        'ingredient_name' => $ingredientName,
                        'success' => false,
                        'message' => 'Ingredient not found'
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'results' => $results,
                'summary' => [
                    'total' => count($request->ingredients),
                    'success' => $successCount,
                    'failed' => count($request->ingredients) - $successCount
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error in bulk price update', [
                'error' => $e->getMessage(),
                'ingredients' => $request->ingredients
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error updating prices',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get ingredient name suggestions
     */
    private function getSuggestions($ingredientName)
    {
        return Ingredient::where('name', 'LIKE', "%{$ingredientName}%")
            ->orWhere('bantay_presyo_name', 'LIKE', "%{$ingredientName}%")
            ->whereNotNull('current_price')
            ->limit(5)
            ->pluck('name')
            ->toArray();
    }

    /**
     * Get pricing statistics
     */
    public function getPricingStats()
    {
        try {
            $stats = [
                'total_ingredients' => Ingredient::whereNotNull('current_price')->count(),
                'stale_prices' => Ingredient::withStalePrices(7)->count(),
                'recent_updates' => Ingredient::where('price_updated_at', '>=', now()->subHours(24))->count(),
                'price_range' => [
                    'min' => Ingredient::whereNotNull('current_price')->min('current_price'),
                    'max' => Ingredient::whereNotNull('current_price')->max('current_price'),
                    'avg' => Ingredient::whereNotNull('current_price')->avg('current_price')
                ],
                'last_update' => Ingredient::whereNotNull('price_updated_at')
                    ->orderBy('price_updated_at', 'desc')
                    ->first()
                    ?->price_updated_at
                    ?->format('Y-m-d H:i:s')
            ];

            return response()->json([
                'success' => true,
                'stats' => $stats
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching pricing statistics'
            ], 500);
        }
    }
}