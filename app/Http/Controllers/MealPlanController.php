<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use App\Models\MealPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;

class MealPlanController extends Controller
{
    /**
     * Display a listing of meal plans.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $userTz = $user->timezone ?? config('app.timezone');
        $date = $request->get('date');
        if (!$date) {
            $date = now($userTz)->toDateString();
        }
        // Parse incoming date string in user's timezone, then keep a Carbon instance in that tz
        $selectedDate = Carbon::createFromFormat('Y-m-d', $date, $userTz)->startOfDay();
        
        // Get meal plans for the selected date
        $mealPlans = $user->mealPlansForDate($date)
            ->with(['meal.nutritionalInfo', 'meal.recipe'])
            ->get();
        
        // Get available meals for suggestions
        $availableMeals = Meal::with(['nutritionalInfo', 'recipe'])
            ->withinBudget($user->daily_budget ?? 500)
            ->get();
        
        return view('meal-plans.index', compact('mealPlans', 'selectedDate', 'availableMeals'));
    }

    /**
     * Show the meal type selection interface.
     */
    public function selectMealType(Request $request)
    {
        $user = Auth::user();
        $userTz = $user->timezone ?? config('app.timezone');
        $date = $request->get('date');
        if (!$date) {
            $date = now($userTz)->toDateString();
        }
        $selectedDate = Carbon::createFromFormat('Y-m-d', $date, $userTz)->startOfDay();
        
        
        // Get existing meal plans for the selected date
        $existingMealPlans = $user->mealPlansForDate($date)
            ->pluck('meal_type')
            ->toArray();
        
        // Get available meal counts for each type
        $availableMeals = Meal::with(['nutritionalInfo'])
            ->withinBudget($user->daily_budget ?? 500)
            ->get();
            
        $mealCounts = [
            'breakfast' => $availableMeals->count(),
            'lunch' => $availableMeals->count(),
            'dinner' => $availableMeals->count(),
            'snack' => $availableMeals->count(),
        ];
        
        return view('meal-plans.select', compact('selectedDate', 'existingMealPlans', 'mealCounts'));
    }

    /**
     * Show the form for creating a new meal plan.
     */
    public function create()
    {
        $user = Auth::user();
        $meals = Meal::with(['nutritionalInfo', 'recipe'])
            ->withinBudget($user->daily_budget ?? 500)
            ->get();
        
        return view('meal-plans.create', compact('meals'));
    }

    /**
     * Store a newly created meal plan.
     */
    public function store(Request $request)
    {
        $request->validate([
            'meal_id' => 'required|exists:meals,id',
            'scheduled_date' => 'required|date',
            'meal_type' => 'required|in:breakfast,lunch,dinner,snack',
        ]);
        
        $user = Auth::user();
        
        // Check if meal already exists for this date and meal type
        $existingMeal = $user->mealPlans()
            ->where('scheduled_date', $request->scheduled_date)
            ->where('meal_type', $request->meal_type)
            ->first();
            
        if ($existingMeal) {
            return back()->withErrors(['meal_type' => 'A meal already exists for this date and meal type.']);
        }
        
        MealPlan::create([
            'user_id' => $user->id,
            'meal_id' => $request->meal_id,
            'scheduled_date' => $request->scheduled_date,
            'meal_type' => $request->meal_type,
        ]);
        
        return redirect()->route('meal-plans.index')
            ->with('success', 'Meal added to your plan successfully!');
    }

    /**
     * Display the specified meal plan.
     */
    public function show(MealPlan $mealPlan)
    {
        Gate::authorize('view', $mealPlan);
        
        $mealPlan->load(['meal.nutritionalInfo', 'meal.recipe']);
        
        return view('meal-plans.show', compact('mealPlan'));
    }

    /**
     * Show the form for editing the specified meal plan.
     */
    public function edit(MealPlan $mealPlan)
    {
        Gate::authorize('update', $mealPlan);
        
        $user = Auth::user();
        $meals = Meal::with(['nutritionalInfo', 'recipe'])
            ->withinBudget($user->daily_budget ?? 500)
            ->get();
        
        return view('meal-plans.edit', compact('mealPlan', 'meals'));
    }

    /**
     * Update the specified meal plan.
     */
    public function update(Request $request, MealPlan $mealPlan)
    {
        Gate::authorize('update', $mealPlan);
        
        $request->validate([
            'meal_id' => 'required|exists:meals,id',
            'scheduled_date' => 'required|date',
            'meal_type' => 'required|in:breakfast,lunch,dinner,snack',
        ]);
        
        $user = Auth::user();
        
        // Check if meal already exists for this date and meal type (excluding current meal)
        $existingMeal = $user->mealPlans()
            ->where('id', '!=', $mealPlan->id)
            ->where('scheduled_date', $request->scheduled_date)
            ->where('meal_type', $request->meal_type)
            ->first();
            
        if ($existingMeal) {
            return back()->withErrors(['meal_type' => 'A meal already exists for this date and meal type.']);
        }
        
        $mealPlan->update([
            'meal_id' => $request->meal_id,
            'scheduled_date' => $request->scheduled_date,
            'meal_type' => $request->meal_type,
        ]);
        
        return redirect()->route('meal-plans.index')
            ->with('success', 'Meal plan updated successfully!');
    }

    /**
     * Remove the specified meal plan.
     */
    public function destroy(MealPlan $mealPlan)
    {
        Gate::authorize('delete', $mealPlan);
        
        $mealPlan->delete();
        
        return redirect()->route('meal-plans.index')
            ->with('success', 'Meal removed from your plan successfully!');
    }
    
    /**
     * Toggle meal completion status.
     */
    public function toggleCompletion(MealPlan $mealPlan)
    {
        Gate::authorize('update', $mealPlan);
        
        $mealPlan->update([
            'is_completed' => !$mealPlan->is_completed
        ]);
        
        return back()->with('success', 'Meal status updated successfully!');
    }
    
    /**
     * Show weekly meal plan view.
     */
    public function weekly(Request $request)
    {
        $user = Auth::user();
        $userTz = $user->timezone ?? config('app.timezone');
        $startDateParam = $request->get('start_date');
        if ($startDateParam) {
            $startDate = Carbon::createFromFormat('Y-m-d', $startDateParam, $userTz)->startOfWeek();
        } else {
            $startDate = now($userTz)->startOfWeek();
        }
        
        $weeklyMeals = $user->weeklyMealPlans($startDate)->get();
        
        $weekDays = collect();
        for ($i = 0; $i < 7; $i++) {
            $date = $startDate->copy()->addDays($i);
            $weekDays->push([
                'date' => $date,
                'meals' => $weeklyMeals->where('scheduled_date', $date->format('Y-m-d'))
            ]);
        }
        
        return view('meal-plans.weekly', compact('weekDays', 'startDate'));
    }
}
