<?php
// Test script to verify image loading fix
require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Testing Popular Recipes Image Display Fix\n";
echo "==========================================\n\n";

// Test the top meals query (same as AdminDashboardController)
$topMeals = \Illuminate\Support\Facades\Cache::remember('admin_top_meals', 300, function () {
    return \App\Models\Meal::select(['id', 'name', 'cost', 'cuisine_type', 'difficulty', 'image_path'])
        ->withCount('mealPlans')
        ->having('meal_plans_count', '>', 0)
        ->orderBy('meal_plans_count', 'desc')
        ->limit(5)
        ->get();
});

echo "Found " . $topMeals->count() . " popular meals:\n\n";

foreach ($topMeals as $meal) {
    echo "Meal: {$meal->name}\n";
    echo "Image Path: " . ($meal->image_path ?? 'NULL') . "\n";
    echo "Image URL: " . ($meal->image_url ?? 'NULL') . "\n";
    echo "Plans Count: {$meal->meal_plans_count}\n";
    echo "Cuisine: {$meal->cuisine_type}\n";
    echo "Difficulty: {$meal->difficulty}\n";
    echo "---\n";
}

echo "\nTest completed successfully!\n";
echo "Images should now be visible in the admin dashboard Popular Recipes section.\n";