<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Show the user dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Simple data for now
        $todayMeals = collect();
        $featuredMeal = null;
        $suggestedMeals = collect();
        $weeklySummary = [
            'totalCalories' => 0,
            'totalCost' => 0,
            'mealCount' => 0,
            'averageCalories' => 0,
            'averageCost' => 0,
        ];
        
        return view('dashboard.index', compact(
            'user',
            'todayMeals',
            'featuredMeal',
            'suggestedMeals',
            'weeklySummary'
        ));
    }
}