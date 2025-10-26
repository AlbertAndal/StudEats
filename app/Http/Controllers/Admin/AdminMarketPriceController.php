<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminLog;
use App\Models\Ingredient;
use App\Models\IngredientPriceHistory;
use App\Services\BantayPresyoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminMarketPriceController extends Controller
{
    public function __construct(private BantayPresyoService $bantayPresyoService)
    {
    }

    /**
     * Display the market prices management page.
     */
    public function index()
    {
        // Get statistics
        $stats = [
            'total_ingredients' => Ingredient::count(),
            'active_ingredients' => Ingredient::active()->count(),
            'with_prices' => Ingredient::whereNotNull('current_price')->count(),
            'stale_prices' => Ingredient::withStalePrices(7)->count(),
        ];

        // Get last update timestamp
        $lastUpdate = $this->bantayPresyoService->getLastUpdateTimestamp();

        // Get recent price updates
        $recentPriceUpdates = IngredientPriceHistory::with('ingredient')
            ->where('price_source', 'bantay_presyo')
            ->latest('recorded_at')
            ->limit(20)
            ->get();

        // Get available regions and commodities
        $regions = BantayPresyoService::getAvailableRegions();
        $commodities = BantayPresyoService::getAvailableCommodities();

        return view('admin.market-prices.index', compact(
            'stats',
            'lastUpdate',
            'recentPriceUpdates',
            'regions',
            'commodities'
        ));
    }

    /**
     * Update market prices from Bantay Presyo.
     */
    public function update(Request $request)
    {
        $request->validate([
            'region' => 'nullable|string|max:50',
            'commodities' => 'nullable|array',
            'commodities.*' => 'integer',
        ]);

        $region = $request->input('region', 'NCR');
        $commodities = $request->input('commodities');

        try {
            // Log the action
            AdminLog::createLog(
                Auth::id(),
                'update_market_prices',
                "Initiated market price update for region: {$region}",
                null,
                ['region' => $region, 'commodities' => $commodities]
            );

            // Fetch prices
            $results = $this->bantayPresyoService->fetchAllPrices($region, $commodities);

            // Log completion
            AdminLog::createLog(
                Auth::id(),
                'update_market_prices_completed',
                "Market price update completed. Fetched: {$results['fetched']}, Failed: {$results['failed']}",
                null,
                ['fetched' => $results['fetched'], 'failed' => $results['failed']]
            );

            if ($results['success']) {
                return redirect()
                    ->route('admin.market-prices.index')
                    ->with('success', "Successfully fetched {$results['fetched']} price records!");
            } else {
                return redirect()
                    ->route('admin.market-prices.index')
                    ->with('warning', "Price update completed with some errors. Fetched: {$results['fetched']}, Failed: {$results['failed']}");
            }
        } catch (\Exception $e) {
            Log::error('Admin market price update failed', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
            ]);

            AdminLog::createLog(
                Auth::id(),
                'update_market_prices_failed',
                "Market price update failed: {$e->getMessage()}",
                null,
                ['error' => $e->getMessage()]
            );

            return redirect()
                ->route('admin.market-prices.index')
                ->with('error', 'Failed to update market prices: ' . $e->getMessage());
        }
    }

    /**
     * Display ingredient price history.
     */
    public function history(Ingredient $ingredient)
    {
        $priceHistory = $ingredient->priceHistory()
            ->latest('recorded_at')
            ->paginate(50);

        return view('admin.market-prices.history', compact('ingredient', 'priceHistory'));
    }

    /**
     * Get price statistics as JSON for AJAX.
     */
    public function stats(Request $request)
    {
        $stats = [
            'total_ingredients' => Ingredient::count(),
            'active_ingredients' => Ingredient::active()->count(),
            'with_prices' => Ingredient::whereNotNull('current_price')->count(),
            'stale_prices' => Ingredient::withStalePrices(7)->count(),
            'last_update' => $this->bantayPresyoService->getLastUpdateTimestamp()?->format('Y-m-d H:i:s'),
        ];

        return response()->json($stats);
    }

    /**
     * Get ingredients list as JSON.
     */
    public function ingredients(Request $request)
    {
        $query = Ingredient::query();

        // Apply filters
        if ($request->has('category')) {
            $query->byCategory($request->input('category'));
        }

        if ($request->has('stale_only') && $request->boolean('stale_only')) {
            $query->withStalePrices(7);
        }

        if ($request->has('active_only') && $request->boolean('active_only')) {
            $query->active();
        }

        $ingredients = $query->orderBy('name')->paginate(50);

        return response()->json($ingredients);
    }

    /**
     * Search and filter market prices for the admin interface.
     */
    public function search(Request $request)
    {
        try {
            $query = Ingredient::with(['priceHistory' => function ($q) {
                $q->latest('recorded_at')->limit(1);
            }]);

            // Apply search filter
            if ($request->filled('search')) {
                $searchTerm = $request->input('search');
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('name', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('bantay_presyo_name', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('category', 'LIKE', "%{$searchTerm}%");
                });
            }

            // Apply category filter
            if ($request->filled('category')) {
                $query->where('category', $request->input('category'));
            }

            // Apply price status filter
            if ($request->filled('price_status')) {
                $status = $request->input('price_status');
                switch ($status) {
                    case 'no_price':
                        $query->whereNull('current_price');
                        break;
                    case 'fresh':
                        $query->whereNotNull('current_price')
                              ->where('price_updated_at', '>=', now()->subDays(7));
                        break;
                    case 'stale':
                        $query->whereNotNull('current_price')
                              ->where(function ($q) {
                                  $q->whereNull('price_updated_at')
                                    ->orWhere('price_updated_at', '<', now()->subDays(7));
                              });
                        break;
                }
            }

            // Apply sorting
            $sortBy = $request->input('sort_by', 'name');
            $sortOrder = $request->input('sort_order', 'asc');
            
            switch ($sortBy) {
                case 'name':
                    $query->orderBy('name', $sortOrder);
                    break;
                case 'price':
                    $query->orderByRaw("COALESCE(current_price, 0) {$sortOrder}");
                    break;
                case 'updated':
                    $query->orderByRaw("COALESCE(price_updated_at, '1970-01-01') {$sortOrder}");
                    break;
                default:
                    $query->orderBy('name', 'asc');
            }

            $ingredients = $query->get()->map(function ($ingredient) {
                $isStale = $ingredient->isPriceStale(7);
                
                return [
                    'id' => $ingredient->id,
                    'name' => $ingredient->name,
                    'bantay_presyo_name' => $ingredient->bantay_presyo_name,
                    'category' => $ingredient->category,
                    'unit' => $ingredient->unit,
                    'current_price' => $ingredient->current_price,
                    'price_updated_at' => $ingredient->price_updated_at?->toISOString(),
                    'is_stale' => $isStale,
                    'price_history' => $ingredient->priceHistory->map(function ($history) {
                        return [
                            'price' => $history->price,
                            'region_code' => $history->region_code,
                            'recorded_at' => $history->recorded_at->toISOString(),
                        ];
                    })->toArray(),
                ];
            });

            return response()->json([
                'success' => true,
                'ingredients' => $ingredients,
                'total' => $ingredients->count(),
            ]);

        } catch (\Exception $e) {
            Log::error('Error searching market prices', [
                'error' => $e->getMessage(),
                'request' => $request->all(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error searching market prices',
                'ingredients' => [],
            ], 500);
        }
    }

    /**
     * Refresh price for a single ingredient.
     */
    public function refreshSingle(Request $request, Ingredient $ingredient)
    {
        try {
            // Log the action
            AdminLog::createLog(
                Auth::id(),
                'refresh_single_price',
                "Refreshing price for ingredient: {$ingredient->name}",
                $ingredient
            );

            // Fetch updated price for this ingredient
            $region = $request->input('region', 'NCR');
            
            // Fetch updated price using Bantay Presyo service
            $result = $this->bantayPresyoService->fetchAllPrices($region, [$ingredient->bantay_presyo_commodity_id]);

            if ($result['success'] && $result['fetched'] > 0) {
                $ingredient->refresh(); // Refresh the model from database
                
                return response()->json([
                    'success' => true,
                    'message' => 'Price updated successfully',
                    'ingredient' => [
                        'id' => $ingredient->id,
                        'name' => $ingredient->name,
                        'current_price' => $ingredient->current_price,
                        'price_updated_at' => $ingredient->price_updated_at?->toISOString(),
                    ],
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No price update available for this ingredient',
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Error refreshing single ingredient price', [
                'ingredient_id' => $ingredient->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error refreshing price',
            ], 500);
        }
    }
}
