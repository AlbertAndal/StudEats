@extends('layouts.admin')

@section('title', 'Add New Recipe')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8" role="region" aria-labelledby="page-title">
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 id="page-title" class="text-3xl font-bold text-gray-900">Add New Recipe</h1>
                <p class="mt-2 text-gray-600">Create a new recipe with ingredients, instructions, and nutritional information.</p>
            </div>
            <a href="{{ route('admin.recipes.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Recipes
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <form action="{{ route('admin.recipes.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8" novalidate>
            @csrf
            @if ($errors->any())
                <div class="p-4 bg-red-50 border border-red-200 rounded-lg" role="alert" aria-live="assertive">
                    <h2 class="font-semibold text-red-800 mb-2 text-sm">There were some problems with your input:</h2>
                    <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <!-- Basic Information -->
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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

                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea id="description" 
                                  name="description" 
                                  rows="3" 
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

            <!-- Recipe Details -->
            <div class="border-t border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Recipe Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
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

                    <div>
                        <label for="servings" class="block text-sm font-medium text-gray-700 mb-2">Servings</label>
               <input type="number" 
                   id="servings" 
                   name="servings" 
                   value="{{ old('servings') }}" 
                   min="1" max="100"
                   required aria-required="true"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('servings')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="space-y-6">
                    <div>
                        <!-- Ingredients Section -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900 mb-1">Recipe Ingredients</h3>
                                <p class="text-sm text-gray-600">Add ingredients with quantities - prices and nutrition will be calculated automatically</p>
                            </div>
                            
                            <div class="p-6 space-y-4">
                                <!-- Ingredients Header -->
                                <div class="grid grid-cols-12 gap-3 text-xs font-medium text-gray-600 uppercase tracking-wide border-b border-gray-200 pb-2">
                                    <div class="col-span-4">Ingredient Name</div>
                                    <div class="col-span-2 text-center">Quantity</div>
                                    <div class="col-span-2 text-center">Unit</div>
                                    <div class="col-span-2 text-center">Unit Price</div>
                                    <div class="col-span-2 text-center">Total Price</div>
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
                                    <div class="flex justify-between items-center bg-blue-50 px-4 py-3 rounded-lg mt-2">
                                        <span class="text-sm font-medium text-gray-700">Cost per Serving:</span>
                                        <span id="cost-per-serving" class="text-lg font-bold text-blue-600">₱0.00</span>
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
                        </div>
                        @error('ingredients')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-xs text-gray-500">
                            💡 Tip: Estimated cost is optional but helps with budget planning. Live prices from Bantay Presyo will override these when available.
                        </p>
                    </div>

                    <div>
                        <label for="instructions" class="block text-sm font-medium text-gray-700 mb-2">Instructions</label>
                        <textarea id="instructions" 
                                  name="instructions" 
                                  rows="8" 
                                  required aria-required="true"
                                  placeholder="Step-by-step cooking instructions... One step per line."
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('instructions') }}</textarea>
                        @error('instructions')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Nutritional Information -->
            <div class="border-t border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Nutritional Information (per serving)</h3>
                        <p class="text-sm text-gray-600 mt-1">Calculated automatically from ingredients using USDA nutrition database</p>
                    </div>
                    <button type="button" 
                            id="calculate-nutrition-btn"
                            onclick="calculateNutrition()"
                            class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        Calculate Nutrition
                    </button>
                </div>
                
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
                <div id="nutrition-results" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div>
                        <label for="calories" class="block text-sm font-medium text-gray-700 mb-2">Calories</label>
                        <input type="number" 
                               id="calories" 
                               name="calories" 
                               value="{{ old('calories') }}" 
                               min="0" max="5000"
                               required aria-required="true"
                               readonly
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-700">
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
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-700">
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
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-700">
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
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-700">
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
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-700">
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
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-700">
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
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-700">
                        @error('sodium')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Nutrition Status Messages -->
                <div id="nutrition-status" class="mt-4"></div>
            </div>

            <!-- Form Actions -->
            <div class="border-t border-gray-200 p-6">
                <div class="flex items-center justify-end gap-4">
                    <a href="{{ route('admin.recipes.index') }}" 
                       class="px-6 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 font-medium rounded-lg transition-colors">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                        </svg>
                        Create Recipe
                    </button>
                </div>
            </div>
        </form>
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
    nameDiv.className = 'col-span-4';
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

    // Unit input with datalist
    const unitDiv = document.createElement('div');
    unitDiv.className = 'col-span-2';
    const unitInput = document.createElement('input');
    unitInput.type = 'text';
    unitInput.name = 'ingredient_units[]';
    unitInput.required = true;
    unitInput.maxLength = 50;
    unitInput.placeholder = 'kg, cups, tbsp';
    unitInput.list = 'units-list';
    unitInput.className = 'w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200';
    unitInput.value = unit;
    unitDiv.appendChild(unitInput);

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
    totalPriceDiv.className = 'col-span-1';
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
    const servingsInput = document.getElementById('servings');
    
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
                servings: parseInt(servingsInput?.value) || 1
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