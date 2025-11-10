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
        
        // Super admins should not access user dashboard
        if ($user->isSuperAdmin()) {
            return redirect()->route('admin.dashboard')
                ->with('info', 'Super admin accounts can only access the admin panel.');
        }
        
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
        
        // Calculate daily calorie summary
        $dailyCalorieTarget = $user->getRecommendedDailyCalories();
        $todayTotalCalories = $todayMeals->sum(function($mealPlan) {
            return $mealPlan->meal->nutritionalInfo->calories ?? 0;
        });
        
        // Calculate calorie difference and percentage
        $calorieDifference = $todayTotalCalories - $dailyCalorieTarget;
        $caloriePercentage = $dailyCalorieTarget > 0 
            ? round(($todayTotalCalories / $dailyCalorieTarget) * 100) 
            : 0;
        
        $dailySummary = [
            'totalCalories' => $todayTotalCalories,
            'targetCalories' => $dailyCalorieTarget,
            'difference' => $calorieDifference,
            'percentage' => $caloriePercentage,
            'isUnder' => $calorieDifference < 0,
            'isOver' => $calorieDifference > 0,
            'isOnTarget' => abs($calorieDifference) <= 50, // Within 50 calories is considered on target
        ];
        
        return view('dashboard.index', compact(
            'user',
            'todayMeals',
            'featuredMeal',
            'suggestedMeals',
            'dailySummary',
            'bmiStatus'
        ));
    }
}