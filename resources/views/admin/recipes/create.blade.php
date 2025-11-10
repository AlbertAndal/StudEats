@extends('layouts.admin')

@section('title', 'Add New Recipe')

@section('content')
<style>
/* Remove spinner arrows from number inputs in readonly nutrition fields */
input[type=number].no-spinners::-webkit-outer-spin-button,
input[type=number].no-spinners::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
input[type=number].no-spinners {
    -moz-appearance: textfield;
    appearance: textfield;
}
</style>

<!-- Admin Header -->
<div class="bg-white shadow-sm border-b border-gray-200 mb-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-6">
            <div class="flex items-center space-x-4">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                    </div>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Add New Recipe</h1>
                    <p class="text-sm text-gray-600">Create a new recipe with ingredients, instructions, and nutritional information</p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.recipes.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Recipes
                </a>
            </div>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <form action="{{ route('admin.recipes.store') }}" method="POST" enctype="multipart/form-data" novalidate>
        @csrf
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-md p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">There were errors with your submission</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        
        <!-- Two Column Admin Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Left Column -->
            <div class="space-y-6">
                <!-- Basic Information Card -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Basic Information</h3>
                        <p class="mt-1 text-sm text-gray-600">Enter the basic details for this recipe</p>
                    </div>
                    <div class="px-6 py-4 space-y-4">
                        <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Recipe Name</label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}" 
                               required maxlength="150"
                               aria-required="true"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="cuisine_type" class="block text-sm font-medium text-gray-700 mb-2">Cuisine Type</label>
                        <input type="text" 
                               id="cuisine_type" 
                               name="cuisine_type" 
                               value="{{ old('cuisine_type') }}" 
                               placeholder="e.g., Filipino, Italian, Chinese"
                               required maxlength="80"
                               aria-required="true"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('cuisine_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="difficulty" class="block text-sm font-medium text-gray-700 mb-2">Difficulty Level</label>
                        <select id="difficulty" 
                                name="difficulty" 
                                required aria-required="true"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select difficulty</option>
                            <option value="easy" {{ old('difficulty') == 'easy' ? 'selected' : '' }}>Easy</option>
                            <option value="medium" {{ old('difficulty') == 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="hard" {{ old('difficulty') == 'hard' ? 'selected' : '' }}>Hard</option>
                        </select>
                        @error('difficulty')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="meal_type" class="block text-sm font-medium text-gray-700 mb-2">Meal Type</label>
                        <select id="meal_type" 
                                name="meal_type" 
                                required aria-required="true"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select meal type</option>
                            <option value="breakfast" {{ old('meal_type') == 'breakfast' ? 'selected' : '' }}>Breakfast</option>
                            <option value="lunch" {{ old('meal_type') == 'lunch' ? 'selected' : '' }}>Lunch</option>
                            <option value="snack" {{ old('meal_type') == 'snack' ? 'selected' : '' }}>Snack</option>
                            <option value="dinner" {{ old('meal_type') == 'dinner' ? 'selected' : '' }}>Dinner</option>
                        </select>
                        @error('meal_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="cost" class="block text-sm font-medium text-gray-700 mb-2">Total Recipe Cost (₱)</label>
                        <input type="number" 
                               id="cost" 
                               name="cost" 
                               value="{{ old('cost') }}" 
                               step="0.01" 
                               min="0" max="999999.99"
                               required aria-required="true"
                               readonly
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-700" aria-describedby="cost_help">
                        <p id="cost_help" class="mt-1 text-xs text-gray-500">Automatically calculated from ingredient prices.</p>
                        @error('cost')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea id="description" 
                                  name="description" 
                                  rows="4" 
                                  required maxlength="500"
                                  aria-required="true"
                                  placeholder="Brief description of the recipe (max 500 characters)..."
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Recipe Image</label>
                        <input type="file" 
                               id="image" 
                               name="image" 
                               accept="image/*"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('image')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" 
                               id="is_featured" 
                               name="is_featured" 
                               value="1"
                               {{ old('is_featured') ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="is_featured" class="ml-2 block text-sm text-gray-700">
                            Mark as Featured Recipe
                        </label>
                    </div>
                    </div>
                </div>

                <!-- Recipe Details Card -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Recipe Details</h3>
                        <p class="mt-1 text-sm text-gray-600">Timing, servings, cost and cooking instructions</p>
                    </div>
                    <div class="px-6 py-4 space-y-4">
                        <div>
                            <label for="prep_time" class="block text-sm font-medium text-gray-700 mb-2">Prep Time (minutes)</label>
                            <input type="number" 
                                   id="prep_time" 
                                   name="prep_time" 
                                   value="{{ old('prep_time') }}" 
                                   min="1" max="1440"
                                   required aria-required="true"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('prep_time')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="cook_time" class="block text-sm font-medium text-gray-700 mb-2">Cook Time (minutes)</label>
                            <input type="number" 
                                   id="cook_time" 
                                   name="cook_time" 
                                   value="{{ old('cook_time') }}" 
                                   min="1" max="1440"
                                   required aria-required="true"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('cook_time')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Hidden servings field - always 1 person -->
                        <input type="hidden" id="servings" name="servings" value="1">

                        <div>
                            <label for="instructions" class="block text-sm font-medium text-gray-700 mb-2">Instructions</label>
                            <textarea id="instructions" 
                                      name="instructions" 
                                      rows="12" 
                                      required aria-required="true"
                                      placeholder="Step-by-step cooking instructions... One step per line."
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('instructions') }}</textarea>
                            @error('instructions')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right Column -->
            <div class="space-y-6">
                <!-- Nutritional Information Card -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">Nutritional Information</h3>
                                <p class="mt-1 text-sm text-gray-600">Automatically calculated from ingredients via USDA database</p>
                            </div>
                            <button type="button" 
                                    id="calculate-nutrition-btn"
                                    onclick="calculateNutrition()"
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                                Calculate
                            </button>
                        </div>
                    </div>
                    <div class="px-6 py-4">
                    
                    <!-- Nutrition Loading Indicator -->
                    <div id="nutrition-loading" class="hidden text-center py-8">
                        <div class="inline-flex items-center px-4 py-2 font-semibold leading-6 text-sm shadow rounded-md text-white bg-green-500 transition ease-in-out duration-150">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Calculating nutrition from USDA database...
                        </div>
                    </div>
                    
                    <!-- Nutrition Results -->
                    <div id="nutrition-results" class="grid grid-cols-2 gap-4 mt-4">
                    <div>
                        <label for="calories" class="block text-sm font-medium text-gray-700 mb-2">Calories</label>
                        <input type="number" 
                               id="calories" 
                               name="calories" 
                               value="{{ old('calories') }}" 
                               min="0" max="5000"
                               required aria-required="true"
                               readonly
                               class="no-spinners w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed">
                        <p class="mt-1 text-xs text-gray-500">Automatically calculated from ingredients</p>
                        @error('calories')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="protein" class="block text-sm font-medium text-gray-700 mb-2">Protein (g)</label>
                        <input type="number" 
                               id="protein" 
                               name="protein" 
                               value="{{ old('protein') }}" 
                               step="0.1" 
                               min="0" max="500"
                               required aria-required="true"
                               readonly
                               class="no-spinners w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed">
                        <p class="mt-1 text-xs text-gray-500">Automatically calculated from ingredients</p>
                        @error('protein')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="carbs" class="block text-sm font-medium text-gray-700 mb-2">Carbohydrates (g)</label>
                        <input type="number" 
                               id="carbs" 
                               name="carbs" 
                               value="{{ old('carbs') }}" 
                               step="0.1" 
                               min="0" max="1000"
                               required aria-required="true"
                               readonly
                               class="no-spinners w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed">
                        <p class="mt-1 text-xs text-gray-500">Automatically calculated from ingredients</p>
                        @error('carbs')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="fats" class="block text-sm font-medium text-gray-700 mb-2">Fats (g)</label>
                        <input type="number" 
                               id="fats" 
                               name="fats" 
                               value="{{ old('fats') }}" 
                               step="0.1" 
                               min="0" max="500"
                               required aria-required="true"
                               readonly
                               class="no-spinners w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed">
                        <p class="mt-1 text-xs text-gray-500">Automatically calculated from ingredients</p>
                        @error('fats')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="fiber" class="block text-sm font-medium text-gray-700 mb-2">Fiber (g)</label>
                        <input type="number" 
                               id="fiber" 
                               name="fiber" 
                               value="{{ old('fiber') }}" 
                               step="0.1" 
                               min="0" max="200"
                               readonly
                               class="no-spinners w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed">
                        <p class="mt-1 text-xs text-gray-500">Automatically calculated from ingredients</p>
                        @error('fiber')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="sugar" class="block text-sm font-medium text-gray-700 mb-2">Sugar (g)</label>
                        <input type="number" 
                               id="sugar" 
                               name="sugar" 
                               value="{{ old('sugar') }}" 
                               step="0.1" 
                               min="0" max="500"
                               readonly
                               class="no-spinners w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed">
                        <p class="mt-1 text-xs text-gray-500">Automatically calculated from ingredients</p>
                        @error('sugar')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="sodium" class="block text-sm font-medium text-gray-700 mb-2">Sodium (mg)</label>
                        <input type="number" 
                               id="sodium" 
                               name="sodium" 
                               value="{{ old('sodium') }}" 
                               min="0" max="100000"
                               readonly
                               class="no-spinners w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed">
                        <p class="mt-1 text-xs text-gray-500">Automatically calculated from ingredients</p>
                        @error('sodium')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                        <!-- Nutrition Status Messages -->
                        <div id="nutrition-status" class="mt-4"></div>
                    </div>
                </div>
                
                <!-- Recipe Ingredients Card -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Recipe Ingredients</h3>
                        <div class="mt-1 text-sm text-gray-600 flex items-center justify-between">
                            <span>Add ingredients manually or use bulk upload.</span>
                            <div class="flex items-center space-x-3">
                                <button type="button" 
                                        onclick="toggleFormatHelp()"
                                        class="text-purple-600 hover:text-purple-700 font-medium">
                                    View bulk upload formats ↓
                                </button>
                                <button type="button" 
                                        onclick="showIngredientInfoModal()"
                                        class="text-blue-600 hover:text-blue-700 font-medium">
                                    ℹ️ Ingredient Info
                                </button>
                            </div>
                        </div>
                        
                        <!-- Format Help Panel (Hidden by default) -->
                        <div id="formatHelp" class="hidden mt-3 p-3 bg-purple-50 rounded-lg border border-purple-200">
                            <h4 class="font-medium text-purple-900 mb-2">Bulk Upload Formats:</h4>
                            <div class="space-y-2 text-sm text-purple-700">
                                <div>
                                    <strong>CSV Format:</strong> <code class="bg-white px-1 rounded">ingredient name,quantity,unit,price</code>
                                    <div class="ml-4 text-xs">Example: <code class="bg-white px-1 rounded">Chicken breast,500,g,8.50</code></div>
                                </div>
                                <div>
                                    <strong>Simple Format:</strong> <code class="bg-white px-1 rounded">ingredient name - quantity unit</code>
                                    <div class="ml-4 text-xs">Example: <code class="bg-white px-1 rounded">Chicken breast - 500 g</code></div>
                                </div>
                                <div>
                                    <strong>Raw List:</strong> <code class="bg-white px-1 rounded">ingredient name</code> (one per line)
                                    <div class="ml-4 text-xs">Example: <code class="bg-white px-1 rounded">Chicken breast</code></div>
                                </div>
                            </div>
                        </div>
                        <p class="mt-1 text-sm text-gray-600">Add ingredients with quantities - prices and nutrition calculated automatically</p>
                    </div>
                    
                    <div class="p-6 space-y-4">
                        <!-- Ingredients Header -->
                        <div class="grid grid-cols-12 gap-3 text-xs font-medium text-gray-600 uppercase tracking-wide border-b border-gray-200 pb-2">
                            <div class="col-span-3">Ingredient Name</div>
                            <div class="col-span-2 text-center">Quantity</div>
                            <div class="col-span-2 text-center">Unit</div>
                            <div class="col-span-2 text-center">Unit Price</div>
                            <div class="col-span-2 text-center">Total Price</div>
                            <div class="col-span-1 text-center">Remove</div>
                        </div>
                        
                        <!-- Ingredients Grid Container -->
                        <div class="space-y-3">
                            <div id="ingredients-container" class="min-h-[80px]">
                                <!-- Ingredients will be added here dynamically -->
                                <div class="text-center py-6 text-gray-400">
                                    <svg class="w-10 h-10 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3-6h.008v.008H15.75V12zm0 3h.008v.008H15.75V15zm0 3h.008v.008H15.75V18zm-12-3h3.75m0 0h3.75m0 0v3.75M5.25 15V9.75M5.25 15a2.25 2.25 0 01-2.25-2.25V9.75A2.25 2.25 0 015.25 7.5h3.75"/>
                                    </svg>
                                    <p class="text-sm font-medium text-gray-500">No ingredients added yet</p>
                                    <p class="text-xs text-gray-400 mt-1">Click "Add Ingredient" to get started</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Total Price Display -->
                        <div class="border-t border-gray-200 pt-4">
                            <div class="flex justify-between items-center bg-green-50 px-4 py-3 rounded-lg">
                                <span class="text-sm font-medium text-gray-700">Total Recipe Cost:</span>
                                <span id="total-recipe-cost" class="text-lg font-bold text-green-600">₱0.00</span>
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="pt-4 flex items-center gap-3">
                            <button type="button" 
                                    onclick="addIngredient()"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                                </svg>
                                Add Ingredient
                            </button>
                            
                            <button type="button" 
                                    onclick="document.getElementById('bulkUploadFile').click()"
                                    class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
                                </svg>
                                Bulk Upload
                            </button>
                            
                            <input type="file" 
                                   id="bulkUploadFile" 
                                   accept=".csv,.txt" 
                                   style="display: none;" 
                                   onchange="handleBulkUpload(event)">
                            
                            <button type="button" 
                                    onclick="saveAllIngredients()"
                                    class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                                Save All Ingredients
                            </button>
                        </div>
                    </div>
                    </div>
                    @error('ingredients')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
        
        <!-- Admin Form Actions -->
        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-200 mt-8">
            <button type="submit" 
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Create Recipe
            </button>
            <a href="{{ route('admin.recipes.index') }}" 
               class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                Cancel
            </a>
        </div>
    </form>
</div>
    </div>
</div>

<script>
// Define modal functions early to ensure availability
function showDeleteConfirmationModal(ingredientName, onConfirm) {
    const modal = document.getElementById('deleteConfirmationModal');
    const modalContent = document.getElementById('deleteConfirmationModalContent');
    const ingredientNameSpan = document.getElementById('deleteIngredientName');
    const confirmBtn = document.getElementById('confirmDeleteBtn');
    
    ingredientNameSpan.textContent = ingredientName || 'this ingredient';
    
    // Set up confirmation handler
    const newConfirmBtn = confirmBtn.cloneNode(true);
    confirmBtn.parentNode.replaceChild(newConfirmBtn, confirmBtn);
    
    newConfirmBtn.onclick = function() {
        onConfirm();
        closeDeleteConfirmationModal();
    };
    
    modal.style.display = 'flex';
    modal.style.opacity = '0';
    modalContent.style.transform = 'scale(0.95)';
    
    setTimeout(() => {
        modal.style.opacity = '1';
        modalContent.style.transform = 'scale(1)';
    }, 10);
}

function closeDeleteConfirmationModal() {
    const modal = document.getElementById('deleteConfirmationModal');
    const modalContent = document.getElementById('deleteConfirmationModalContent');
    
    modal.style.opacity = '0';
    modalContent.style.transform = 'scale(0.95)';
    
    setTimeout(() => {
        modal.style.display = 'none';
    }, 300);
}

let debounceTimer = null;
let priceCache = new Map();

function createIngredientRow(name = '', quantity = '', unit = '', unitPrice = '', totalPrice = '') {
    const wrapper = document.createElement('div');
    wrapper.className = 'ingredient-item grid grid-cols-12 gap-3 py-3 border-b border-gray-100';

    // Ingredient Name input with autocomplete
    const nameDiv = document.createElement('div');
    nameDiv.className = 'col-span-3';
    const nameInput = document.createElement('input');
    nameInput.type = 'text';
    nameInput.name = 'ingredient_names[]';
    nameInput.required = true;
    nameInput.maxLength = 100;
    nameInput.placeholder = 'e.g., Chicken breast, Rice, Garlic';
    nameInput.className = 'w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200';
    nameInput.value = name;
    nameInput.addEventListener('input', debounceFunction(() => fetchIngredientPrice(nameInput), 500));
    nameDiv.appendChild(nameInput);

    // Quantity input
    const quantityDiv = document.createElement('div');
    quantityDiv.className = 'col-span-2';
    const quantityInput = document.createElement('input');
    quantityInput.type = 'number';
    quantityInput.name = 'ingredient_quantities[]';
    quantityInput.required = true;
    quantityInput.step = '0.01';
    quantityInput.min = '0';
    quantityInput.placeholder = '1';
    quantityInput.className = 'w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-center transition-colors duration-200';
    quantityInput.value = quantity;
    quantityInput.addEventListener('input', () => updateRowPrice(wrapper));
    quantityDiv.appendChild(quantityInput);

    // Unit select dropdown with common options
    const unitDiv = document.createElement('div');
    unitDiv.className = 'col-span-2';
    const unitSelect = document.createElement('select');
    unitSelect.name = 'ingredient_units[]';
    unitSelect.required = true;
    unitSelect.className = 'w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200';
    unitSelect.value = unit;
    
    // Standard units supported by USDA API - only weight and volume units
    const units = [
        { value: '', text: 'Select unit...' },
        // Weight units
        { value: 'kg', text: 'Kilogram (kg)' },
        { value: 'g', text: 'Gram (g)' },
        { value: 'lb', text: 'Pound (lb)' },
        { value: 'oz', text: 'Ounce (oz)' },
        // Volume units
        { value: 'cup', text: 'Cup' },
        { value: 'tbsp', text: 'Tablespoon (tbsp)' },
        { value: 'tsp', text: 'Teaspoon (tsp)' },
        { value: 'ml', text: 'Milliliter (ml)' },
        { value: 'l', text: 'Liter (l)' },
    ];
    
    units.forEach(unitOption => {
        const option = document.createElement('option');
        option.value = unitOption.value;
        option.textContent = unitOption.text;
        if (unit === unitOption.value) {
            option.selected = true;
        }
        unitSelect.appendChild(option);
    });
    
    unitDiv.appendChild(unitSelect);

    // Unit Price input (editable)
    const unitPriceDiv = document.createElement('div');
    unitPriceDiv.className = 'col-span-2';
    const unitPriceInput = document.createElement('input');
    unitPriceInput.type = 'number';
    unitPriceInput.name = 'ingredient_prices[]';
    unitPriceInput.step = '0.01';
    unitPriceInput.min = '0';
    unitPriceInput.placeholder = '0.00';
    unitPriceInput.className = 'w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-center transition-colors duration-200';
    unitPriceInput.value = unitPrice || '';
    unitPriceInput.addEventListener('input', () => updateRowPrice(wrapper));
    unitPriceDiv.appendChild(unitPriceInput);

    // Total Price display (read-only)
    const totalPriceDiv = document.createElement('div');
    totalPriceDiv.className = 'col-span-2';
    const totalPriceDisplay = document.createElement('input');
    totalPriceDisplay.type = 'text';
    totalPriceDisplay.readonly = true;
    totalPriceDisplay.placeholder = '₱0.00';
    totalPriceDisplay.className = 'w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-green-50 text-green-700 text-center font-medium';
    totalPriceDisplay.value = totalPrice ? `₱${parseFloat(totalPrice).toFixed(2)}` : '';
    totalPriceDiv.appendChild(totalPriceDisplay);

    // Remove button
    const btnWrapper = document.createElement('div');
    btnWrapper.className = 'col-span-1 flex items-center justify-center';
    const removeBtn = document.createElement('button');
    removeBtn.type = 'button';
    removeBtn.className = 'w-8 h-8 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors flex items-center justify-center font-semibold text-lg';
    removeBtn.setAttribute('aria-label', 'Remove ingredient');
    removeBtn.setAttribute('title', 'Remove ingredient');
    removeBtn.addEventListener('click', function() { removeIngredient(removeBtn); });
    removeBtn.innerHTML = '×';
    btnWrapper.appendChild(removeBtn);

    wrapper.appendChild(nameDiv);
    wrapper.appendChild(quantityDiv);
    wrapper.appendChild(unitDiv);
    wrapper.appendChild(unitPriceDiv);
    wrapper.appendChild(totalPriceDiv);
    wrapper.appendChild(btnWrapper);
    
    return wrapper;
}

function debounceFunction(func, wait) {
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(debounceTimer);
            func(...args);
        };
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(later, wait);
    };
}

async function fetchIngredientPrice(nameInput) {
    const ingredientName = nameInput.value.trim();
    if (ingredientName.length < 2) return;
    
    // Check cache first
    if (priceCache.has(ingredientName)) {
        const cachedData = priceCache.get(ingredientName);
        updateIngredientPrice(nameInput, cachedData.price);
        return;
    }
    
    try {
        // Get CSRF token with fallback
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                         document.querySelector('input[name="_token"]')?.value || '';
        
        // Use admin-specific API endpoint without CSRF for seamless experience
        const response = await fetch('/api/admin-api/ingredient-price', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                ingredient_name: ingredientName,
                region: 'NCR'
            })
        });
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const responseText = await response.text();
        let data;
        
        try {
            data = JSON.parse(responseText);
        } catch (jsonError) {
            console.error('JSON parsing error for ingredient price:', jsonError);
            console.error('Response text:', responseText);
            throw new Error('Invalid JSON response from ingredient price API');
        }
        
        if (data.success && data.price) {
            const price = parseFloat(data.price);
            priceCache.set(ingredientName, { price, updated: Date.now() });
            updateIngredientPrice(nameInput, price);
        }
    } catch (error) {
        console.log('Price fetch failed:', error.message);
    }
}

