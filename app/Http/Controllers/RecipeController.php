<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecipeController extends Controller
{
    /**
     * Display a listing of recipes.
     */
    public function index(Request $request)
    {
        $query = Meal::with(['recipe', 'nutritionalInfo']);
        
        // Filter by cuisine type
        if ($request->filled('cuisine_type')) {
            $query->where('cuisine_type', $request->cuisine_type);
        }
        
        // Filter by difficulty
        if ($request->filled('difficulty')) {
            $query->where('difficulty', $request->difficulty);
        }
        
        // Filter by budget - only apply if max_cost is present and is a positive number
        if ($request->filled('max_cost') && is_numeric($request->max_cost) && $request->max_cost > 0) {
            $query->where('cost', '<=', $request->max_cost);
        }
        
        // Search by name - only apply if search term is not empty
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }
        
        $meals = $query->paginate(12);
        
        return view('recipes.index', compact('meals'));
    }

    /**
     * Display the specified recipe.
     */
    public function show(Meal $meal)
    {
        $meal->load(['recipe', 'nutritionalInfo']);
        
        // Get similar meals
        $similarMeals = Meal::where('cuisine_type', $meal->cuisine_type)
            ->where('id', '!=', $meal->id)
            ->with(['nutritionalInfo'])
            ->limit(4)
            ->get();
        
        return view('recipes.show', compact('meal', 'similarMeals'));
    }
    
    /**
     * Show recipe search page.
     */
    public function search(Request $request)
    {
        $cuisineTypes = Meal::distinct()->pluck('cuisine_type');
        $difficulties = ['Easy', 'Medium', 'Hard'];
        
        return view('recipes.search', compact('cuisineTypes', 'difficulties'));
    }
}

