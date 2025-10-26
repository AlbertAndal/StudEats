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
                        <label for="cost" class="block text-sm font-medium text-gray-700 mb-2">Estimated Cost (₱)</label>
               <input type="number" 
                   id="cost" 
                   name="cost" 
                   value="{{ old('cost') }}" 
                   step="0.01" 
                   min="0" max="999999.99"
                   required aria-required="true"
                   inputmode="decimal"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" aria-describedby="cost_help">
               <p id="cost_help" class="mt-1 text-xs text-gray-500">Enter estimated total cost in PHP (₱).</p>
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
                                <p class="text-sm text-gray-600">Add ingredients with quantities and estimated costs</p>
                            </div>
                            
                            <div class="p-6 space-y-4">
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
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Nutritional Information (per serving)</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div>
                        <label for="calories" class="block text-sm font-medium text-gray-700 mb-2">Calories</label>
               <input type="number" 
                   id="calories" 
                   name="calories" 
                   value="{{ old('calories') }}" 
                   min="0" max="5000"
                   required aria-required="true"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
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
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
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
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
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
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
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
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
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
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
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
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('sodium')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
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
function createIngredientRow(name = '', quantity = '', unit = '', price = '') {
    const wrapper = document.createElement('div');
    wrapper.className = 'ingredient-item grid grid-cols-12 gap-3';

    // Create wrapper divs for each input to match the edit form structure
    const nameDiv = document.createElement('div');
    nameDiv.className = 'col-span-5';
    const nameInput = document.createElement('input');
    nameInput.type = 'text';
    nameInput.name = 'ingredient_names[]';
    nameInput.required = true;
    nameInput.maxLength = 100;
    nameInput.placeholder = 'e.g., Chicken breast, Rice, Garlic';
    nameInput.className = 'w-full px-4 py-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 bg-gray-50 focus:bg-white';
    nameInput.value = name;
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
    quantityInput.placeholder = '2';
    quantityInput.className = 'w-full px-3 py-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-center transition-colors duration-200 bg-gray-50 focus:bg-white';
    quantityInput.value = quantity;
    quantityDiv.appendChild(quantityInput);

    // Unit input
    const unitDiv = document.createElement('div');
    unitDiv.className = 'col-span-2';
    const unitInput = document.createElement('input');
    unitInput.type = 'text';
    unitInput.name = 'ingredient_units[]';
    unitInput.required = true;
    unitInput.maxLength = 50;
    unitInput.placeholder = 'kg, cups, tbsp';
    unitInput.list = 'units-list';
    unitInput.className = 'w-full px-3 py-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 bg-gray-50 focus:bg-white';
    unitInput.value = unit;
    unitDiv.appendChild(unitInput);

    // Price input
    const priceDiv = document.createElement('div');
    priceDiv.className = 'col-span-2';
    const priceInput = document.createElement('input');
    priceInput.type = 'number';
    priceInput.name = 'ingredient_prices[]';
    priceInput.step = '0.01';
    priceInput.min = '0';
    priceInput.placeholder = '0.00';
    priceInput.className = 'w-full px-3 py-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-center transition-colors duration-200 bg-gray-50 focus:bg-white';
    priceInput.value = price;
    priceInput.title = 'Optional: Estimated cost for this ingredient';
    priceDiv.appendChild(priceInput);

    // Remove button
    const btnWrapper = document.createElement('div');
    btnWrapper.className = 'col-span-1 flex items-center justify-center';
    const btn = document.createElement('button');
    btn.type = 'button';
    btn.className = 'w-10 h-10 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors flex items-center justify-center font-semibold';
    btn.setAttribute('aria-label', 'Remove ingredient');
    btn.addEventListener('click', function() { removeIngredient(btn); });
    btn.innerHTML = '×';
    btnWrapper.appendChild(btn);

    wrapper.appendChild(nameDiv);
    wrapper.appendChild(quantityDiv);
    wrapper.appendChild(unitDiv);
    wrapper.appendChild(priceDiv);
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

    // Handle old input
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
        // Add 3 empty ingredient rows by default
        addIngredient();
        addIngredient();
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
                submitBtn.innerHTML = '<svg class="animate-spin w-4 h-4 inline mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Creating...';
            }
        });
    }
});
</script>
@endsection