<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Meal;
use App\Models\MealPlan;
use Carbon\Carbon;

class FilipinoWeeklyMealPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get a user to assign meal plans to (or create one if none exists)
        $user = User::first();
        if (!$user) {
            $user = User::create([
                'name' => 'Filipino Food Lover',
                'email' => 'filipino@example.com',
                'password' => bcrypt('password'),
                'age' => 25,
                'daily_budget' => 500.00,
                'dietary_preferences' => ['Filipino', 'traditional']
            ]);
        }

        // Get all Filipino meals by categories
        $breakfastMeals = Meal::whereIn('name', ['Tapsilog', 'Longsilog', 'Champorado'])->get();
        $lunchMeals = Meal::whereIn('name', ['Chicken Adobo', 'Sinigang na Baboy', 'Kare-Kare'])->get();
        $dinnerMeals = Meal::whereIn('name', ['Crispy Pata', 'Lechon Kawali', 'Pancit Canton'])->get();
        $snackMeals = Meal::whereIn('name', ['Turon', 'Fresh Lumpia (Lumpiang Sariwa)', 'Puto'])->get();

        // Create a 7-day Filipino meal plan starting from today
        $startDate = Carbon::today();

        for ($day = 0; $day < 7; $day++) {
            $currentDate = $startDate->copy()->addDays($day);

            // Breakfast
            if ($breakfastMeals->isNotEmpty()) {
                $breakfastMeal = $breakfastMeals[$day % $breakfastMeals->count()];
                MealPlan::create([
                    'user_id' => $user->id,
                    'meal_id' => $breakfastMeal->id,
                    'scheduled_date' => $currentDate,
                    'meal_type' => 'breakfast',
                    'is_completed' => false,
                ]);
            }

            // Lunch
            if ($lunchMeals->isNotEmpty()) {
                $lunchMeal = $lunchMeals[$day % $lunchMeals->count()];
                MealPlan::create([
                    'user_id' => $user->id,
                    'meal_id' => $lunchMeal->id,
                    'scheduled_date' => $currentDate,
                    'meal_type' => 'lunch',
                    'is_completed' => false,
                ]);
            }

            // Dinner
            if ($dinnerMeals->isNotEmpty()) {
                $dinnerMeal = $dinnerMeals[$day % $dinnerMeals->count()];
                MealPlan::create([
                    'user_id' => $user->id,
                    'meal_id' => $dinnerMeal->id,
                    'scheduled_date' => $currentDate,
                    'meal_type' => 'dinner',
                    'is_completed' => false,
                ]);
            }

            // Snack (every other day to avoid too many meals)
            if ($snackMeals->isNotEmpty() && $day % 2 == 0) {
                $snackMeal = $snackMeals[($day / 2) % $snackMeals->count()];
                MealPlan::create([
                    'user_id' => $user->id,
                    'meal_id' => $snackMeal->id,
                    'scheduled_date' => $currentDate,
                    'meal_type' => 'snack',
                    'is_completed' => false,
                ]);
            }
        }
    }
}