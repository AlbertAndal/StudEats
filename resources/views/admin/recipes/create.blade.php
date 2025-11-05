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
                            <div class="col-span-1"></div>
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
                        
                        <!-- Add Ingredient Button -->
                        <div class="pt-4">
                            <button type="button" 
                                    onclick="addIngredient()"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                                </svg>
                                Add Ingredient
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
    
    // Add unit options
    const units = [
        { value: '', text: 'Select unit...' },
        { value: 'kg', text: 'Kilogram (kg)' },
        { value: 'g', text: 'Gram (g)' },
        { value: 'lb', text: 'Pound (lb)' },
        { value: 'oz', text: 'Ounce (oz)' },
        { value: 'L', text: 'Liter (L)' },
        { value: 'mL', text: 'Milliliter (mL)' },
        { value: 'cup', text: 'Cup' },
        { value: 'cups', text: 'Cups' },
        { value: 'tbsp', text: 'Tablespoon (tbsp)' },
        { value: 'tsp', text: 'Teaspoon (tsp)' },
        { value: 'pcs', text: 'Pieces (pcs)' },
        { value: 'pieces', text: 'Pieces' },
        { value: 'can', text: 'Can' },
        { value: 'pack', text: 'Pack' },
        { value: 'bunch', text: 'Bunch' },
        { value: 'cloves', text: 'Cloves' },
        { value: 'head', text: 'Head' },
        { value: 'slice', text: 'Slice' },
        { value: 'slices', text: 'Slices' }
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

    // Unit Price display (read-only)
    const unitPriceDiv = document.createElement('div');
    unitPriceDiv.className = 'col-span-2';
    const unitPriceDisplay = document.createElement('input');
    unitPriceDisplay.type = 'text';
    unitPriceDisplay.readonly = true;
    unitPriceDisplay.placeholder = '₱0.00';
    unitPriceDisplay.className = 'w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-gray-50 text-gray-700 text-center';
    unitPriceDisplay.value = unitPrice ? `₱${parseFloat(unitPrice).toFixed(2)}` : '';
    unitPriceDiv.appendChild(unitPriceDisplay);
    
    // Hidden input for unit price data
    const unitPriceInput = document.createElement('input');
    unitPriceInput.type = 'hidden';
    unitPriceInput.name = 'ingredient_prices[]';
    unitPriceInput.value = unitPrice || '0';
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
    const btn = document.createElement('button');
    btn.type = 'button';
    btn.className = 'w-8 h-8 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors flex items-center justify-center font-semibold text-lg';
    btn.setAttribute('aria-label', 'Remove ingredient');
    btn.addEventListener('click', function() { removeIngredient(btn); });
    btn.innerHTML = '×';
    btnWrapper.appendChild(btn);

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
        const response = await fetch('/api/ingredient-price', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            body: JSON.stringify({
                ingredient_name: ingredientName,
                region: 'NCR'
            })
        });
        
        const data = await response.json();
        
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
    
    const unitPriceDisplay = row.querySelector('input[readonly]:not([name])');
    const unitPriceInput = row.querySelector('input[name="ingredient_prices[]"]');
    
    if (unitPriceDisplay && unitPriceInput) {
        unitPriceDisplay.value = `₱${unitPrice.toFixed(2)}`;
        unitPriceInput.value = unitPrice.toString();
        updateRowPrice(row);
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
        const unitInput = row.querySelector('input[name="ingredient_units[]"]');
        
        if (nameInput?.value && quantityInput?.value && unitInput?.value) {
            ingredients.push({
                name: nameInput.value.trim(),
                quantity: parseFloat(quantityInput.value) || 0,
                unit: unitInput.value.trim()
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
        const response = await fetch('/api/calculate-recipe-nutrition', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            body: JSON.stringify({
                ingredients: ingredients,
                servings: 1
            })
        });
        
        const data = await response.json();
        
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

function removeIngredient(button) {
    const container = document.getElementById('ingredients-container');
    const row = button.closest('.ingredient-item');
    
    if (container.querySelectorAll('.ingredient-item').length > 1) {
        row.remove();
        updateTotalCost();
    }
    
    toggleRemoveButtons();
    
    // Show placeholder if no ingredients left
    if (container.querySelectorAll('.ingredient-item').length === 0) {
        container.innerHTML = `
            <div class="text-center py-6 text-gray-400">
                <svg class="w-10 h-10 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3-6h.008v.008H15.75V12zm0 3h.008v.008H15.75V15zm0 3h.008v.008H15.75V18zm-12-3h3.75m0 0h3.75m0 0v3.75M5.25 15V9.75M5.25 15a2.25 2.25 0 01-2.25-2.25V9.75A2.25 2.25 0 015.25 7.5h3.75"/>
                </svg>
                <p class="text-sm font-medium text-gray-500">No ingredients added yet</p>
                <p class="text-xs text-gray-400 mt-1">Click "Add Ingredient" to get started</p>
            </div>
        `;
    }
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

document.addEventListener('DOMContentLoaded', function() {
    // Add CSRF token to meta if not exists
    if (!document.querySelector('meta[name="csrf-token"]')) {
        const meta = document.createElement('meta');
        meta.name = 'csrf-token';
        meta.content = document.querySelector('input[name="_token"]')?.value || '';
        document.head.appendChild(meta);
    }
    
    // Add datalist for common units
    const datalist = document.createElement('datalist');
    datalist.id = 'units-list';
    const units = ['kg', 'g', 'lb', 'oz', 'L', 'mL', 'cup', 'cups', 'tbsp', 'tsp', 'pcs', 'pieces', 'can', 'pack', 'bunch', 'cloves', 'head'];
    units.forEach(unit => {
        const option = document.createElement('option');
        option.value = unit;
        datalist.appendChild(option);
    });
    document.body.appendChild(datalist);

    // Handle old input restoration
    const oldNames = <?php echo json_encode(old('ingredient_names', []), JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_QUOT); ?>;
    const oldQuantities = <?php echo json_encode(old('ingredient_quantities', []), JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_QUOT); ?>;
    const oldUnits = <?php echo json_encode(old('ingredient_units', []), JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_QUOT); ?>;
    const oldPrices = <?php echo json_encode(old('ingredient_prices', []), JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_QUOT); ?>;
    
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

    // Prevent double submit
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
                submitBtn.innerHTML = '<svg class="animate-spin w-4 h-4 inline mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Creating Recipe...';
            }
        });
    }
});
</script>
@endsection