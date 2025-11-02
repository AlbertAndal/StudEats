<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminMarketPriceController;
use App\Http\Controllers\Admin\AdminRecipeController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\MealPlanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProfilePhotoController;
use App\Http\Controllers\RecipeController;
use Illuminate\Support\Facades\Route;

// Welcome page
Route::get('/', function () {
    // Get sample meals for the landing page (featured first, then by cost)
    $sampleMeals = \App\Models\Meal::whereNotNull('image_path')
        ->orderBy('is_featured', 'desc')
        ->orderBy('cost', 'asc')
        ->limit(6)
        ->get();
    
    return view('welcome', compact('sampleMeals'));
})->name('welcome');

// Contact page
Route::get('/contact', [App\Http\Controllers\ContactController::class, 'show'])->name('contact.show');
Route::post('/contact', [App\Http\Controllers\ContactController::class, 'submit'])->name('contact.submit');

// Legal pages
Route::get('/privacy-policy', function () {
    return view('legal.privacy-policy');
})->name('privacy-policy');

Route::get('/terms-of-service', function () {
    return view('legal.terms-of-service');
})->name('terms-of-service');

Route::get('/policies', function () {
    return view('legal.combined-policies');
})->name('policies.combined');

// Calendar component demo (for development)
Route::get('/calendar-demo', function () {
    return view('components.calendar-demo');
})->name('calendar.demo');

// Nutrition API Test Page (for development/admin)
Route::get('/test-nutrition-api', function () {
    return view('test-nutrition-api');
})->middleware(['auth', 'admin'])->name('test.nutrition.api');

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/admin/login', [AuthController::class, 'showAdminLogin'])->name('admin.login');
    Route::post('/admin/login', [AuthController::class, 'adminLogin'])->name('admin.login.submit');
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendPasswordResetLink'])->name('password.email');
});

// Email verification routes (accessible to both guests and authenticated users)
Route::get('/email/verify', [EmailVerificationController::class, 'showVerification'])->name('email.verify.form');
Route::post('/email/verify', [EmailVerificationController::class, 'verifyOtp'])->name('email.verify.otp');
Route::post('/email/resend', [EmailVerificationController::class, 'resendOtp'])->name('email.verify.resend');
Route::get('/email/verify/{token}', [EmailVerificationController::class, 'verifyEmail'])->name('email.verify.token');

// Public recipes routes (accessible to all users)
Route::get('/recipes', [RecipeController::class, 'index'])->name('recipes.index');
Route::get('/recipes/search', [RecipeController::class, 'search'])->name('recipes.search');
Route::get('/recipes/{meal}', [RecipeController::class, 'show'])->name('recipes.show');

