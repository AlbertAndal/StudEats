<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // IMPORTANT: Create default admin account first
        $this->call([
            AdminSeeder::class,
        ]);

        // Create a sample user for testing
        User::firstOrCreate(
            ['email' => 'john@example.com'],
            [
                'name' => 'John Doe',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'age' => 25,
                'daily_budget' => 300.00,
                'dietary_preferences' => ['vegetarian', 'low_carb']
            ]
        );

        // Seed Filipino cuisine meals
        $this->call([
            FilipinoCuisineSeeder::class,
            FilipinoMealPlanSeeder::class,
            FilipinoWeeklyMealPlanSeeder::class
        ]);
    }
}
