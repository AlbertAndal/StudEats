<?php

use App\Http\Controllers\Api\NutritionController;
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

// Nutrition calculation API
Route::post('/nutrition/calculate', [NutritionController::class, 'calculate'])->name('api.nutrition.calculate');
Route::get('/nutrition/ingredients', [NutritionController::class, 'getIngredients'])->name('api.nutrition.ingredients');
