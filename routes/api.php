<?php

use App\Http\Controllers\Api\NutritionController;
use App\Http\Controllers\Api\SessionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assig Please fix the following errors:

    Failed to save recipe: The calories field is required. (and 1 more error)

ned to the "api" middleware group. Make something great!
|
*/

// Session & CSRF management API
Route::get('/csrf-token', [SessionController::class, 'getCsrfToken'])->name('api.csrf-token');
Route::get('/session-check', [SessionController::class, 'checkSession'])->name('api.session-check');
Route::post('/session-refresh', [SessionController::class, 'refreshSession'])->name('api.session-refresh');
Route::post('/csrf-validate', [SessionController::class, 'validateToken'])->name('api.csrf-validate');

// Nutrition calculation API (no auth required for better UX)
Route::post('/nutrition/calculate', [NutritionController::class, 'calculate'])->name('api.nutrition.calculate');
Route::get('/nutrition/ingredients', [NutritionController::class, 'getIngredients'])->name('api.nutrition.ingredients');

// Additional nutrition endpoints without CSRF/auth requirements  
Route::post('/calculate-ingredient-nutrition', [\App\Http\Controllers\Api\NutritionApiController::class, 'calculateIngredient'])->name('api.calculate-ingredient-nutrition');
Route::post('/calculate-recipe-nutrition', [\App\Http\Controllers\Api\NutritionApiController::class, 'calculateRecipe'])->name('api.calculate-recipe-nutrition');
