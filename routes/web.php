<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminRecipeController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MealPlanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecipeController;
use Illuminate\Support\Facades\Route;

// Welcome page
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendPasswordResetLink'])->name('password.email');
});

// Logout route
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Protected routes
Route::middleware('auth')->group(function () {
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
    
    // Recipes
    Route::get('/recipes', [RecipeController::class, 'index'])->name('recipes.index');
    Route::get('/recipes/search', [RecipeController::class, 'search'])->name('recipes.search');
    Route::get('/recipes/{meal}', [RecipeController::class, 'show'])->name('recipes.show');
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');
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
});