// Logout route
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Protected routes (removed 'verified' middleware)
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Meal Plans
    Route::get('/meal-plans', [MealPlanController::class, 'index'])->name('meal-plans.index');
    Route::get('/meal-plans/create', [MealPlanController::class, 'create'])->name('meal-plans.create');
    Route::get('/meal-plans/select', [MealPlanController::class, 'selectMealType'])->name('meal-plans.select');
    Route::get('/meal-plans/weekly', [MealPlanController::class, 'weekly'])->name('meal-plans.weekly');
    Route::post('/meal-plans', [MealPlanController::class, 'store'])->name('meal-plans.store');
    Route::get('/meal-plans/{mealPlan}', [MealPlanController::class, 'show'])->name('meal-plans.show');
    Route::get('/meal-plans/{mealPlan}/edit', [MealPlanController::class, 'edit'])->name('meal-plans.edit');
    Route::put('/meal-plans/{mealPlan}', [MealPlanController::class, 'update'])->name('meal-plans.update');
    Route::delete('/meal-plans/{mealPlan}', [MealPlanController::class, 'destroy'])->name('meal-plans.destroy');
    Route::patch('/meal-plans/{mealPlan}/toggle', [MealPlanController::class, 'toggleCompletion'])->name('meal-plans.toggle');

    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');
    
    // Profile Photo routes
    Route::post('/profile/photo/upload', [ProfilePhotoController::class, 'upload'])->name('profile.photo.upload');
    Route::post('/profile/photo/crop', [ProfilePhotoController::class, 'crop'])->name('profile.photo.crop');
    Route::delete('/profile/photo', [ProfilePhotoController::class, 'delete'])->name('profile.photo.delete');
    Route::get('/profile/photo/status', [ProfilePhotoController::class, 'status'])->name('profile.photo.status');
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/system-health', [AdminDashboardController::class, 'systemHealth'])->name('system-health');

    // User Management
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('users.show');
    Route::patch('/users/{user}/suspend', [AdminUserController::class, 'suspend'])->name('users.suspend');
    Route::patch('/users/{user}/activate', [AdminUserController::class, 'activate'])->name('users.activate');
    Route::patch('/users/{user}/reset-password', [AdminUserController::class, 'resetPassword'])->name('users.reset-password');
    Route::patch('/users/{user}/role', [AdminUserController::class, 'updateRole'])->name('users.update-role');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');

    // Recipe Management
    Route::resource('recipes', AdminRecipeController::class);
    Route::post('recipes/{recipe}/toggle-featured', [AdminRecipeController::class, 'toggleFeatured'])->name('recipes.toggle-featured');

    // Analytics
    Route::get('/analytics/data', [AnalyticsController::class, 'getData'])->name('analytics.data');
    Route::get('/analytics/hourly', [AnalyticsController::class, 'getHourlyActivity'])->name('analytics.hourly');
    Route::post('/analytics/refresh', [AnalyticsController::class, 'refresh'])->name('analytics.refresh');

    // Market Prices
    Route::get('/market-prices', [AdminMarketPriceController::class, 'index'])->name('market-prices.index');
    Route::post('/market-prices/update', [AdminMarketPriceController::class, 'update'])->name('market-prices.update');
    Route::get('/market-prices/stats', [AdminMarketPriceController::class, 'stats'])->name('market-prices.stats');
    Route::get('/market-prices/ingredients', [AdminMarketPriceController::class, 'ingredients'])->name('market-prices.ingredients');
    Route::get('/market-prices/{ingredient}/history', [AdminMarketPriceController::class, 'history'])->name('market-prices.history');
});

// API Routes for Dynamic Pricing System
Route::prefix('api')->group(function () {
    Route::post('/ingredient-price', [App\Http\Controllers\Api\IngredientPriceController::class, 'getPrice']);
    Route::get('/ingredients-list', [App\Http\Controllers\Api\IngredientPriceController::class, 'getIngredientsList']);
    Route::post('/ingredient-prices/bulk', [App\Http\Controllers\Api\IngredientPriceController::class, 'bulkUpdatePrices']);
    Route::get('/pricing-stats', [App\Http\Controllers\Api\IngredientPriceController::class, 'getPricingStats']);
    
    // Market Prices Search API (keep auth for admin-only routes)
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/market-prices/search', [AdminMarketPriceController::class, 'search']);
        Route::post('/ingredient-price/{ingredient}/refresh', [AdminMarketPriceController::class, 'refreshSingle']);
        
        // Nutrition API endpoints (admin only)
        Route::post('/calculate-ingredient-nutrition', [App\Http\Controllers\Api\NutritionApiController::class, 'calculateIngredient']);
        Route::post('/calculate-recipe-nutrition', [App\Http\Controllers\Api\NutritionApiController::class, 'calculateRecipe']);
        Route::get('/search-food', [App\Http\Controllers\Api\NutritionApiController::class, 'searchFood']);
    });
});

// Health check route for Railway
Route::get('/health', function () {
    try {
        // Check database connection
        DB::connection()->getPdo();
        
        return response()->json([
            'status' => 'healthy',
            'database' => 'connected',
            'timestamp' => now()->toISOString()
        ], 200);
    } catch (Exception $e) {
        return response()->json([
            'status' => 'unhealthy',
            'database' => 'disconnected',
            'error' => $e->getMessage(),
            'timestamp' => now()->toISOString()
        ], 503);
    }
});