function updateIngredientPrice(nameInput, unitPrice) {
    const row = nameInput.closest('.ingredient-item');
    if (!row) return;
    
    const unitPriceInput = row.querySelector('input[name="ingredient_prices[]"]');
    
    if (unitPriceInput) {
        unitPriceInput.value = unitPrice.toFixed(2);
        updateRowPrice(row);
    }
}

function validateIngredient(row) {
    const nameInput = row.querySelector('input[name="ingredient_names[]"]');
    const quantityInput = row.querySelector('input[name="ingredient_quantities[]"]');
    const unitSelect = row.querySelector('select[name="ingredient_units[]"]');
    const unitPriceInput = row.querySelector('input[name="ingredient_prices[]"]');
    
    // Validate required fields
    if (!nameInput.value.trim()) {
        showIngredientNameModal();
        nameInput.focus();
        return false;
    }
    
    if (!quantityInput.value || parseFloat(quantityInput.value) <= 0) {
        showValidationModal('Please enter a valid quantity', 'Enter a quantity greater than 0 for this ingredient.');
        quantityInput.focus();
        return false;
    }
    
    if (!unitSelect.value) {
        alert('Please select a unit');
        unitSelect.focus();
        return false;
    }
    
    if (!unitPriceInput.value || parseFloat(unitPriceInput.value) < 0) {
        alert('Please enter a valid price');
        unitPriceInput.focus();
        return false;
    }
    
    // Visual feedback - mark as validated
    row.classList.add('bg-green-50', 'border-l-4', 'border-l-green-500');
    setTimeout(() => {
        row.classList.remove('bg-green-50', 'border-l-4', 'border-l-green-500');
    }, 2000);
    
    return true;
}

