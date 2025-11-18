<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    /**
     * Display a listing of recipes.
     */
    public function index(Request $request)
    {
        try {
            $query = Meal::with(['recipe', 'nutritionalInfo']);

            // Search functionality
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%")
                      ->orWhere('cuisine_type', 'like', "%{$search}%");
                });
            }

            // Filter by cuisine type
            if ($request->filled('cuisine_type') && $request->cuisine_type !== '') {
                $query->where('cuisine_type', $request->cuisine_type);
            }

            // Filter by difficulty
            if ($request->filled('difficulty') && $request->difficulty !== '') {
                $query->where('difficulty', $request->difficulty);
            }

            // Filter by meal type
            if ($request->filled('meal_type') && $request->meal_type !== '') {
                $query->where('meal_type', $request->meal_type);
            }

            // Filter by price range
            if ($request->filled('price_range') && $request->price_range !== '') {
                switch ($request->price_range) {
                    case 'under_50':
                        $query->where('cost', '<', 50);
                        break;
                    case '50_100':
                        $query->whereBetween('cost', [50, 100]);
                        break;
                    case '100_200':
                        $query->whereBetween('cost', [100, 200]);
                        break;
                    case 'over_200':
                        $query->where('cost', '>', 200);
                        break;
                }
            }

            // Filter by calorie range
            if ($request->filled('calorie_range') && $request->calorie_range !== '') {
                switch ($request->calorie_range) {
                    case 'under_100':
                        $query->whereHas('nutritionalInfo', function($q) {
                            $q->where('calories', '<', 100);
                        });
                        break;
                    case '100_200':
                        $query->whereHas('nutritionalInfo', function($q) {
                            $q->whereBetween('calories', [100, 200]);
                        });
                        break;
                    case '200_300':
                        $query->whereHas('nutritionalInfo', function($q) {
                            $q->whereBetween('calories', [200, 300]);
                        });
                        break;
                    case '300_400':
                        $query->whereHas('nutritionalInfo', function($q) {
                            $q->whereBetween('calories', [300, 400]);
                        });
                        break;
                    case '400_500':
                        $query->whereHas('nutritionalInfo', function($q) {
                            $q->whereBetween('calories', [400, 500]);
                        });
                        break;
                    case 'over_500':
                        $query->whereHas('nutritionalInfo', function($q) {
                            $q->where('calories', '>', 500);
                        });
                        break;
                }
            }

            // Featured recipes at the top
            $query->orderBy('is_featured', 'desc')->latest();

            $recipes = $query->paginate(12)->withQueryString();

            $availableCuisines = Meal::select('cuisine_type')
                ->distinct()
                ->whereNotNull('cuisine_type')
                ->where('cuisine_type', '!=', '')
                ->orderBy('cuisine_type')
                ->pluck('cuisine_type');

            return view('recipes.index', compact('recipes', 'availableCuisines'));
        } catch (\Exception $e) {
            \Log::error('Recipes index error: ' . $e->getMessage());
            return back()->with('error', 'Unable to load recipes. Please try again.');
        }
    }

    /**
     * Search for recipes.
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        
        $recipes = Meal::with(['recipe', 'nutritionalInfo'])
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%")
                  ->orWhere('cuisine_type', 'like', "%{$query}%");
            })
            ->orderBy('is_featured', 'desc')
            ->latest()
            ->paginate(12);

        return view('recipes.search', compact('recipes', 'query'));
    }

    /**
     * Display the specified recipe.
     */
    public function show(Meal $meal)
    {
        $meal->load(['recipe', 'nutritionalInfo']);

        // Get similar recipes based on cuisine type
        $similarRecipes = Meal::with(['recipe', 'nutritionalInfo'])
            ->where('id', '!=', $meal->id)
            ->where('cuisine_type', $meal->cuisine_type)
            ->limit(3)
            ->get();

        // Get nutrient warnings based on PDRI
        $nutrientWarnings = [];
        if (auth()->check()) {
            $nutrientWarnings = \App\Models\PdriReference::getNutrientWarnings($meal, auth()->user());
        }

        return view('recipes.show', compact('meal', 'similarRecipes', 'nutrientWarnings'));
    }
}
