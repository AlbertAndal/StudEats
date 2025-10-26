@extends('layouts.admin')

@section('title', 'Edit Recipe - ' . $recipe->name)

@section('content')
    <!-- Sticky Header -->
    <div class="sticky top-0 z-30 bg-white border-b border-gray-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-4">
                    <a href="{{ route('admin.recipes.index') }}" 
                       class="inline-flex items-center text-gray-600 hover:text-gray-900 transition-colors"
                       title="Back to Recipes">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-lg font-bold text-gray-900">Edit Recipe</h1>
                        <p class="text-xs text-gray-500">{{ $recipe->name }}</p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.recipes.show', $recipe) }}" 
                       class="inline-flex items-center px-3 py-1.5 text-sm bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Preview
                    </a>
                    <button type="submit" 
                            form="recipe-form"
                            class="inline-flex items-center px-4 py-1.5 text-sm bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-lg hover:from-blue-700 hover:to-purple-700 transition-all shadow-md hover:shadow-lg">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        Save Changes
                    </button>
                </div>
            </div>
        </div>
    </div>

        <form method="POST" action="{{ route('admin.recipes.update', $recipe) }}" enctype="multipart/form-data" class="space-y-8">
            @csrf
            @method('PUT')

            <!-- Basic Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="text-lg font-semibold text-gray-900">Basic Information</h2>
                    <p class="text-sm text-gray-600 mt-1">Recipe name, description, and basic details</p>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Recipe Name -->
                        <div class="lg:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Recipe Name *</label>
                            <input type="text" 
                                   name="name" 
                                   value="{{ old('name', $recipe->name) }}" 
                                   required
                                   class="w-full px-4 py-3 border @error('name') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Enter recipe name">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="lg:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                            <textarea name="description" 
                                      rows="4" 
                                      required
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror"
                                      placeholder="Describe the recipe...">{{ old('description', $recipe->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Cuisine Type -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Cuisine Type *</label>
                            <select name="cuisine_type" 
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('cuisine_type') border-red-500 @enderror">
                                <option value="">Select Cuisine</option>
                                @foreach($cuisineTypes as $cuisine)
                                    <option value="{{ $cuisine }}" {{ old('cuisine_type', $recipe->cuisine_type) === $cuisine ? 'selected' : '' }}>
                                        {{ ucfirst($cuisine) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('cuisine_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Difficulty -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Difficulty Level *</label>
                            <select name="difficulty" 
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('difficulty') border-red-500 @enderror">
                                <option value="">Select Difficulty</option>
                                @foreach($difficulties as $difficulty)
                                    <option value="{{ $difficulty }}" {{ old('difficulty', $recipe->difficulty) === $difficulty ? 'selected' : '' }}>
                                        {{ ucfirst($difficulty) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('difficulty')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Calories -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Calories per Serving *</label>
                            <input type="number" 
                                   name="calories" 
                                   value="{{ old('calories', $recipe->calories) }}" 
                                   min="1" 
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('calories') border-red-500 @enderror"
                                   placeholder="Enter calories">
                            @error('calories')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Cost -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Cost (₱) *</label>
                            <input type="number" 
                                   name="cost" 
                                   value="{{ old('cost', $recipe->cost) }}" 
                                   min="0" 
                                   step="0.01" 
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('cost') border-red-500 @enderror"
                                   placeholder="0.00">
                            @error('cost')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Recipe Image -->
                        <div class="lg:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Recipe Image</label>
                            @if($recipe->image_path)
                                <div class="mb-4">
                                    <img src="{{ $recipe->image_url }}" 
                                         alt="{{ $recipe->name }}" 
                                         class="w-32 h-32 object-cover rounded-lg border border-gray-200">
                                    <p class="text-sm text-gray-600 mt-1">Current image</p>
                                </div>
                            @endif
                            <input type="file" 
                                   name="image" 
                                   accept="image/*"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('image') border-red-500 @enderror">
                            <p class="text-sm text-gray-600 mt-1">Upload a new image to replace the current one (JPG, PNG, GIF - Max 2MB)</p>
                            @error('image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Featured Status -->
                        <div class="lg:col-span-2">
                            <div class="flex items-center">
                                <input type="hidden" name="is_featured" value="0">
                                <input type="checkbox" 
                                       name="is_featured" 
                                       value="1" 
                                       {{ old('is_featured', $recipe->is_featured) ? 'checked' : '' }}
                                       class="h-5 w-5 text-blue-600 border-gray-300 rounded focus:ring-2 focus:ring-blue-500">
                                <label class="ml-3 text-sm font-medium text-gray-700">Feature this recipe</label>
                            </div>
                            <p class="text-sm text-gray-600 mt-1">Featured recipes are highlighted on the homepage and recipe listings</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recipe Details -->
            @if($recipe->recipe)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="text-lg font-semibold text-gray-900">Recipe Details</h2>
                    <p class="text-sm text-gray-600 mt-1">Cooking instructions, ingredients, and timing</p>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                        <!-- Prep Time -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Prep Time (minutes)</label>
                            <input type="number" 
                                   name="prep_time" 
                                   value="{{ old('prep_time', $recipe->recipe->prep_time ?? '') }}" 
                                   min="0"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('prep_time') border-red-500 @enderror"
                                   placeholder="15">
                            @error('prep_time')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Cook Time -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Cook Time (minutes)</label>
                            <input type="number" 
                                   name="cook_time" 
                                   value="{{ old('cook_time', $recipe->recipe->cook_time ?? '') }}" 
                                   min="0"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('cook_time') border-red-500 @enderror"
                                   placeholder="30">
                            @error('cook_time')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Servings -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Servings</label>
                            <input type="number" 
                                   name="servings" 
                                   value="{{ old('servings', $recipe->recipe->servings ?? '') }}" 
                                   min="1"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('servings') border-red-500 @enderror"
                                   placeholder="4">
                            @error('servings')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="space-y-6">
                        <!-- Ingredients -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">Ingredients</label>
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                <div class="grid grid-cols-12 gap-2 mb-3 text-xs font-medium text-gray-600">
                                    <div class="col-span-4">Name</div>
                                    <div class="col-span-2">Quantity</div>
                                    <div class="col-span-2">Unit</div>
                                    <div class="col-span-3">Est. Cost (₱)</div>
                                    <div class="col-span-1"></div>
                                </div>
                                <div id="ingredients-container" class="space-y-2">
                                    <!-- Ingredients will be added here dynamically -->
                                </div>
                                <button type="button" 
                                        onclick="addIngredient()"
                                        class="mt-3 inline-flex items-center px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                                    </svg>
                                    Add Ingredient
                                </button>
                            </div>
                            @error('ingredients')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-2 text-xs text-gray-500">
                                💡 Tip: Estimated cost is optional but helps with budget planning. Live prices from Bantay Presyo will override these when available.
                            </p>
                        </div>
                    </div>

                    <!-- Instructions -->
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Instructions</label>
                        <textarea name="instructions" 
                                  rows="10"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('instructions') border-red-500 @enderror"
                                  placeholder="Enter step-by-step cooking instructions...">{{ old('instructions', $recipe->recipe->instructions ?? '') }}</textarea>
                        <p class="text-sm text-gray-600 mt-1">Provide clear, step-by-step cooking instructions</p>
                        @error('instructions')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            @endif

            <!-- Nutritional Information -->
            @if($recipe->nutritionalInfo)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="text-lg font-semibold text-gray-900">Nutritional Information</h2>
                    <p class="text-sm text-gray-600 mt-1">Detailed nutrition facts per serving</p>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
                        <!-- Protein -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Protein (g)</label>
                            <input type="number" 
                                   name="protein" 
                                   value="{{ old('protein', $recipe->nutritionalInfo->protein ?? '') }}" 
                                   min="0" 
                                   step="0.1"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('protein') border-red-500 @enderror"
                                   placeholder="0.0">
                            @error('protein')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Carbs -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Carbohydrates (g)</label>
                            <input type="number" 
                                   name="carbs" 
                                   value="{{ old('carbs', $recipe->nutritionalInfo->carbs ?? '') }}" 
                                   min="0" 
                                   step="0.1"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('carbs') border-red-500 @enderror"
                                   placeholder="0.0">
                            @error('carbs')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Fats -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Fats (g)</label>
                            <input type="number" 
                                   name="fats" 
                                   value="{{ old('fats', $recipe->nutritionalInfo->fats ?? '') }}" 
                                   min="0" 
                                   step="0.1"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('fats') border-red-500 @enderror"
                                   placeholder="0.0">
                            @error('fats')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Fiber -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Fiber (g)</label>
                            <input type="number" 
                                   name="fiber" 
                                   value="{{ old('fiber', $recipe->nutritionalInfo->fiber ?? '') }}" 
                                   min="0" 
                                   step="0.1"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('fiber') border-red-500 @enderror"
                                   placeholder="0.0">
                            @error('fiber')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Sugar -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Sugar (g)</label>
                            <input type="number" 
                                   name="sugar" 
                                   value="{{ old('sugar', $recipe->nutritionalInfo->sugar ?? '') }}" 
                                   min="0" 
                                   step="0.1"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('sugar') border-red-500 @enderror"
                                   placeholder="0.0">
                            @error('sugar')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Sodium -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Sodium (mg)</label>
                            <input type="number" 
                                   name="sodium" 
                                   value="{{ old('sodium', $recipe->nutritionalInfo->sodium ?? '') }}" 
                                   min="0" 
                                   step="0.1"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('sodium') border-red-500 @enderror"
                                   placeholder="0.0">
                            @error('sodium')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Form Actions -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200 bg-gray-50 px-6 py-4 rounded-xl">
                <div class="text-sm text-gray-600">
                    Last updated {{ $recipe->updated_at->diffForHumans() }}
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('admin.recipes.index') }}" 
                       class="px-6 py-3 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-lg hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 3.75V16.5L12 14.25 7.5 16.5V3.75a.75.75 0 01.75-.75h7.5a.75.75 0 01.75.75z"/>
                        </svg>
                        Update Recipe
                    </button>
                </div>
            </div>
        </form>
    </div>

    @if($errors->any())
        <div class="fixed bottom-4 right-4 bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded-lg shadow-lg max-w-md z-50">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                </svg>
                <div>
                    <strong>Please fix the following errors:</strong>
                    <ul class="mt-1 list-disc list-inside text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif
@endsection

<script>
function createIngredientRow(name = '', quantity = '', unit = '', price = '') {
    const wrapper = document.createElement('div');
    wrapper.className = 'ingredient-item grid grid-cols-12 gap-2 items-start';

    // Ingredient name input
    const nameInput = document.createElement('input');
    nameInput.type = 'text';
    nameInput.name = 'ingredient_names[]';
    nameInput.required = true;
    nameInput.maxLength = 100;
    nameInput.placeholder = 'e.g., Chicken breast, Rice, Garlic';
    nameInput.className = 'col-span-4 px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent';
    nameInput.value = name;

    // Quantity input
    const quantityInput = document.createElement('input');
    quantityInput.type = 'number';
    quantityInput.name = 'ingredient_quantities[]';
    quantityInput.required = true;
    quantityInput.step = '0.01';
    quantityInput.min = '0';
    quantityInput.placeholder = '2';
    quantityInput.className = 'col-span-2 px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent';
    quantityInput.value = quantity;

    // Unit input
    const unitInput = document.createElement('input');
    unitInput.type = 'text';
    unitInput.name = 'ingredient_units[]';
    unitInput.required = true;
    unitInput.maxLength = 50;
    unitInput.placeholder = 'kg, cups, tbsp';
    unitInput.list = 'units-list';
    unitInput.className = 'col-span-2 px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent';
    unitInput.value = unit;

    // Price input
    const priceInput = document.createElement('input');
    priceInput.type = 'number';
    priceInput.name = 'ingredient_prices[]';
    priceInput.step = '0.01';
    priceInput.min = '0';
    priceInput.placeholder = '0.00';
    priceInput.className = 'col-span-3 px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent';
    priceInput.value = price;
    priceInput.title = 'Optional: Estimated cost for this ingredient';

    // Remove button
    const btnWrapper = document.createElement('div');
    btnWrapper.className = 'col-span-1 flex items-center';
    const btn = document.createElement('button');
    btn.type = 'button';
    btn.className = 'w-full px-2 py-2 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg transition-colors border border-red-200';
    btn.setAttribute('aria-label', 'Remove ingredient');
    btn.addEventListener('click', function() { removeIngredient(btn); });
    btn.innerHTML = '<svg class="w-4 h-4 mx-auto" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>';
    btnWrapper.appendChild(btn);

    wrapper.appendChild(nameInput);
    wrapper.appendChild(quantityInput);
    wrapper.appendChild(unitInput);
    wrapper.appendChild(priceInput);
    wrapper.appendChild(btnWrapper);
    
    return wrapper;
}

function addIngredient(name = '', quantity = '', unit = '', price = '') {
    const container = document.getElementById('ingredients-container');
    const row = createIngredientRow(name, quantity, unit, price);
    container.appendChild(row);
    row.querySelector('input').focus();
    toggleRemoveButtons();
}

function removeIngredient(button) {
    const container = document.getElementById('ingredients-container');
    if (container.children.length > 1) {
        button.closest('.ingredient-item').remove();
    }
    toggleRemoveButtons();
}

function toggleRemoveButtons() {
    const container = document.getElementById('ingredients-container');
    const disable = container.children.length <= 1;
    [...container.querySelectorAll('button[aria-label="Remove ingredient"]')].forEach(btn => {
        btn.disabled = disable;
        btn.classList.toggle('opacity-50', disable);
        btn.classList.toggle('cursor-not-allowed', disable);
    });
}

document.addEventListener('DOMContentLoaded', function() {
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

    // Parse existing recipe ingredients
    const recipeIngredients = <?php 
        if ($recipe->recipe && isset($recipe->recipe->ingredients)) {
            $ingredients = collect($recipe->recipe->ingredients)->map(function($ingredient) {
                if (is_array($ingredient)) {
                    return [
                        'name' => $ingredient['name'] ?? '',
                        'quantity' => $ingredient['amount'] ?? '',
                        'unit' => $ingredient['unit'] ?? '',
                        'price' => $ingredient['price'] ?? ''
                    ];
                }
                // Handle old string format: "Ingredient - 2 kg"
                if (is_string($ingredient)) {
                    $parts = explode(' - ', $ingredient, 2);
                    $name = $parts[0] ?? '';
                    $amountUnit = $parts[1] ?? '';
                    $amountParts = explode(' ', trim($amountUnit), 2);
                    return [
                        'name' => $name,
                        'quantity' => $amountParts[0] ?? '',
                        'unit' => $amountParts[1] ?? '',
                        'price' => ''
                    ];
                }
                return ['name' => '', 'quantity' => '', 'unit' => '', 'price' => ''];
            });
            echo json_encode($ingredients, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_QUOT);
        } else {
            echo '[]';
        }
    ?>;

    // Handle old input (validation errors)
    const oldNames = <?php echo json_encode(old('ingredient_names', []), JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_QUOT); ?>;
    const oldQuantities = <?php echo json_encode(old('ingredient_quantities', []), JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_QUOT); ?>;
    const oldUnits = <?php echo json_encode(old('ingredient_units', []), JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_QUOT); ?>;
    const oldPrices = <?php echo json_encode(old('ingredient_prices', []), JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_QUOT); ?>;
    
    const container = document.getElementById('ingredients-container');
    container.innerHTML = '';
    
    // Prioritize old input (after validation errors)
    if (oldNames && oldNames.length) {
        for (let i = 0; i < oldNames.length; i++) {
            addIngredient(oldNames[i] || '', oldQuantities[i] || '', oldUnits[i] || '', oldPrices[i] || '');
        }
    } else if (recipeIngredients && recipeIngredients.length) {
        // Load existing recipe ingredients
        recipeIngredients.forEach(ing => {
            addIngredient(ing.name || '', ing.quantity || '', ing.unit || '', ing.price || '');
        });
    } else {
        // Add 1 empty row as fallback
        addIngredient();
    }

    // Prevent double submit
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<svg class="animate-spin w-4 h-4 inline mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Updating...';
            }
        });
    }
});
</script>