function saveAllIngredients() {
    const container = document.getElementById('ingredients-container');
    const rows = container.querySelectorAll('.ingredient-item');
    
    if (rows.length === 0) {
        showIngredientMessage('Please add ingredients first', 'error');
        return;
    }
    
    let savedCount = 0;
    let hasErrors = false;
    
    rows.forEach(row => {
        if (validateIngredient(row)) {
            savedCount++;
        } else {
            hasErrors = true;
        }
    });
    
    if (!hasErrors) {
        showIngredientMessage(`Successfully validated ${savedCount} ingredient(s)!`, 'success');
    } else {
        showIngredientMessage('Some ingredients have validation errors', 'error');
    }
}

function updateRowPrice(row) {
    const quantityInput = row.querySelector('input[name="ingredient_quantities[]"]');
    const unitPriceInput = row.querySelector('input[name="ingredient_prices[]"]');
    const totalPriceDisplay = row.querySelector('.bg-green-50');
    
    if (!quantityInput || !unitPriceInput || !totalPriceDisplay) return;
    
    const quantity = parseFloat(quantityInput.value) || 0;
    const unitPrice = parseFloat(unitPriceInput.value) || 0;
    const totalPrice = quantity * unitPrice;
    
    totalPriceDisplay.value = totalPrice > 0 ? `₱${totalPrice.toFixed(2)}` : '';
    
    updateTotalCost();
}

