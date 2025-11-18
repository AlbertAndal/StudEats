<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use App\Models\MealPlan;
use App\Services\EmailService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class MealPlanController extends Controller
{
    protected EmailService $emailService;

    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    /**
     * Display a listing of meal plans.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $userTz = $user->timezone ?? config('app.timezone');
        $date = $request->get('date');
        if (! $date) {
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
     * Show the form for creating a new meal plan.
     */
    public function create()
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return redirect()->route('login')->with('error', 'Please login to add meals to your plan.');
            }
                    
            // Safely get BMI category with fallback
            $userBMICategory = 'normal'; // Default fallback
            try {
                if ($user->height && $user->weight && $user->height > 0 && $user->weight > 0) {
                    $userBMICategory = $user->getBMICategory();
                }
            } catch (\Exception $e) {
                Log::warning('BMI calculation failed for user: ' . $user->id, [
                    'error' => $e->getMessage()
                ]);
            }

            // Build meals query with error handling
            try {
                $mealsQuery = Meal::query();
                
                // Only apply filters if explicitly requested through filter form
                $hasAnyFilters = request()->hasAny(['search', 'cuisine_type', 'budget', 'price_range', 'calorie_range']);
                
                // If no explicit filters, show all meals (only apply basic budget constraint)
                if (!$hasAnyFilters) {
                    // Apply basic budget filter to prevent showing extremely expensive meals
                    $budget = $user->daily_budget ?? 1000; // Increased default to show more meals
                    if ($budget && is_numeric($budget)) {
                        $mealsQuery->where('cost', '<=', (float)$budget * 2); // Allow up to 2x daily budget
                    }
                } else {
                    // Apply all explicit filters when filtering is requested
                    
                    // Search functionality
                    if (request('search')) {
                        $search = request('search');
                        $mealsQuery->where(function ($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%")
                              ->orWhere('description', 'like', "%{$search}%")
                              ->orWhere('cuisine_type', 'like', "%{$search}%");
                        });
                    }

                    // Filter by cuisine type
                    if (request('cuisine_type')) {
                        $mealsQuery->where('cuisine_type', request('cuisine_type'));
                    }

                    // Filter by budget
                    if (request('budget')) {
                        $budgetFilter = (float)request('budget');
                        $mealsQuery->where('cost', '<=', $budgetFilter);
                    }

                    // Apply user's daily budget filter if no explicit budget filter
                    if (!request('budget')) {
                        $budget = $user->daily_budget ?? 1000;
                        if ($budget && is_numeric($budget)) {
                            $mealsQuery->where('cost', '<=', (float)$budget);
                        }
                    }

                    // Apply BMI filtering only when explicitly filtering
                    if (!in_array($userBMICategory, ['normal', 'unknown'])) {
                        try {
                            $mealsQuery->forBMICategory($userBMICategory);
                        } catch (\Exception $e) {
                            Log::warning('BMI category filtering failed: ' . $e->getMessage(), [
                                'bmi_category' => $userBMICategory,
                                'error' => $e->getMessage()
                            ]);
                        }
                    }

                    // Filter by meal type when explicitly filtering
                    if (request('meal_type')) {
                        $mealType = request('meal_type');
                        if (in_array($mealType, ['breakfast', 'lunch', 'dinner', 'snack'])) {
                            $mealsQuery->byMealType($mealType);
                        }
                    }
                }

                // Filter by price range if specified
                if (request('price_range')) {
                    $priceRange = request('price_range');
                    switch ($priceRange) {
                        case 'under_50':
                            $mealsQuery->where('cost', '<', 50);
                            break;
                        case '50_100':
                            $mealsQuery->whereBetween('cost', [50, 100]);
                            break;
                        case '100_200':
                            $mealsQuery->whereBetween('cost', [100, 200]);
                            break;
                        case 'over_200':
                            $mealsQuery->where('cost', '>', 200);
                            break;
                    }
                }

                // Filter by calorie range if specified
                if (request('calorie_range')) {
                    $calorieRange = request('calorie_range');
                    switch ($calorieRange) {
                        case 'under_100':
                            $mealsQuery->whereHas('nutritionalInfo', function ($query) {
                                $query->where('calories', '<', 100);
                            });
                            break;
                        case '100_200':
                            $mealsQuery->whereHas('nutritionalInfo', function ($query) {
                                $query->whereBetween('calories', [100, 200]);
                            });
                            break;
                        case '200_300':
                            $mealsQuery->whereHas('nutritionalInfo', function ($query) {
                                $query->whereBetween('calories', [200, 300]);
                            });
                            break;
                        case '300_400':
                            $mealsQuery->whereHas('nutritionalInfo', function ($query) {
                                $query->whereBetween('calories', [300, 400]);
                            });
                            break;
                        case '400_500':
                            $mealsQuery->whereHas('nutritionalInfo', function ($query) {
                                $query->whereBetween('calories', [400, 500]);
                            });
                            break;
                        case 'over_500':
                            $mealsQuery->whereHas('nutritionalInfo', function ($query) {
                                $query->where('calories', '>', 500);
                            });
                            break;
                    }
                }

                // Eager load relationships with error handling
                try {
                    $meals = $mealsQuery->with(['nutritionalInfo', 'recipe'])->get();
                } catch (\Exception $e) {
                    Log::error('Failed to load meals with relationships: ' . $e->getMessage());
                    // Fallback: load meals without relationships
                    $meals = $mealsQuery->get();
                }
            } catch (\Exception $e) {
                Log::error('Failed to build meals query: ' . $e->getMessage(), [
                    'trace' => $e->getTraceAsString()
                ]);
                // Fallback: get all meals within budget
                $meals = Meal::where('cost', '<=', $user->daily_budget ?? 500)->get();
            }

            // Ensure $meals is a collection
            if (!$meals) {
                $meals = collect();
            }

            // Get available cuisine types from database
            $availableCuisines = Meal::whereNotNull('cuisine_type')
                ->where('cuisine_type', '!=', '')
                ->distinct()
                ->pluck('cuisine_type')
                ->sort()
                ->values();

            // Safely get BMI status
            $bmiStatus = null;
            try {
                if ($user->height && $user->weight && $user->height > 0 && $user->weight > 0) {
                    $bmiStatus = $user->getBMIStatus();
                }
            } catch (\Exception $e) {
                Log::warning('BMI status calculation failed for user: ' . $user->id, [
                    'error' => $e->getMessage()
                ]);
            }

            // Get existing meal plans for the selected date
            $selectedDate = request('date', now()->format('Y-m-d'));
            try {
                $existingMealTypes = $user->mealPlans()
                    ->where('scheduled_date', $selectedDate)
                    ->pluck('meal_type')
                    ->toArray();
            } catch (\Exception $e) {
                Log::warning('Failed to get existing meal types: ' . $e->getMessage());
                $existingMealTypes = [];
            }

            return view('meal-plans.create', compact(
            'meals',
            'bmiStatus',
            'existingMealTypes',
            'availableCuisines'
        ));
                
        } catch (\Exception $e) {
            Log::error('Meal plan create error: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'url' => request()->fullUrl(),
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
                    
            $errorMessage = config('app.debug') 
                ? 'Unable to load meal selection. Please try again. Error: ' . $e->getMessage()
                : 'Unable to load meal selection. Please try again.';
                    
            return redirect()->route('meal-plans.index')
                ->with('error', $errorMessage);
        }
    }

        /**
     * Show the form for bulk creating meal plans for entire day.
     */
    public function bulkCreate()
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return redirect()->route('login')->with('error', 'Please login to create meal plans.');
            }

            // Get all available meals grouped by meal type
            $meals = Meal::with(['nutritionalInfo', 'recipe'])
                ->where('cost', '<=', ($user->daily_budget ?? 1000) * 2) // Allow flexible budget
                ->orderBy('meal_type')
                ->orderBy('name')
                ->get();

            return view('meal-plans.bulk-create', compact('meals'));
        } catch (\Exception $e) {
            Log::error('Bulk meal plan create error: ' . $e->getMessage());
            return redirect()->route('meal-plans.index')
                ->with('error', 'Unable to load bulk meal planning. Please try again.');
        }
    }

    /**
     * Store bulk meal plans for entire day.
     */
    public function bulkStore(Request $request)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return redirect()->route('login')->with('error', 'Please login to create meal plans.');
            }

            $request->validate([
                'scheduled_date' => 'required|date',
                'breakfast_meal_id' => 'required|exists:meals,id',
                'lunch_meal_id' => 'required|exists:meals,id', 
                'dinner_meal_id' => 'required|exists:meals,id',
                'snack_meal_id' => 'nullable|exists:meals,id',
            ]);

            $scheduledDate = $request->scheduled_date;
            $userTz = $user->timezone ?? config('app.timezone');

            // Check for existing meal plans on this date
            $existingPlans = $user->mealPlansForDate($scheduledDate)->get();
            if ($existingPlans->isNotEmpty()) {
                return back()->with('error', 'You already have meal plans for this date. Please choose a different date or edit existing plans.');
            }

            DB::beginTransaction();

            // Create meal plans for each meal type
            $mealPlans = [];
            $mealTypes = [
                'breakfast' => ['time' => '08:00', 'meal_id' => $request->breakfast_meal_id],
                'lunch' => ['time' => '12:00', 'meal_id' => $request->lunch_meal_id],
                'dinner' => ['time' => '18:00', 'meal_id' => $request->dinner_meal_id],
            ];

            // Add snack if selected
            if ($request->snack_meal_id) {
                $mealTypes['snack'] = ['time' => '15:00', 'meal_id' => $request->snack_meal_id];
            }

            foreach ($mealTypes as $mealType => $data) {
                $mealPlan = MealPlan::create([
                    'user_id' => $user->id,
                    'meal_id' => $data['meal_id'],
                    'scheduled_date' => $scheduledDate,
                    'scheduled_time' => $data['time'],
                    'meal_type' => $mealType,
                    'status' => 'planned',
                    'created_at' => now($userTz),
                    'updated_at' => now($userTz),
                ]);
                
                $mealPlans[] = $mealPlan;
            }

            DB::commit();

            // Send notification email
            try {
                $this->emailService->sendBulkMealPlanConfirmation($user, $mealPlans, $scheduledDate);
            } catch (\Exception $e) {
                Log::warning('Failed to send bulk meal plan confirmation email: ' . $e->getMessage());
            }

            return redirect()->route('meal-plans.index', ['date' => $scheduledDate])
                ->with('success', 'Your daily meal plan has been created successfully! Check your email for confirmation.');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Bulk meal plan creation failed: ' . $e->getMessage(), [
                'user_id' => $user->id ?? null,
                'date' => $request->scheduled_date ?? null,
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Failed to create meal plan. Please try again.')
                ->withInput();
        }
    }
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

        $mealPlan = MealPlan::create([
            'user_id' => $user->id,
            'meal_id' => $request->meal_id,
            'scheduled_date' => $request->scheduled_date,
            'meal_type' => $request->meal_type,
        ]);

        // Send meal plan confirmation email
        $meal = Meal::find($request->meal_id);
        $submissionData = [
            'meal_name' => $meal->name,
            'meal_type' => ucfirst($request->meal_type),
            'scheduled_date' => Carbon::parse($request->scheduled_date)->format('F j, Y'),
            'cost' => '₱'.number_format($meal->cost, 2),
            'cuisine_type' => $meal->cuisine_type,
            'difficulty' => ucfirst($meal->difficulty),
        ];

        $nextSteps = [
            'View your complete meal plan for the week',
            'Generate a shopping list for your meals',
            'Set up meal prep reminders if needed',
            'Track your nutrition and budget progress',
        ];

        $this->emailService->sendFormSubmissionConfirmation(
            $user,
            'meal_plan',
            $submissionData,
            'MP-'.$mealPlan->id.'-'.time(),
            $nextSteps
        );

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
            'notes' => 'nullable|string|max:500',
            'servings' => 'nullable|integer|min:1|max:10',
            'prep_reminder' => 'nullable|in:none,30min,1hour,2hours,1day',
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
            'notes' => $request->notes,
            'servings' => $request->servings ?? 1,
            'prep_reminder' => $request->prep_reminder ?? 'none',
        ]);

        return redirect()->route('meal-plans.index')
            ->with('success', 'Meal plan updated successfully with all your preferences!');
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
            'is_completed' => ! $mealPlan->is_completed,
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
                'meals' => $weeklyMeals->filter(function ($meal) use ($date) {
                    return $meal->scheduled_date->format('Y-m-d') === $date->format('Y-m-d');
                }),
            ]);
        }

        return view('meal-plans.weekly', compact('weekDays', 'startDate'));
    }
}
