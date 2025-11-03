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

        // Featured recipes at the top
        $query->orderBy('is_featured', 'desc')->latest();

        $recipes = $query->paginate(12)->withQueryString();

        $cuisineTypes = Meal::select('cuisine_type')
            ->distinct()
            ->whereNotNull('cuisine_type')
            ->pluck('cuisine_type');

        return view('recipes.index', compact('recipes', 'cuisineTypes'));
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

        return view('recipes.show', compact('meal', 'similarRecipes'));
    }
}