function showIngredientMessage(message, type) {
    // Create message element
    const messageEl = document.createElement('div');
    messageEl.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg ${type === 'error' ? 'bg-red-50 text-red-700 border border-red-200' : 'bg-green-50 text-green-700 border border-green-200'}`;
    messageEl.textContent = message;
    
    document.body.appendChild(messageEl);
    
    setTimeout(() => {
        messageEl.remove();
    }, 3000);
}

function updateTotalCost() {
    const container = document.getElementById('ingredients-container');
    if (!container) return;
    
    let totalCost = 0;
    const rows = container.querySelectorAll('.ingredient-item');
    
    rows.forEach(row => {
        const quantityInput = row.querySelector('input[name="ingredient_quantities[]"]');
        const unitPriceInput = row.querySelector('input[name="ingredient_prices[]"]');
        
        if (quantityInput && unitPriceInput) {
            const quantity = parseFloat(quantityInput.value) || 0;
            const unitPrice = parseFloat(unitPriceInput.value) || 0;
            totalCost += quantity * unitPrice;
        }
    });
    
    // Update total cost display
    const totalCostElement = document.getElementById('total-recipe-cost');
    if (totalCostElement) {
        totalCostElement.textContent = `₱${totalCost.toFixed(2)}`;
    }
    
    // Update cost per serving
    const servingsInput = document.getElementById('servings');
    const costPerServingElement = document.getElementById('cost-per-serving');
    const costInput = document.getElementById('cost');
    
    if (servingsInput && costPerServingElement) {
        const servings = parseInt(servingsInput.value) || 1;
        const costPerServing = totalCost / servings;
        costPerServingElement.textContent = `₱${costPerServing.toFixed(2)}`;
    }
    
    // Update the cost input field
    if (costInput) {
        costInput.value = totalCost.toFixed(2);
    }
}

async function calculateNutrition() {
    const container = document.getElementById('ingredients-container');
    const rows = container.querySelectorAll('.ingredient-item');
    
    if (rows.length === 0) {
        showNutritionMessage('Please add ingredients first.', 'error');
        return;
    }
    
    const ingredients = [];
    for (let row of rows) {
        const nameInput = row.querySelector('input[name="ingredient_names[]"]');
        const quantityInput = row.querySelector('input[name="ingredient_quantities[]"]');
        const unitSelect = row.querySelector('select[name="ingredient_units[]"]');
        
        if (nameInput?.value && quantityInput?.value && unitSelect?.value) {
            ingredients.push({
                name: nameInput.value.trim(),
                quantity: parseFloat(quantityInput.value) || 0,
                unit: unitSelect.value.trim()
            });
        }
    }
    
    if (ingredients.length === 0) {
        showNutritionMessage('Please fill in ingredient details first.', 'error');
        return;
    }
    
    // Show loading state
    const loadingEl = document.getElementById('nutrition-loading');
    const resultsEl = document.getElementById('nutrition-results');
    const btnEl = document.getElementById('calculate-nutrition-btn');
    
    loadingEl.classList.remove('hidden');
    resultsEl.classList.add('opacity-50');
    btnEl.disabled = true;
    btnEl.innerHTML = '<svg class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Calculating...';
    
    try {
        // Get CSRF token with fallback
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                         document.querySelector('input[name="_token"]')?.value || '';
        
        // Use admin-specific API endpoint without CSRF for seamless experience
        const response = await fetch('/api/calculate-recipe-nutrition', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                ingredients: ingredients,
                servings: 1
            })
        });
        
        if (!response.ok) {
            // Log the error but continue for better UX
            console.warn('API response not OK:', response.status);
            // Don't throw error, just log it
        }
        
        const responseText = await response.text();
        let data;
        
        try {
            data = JSON.parse(responseText);
        } catch (jsonError) {
            console.error('JSON parsing error for nutrition calculation:', jsonError);
            console.error('Response text:', responseText);
            throw new Error('Invalid JSON response from nutrition calculation API');
        }
        
        if (data.success && data.per_serving) {
            // Update nutrition fields
            const nutrition = data.per_serving;
            document.getElementById('calories').value = Math.round(nutrition.calories || 0);
            document.getElementById('protein').value = (nutrition.protein || 0).toFixed(1);
            document.getElementById('carbs').value = (nutrition.carbs || 0).toFixed(1);
            document.getElementById('fats').value = (nutrition.fats || 0).toFixed(1);
            document.getElementById('fiber').value = (nutrition.fiber || 0).toFixed(1);
            document.getElementById('sugar').value = (nutrition.sugar || 0).toFixed(1);
            document.getElementById('sodium').value = Math.round(nutrition.sodium || 0);
            
            showNutritionMessage(`Nutrition calculated successfully for ${ingredients.length} ingredients.`, 'success');
        } else {
            throw new Error(data.message || 'Failed to calculate nutrition');
        }
    } catch (error) {
        console.error('Nutrition calculation error:', error);
        showNutritionMessage(`Error: ${error.message}`, 'error');
    } finally {
        // Hide loading state
        loadingEl.classList.add('hidden');
        resultsEl.classList.remove('opacity-50');
        btnEl.disabled = false;
        btnEl.innerHTML = '<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>Calculate Nutrition';
    }
}

function showNutritionMessage(message, type) {
    const statusEl = document.getElementById('nutrition-status');
    statusEl.innerHTML = `
        <div class="p-3 rounded-lg ${type === 'error' ? 'bg-red-50 text-red-700 border border-red-200' : 'bg-green-50 text-green-700 border border-green-200'}">
            ${message}
        </div>
    `;
    
    setTimeout(() => {
        statusEl.innerHTML = '';
    }, 5000);
}

function addIngredient(name = '', quantity = '', unit = '', unitPrice = '', totalPrice = '') {
    const container = document.getElementById('ingredients-container');
    
    // Remove placeholder if it exists
    const placeholder = container.querySelector('.text-center.py-6');
    if (placeholder) {
        placeholder.remove();
    }
    
    const row = createIngredientRow(name, quantity, unit, unitPrice, totalPrice);
    container.appendChild(row);
    row.querySelector('input').focus();
    toggleRemoveButtons();
    updateTotalCost();
}

function handleBulkUpload(event) {
    const file = event.target.files[0];
    if (!file) return;
    
    const reader = new FileReader();
    reader.onload = function(e) {
        const text = e.target.result;
        parseBulkIngredients(text);
    };
    reader.readAsText(file);
    
    // Reset the file input so the same file can be uploaded again
    event.target.value = '';
}

function parseBulkIngredients(text) {
    try {
        const lines = text.split('\n').filter(line => line.trim() !== '');
        let addedCount = 0;
        
        lines.forEach((line, index) => {
            const trimmedLine = line.trim();
            if (!trimmedLine) return;
            
            // Support multiple formats:
            // 1. CSV format: "ingredient name,quantity,unit,price"
            // 2. Simple format: "ingredient name - quantity unit"
            // 3. Raw ingredient list: "ingredient name"
            
            let name = '', quantity = '', unit = '', unitPrice = '';
            
            if (trimmedLine.includes(',')) {
                // CSV format
                const parts = trimmedLine.split(',').map(p => p.trim());
                name = parts[0] || '';
                quantity = parts[1] || '';
                unit = parts[2] || '';
                unitPrice = parts[3] || '';
            } else if (trimmedLine.includes(' - ')) {
                // Simple format: "ingredient name - quantity unit"
                const parts = trimmedLine.split(' - ');
                name = parts[0].trim();
                if (parts[1]) {
                    const quantityUnit = parts[1].trim().split(' ');
                    quantity = quantityUnit[0] || '';
                    unit = quantityUnit[1] || '';
                }
            } else {
                // Raw ingredient list
                name = trimmedLine;
            }
            
            if (name) {
                addIngredient(name, quantity, unit, unitPrice, '');
                addedCount++;
            }
        });
        
        if (addedCount > 0) {
            showIngredientMessage(`Successfully added ${addedCount} ingredients from bulk upload.`, 'success');
        } else {
            showIngredientMessage('No valid ingredients found in the uploaded file.', 'error');
        }
        
    } catch (error) {
        console.error('Error parsing bulk ingredients:', error);
        showIngredientMessage('Error parsing the uploaded file. Please check the format.', 'error');
    }
}

function removeIngredient(button) {
    const container = document.getElementById('ingredients-container');
    const row = button.closest('.ingredient-item');
    const nameInput = row.querySelector('input[name="ingredient_names[]"]');
    const ingredientName = nameInput ? nameInput.value.trim() : '';
    
    // Check if this is the last ingredient
    if (container.querySelectorAll('.ingredient-item').length <= 1) {
        console.log('Cannot delete the last ingredient');
        showValidationModal('Cannot Delete Last Ingredient', 'You must have at least one ingredient in your recipe.');
        return;
    }
    
    // Show confirmation modal
    showDeleteConfirmationModal(ingredientName || 'this ingredient', function() {
        console.log('Confirmed deletion of ingredient:', ingredientName || 'unnamed ingredient');
        row.remove();
        updateTotalCost();
        toggleRemoveButtons();
        
        // Show placeholder if no ingredients left
        if (container.querySelectorAll('.ingredient-item').length === 0) {
            container.innerHTML = `
                <div class="text-center py-6 text-gray-400">
                    <svg class="w-10 h-10 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3-6h.008v.008H15.75V12zm0 3h.008v.008H15.75V15zm0 3h.008v.008H15.75V18zm-12-3h3.75m0 0h3.75m0 0v3.75M5.25 15V9.75M5.25 15a2.25 2.25 0 01-2.25-2.25V9.75A2.25 2.25 0 715.25 7.5h3.75"/>
                    </svg>
                    <p class="text-sm font-medium text-gray-500">No ingredients added yet</p>
                    <p class="text-xs text-gray-400 mt-1">Click "Add Ingredient" to get started</p>
                </div>
            `;
        }
    });
}

function toggleRemoveButtons() {
    const container = document.getElementById('ingredients-container');
    const ingredientRows = container.querySelectorAll('.ingredient-item');
    const disable = ingredientRows.length <= 1;
    
    ingredientRows.forEach(row => {
        const btn = row.querySelector('button[aria-label="Remove ingredient"]');
        if (btn) {
            btn.disabled = disable;
            btn.classList.toggle('opacity-50', disable);
            btn.classList.toggle('cursor-not-allowed', disable);
        }
    });
}

function toggleFormatHelp() {
    const helpPanel = document.getElementById('formatHelp');
    const button = event.target;
    
    if (helpPanel.classList.contains('hidden')) {
        helpPanel.classList.remove('hidden');
        button.textContent = 'Hide bulk upload formats ↑';
    } else {
        helpPanel.classList.add('hidden');
        button.textContent = 'View bulk upload formats ↓';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Ensure CSRF token is available
    let csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    // If no meta tag exists, try to get from form
    if (!csrfToken) {
        csrfToken = document.querySelector('input[name="_token"]')?.value;
    }
    
    // If still no token, create meta tag with form token
    if (!document.querySelector('meta[name="csrf-token"]') && csrfToken) {
        const meta = document.createElement('meta');
        meta.name = 'csrf-token';
        meta.content = csrfToken;
        document.head.appendChild(meta);
    }
    
    // Log token for debugging (remove in production)
    if (!csrfToken) {
        console.log('CSRF token not found, but continuing for better UX');
    }
    
    // Add datalist for common units
    const datalist = document.createElement('datalist');
    datalist.id = 'units-list';
    // Standard units supported by USDA API - only weight and volume units
    const units = ['kg', 'g', 'lb', 'oz', 'l', 'ml', 'cup', 'tbsp', 'tsp'];
    units.forEach(unit => {
        const option = document.createElement('option');
        option.value = unit;
        datalist.appendChild(option);
    });
    document.body.appendChild(datalist);

    // Handle old input restoration with error handling
    let oldNames = [];
    let oldQuantities = [];
    let oldUnits = [];
    let oldPrices = [];
    
    try {
        oldNames = @json(old('ingredient_names', []));
        oldQuantities = @json(old('ingredient_quantities', []));
        oldUnits = @json(old('ingredient_units', []));
        oldPrices = @json(old('ingredient_prices', []));
    } catch (e) {
        console.error('Error parsing old form data:', e);
        oldNames = [];
        oldQuantities = [];
        oldUnits = [];
        oldPrices = [];
    }
    
    const container = document.getElementById('ingredients-container');
    container.innerHTML = '';
    
    if (oldNames && oldNames.length) {
        for (let i = 0; i < oldNames.length; i++) {
            addIngredient(oldNames[i] || '', oldQuantities[i] || '', oldUnits[i] || '', oldPrices[i] || '');
        }
    } else {
        // Add 2 empty ingredient rows by default
        addIngredient();
        addIngredient();
    }

    // Add event listeners to servings input for cost per serving calculation
    const servingsInput = document.getElementById('servings');
    if (servingsInput) {
        servingsInput.addEventListener('input', updateTotalCost);
    }

    // Prevent double submit and clean up empty ingredients
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            // Remove empty ingredient rows before submission
            const container = document.getElementById('ingredients-container');
            const rows = container.querySelectorAll('.ingredient-item');
            
            rows.forEach(row => {
                const nameInput = row.querySelector('input[name="ingredient_names[]"]');
                const quantityInput = row.querySelector('input[name="ingredient_quantities[]"]');
                const unitSelect = row.querySelector('select[name="ingredient_units[]"]');
                
                // Remove row if all fields are empty
                if (!nameInput?.value?.trim() && !quantityInput?.value && !unitSelect?.value) {
                    row.remove();
                }
            });
            
            // Check if at least one ingredient remains
            const remainingRows = container.querySelectorAll('.ingredient-item');
            if (remainingRows.length === 0) {
                e.preventDefault();
                alert('Please add at least one ingredient before submitting.');
                return false;
            }
            
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
                submitBtn.innerHTML = '<svg class="animate-spin w-4 h-4 inline mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 818-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Creating Recipe...';
            }
        });
    }

    // Ingredient Info Modal Functions
    function showIngredientInfoModal() {
        const modal = document.getElementById('ingredientInfoModal');
        const modalContent = document.getElementById('ingredientInfoModalContent');
        
        modal.style.display = 'flex';
        modal.style.opacity = '0';
        modalContent.style.transform = 'scale(0.95)';
        
        // Animate in
        setTimeout(() => {
            modal.style.opacity = '1';
            modalContent.style.transform = 'scale(1)';
        }, 10);
    }

    function closeIngredientInfoModal() {
        const modal = document.getElementById('ingredientInfoModal');
        const modalContent = document.getElementById('ingredientInfoModalContent');
        
        modal.style.opacity = '0';
        modalContent.style.transform = 'scale(0.95)';
        
        setTimeout(() => {
            modal.style.display = 'none';
        }, 300);
    }

    // Close modal on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeIngredientInfoModal();
            closeValidationModal();
            closeIngredientNameModal();
            closeDeleteConfirmationModal();
        }
    });

    // Close modal on backdrop click
    document.addEventListener('click', function(e) {
        if (e.target && e.target.id === 'ingredientInfoModal') {
            closeIngredientInfoModal();
        }
        if (e.target && e.target.id === 'validationModal') {
            closeValidationModal();
        }
        if (e.target && e.target.id === 'ingredientNameModal') {
            closeIngredientNameModal();
        }
        if (e.target && e.target.id === 'deleteConfirmationModal') {
            closeDeleteConfirmationModal();
        }
    });

    // Validation Modal Functions
    function showValidationModal(title, message) {
        const modal = document.getElementById('validationModal');
        const modalContent = document.getElementById('validationModalContent');
        const titleElement = document.getElementById('validationModalTitle');
        const messageElement = document.getElementById('validationModalMessage');
        
        titleElement.textContent = title;
        messageElement.textContent = message;
        
        modal.style.display = 'flex';
        modal.style.opacity = '0';
        modalContent.style.transform = 'scale(0.95)';
        
        // Animate in
        setTimeout(() => {
            modal.style.opacity = '1';
            modalContent.style.transform = 'scale(1)';
        }, 10);
    }

    function closeValidationModal() {
        const modal = document.getElementById('validationModal');
        const modalContent = document.getElementById('validationModalContent');
        
        modal.style.opacity = '0';
        modalContent.style.transform = 'scale(0.95)';
        
        setTimeout(() => {
            modal.style.display = 'none';
        }, 300);
    }

    // Ingredient Name Modal Functions
    function showIngredientNameModal() {
        const modal = document.getElementById('ingredientNameModal');
        const modalContent = document.getElementById('ingredientNameModalContent');
        
        modal.style.display = 'flex';
        modal.style.opacity = '0';
        modalContent.style.transform = 'scale(0.95)';
        
        // Animate in
        setTimeout(() => {
            modal.style.opacity = '1';
            modalContent.style.transform = 'scale(1)';
        }, 10);
    }

    function closeIngredientNameModal() {
        const modal = document.getElementById('ingredientNameModal');
        const modalContent = document.getElementById('ingredientNameModalContent');
        
        modal.style.opacity = '0';
        modalContent.style.transform = 'scale(0.95)';
        
        setTimeout(() => {
            modal.style.display = 'none';
        }, 300);
    }
});

// Global Modal Functions (accessible from onclick attributes)

// Ingredient Info Modal Functions
function showIngredientInfoModal() {
    const modal = document.getElementById('ingredientInfoModal');
    const modalContent = document.getElementById('ingredientInfoModalContent');
    
    modal.style.display = 'flex';
    modal.style.opacity = '0';
    modalContent.style.transform = 'scale(0.95)';
    
    // Animate in
    setTimeout(() => {
        modal.style.opacity = '1';
        modalContent.style.transform = 'scale(1)';
    }, 10);
}

function closeIngredientInfoModal() {
    const modal = document.getElementById('ingredientInfoModal');
    const modalContent = document.getElementById('ingredientInfoModalContent');
    
    modal.style.opacity = '0';
    modalContent.style.transform = 'scale(0.95)';
    
    setTimeout(() => {
        modal.style.display = 'none';
    }, 300);
}

// Validation Modal Functions
function showValidationModal(title, message) {
    const modal = document.getElementById('validationModal');
    const modalContent = document.getElementById('validationModalContent');
    const titleElement = document.getElementById('validationModalTitle');
    const messageElement = document.getElementById('validationModalMessage');
    
    titleElement.textContent = title;
    messageElement.textContent = message;
    
    modal.style.display = 'flex';
    modal.style.opacity = '0';
    modalContent.style.transform = 'scale(0.95)';
    
    // Animate in
    setTimeout(() => {
        modal.style.opacity = '1';
        modalContent.style.transform = 'scale(1)';
    }, 10);
}

function closeValidationModal() {
    const modal = document.getElementById('validationModal');
    const modalContent = document.getElementById('validationModalContent');
    
    modal.style.opacity = '0';
    modalContent.style.transform = 'scale(0.95)';
    
    setTimeout(() => {
        modal.style.display = 'none';
    }, 300);
}

// Ingredient Name Modal Functions
function showIngredientNameModal() {
    const modal = document.getElementById('ingredientNameModal');
    const modalContent = document.getElementById('ingredientNameModalContent');
    
    modal.style.display = 'flex';
    modal.style.opacity = '0';
    modalContent.style.transform = 'scale(0.95)';
    
    // Animate in
    setTimeout(() => {
        modal.style.opacity = '1';
        modalContent.style.transform = 'scale(1)';
    }, 10);
}

function closeIngredientNameModal() {
    const modal = document.getElementById('ingredientNameModal');
    const modalContent = document.getElementById('ingredientNameModalContent');
    
    modal.style.opacity = '0';
    modalContent.style.transform = 'scale(0.95)';
    
    setTimeout(() => {
        modal.style.display = 'none';
    }, 300);
}
</script>

<!-- Ingredient Information Modal -->
<div id="ingredientInfoModal" class="hidden fixed inset-0 z-50 flex items-center justify-center transition-opacity duration-300" style="display: none; opacity: 0;">
    <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full mx-4 transform transition-all duration-300" id="ingredientInfoModalContent" style="transform: scale(0.95);">
        <div class="p-6">
            <!-- Icon and Title -->
            <div class="flex items-start mb-4">
                <div class="flex-shrink-0">
                    <div class="p-3 bg-blue-100 rounded-full">
                        <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4 flex-1">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Ingredient Information</h3>
                    <p class="text-sm text-gray-600 mb-4">
                        Guidelines for entering ingredient names in the recipe form.
                    </p>
                    
                    <!-- Information Content -->
                    <div class="space-y-4">
                        <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                            <h4 class="font-semibold text-green-800 mb-2">✅ Best Practices</h4>
                            <ul class="text-sm text-green-700 space-y-1">
                                <li>• Use specific ingredient names (e.g., "Chicken breast" not just "Chicken")</li>
                                <li>• Include preparation when relevant (e.g., "Onion, diced" or "Garlic, minced")</li>
                                <li>• Use common ingredient names for better price matching</li>
                                <li>• Be consistent with naming across recipes</li>
                            </ul>
                        </div>
                        
                        <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <h4 class="font-semibold text-yellow-800 mb-2">📝 Examples</h4>
                            <div class="text-sm text-yellow-700 space-y-1">
                                <div><strong>Good:</strong> "Chicken breast, boneless"</div>
                                <div><strong>Good:</strong> "Rice, jasmine"</div>
                                <div><strong>Good:</strong> "Tomato, fresh"</div>
                                <div><strong>Avoid:</strong> "Chicken (any type)"</div>
                                <div><strong>Avoid:</strong> "Rice - whatever available"</div>
                            </div>
                        </div>
                        
                        <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <h4 class="font-semibold text-blue-800 mb-2">⚡ Automatic Features</h4>
                            <ul class="text-sm text-blue-700 space-y-1">
                                <li>• Prices are automatically fetched from market data</li>
                                <li>• Nutritional information is calculated via USDA database</li>
                                <li>• Total recipe cost is computed from ingredient costs</li>
                                <li>• Cost per serving is calculated automatically</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Action Button -->
            <div class="flex justify-end mt-6">
                <button onclick="closeIngredientInfoModal()" 
                        class="px-6 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-lg">
                    Got it
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Validation Modal -->
<div id="validationModal" class="hidden fixed inset-0 z-50 flex items-center justify-center transition-opacity duration-300" style="display: none; opacity: 0;">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4 transform transition-all duration-300" id="validationModalContent" style="transform: scale(0.95);">
        <div class="p-6">
            <!-- Icon and Title -->
            <div class="flex items-start mb-4">
                <div class="flex-shrink-0">
                    <div class="p-3 bg-yellow-100 rounded-full">
                        <svg class="w-7 h-7 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4 flex-1">
                    <h3 id="validationModalTitle" class="text-xl font-bold text-gray-900 mb-2">Validation Error</h3>
                    <p id="validationModalMessage" class="text-sm text-gray-600">
                        Please correct the highlighted field.
                    </p>
                </div>
            </div>
            
            <!-- Action Button -->
            <div class="flex justify-end mt-6">
                <button onclick="closeValidationModal()" 
                        class="px-6 py-2.5 bg-yellow-600 text-white font-medium rounded-lg hover:bg-yellow-700 transition-colors focus:outline-none focus:ring-2 focus:ring-yellow-500 shadow-lg">
                    OK
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Ingredient Name Required Modal -->
<div id="ingredientNameModal" class="hidden fixed inset-0 z-50 flex items-center justify-center transition-opacity duration-300" style="display: none; opacity: 0;">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4 transform transition-all duration-300" id="ingredientNameModalContent" style="transform: scale(0.95);">
        <div class="p-6">
            <!-- Icon and Title -->
            <div class="flex items-start mb-4">
                <div class="flex-shrink-0">
                    <div class="p-3 bg-red-100 rounded-full">
                        <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4 flex-1">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Ingredient Name Required</h3>
                    <p class="text-sm text-gray-600">
                        Please enter an ingredient name before saving. All ingredients must have a name to continue.
                    </p>
                    <div class="mt-3 p-3 bg-red-50 border border-red-200 rounded-lg">
                        <p class="text-xs text-red-700">
                            <strong>Tip:</strong> Use specific ingredient names like "Chicken breast, boneless" or "Rice, jasmine" for better results.
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex justify-end mt-6">
                <button onclick="closeIngredientNameModal()" 
                        class="px-6 py-2.5 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition-colors focus:outline-none focus:ring-2 focus:ring-red-500 shadow-lg">
                    OK
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteConfirmationModal" class="hidden fixed inset-0 z-50 flex items-center justify-center transition-opacity duration-300" style="display: none; opacity: 0;">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4 transform transition-all duration-300" id="deleteConfirmationModalContent" style="transform: scale(0.95);">
        <div class="p-6">
            <!-- Icon and Title -->
            <div class="flex items-start mb-4">
                <div class="flex-shrink-0">
                    <div class="p-3 bg-red-100 rounded-full">
                        <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4 flex-1">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Delete Ingredient</h3>
                    <p class="text-sm text-gray-600 mb-3">
                        Are you sure you want to remove <strong id="deleteIngredientName" class="text-gray-900">this ingredient</strong> from the recipe?
                    </p>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3 mt-6">
                <button onclick="closeDeleteConfirmationModal()" 
                        class="px-4 py-2.5 text-gray-700 bg-gray-100 border border-gray-200 rounded-lg hover:bg-gray-200 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-500 shadow-sm">
                    Cancel
                </button>
                <button id="confirmDeleteBtn"
                        class="px-4 py-2.5 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition-colors focus:outline-none focus:ring-2 focus:ring-red-500 shadow-lg">
                    Delete
                </button>
            </div>
        </div>
    </div>
</div>

@endsection