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

// Debug endpoint for checking API configuration (remove in production)
Route::get('/debug/nutrition-config', function () {
    return response()->json([
        'api_key_loaded' => env('NUTRITION_API_KEY') ? 'YES' : 'NO',
        'api_key_length' => env('NUTRITION_API_KEY') ? strlen(env('NUTRITION_API_KEY')) : 0,
        'api_key_preview' => env('NUTRITION_API_KEY') ? substr(env('NUTRITION_API_KEY'), 0, 8) . '...' : 'NOT_SET',
        'api_url' => env('NUTRITION_API_URL', 'NOT_SET'),
        'environment' => app()->environment(),
        'config_cached' => config('app.name') ? 'YES' : 'NO',
        'timestamp' => now()->toISOString()
    ]);
});

// Test nutrition API endpoint
Route::post('/debug/test-nutrition', function (\Illuminate\Http\Request $request) {
    try {
        $service = app(\App\Services\NutritionApiService::class);
        $testIngredient = $request->input('ingredient', 'chicken breast');
        
        $result = $service->searchFood($testIngredient);
        
        return response()->json([
            'success' => true,
            'test_ingredient' => $testIngredient,
            'api_responded' => $result ? 'YES' : 'NO',
            'result_count' => isset($result['foods']) ? count($result['foods']) : 0,
            'first_result' => isset($result['foods'][0]['description']) ? $result['foods'][0]['description'] : 'N/A',
            'raw_response' => $result
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]);
    }
});

// Nutrition API routes
Route::post('/nutrition/calculate', [NutritionController::class, 'calculate'])->name('api.nutrition.calculate');
Route::get('/nutrition/ingredients', [NutritionController::class, 'getIngredients'])->name('api.nutrition.ingredients');

// Additional nutrition endpoints without CSRF/auth requirements  
Route::post('/calculate-ingredient-nutrition', [\App\Http\Controllers\Api\NutritionApiController::class, 'calculateIngredient'])->name('api.calculate-ingredient-nutrition');
Route::post('/calculate-recipe-nutrition', [\App\Http\Controllers\Api\NutritionApiController::class, 'calculateRecipe'])->name('api.calculate-recipe-nutrition');
