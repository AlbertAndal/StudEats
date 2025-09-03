<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\MealPlan;
use App\Models\Meal;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Show the user dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get today's date
        $today = Carbon::now()->toDateString();
        
        // Get today's meal plans for the authenticated user
        $todayMeals = MealPlan::where('user_id', $user->id)
            ->where('scheduled_date', $today)
            ->with(['meal.nutritionalInfo'])
            ->orderBy('meal_type')
            ->get();
        
        // Get a featured meal (prioritize meals with actual image files)
        $featuredMealsWithImages = Meal::with(['nutritionalInfo', 'recipe'])
            ->where('is_featured', true)
            ->whereNotNull('image_path')
            ->get()
            ->filter(function($meal) {
                return $meal->image_url !== null; // This checks if file actually exists
            });
        
        if ($featuredMealsWithImages->count() > 0) {
            $featuredMeal = $featuredMealsWithImages->random();
        } else {
            // Fallback to any featured meal
            $featuredMeal = Meal::with(['nutritionalInfo', 'recipe'])
                ->where('is_featured', true)
                ->inRandomOrder()
                ->first();
        }
        
        // Get suggested meals based on user's budget and BMI
        $userBMICategory = $user->getBMICategory();
        $suggestedMeals = Meal::with(['nutritionalInfo', 'recipe'])
            ->withinBudget($user->daily_budget ?? 500)
            ->forBMICategory($userBMICategory)
            ->inRandomOrder()
            ->limit(3)
            ->get();
        
        // Get user's BMI status for dashboard display
        $bmiStatus = $user->getBMIStatus();
        
        // Calculate weekly summary
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        
        $weeklyMeals = MealPlan::where('user_id', $user->id)
            ->whereBetween('scheduled_date', [$startOfWeek->toDateString(), $endOfWeek->toDateString()])
            ->with(['meal.nutritionalInfo'])
            ->get();
        
        $weeklySummary = [
            'totalCalories' => $weeklyMeals->sum(function($mealPlan) {
                return $mealPlan->meal->nutritionalInfo->calories ?? 0;
            }),
            'totalCost' => $weeklyMeals->sum(function($mealPlan) {
                return $mealPlan->meal->cost ?? 0;
            }),
            'mealCount' => $weeklyMeals->count(),
            'averageCalories' => $weeklyMeals->count() > 0 ? round($weeklyMeals->avg(function($mealPlan) {
                return $mealPlan->meal->nutritionalInfo->calories ?? 0;
            })) : 0,
            'averageCost' => $weeklyMeals->count() > 0 ? round($weeklyMeals->avg(function($mealPlan) {
                return $mealPlan->meal->cost ?? 0;
            }), 2) : 0,
        ];
        
        return view('dashboard.index', compact(
            'user',
            'todayMeals',
            'featuredMeal',
            'suggestedMeals',
            'weeklySummary',
            'bmiStatus'
        ));
    }
}