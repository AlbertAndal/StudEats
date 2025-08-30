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
        // Create a sample user
        User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password'),
            'age' => 25,
            'daily_budget' => 300.00,
            'dietary_preferences' => ['vegetarian', 'low_carb']
        ]);

        // Seed Filipino cuisine meals
        $this->call([
            FilipinoCuisineSeeder::class
        ]);
    }
}
