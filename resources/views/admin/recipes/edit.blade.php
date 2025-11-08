@extends('layouts.admin')

@section('title', 'Edit Recipe - ' . $recipe->name)

@push('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- Nutrition Calculator Script -->
<script src="{{ asset('js/nutrition-calculator.js') }}" defer></script>
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
@endpush

@section('content')
    <!-- Minimalist Header -->
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex items-center justify-between py-5">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.recipes.index') }}" 
                       class="inline-flex items-center justify-center w-9 h-9 text-gray-400 hover:text-gray-600 hover:bg-gray-50 rounded-lg transition-all duration-200"
                       title="Back to Recipe Management">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                    </a>
                    <div class="h-8 w-px bg-gray-200"></div>
                    <div>
                        <div class="flex items-center space-x-2">
                            <h1 class="text-xl font-semibold text-gray-900 tracking-tight">Edit Recipe</h1>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600">
                                ID: {{ $recipe->id }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-500 mt-0.5">{{ $recipe->name }}</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('admin.recipes.show', $recipe) }}" 
                       class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 hover:border-gray-300 transition-all duration-200">
                        <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        Preview
                    </a>
                    <button type="submit" 
                            form="recipe-form"
                            class="inline-flex items-center px-5 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 transition-all duration-200 shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        Save Changes
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Minimalist Breadcrumb -->
    <div class="bg-gray-50/50">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 py-3">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2 text-xs">
                    <li><a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-gray-900 transition-colors">Dashboard</a></li>
                    <li><svg class="w-3 h-3 text-gray-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg></li>
                    <li><a href="{{ route('admin.recipes.index') }}" class="text-gray-500 hover:text-gray-900 transition-colors">Recipes</a></li>
                    <li><svg class="w-3 h-3 text-gray-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg></li>
                    <li><span class="text-gray-900 font-medium">Edit</span></li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-6 lg:px-8 py-8">
        <form id="recipe-form" method="POST" action="{{ route('admin.recipes.update', $recipe) }}" enctype="multipart/form-data" onsubmit="return validateForm(event)">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Form -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Basic Information -->
                    <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100">
                            <h3 class="text-base font-semibold text-gray-900">Recipe Information</h3>
                            <p class="text-sm text-gray-500 mt-0.5">Basic recipe details and metadata</p>
                        </div>
                        <div class="p-6 space-y-6">
                            <div class="grid grid-cols-3 gap-3">
                                <div class="col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Recipe Name *</label>
                                    <input type="text" name="name" value="{{ old('name', $recipe->name) }}" required maxlength="60"
                                           class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-gray-900 focus:border-gray-900 transition-all duration-200 bg-white hover:border-gray-300"
                                           placeholder="e.g., Chicken Adobo">
                                    @error('name')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                                    
                                    <!-- Hidden servings field - always 1 person -->
                                    <input type="hidden" name="servings" value="1">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                                <textarea name="description" rows="3" required maxlength="200"
                                          class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-gray-900 focus:border-gray-900 resize-none transition-all duration-200 bg-white hover:border-gray-300"
                                          placeholder="Brief description that captures the essence of this recipe (max 200 chars)">{{ old('description', $recipe->description) }}</textarea>
                                <div class="text-right mt-0.5">
                                    <span class="text-xs text-gray-400" id="desc-count">{{ strlen(old('description', $recipe->description)) }}/200</span>
                                </div>
                                @error('description')<p class="mt-0.5 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div class="grid grid-cols-6 gap-2">
                                <div class="col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Cuisine *</label>
                                    <select name="cuisine_type" required class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-gray-900 focus:border-gray-900 transition-all duration-200 bg-white hover:border-gray-300">
                                        <option value="">Select</option>
                                        @foreach($cuisineTypes as $cuisine)
                                            <option value="{{ $cuisine }}" {{ old('cuisine_type', $recipe->cuisine_type) === $cuisine ? 'selected' : '' }}>{{ ucfirst($cuisine) }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-span-2">
                                    <label class="block text-sm font-semibold text-gray-800 mb-2">Difficulty *</label>
                                    <select name="difficulty" required class="w-full px-3 py-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 bg-gray-50 focus:bg-white">
                                        <option value="">Select</option>
                                        @foreach($difficulties as $difficulty)
                                            <option value="{{ $difficulty }}" {{ old('difficulty', $recipe->difficulty) === $difficulty ? 'selected' : '' }}>{{ ucfirst($difficulty) }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-span-2">
                                    <label class="block text-sm font-semibold text-gray-800 mb-2">Meal Type *</label>
                                    <select name="meal_type" required class="w-full px-3 py-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 bg-gray-50 focus:bg-white">
                                        <option value="">Select</option>
                                        <option value="breakfast" {{ old('meal_type', $recipe->meal_type) === 'breakfast' ? 'selected' : '' }}>Breakfast</option>
                                        <option value="lunch" {{ old('meal_type', $recipe->meal_type) === 'lunch' ? 'selected' : '' }}>Lunch</option>
                                        <option value="snack" {{ old('meal_type', $recipe->meal_type) === 'snack' ? 'selected' : '' }}>Snack</option>
                                        <option value="dinner" {{ old('meal_type', $recipe->meal_type) === 'dinner' ? 'selected' : '' }}>Dinner</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-800 mb-2">Calories *</label>
                                    <input type="number" name="calories" value="{{ old('calories', $recipe->calories) }}" min="1" max="9999" required
                                           class="w-full px-3 py-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-center transition-colors duration-200 bg-gray-50 focus:bg-white" placeholder="300">
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-800 mb-2">Cost (₱) *</label>
                                    <input type="number" name="cost" value="{{ old('cost', $recipe->cost) }}" min="0" max="9999.99" step="0.01" required
                                           class="w-full px-3 py-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-center transition-colors duration-200 bg-gray-50 focus:bg-white" placeholder="99.99">
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="flex items-center text-sm font-semibold text-gray-800 mb-2">
                                        <svg class="w-4 h-4 mr-2 text-blue-600 lucide lucide-clock" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="12" cy="12" r="10"/>
                                            <polyline points="12,6 12,12 16,14"/>
                                        </svg>
                                        Prep Time (minutes)
                                    </label>
                                    <input type="number" name="prep_time" value="{{ old('prep_time', $recipe->recipe->prep_time ?? '') }}" min="0" max="480"
                                           class="w-full px-4 py-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 bg-gray-50 focus:bg-white" placeholder="15">
                                </div>

                                <div>
                                    <label class="flex items-center text-sm font-semibold text-gray-800 mb-2">
                                        <svg class="w-4 h-4 mr-2 text-orange-600 lucide lucide-flame" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 2.5z"/>
                                        </svg>
                                        Cook Time (minutes)
                                    </label>
                                    <input type="number" name="cook_time" value="{{ old('cook_time', $recipe->recipe->cook_time ?? '') }}" min="0" max="480"
                                           class="w-full px-4 py-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 bg-gray-50 focus:bg-white" placeholder="30">
                                </div>
                            </div>

                            <div class="pt-4">
                                <div class="bg-gradient-to-r from-yellow-50 to-amber-50 border border-yellow-200 rounded-lg p-4">
                                    <label class="flex items-center cursor-pointer group">
                                        <input type="hidden" name="is_featured" value="0">
                                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $recipe->is_featured) ? 'checked' : '' }}
                                               class="h-5 w-5 text-yellow-600 border-gray-300 rounded-md focus:ring-2 focus:ring-yellow-500 transition-colors duration-200">
                                        <div class="ml-3">
                                            <span class="text-sm font-semibold text-gray-900 group-hover:text-yellow-800 flex items-center">
                                                <svg class="w-4 h-4 mr-1 text-yellow-600" fill="currentColor" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                    <polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26"/>
                                                </svg>
                                                Feature this recipe
                                            </span>
                                            <p class="text-xs text-gray-600 mt-1">Featured recipes appear prominently in meal recommendations</p>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recipe Ingredients -->
                    <div class="bg-white rounded-xl border border-gray-100">
                        <div class="px-6 py-4 border-b border-gray-100">
                            <h3 class="text-base font-semibold text-gray-900">Recipe Ingredients</h3>
                            <p class="text-sm text-gray-500 mt-0.5">Ingredient list with quantities and pricing</p>
                        </div>
                        
                        <div class="p-6 space-y-4">
                            <!-- Ingredients Grid Container -->
                            <div class="space-y-3">
                            
                            <!-- Column Headers -->
                            <div class="grid grid-cols-12 gap-3 pb-2 border-b border-gray-200">
                                <div class="col-span-4">
                                    <label class="text-xs font-semibold text-gray-600 uppercase tracking-wider">Ingredient Name</label>
                                </div>
                                <div class="col-span-2">
                                    <label class="text-xs font-semibold text-gray-600 uppercase tracking-wider text-center">Quantity</label>
                                </div>
                                <div class="col-span-1">
                                    <label class="text-xs font-semibold text-gray-600 uppercase tracking-wider text-center">Unit</label>
                                </div>
                                <div class="col-span-2">
                                    <label class="text-xs font-semibold text-gray-600 uppercase tracking-wider text-center">Unit Price</label>
                                </div>
                                <div class="col-span-2">
                                    <label class="text-xs font-semibold text-gray-600 uppercase tracking-wider text-center">Total Price</label>
                                </div>
                                <div class="col-span-1">
                                    <label class="text-xs font-semibold text-gray-600 uppercase tracking-wider text-center">Action</label>
                                </div>
                            </div>
                            
                            <!-- Ingredients List -->
                            <div id="recipe-ingredients" class="mb-3">
                                @if($recipe->recipe->ingredients && count($recipe->recipe->ingredients) > 0)
                                    @foreach($recipe->recipe->ingredients as $index => $ingredient)
                                        @php
                                            // Parse ingredient data safely
                                            if (is_array($ingredient)) {
                                                $name = $ingredient['name'] ?? '';
                                                $quantity = $ingredient['amount'] ?? '';
                                                $unit = $ingredient['unit'] ?? '';
                                                $price = $ingredient['price'] ?? '';
                                            } else {
                                                // Parse string format: "Name - Quantity Unit"
                                                $parts = explode(' - ', (string)$ingredient, 2);
                                                $name = $parts[0] ?? '';
                                                $amountUnit = $parts[1] ?? '';
                                                $amountParts = explode(' ', trim($amountUnit), 2);
                                                $quantity = $amountParts[0] ?? '';
                                                $unit = $amountParts[1] ?? '';
                                                $price = '';
                                            }
                                        @endphp
                                        <div class="grid grid-cols-12 gap-3 ingredient-item">
                                            <div class="col-span-4">
                                                <input type="text" name="ingredient_names[]" 
                                                       value="{{ $name }}"
                                                       class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-gray-900 focus:border-gray-900 transition-all duration-200 bg-white hover:border-gray-300 recipe-ing-name" 
                                                       placeholder="e.g., Chicken breast, Rice, Garlic">
                                            </div>
                                            <div class="col-span-2">
                                                <input type="number" name="ingredient_quantities[]" 
                                                       value="{{ $quantity }}"
                                                       class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-gray-900 focus:border-gray-900 text-center transition-all duration-200 bg-white hover:border-gray-300 recipe-ing-qty" 
                                                       placeholder="500" min="0.01" step="0.01" onchange="calculateRowTotal(this)">
                                            </div>
                                            <div class="col-span-1">
                                                <select name="ingredient_units[]" class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-gray-900 focus:border-gray-900 transition-all duration-200 bg-white hover:border-gray-300 recipe-ing-unit">
                                                    <option value="">Select unit...</option>
                                                    <option value="kg" {{ $unit == 'kg' ? 'selected' : '' }}>Kilogram (kg)</option>
                                                    <option value="g" {{ $unit == 'g' ? 'selected' : '' }}>Gram (g)</option>
                                                    <option value="lb" {{ $unit == 'lb' ? 'selected' : '' }}>Pound (lb)</option>
                                                    <option value="oz" {{ $unit == 'oz' ? 'selected' : '' }}>Ounce (oz)</option>
                                                    <option value="cup" {{ $unit == 'cup' ? 'selected' : '' }}>Cup</option>
                                                    <option value="tbsp" {{ $unit == 'tbsp' ? 'selected' : '' }}>Tablespoon (tbsp)</option>
                                                    <option value="tsp" {{ $unit == 'tsp' ? 'selected' : '' }}>Teaspoon (tsp)</option>
                                                    <option value="ml" {{ $unit == 'ml' ? 'selected' : '' }}>Milliliter (ml)</option>
                                                    <option value="l" {{ $unit == 'l' ? 'selected' : '' }}>Liter (l)</option>
                                                </select>
                                            </div>
                                            <div class="col-span-2">
                                                <input type="number" name="ingredient_prices[]"
                                                       value="{{ $price }}"
                                                       class="w-full px-3 py-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-center transition-colors duration-200 bg-gray-50 focus:bg-white recipe-ing-price" 
                                                       placeholder="0.00" min="0" step="0.01" onchange="calculateRowTotal(this)">
                                            </div>
                                            <div class="col-span-2">
                                                <input type="number" name="ingredient_totals[]" 
                                                       value="{{ $price && $quantity ? number_format($price * $quantity, 2, '.', '') : '' }}"
                                                       class="w-full px-3 py-3 text-sm border border-gray-200 rounded-lg bg-gray-100 text-center text-gray-600 recipe-ing-total" 
                                                       placeholder="0.00" readonly>
                                            </div>
                                            <div class="col-span-1 flex items-center justify-center">
                                                <button type="button" onclick="this.parentElement.parentElement.remove(); updateIngredientCount();" 
                                                        class="w-10 h-10 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors flex items-center justify-center font-semibold">
                                                    ×
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            
                            </div>
                            
                            <!-- Add Ingredient Button -->
                            <div class="pt-4 flex items-center gap-3">
                                <button type="button" onclick="addRecipeIngredient()" 
                                        class="inline-flex items-center px-4 py-2 bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium rounded-lg transition-all duration-200">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Add Ingredient
                                </button>
                                
                                <button type="button" 
                                        onclick="document.getElementById('editBulkUploadFile').click()"
                                        class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
                                    </svg>
                                    Bulk Upload
                                </button>
                                
                                <input type="file" 
                                       id="editBulkUploadFile" 
                                       accept=".csv,.txt" 
                                       style="display: none;" 
                                       onchange="handleEditBulkUpload(event)">
                            </div>
                            
                            <!-- Nutrition Calculation Section -->
                            <div class="grid grid-cols-2 gap-3 pt-4">
                                <div>
                                    <button type="button" onclick="calculateRecipeNutrition()" 
                                            class="w-full px-4 py-3 bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium rounded-lg transition-all duration-200 flex items-center justify-center space-x-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                        </svg>
                                        <span>Calculate Nutrition</span>
                                    </button>
                                </div>
                                <div>
                                    <!-- Servings hidden - always 1 person -->
                                    <input type="hidden" name="servings" id="recipe-servings" value="1">
                                </div>
                            </div>
                            
                            <!-- Nutrition Results -->
                            <div id="recipe-result" class="hidden pt-4">
                                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                    <h4 class="text-sm font-semibold text-gray-900 mb-3 flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                        </svg>
                                        Calculated Recipe Nutrition (per serving)
                                    </h4>
                                    <div class="grid grid-cols-4 gap-3 mb-4">
                                        <div class="bg-white border border-gray-100 p-3 rounded-lg text-center">
                                            <div class="text-xl font-bold text-gray-900" id="recipe-calories">0</div>
                                            <div class="text-xs text-gray-500 font-medium mt-1">Calories</div>
                                        </div>
                                        <div class="bg-white border border-gray-100 p-3 rounded-lg text-center">
                                            <div class="text-xl font-bold text-gray-900" id="recipe-protein">0g</div>
                                            <div class="text-xs text-gray-500 font-medium mt-1">Protein</div>
                                        </div>
                                        <div class="bg-white border border-gray-100 p-3 rounded-lg text-center">
                                            <div class="text-xl font-bold text-gray-900" id="recipe-carbs">0g</div>
                                            <div class="text-xs text-gray-500 font-medium mt-1">Carbs</div>
                                        </div>
                                        <div class="bg-white border border-gray-100 p-3 rounded-lg text-center">
                                            <div class="text-xl font-bold text-gray-900" id="recipe-fats">0g</div>
                                            <div class="text-xs text-gray-500 font-medium mt-1">Fats</div>
                                        </div>
                                    </div>
                                <div class="bg-gray-100 p-4 rounded-lg">
                                    <div class="grid grid-cols-2 gap-4 text-sm">
                                        <div class="space-y-2">
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Total Cost:</span>
                                                <span class="font-medium" id="recipe-cost">₱0.00</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Cost per Serving:</span>
                                                <span class="font-medium" id="recipe-cost-per-serving">₱0.00</span>
                                            </div>
                                        </div>
                                        <div class="space-y-2">
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Fiber:</span>
                                                <span class="font-medium" id="recipe-fiber">0g</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Sugar:</span>
                                                <span class="font-medium" id="recipe-sugar">0g</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Instructions -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h3 class="text-lg font-semibold text-gray-900">Cooking Instructions</h3>
                            <p class="text-sm text-gray-600">Step-by-step cooking guide</p>
                        </div>
                        <div class="p-6">
                            <textarea name="instructions" rows="6" maxlength="1000"
                                      class="w-full px-4 py-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 resize-none transition-colors duration-200 bg-gray-50 focus:bg-white"
                                      placeholder="1. Prepare all ingredients and equipment needed&#10;2. Heat oil in a large pan over medium heat&#10;3. Add ingredients step by step...&#10;4. Season to taste and serve hot&#10;&#10;(max 1000 characters)">{{ old('instructions', $recipe->recipe->instructions ?? '') }}</textarea>
                            <div class="flex justify-between items-center mt-1">
                                <span class="text-xs text-gray-400" id="inst-count">{{ strlen(old('instructions', $recipe->recipe->instructions ?? '')) }}/1000</span>
                                @error('instructions')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>

                    <!-- Nutritional Information -->
                    @if($recipe->nutritionalInfo)
                    <details class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden" open>
                        <summary class="px-6 py-4 border-b border-gray-100 cursor-pointer flex items-center justify-between hover:bg-gray-50/50 transition-colors">
                            <div>
                                <h3 class="text-base font-semibold text-gray-900">Nutritional Information</h3>
                                <p class="text-sm text-gray-500 mt-0.5">Auto-calculated from ingredients - Read only</p>
                            </div>
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </summary>
                        <div class="p-6">
                            <div class="grid grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Protein (g)</label>
                                    <input type="number" name="protein" value="{{ old('protein', $recipe->nutritionalInfo->protein ?? 0) }}" min="0" step="0.1" readonly
                                           class="no-spinners w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Carbs (g)</label>
                                    <input type="number" name="carbs" value="{{ old('carbs', $recipe->nutritionalInfo->carbs ?? 0) }}" min="0" step="0.1" readonly
                                           class="no-spinners w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Fats (g)</label>
                                    <input type="number" name="fats" value="{{ old('fats', $recipe->nutritionalInfo->fats ?? 0) }}" min="0" step="0.1" readonly
                                           class="no-spinners w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Fiber (g)</label>
                                    <input type="number" name="fiber" value="{{ old('fiber', $recipe->nutritionalInfo->fiber ?? 0) }}" min="0" step="0.1" readonly
                                           class="no-spinners w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Sugar (g)</label>
                                    <input type="number" name="sugar" value="{{ old('sugar', $recipe->nutritionalInfo->sugar ?? 0) }}" min="0" step="0.1" readonly
                                           class="no-spinners w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Sodium (mg)</label>
                                    <input type="number" name="sodium" value="{{ old('sodium', $recipe->nutritionalInfo->sodium ?? 0) }}" min="0" step="0.1" readonly
                                           class="no-spinners w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed">
                                </div>
                            </div>
                        </div>
                    </details>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1 space-y-4">
                    <!-- Recipe Image -->
                    <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                        <div class="px-5 py-3.5 border-b border-gray-100">
                            <h3 class="text-sm font-semibold text-gray-900">Recipe Image</h3>
                            <p class="text-xs text-gray-500 mt-0.5">Upload or update photo</p>
                        </div>
                        <div class="p-5">
                            @if($recipe->image_path)
                                <div class="mb-3">
                                    <img src="{{ $recipe->image_url }}" 
                                         alt="{{ $recipe->name }}" 
                                         class="w-full h-36 object-cover rounded-lg border border-gray-100">
                                    <p class="text-xs text-gray-500 mt-2">Current image</p>
                                </div>
                            @endif
                            <input type="file" name="image" accept="image/*"
                                   class="w-full text-xs file:mr-2 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-gray-900 file:text-white hover:file:bg-gray-800 file:transition-colors cursor-pointer border border-gray-200 rounded-lg hover:border-gray-300">
                            <p class="text-xs text-gray-500 mt-2">Max 2MB (JPG, PNG, GIF)</p>
                            @error('image')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <!-- Recipe Status -->
                    <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                        <div class="px-5 py-3.5 border-b border-gray-100">
                            <h4 class="text-sm font-semibold text-gray-900">Recipe Status</h4>
                        </div>
                        <div class="p-5 space-y-3">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-500 flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-gray-400 lucide lucide-clock" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"/>
                                        <polyline points="12,6 12,12 16,14"/>
                                    </svg>
                                    Last updated
                                </span>
                                <span class="font-medium text-gray-900">{{ $recipe->updated_at->diffForHumans() }}</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600 flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-gray-400 lucide lucide-calendar" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M8 2v4"/>
                                        <path d="M16 2v4"/>
                                        <rect width="18" height="18" x="3" y="4" rx="2"/>
                                        <path d="M3 10h18"/>
                                    </svg>
                                    Created
                                </span>
                                <span class="font-medium text-gray-900">{{ $recipe->created_at->format('M d, Y') }}</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600 flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-gray-400 lucide lucide-flag" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/>
                                        <line x1="4" x2="4" y1="22" y2="15"/>
                                    </svg>
                                    Status
                                </span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $recipe->is_featured ? 'bg-gradient-to-r from-yellow-100 to-amber-100 text-yellow-800 border border-yellow-200' : 'bg-gradient-to-r from-gray-100 to-gray-200 text-gray-800 border border-gray-300' }}">
                                    @if($recipe->is_featured)
                                        <svg class="w-3 h-3 mr-1 lucide lucide-star" fill="currentColor" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26"/>
                                        </svg>
                                        Featured
                                    @else
                                        <svg class="w-3 h-3 mr-1 lucide lucide-circle" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <circle cx="12" cy="12" r="10"/>
                                        </svg>
                                        Standard
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Stats -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden sticky top-96 z-10">
                        <div class="px-5 py-3 border-b border-gray-200 bg-gray-50">
                            <h4 class="text-base font-semibold text-gray-900">Quick Stats</h4>
                        </div>
                        <div class="p-5">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="text-center p-3 bg-blue-50 rounded-lg border border-blue-100">
                                    <div class="text-2xl font-bold text-blue-600">{{ $recipe->calories }}</div>
                                    <div class="text-xs text-gray-600 mt-1">Calories</div>
                                </div>
                                <div class="text-center p-3 bg-green-50 rounded-lg border border-green-100">
                                    <div class="text-2xl font-bold text-green-600">₱{{ number_format($recipe->cost, 2) }}</div>
                                    <div class="text-xs text-gray-600 mt-1">Cost</div>
                                </div>
                                @if($recipe->recipe)
                                <div class="text-center p-3 bg-purple-50 rounded-lg border border-purple-100">
                                    <div class="text-2xl font-bold text-purple-600">{{ $recipe->recipe->prep_time ?? 0 }}</div>
                                    <div class="text-xs text-gray-600 mt-1">Prep (min)</div>
                                </div>
                                <div class="text-center p-3 bg-orange-50 rounded-lg border border-orange-100">
                                    <div class="text-2xl font-bold text-orange-600">{{ $recipe->recipe->cook_time ?? 0 }}</div>
                                    <div class="text-xs text-gray-600 mt-1">Cook (min)</div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Recipe Tags -->
                    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                        <div class="px-5 py-3 border-b border-gray-200 bg-gray-50">
                            <h4 class="text-base font-semibold text-gray-900">Recipe Tags</h4>
                        </div>
                        <div class="p-5">
                            <div class="flex flex-wrap gap-2">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200">
                                    {{ ucfirst($recipe->cuisine_type) }}
                                </span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 border border-purple-200">
                                    {{ ucfirst($recipe->difficulty) }}
                                </span>
                                @if($recipe->recipe && $recipe->recipe->servings)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                    {{ $recipe->recipe->servings }} servings
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Error Messages -->
    @if($errors->any())
        <div class="fixed top-20 right-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg shadow-lg max-w-md z-50">
            <h4 class="font-semibold text-sm mb-2">Please fix the following errors:</h4>
            <ul class="text-sm space-y-1">
                @foreach($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
@endsection

@push('styles')
<style>
    /* Clean, minimal styles */
    .sticky { position: sticky; }
    .transition-colors { transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out; }
    
    /* Ingredient layout styling to match Recipe Information section */
    .ingredient-item {
        transition: all 0.2s ease-in-out;
    }
    .ingredient-item:hover {
        background-color: #f8fafc;
        border-radius: 0.5rem;
        padding: 0.5rem;
        margin: -0.5rem;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
    }
    
    /* Match Recipe Information section input styling */
    .ingredient-item input, .ingredient-item select {
        background-color: #f9fafb;
        transition: all 0.2s ease-in-out;
    }
    .ingredient-item input:focus, .ingredient-item select:focus {
        background-color: #ffffff;
        border-color: #3b82f6;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
    }
    
    /* Focus states matching Recipe Information section */
    input:focus, textarea:focus, select:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
    }
    
    /* Mobile responsive */
    @media (max-width: 768px) {
        .lg\:grid-cols-3 { grid-template-columns: 1fr; }
        .lg\:col-span-2 { grid-column: span 1; }
        .lg\:col-span-1 { grid-column: span 1; }
        
        /* Stack ingredients on mobile */
        .ingredient-item {
            grid-template-columns: 1fr 1fr;
            gap: 0.5rem;
        }
        .ingredient-item > *:nth-child(1) { grid-column: span 2; }
        .ingredient-item > *:nth-child(2), 
        .ingredient-item > *:nth-child(3) { grid-column: span 1; }
        .ingredient-item > *:nth-child(4), 
        .ingredient-item > *:nth-child(5) { grid-column: span 1; }
    }
</style>
@endpush

<script>
// Grid-based ingredient management functions
function addRecipeIngredient() {
    const container = document.getElementById('recipe-ingredients');
    if (!container) {
        console.error('Recipe ingredients container not found');
        return;
    }
    
    const newRow = document.createElement('div');
    newRow.className = 'grid grid-cols-12 gap-3 ingredient-item';
    newRow.innerHTML = `
        <div class="col-span-4">
            <input type="text" name="ingredient_names[]" 
                   class="w-full px-4 py-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 bg-gray-50 focus:bg-white recipe-ing-name" 
                   placeholder="e.g., Chicken breast, Rice, Garlic">
        </div>
        <div class="col-span-2">
            <input type="number" name="ingredient_quantities[]" 
                   class="w-full px-3 py-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-center transition-colors duration-200 bg-gray-50 focus:bg-white recipe-ing-qty" 
                   placeholder="500" min="0.01" step="0.01" onchange="calculateRowTotal(this)">
        </div>
        <div class="col-span-1">
            <select name="ingredient_units[]" class="w-full px-3 py-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 bg-gray-50 focus:bg-white recipe-ing-unit">
                <option value="">Select unit...</option>
                <option value="kg">Kilogram (kg)</option>
                <option value="g">Gram (g)</option>
                <option value="lb">Pound (lb)</option>
                <option value="oz">Ounce (oz)</option>
                <option value="cup">Cup</option>
                <option value="tbsp">Tablespoon (tbsp)</option>
                <option value="tsp">Teaspoon (tsp)</option>
                <option value="ml">Milliliter (ml)</option>
                <option value="l">Liter (l)</option>
            </select>
        </div>
        <div class="col-span-2">
            <input type="number" name="ingredient_prices[]"
                   class="w-full px-3 py-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-center transition-colors duration-200 bg-gray-50 focus:bg-white recipe-ing-price" 
                   placeholder="0.00" min="0" step="0.01" onchange="calculateRowTotal(this)">
        </div>
        <div class="col-span-2">
            <input type="number" name="ingredient_totals[]" 
                   class="w-full px-3 py-3 text-sm border border-gray-200 rounded-lg bg-gray-100 text-center text-gray-600 recipe-ing-total" 
                   placeholder="0.00" readonly>
        </div>
        <div class="col-span-1 flex items-center justify-center">
            <button type="button" onclick="this.parentElement.parentElement.remove(); updateIngredientCount();" 
                    class="w-10 h-10 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors flex items-center justify-center font-semibold">
                ×
            </button>
        </div>
    `;
    container.appendChild(newRow);
    updateIngredientCount();
    console.log('New ingredient row added');
}

function handleEditBulkUpload(event) {
    const file = event.target.files[0];
    if (!file) return;
    
    const reader = new FileReader();
    reader.onload = function(e) {
        const text = e.target.result;
        parseEditBulkIngredients(text);
    };
    reader.readAsText(file);
    
    // Reset the file input so the same file can be uploaded again
    event.target.value = '';
}

function parseEditBulkIngredients(text) {
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
            
            let name = '', quantity = '', unit = 'g', price = '';
            
            if (trimmedLine.includes(',')) {
                // CSV format
                const parts = trimmedLine.split(',').map(p => p.trim());
                name = parts[0] || '';
                quantity = parts[1] || '';
                unit = parts[2] || 'g';
                price = parts[3] || '';
            } else if (trimmedLine.includes(' - ')) {
                // Simple format: "ingredient name - quantity unit"
                const parts = trimmedLine.split(' - ');
                name = parts[0].trim();
                if (parts[1]) {
                    const quantityUnit = parts[1].trim().split(' ');
                    quantity = quantityUnit[0] || '';
                    unit = quantityUnit[1] || 'g';
                }
            } else {
                // Raw ingredient list
                name = trimmedLine;
            }
            
            if (name) {
                // Add the ingredient using the edit page structure
                const container = document.getElementById('recipe-ingredients');
                if (container) {
                    const newRow = document.createElement('div');
                    newRow.className = 'grid grid-cols-12 gap-3 ingredient-item';
                    newRow.innerHTML = `
                        <div class="col-span-4">
                            <input type="text" name="ingredient_names[]" 
                                   value="${name}"
                                   class="w-full px-4 py-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 bg-gray-50 focus:bg-white recipe-ing-name" 
                                   placeholder="e.g., Chicken breast, Rice, Garlic">
                        </div>
                        <div class="col-span-2">
                            <input type="number" name="ingredient_quantities[]" 
                                   value="${quantity}"
                                   class="w-full px-3 py-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-center transition-colors duration-200 bg-gray-50 focus:bg-white recipe-ing-qty" 
                                   placeholder="500" min="0.01" step="0.01" onchange="calculateRowTotal(this)">
                        </div>
                        <div class="col-span-1">
                            <select name="ingredient_units[]" class="w-full px-3 py-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 bg-gray-50 focus:bg-white recipe-ing-unit">
                                <option value="">Select unit...</option>
                                <option value="kg" ${unit === 'kg' ? 'selected' : ''}>Kilogram (kg)</option>
                                <option value="g" ${unit === 'g' ? 'selected' : ''}>Gram (g)</option>
                                <option value="lb" ${unit === 'lb' ? 'selected' : ''}>Pound (lb)</option>
                                <option value="oz" ${unit === 'oz' ? 'selected' : ''}>Ounce (oz)</option>
                                <option value="cup" ${unit === 'cup' ? 'selected' : ''}>Cup</option>
                                <option value="tbsp" ${unit === 'tbsp' ? 'selected' : ''}>Tablespoon (tbsp)</option>
                                <option value="tsp" ${unit === 'tsp' ? 'selected' : ''}>Teaspoon (tsp)</option>
                                <option value="ml" ${unit === 'ml' ? 'selected' : ''}>Milliliter (ml)</option>
                                <option value="l" ${unit === 'l' ? 'selected' : ''}>Liter (l)</option>
                            </select>
                        </div>
                        <div class="col-span-2">
                            <input type="number" name="ingredient_prices[]"
                                   value="${price}"
                                   class="w-full px-3 py-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-center transition-colors duration-200 bg-gray-50 focus:bg-white recipe-ing-price" 
                                   placeholder="0.00" min="0" step="0.01" onchange="calculateRowTotal(this)">
                        </div>
                        <div class="col-span-2">
                            <input type="number" name="ingredient_totals[]" 
                                   class="w-full px-3 py-3 text-sm border border-gray-200 rounded-lg bg-gray-100 text-center text-gray-600 recipe-ing-total" 
                                   placeholder="0.00" readonly>
                        </div>
                        <div class="col-span-1 flex items-center justify-center">
                            <button type="button" onclick="this.parentElement.parentElement.remove(); updateIngredientCount();" 
                                    class="w-10 h-10 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors flex items-center justify-center font-semibold">
                                ×
                            </button>
                        </div>
                    `;
                    container.appendChild(newRow);
                    addedCount++;
                }
            }
        });
        
        updateIngredientCount();
        
        // Trigger calculations for all newly added rows
        if (addedCount > 0) {
            setTimeout(() => {
                document.querySelectorAll('#recipe-ingredients .ingredient-item').forEach(row => {
                    const qtyInput = row.querySelector('.recipe-ing-qty');
                    const priceInput = row.querySelector('.recipe-ing-price');
                    if (qtyInput && priceInput && (qtyInput.value || priceInput.value)) {
                        calculateRowTotal(qtyInput);
                    }
                });
                console.log('Bulk upload calculations triggered for all rows');
            }, 100);
        }
        
        if (addedCount > 0) {
            // Show success message (you can customize this based on existing message system)
            console.log(`Successfully added ${addedCount} ingredients from bulk upload.`);
            alert(`Successfully added ${addedCount} ingredients from bulk upload.`);
        } else {
            alert('No valid ingredients found in the uploaded file.');
        }
        
    } catch (error) {
        console.error('Error parsing bulk ingredients:', error);
        alert('Error parsing the uploaded file. Please check the format.');
    }
}

function updateIngredientCount() {
    const count = document.querySelectorAll('#recipe-ingredients .ingredient-item').length;
    console.log('Ingredient count updated:', count);
    // Update any ingredient count displays if they exist
    const countElement = document.getElementById('ingredient-count');
    if (countElement) {
        countElement.textContent = `${count} items`;
    }
}

// Calculate total price for a single ingredient row
function calculateRowTotal(element) {
    const row = element.closest('.ingredient-item');
    const quantityInput = row.querySelector('.recipe-ing-qty');
    const priceInput = row.querySelector('.recipe-ing-price');
    const totalInput = row.querySelector('.recipe-ing-total');
    
    if (!quantityInput || !priceInput || !totalInput) {
        console.error('Missing input elements in row', row);
        return;
    }
    
    const quantity = parseFloat(quantityInput.value) || 0;
    const price = parseFloat(priceInput.value) || 0;
    const total = quantity * price;
    
    totalInput.value = total.toFixed(2);
    
    // Debug logging
    console.log('Row total calculated:', {
        quantity: quantity,
        price: price,
        total: total.toFixed(2),
        element: element.className
    });
    
    // Update the display immediately
    totalInput.dispatchEvent(new Event('change'));
}

async function calculateRecipeNutrition() {
    const ingredients = [];
    const rows = document.querySelectorAll('#recipe-ingredients .ingredient-item');
    
    // Collect ingredients from the grid
    rows.forEach(row => {
        const name = row.querySelector('.recipe-ing-name')?.value?.trim();
        const qty = row.querySelector('.recipe-ing-qty')?.value?.trim();
        const unit = row.querySelector('.recipe-ing-unit')?.value?.trim();
        const price = row.querySelector('.recipe-ing-price')?.value?.trim();
        
        if (name && qty && unit) {
            ingredients.push({ 
                name, 
                quantity: parseFloat(qty), 
                unit,
                price: price ? parseFloat(price) : 0 
            });
        }
    });
    
    if (ingredients.length === 0) {
        alert('Please add at least one ingredient.');
        return;
    }
    
    const servingsInput = document.getElementById('recipe-servings');
    const servings = servingsInput ? parseInt(servingsInput.value) || 1 : 1;
    
    console.log('Calculating nutrition for:', ingredients, 'Servings:', servings);
    
    try {
        const response = await fetch('/api/calculate-recipe-nutrition', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
                
            },
            body: JSON.stringify({ ingredients, servings })
        });
        
        const data = await response.json();
        console.log('Nutrition API response:', data);
        
        if (data.success) {
            displayRecipeResults(data, servings, ingredients);
            // Auto-populate form fields
            populateFormFields(data, servings, ingredients);
        } else {
            alert('Error calculating nutrition: ' + (data.message || 'Unknown error'));
        }
    } catch (error) {
        console.error('Nutrition calculation error:', error);
        alert('Failed to calculate nutrition. Please try again.');
    }
}

function displayRecipeResults(data, servings, ingredients) {
    const resultDiv = document.getElementById('recipe-result');
    if (!resultDiv) return;
    
    const perServing = data.per_serving || {};
    const total = data.total || {};
    
    // Calculate total cost
    const totalCost = ingredients.reduce((sum, ing) => sum + (ing.price || 0), 0);
    const costPerServing = totalCost / servings;
    
    // Update nutrition display
    const updateElement = (id, value) => {
        const element = document.getElementById(id);
        if (element) element.textContent = value;
    };
    
    updateElement('recipe-calories', Math.round(perServing.calories || 0));
    updateElement('recipe-protein', (perServing.protein || 0).toFixed(1) + 'g');
    updateElement('recipe-carbs', (perServing.carbs || 0).toFixed(1) + 'g');
    updateElement('recipe-fats', (perServing.fats || 0).toFixed(1) + 'g');
    updateElement('recipe-fiber', (perServing.fiber || 0).toFixed(1) + 'g');
    updateElement('recipe-sugar', (perServing.sugar || 0).toFixed(1) + 'g');
    updateElement('recipe-cost', '₱' + totalCost.toFixed(2));
    updateElement('recipe-cost-per-serving', '₱' + costPerServing.toFixed(2));
    
    resultDiv.classList.remove('hidden');
}

function populateFormFields(data, servings, ingredients) {
    const perServing = data.per_serving || {};
    const totalCost = ingredients.reduce((sum, ing) => sum + (ing.price || 0), 0);
    const costPerServing = totalCost / servings;
    
    // Auto-fill main form fields
    const fields = {
        'calories': Math.round(perServing.calories || 0),
        'cost': costPerServing.toFixed(2),
        'protein': (perServing.protein || 0).toFixed(1),
        'carbs': (perServing.carbs || 0).toFixed(1),
        'fats': (perServing.fats || 0).toFixed(1),
        'fiber': (perServing.fiber || 0).toFixed(1),
        'sugar': (perServing.sugar || 0).toFixed(1),
        'sodium': Math.round(perServing.sodium || 0)
    };
    
    Object.entries(fields).forEach(([field, value]) => {
        const input = document.querySelector(`input[name="${field}"]`);
        if (input) {
            input.value = value;
            // Visual feedback
            input.style.background = '#f0f9ff';
            input.style.borderColor = '#0ea5e9';
            setTimeout(() => {
                input.style.background = '';
                input.style.borderColor = '';
            }, 2000);
        }
    });
    
    console.log('✅ Form fields auto-populated with nutrition data');
}

// Initialize ingredient count when DOM loads
document.addEventListener('DOMContentLoaded', function() {
    updateIngredientCount();
    console.log('✅ Grid-based ingredient management initialized');
    
    // Calculate totals for existing ingredients on page load
    document.querySelectorAll('#recipe-ingredients .ingredient-item').forEach(row => {
        const qtyInput = row.querySelector('.recipe-ing-qty');
        const priceInput = row.querySelector('.recipe-ing-price');
        if (qtyInput && priceInput && (qtyInput.value || priceInput.value)) {
            calculateRowTotal(qtyInput);
        }
    });
    console.log('✅ Existing ingredient calculations initialized');
    
    // Add event delegation for ingredient calculations
    const container = document.getElementById('recipe-ingredients');
    if (container) {
        // Use event delegation to handle dynamically added elements
        container.addEventListener('input', function(e) {
            if (e.target.classList.contains('recipe-ing-qty') || 
                e.target.classList.contains('recipe-ing-price')) {
                calculateRowTotal(e.target);
            }
        });
        
        container.addEventListener('change', function(e) {
            if (e.target.classList.contains('recipe-ing-qty') || 
                e.target.classList.contains('recipe-ing-price')) {
                calculateRowTotal(e.target);
            }
        });
        
        console.log('✅ Event delegation for ingredient calculations added');
    }
});
</script>

<style>
@keyframes slide-up {
    from {
        transform: translateY(100%);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}
.animate-slide-up {
    animation: slide-up 0.3s ease-out;
}
</style>

<script>
// Comprehensive ingredient management system
class IngredientManager {
    constructor() {
        this.ingredients = [];
        this.apiStatus = 'unknown';
        this.loadExistingIngredients();
        this.checkApiStatus();
        this.updateIngredientCount();
        console.log('✅ Grid-based ingredient management initialized');
    }

    validateName() {
        const input = document.getElementById('new-ingredient-name');
        const error = document.getElementById('name-error');
        if (!input || !error) return true;
        
        const value = input.value.trim();
        
        if (!value) {
            this.showError(error, 'Ingredient name is required');
            return false;
        } else if (value.length < 2) {
            this.showError(error, 'Name must be at least 2 characters');
            return false;
        } else if (this.isDuplicateIngredient(value)) {
            this.showError(error, 'This ingredient is already added');
            return false;
        } else {
            this.hideError(error);
            return true;
        }
    }
    
    validateQuantity() {
        const input = document.getElementById('new-ingredient-quantity');
        const error = document.getElementById('quantity-error');
        if (!input || !error) return true;
        
        const value = parseFloat(input.value);
        
        if (!input.value) {
            this.showError(error, 'Quantity is required');
            return false;
        } else if (isNaN(value) || value <= 0) {
            this.showError(error, 'Quantity must be a positive number');
            return false;
        } else if (value > 10000) {
            this.showError(error, 'Quantity seems too large');
            return false;
        } else {
            this.hideError(error);
            return true;
        }
    }
    
    validateUnit() {
        const select = document.getElementById('new-ingredient-unit');
        const error = document.getElementById('unit-error');
        if (!select || !error) return true;
        
        if (!select.value) {
            this.showError(error, 'Unit selection is required');
            return false;
        } else {
            this.hideError(error);
            return true;
        }
    }
    
    validateAll() {
        return this.validateName() && this.validateQuantity() && this.validateUnit();
    }
    
    showError(errorElement, message) {
        errorElement.textContent = message;
        errorElement.classList.remove('hidden');
    }
    
    hideError(errorElement) {
        errorElement.classList.add('hidden');
    }
    
    isDuplicateIngredient(name) {
        return this.ingredients.some(ingredient => 
            ingredient.name.toLowerCase() === name.toLowerCase()
        );
    }
    
    addIngredient() {
        if (!this.validateAll()) return;
        
        const nameInput = document.getElementById('new-ingredient-name');
        const quantityInput = document.getElementById('new-ingredient-quantity');
        const unitSelect = document.getElementById('new-ingredient-unit');
        
        if (!nameInput || !quantityInput || !unitSelect) return;
        
        const name = nameInput.value.trim();
        const quantity = parseFloat(quantityInput.value);
        const unit = unitSelect.value;
        
        const ingredient = { name, quantity, unit, id: Date.now() };
        this.ingredients.push(ingredient);
        this.renderIngredient(ingredient);
        this.clearForm();
        this.updateIngredientCount();
        this.hideEmptyState();
        
        // Show success feedback
        this.showSuccessMessage(`Added ${name} to recipe`);
    }
    
    removeIngredient(element) {
        const index = parseInt(element.dataset.index);
        const ingredientName = element.querySelector('.ingredient-name').value;
        
        // Confirmation dialog
        if (confirm(`Remove "${ingredientName}" from the recipe?`)) {
            element.remove();
            this.ingredients = this.ingredients.filter((_, i) => i !== index);
            this.updateIngredientCount();
            
            if (this.ingredients.length === 0) {
                this.showEmptyState();
            }
            
            this.showSuccessMessage(`Removed ${ingredientName} from recipe`);
        }
    }
    
    renderIngredient(ingredient) {
        const container = document.getElementById('ingredients-container');
        if (!container) return;
        
        // Remove empty state if it exists
        this.hideEmptyState();
        
        const index = this.ingredients.length - 1;
        const template = `
        <div class="ingredient-item col-span-4 grid grid-cols-4 gap-3 p-3 bg-gray-50 rounded-lg" data-index="${index}">
            <div>
                <input type="text" name="ingredients[${index}][name]" value="${ingredient.name}" 
                       class="ingredient-name w-full px-3 py-2 border border-gray-300 rounded-md text-sm" readonly>
            </div>
            <div>
                <input type="number" name="ingredients[${index}][quantity]" value="${ingredient.quantity}" 
                       class="ingredient-quantity w-full px-3 py-2 border border-gray-300 rounded-md text-sm" readonly>
            </div>
            <div>
                <input type="text" name="ingredients[${index}][unit]" value="${ingredient.unit}" 
                       class="ingredient-unit w-full px-3 py-2 border border-gray-300 rounded-md text-sm" readonly>
            </div>
            <div class="flex justify-end">
                <button type="button" onclick="window.ingredientManager.removeIngredient(this.parentElement.parentElement)" 
                        class="text-red-600 hover:text-red-800 font-medium">
                    × Remove
                </button>
            </div>
        </div>
        `;
        
        container.insertAdjacentHTML('beforeend', template);
    }
    
    clearForm() {
        const nameInput = document.getElementById('new-ingredient-name');
        const quantityInput = document.getElementById('new-ingredient-quantity');
        const unitSelect = document.getElementById('new-ingredient-unit');
        
        if (nameInput) nameInput.value = '';
        if (quantityInput) quantityInput.value = '';
        if (unitSelect) unitSelect.value = '';
        
        // Hide any error messages
        ['name-error', 'quantity-error', 'unit-error'].forEach(id => {
            const element = document.getElementById(id);
            if (element) element.classList.add('hidden');
        });
    }
    
    updateIngredientCount() {
        const count = this.ingredients.length;
        const countElement = document.getElementById('ingredient-count');
        if (countElement) {
            countElement.textContent = `${count} items`;
        }
        
        // Enable/disable calculate button
        const calcBtn = document.getElementById('calculate-nutrition-btn');
        if (calcBtn) {
            calcBtn.disabled = count === 0;
        }
        
        // Show/hide empty state
        if (count === 0) {
            this.showEmptyState();
        }
    }
    
    hideEmptyState() {
        const emptyState = document.getElementById('empty-state');
        if (emptyState) emptyState.remove();
    }
    
    showEmptyState() {
        const container = document.getElementById('ingredients-container');
        if (!container) return;
        
        container.innerHTML = `
        <div id="empty-state" class="col-span-4 text-center py-8 text-gray-500">
            <p class="text-lg">No ingredients added</p>
            <p class="text-sm">Use the form above to add ingredients to your recipe</p>
        </div>
        `;
    }
    
    loadExistingIngredients() {
        // Load existing ingredients from the page
        const existingItems = document.querySelectorAll('.ingredient-item');
        existingItems.forEach((item, index) => {
            const nameInput = item.querySelector('.ingredient-name');
            const quantityInput = item.querySelector('.ingredient-quantity');
            const unitSelect = item.querySelector('.ingredient-unit');
            
            if (nameInput && quantityInput && unitSelect) {
                const name = nameInput.value;
                const quantity = parseFloat(quantityInput.value);
                const unit = unitSelect.value;
                
                if (name && quantity && unit) {
                    this.ingredients.push({
                        name, quantity, unit, id: Date.now() + index
                    });
                }
            }
        });
    }
    
    async checkApiStatus() {
        try {
            const response = await fetch('/api/search-food?query=apple', {
                headers: { 'Accept': 'application/json' }
            });
            
            if (response.ok) {
                this.apiStatus = 'connected';
                const indicator = document.getElementById('api-status-indicator');
                const text = document.getElementById('api-status-text');
                if (indicator) indicator.className = 'w-2 h-2 rounded-full bg-green-500';
                if (text) text.textContent = 'API';
            } else {
                throw new Error('API not responding');
            }
        } catch (error) {
            this.apiStatus = 'disconnected';
            const indicator = document.getElementById('api-status-indicator');
            const text = document.getElementById('api-status-text');
            if (indicator) indicator.className = 'w-2 h-2 rounded-full bg-red-500';
            if (text) text.textContent = 'API';
        }
    }
    
    async calculateNutrition() {
        if (this.ingredients.length === 0) {
            alert('Please add ingredients before calculating nutrition.');
            return;
        }
        
        if (this.apiStatus === 'disconnected') {
            alert('Nutrition API is currently unavailable. Please try again later.');
            return;
        }
        
        this.showLoading(true);
        
        try {
            const servingsInput = document.querySelector('input[name="servings"]');
            const servings = servingsInput ? parseInt(servingsInput.value) || 1 : 1;
            
            const response = await fetch('/api/calculate-recipe-nutrition', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                    
                },
                body: JSON.stringify({
                    ingredients: this.ingredients,
                    servings: servings
                })
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.displayNutritionResults(data, servings);
                this.autoFillNutritionFields(data);
            } else {
                throw new Error(data.message || 'Nutrition calculation failed');
            }
        } catch (error) {
            const statusElement = document.getElementById('analysis-status');
            if (statusElement) {
                statusElement.innerHTML = `
                <div class="text-red-600 text-sm">
                    Error: ${error.message}
                </div>
                `;
            }
        } finally {
            this.showLoading(false);
        }
    }
    
    displayNutritionResults(data, servings) {
        const total = data.total || {};
        const perServing = data.per_serving || {};
        
        // Update main panels
        const updateElement = (id, value) => {
            const element = document.getElementById(id);
            if (element) element.textContent = value;
        };
        
        updateElement('total-calories', Math.round(total.calories || 0));
        updateElement('total-protein', (total.protein || 0).toFixed(1) + 'g');
        updateElement('total-carbs', (total.carbs || 0).toFixed(1) + 'g');
        updateElement('total-fats', (total.fats || 0).toFixed(1) + 'g');
        
        // Additional nutrients
        updateElement('total-fiber', (total.fiber || 0).toFixed(1) + 'g');
        updateElement('total-sugar', (total.sugar || 0).toFixed(1) + 'g');
        updateElement('total-sodium', Math.round(total.sodium || 0) + 'mg');
        
        // Per serving
        updateElement('per-serving-calories', Math.round(perServing.calories || 0) + ' kcal');
        updateElement('per-serving-protein', (perServing.protein || 0).toFixed(1) + 'g');
        updateElement('per-serving-carbs', (perServing.carbs || 0).toFixed(1) + 'g');
        
        // Status
        const statusElement = document.getElementById('analysis-status');
        if (statusElement) {
            statusElement.innerHTML = `
            <div class="text-green-600 text-sm">
                ✓ Analysis Complete
                <span class="text-xs text-gray-500 block">
                    Based on ${this.ingredients.length} ingredients
                </span>
            </div>
            `;
        }
        
        const resultsElement = document.getElementById('nutrition-results');
        if (resultsElement) {
            resultsElement.classList.remove('hidden');
        }
    }
    
    autoFillNutritionFields(data) {
        const perServing = data.per_serving || {};
        
        // Auto-fill form fields
        const fields = {
            'calories': Math.round(perServing.calories || 0),
            'protein': (perServing.protein || 0).toFixed(1),
            'carbs': (perServing.carbs || 0).toFixed(1),
            'fats': (perServing.fats || 0).toFixed(1),
            'fiber': (perServing.fiber || 0).toFixed(1),
            'sugar': (perServing.sugar || 0).toFixed(1),
            'sodium': Math.round(perServing.sodium || 0)
        };
        
        Object.entries(fields).forEach(([field, value]) => {
            const input = document.querySelector(`input[name="${field}"]`);
            if (input) {
                input.value = value;
                // Visual feedback
                input.style.background = '#f0f9ff';
                input.style.borderColor = '#0ea5e9';
                setTimeout(() => {
                    input.style.background = '';
                    input.style.borderColor = '';
                }, 2000);
            }
        });
    }
    
    showLoading(show) {
        const loading = document.getElementById('nutrition-loading');
        const button = document.getElementById('calculate-nutrition-btn');
        const buttonText = document.getElementById('calculate-btn-text');
        
        if (show) {
            if (loading) loading.classList.remove('hidden');
            if (button) button.disabled = true;
            if (buttonText) buttonText.textContent = 'Calculating...';
        } else {
            if (loading) loading.classList.add('hidden');
            if (button) button.disabled = false;
            if (buttonText) buttonText.textContent = 'Calculate Nutrition';
        }
    }
    
    showSuccessMessage(message) {
        // Create and show a temporary success message
        const toast = document.createElement('div');
        toast.className = 'fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded z-50';
        toast.textContent = message;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.remove();
        }, 3000);
    }
}

// Initialize the ingredient manager when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.ingredientManager = new IngredientManager();
    console.log('Comprehensive Ingredient Management System initialized');
});

console.log('✅ Grid-based ingredient management functions loaded');
</script>

<style>
@keyframes slide-up {
    from {
        transform: translateY(100%);
        opacity: 0;
    }
            return false;
        } else if (this.isDuplicateIngredient(value)) {
            this.showError(error, 'This ingredient is already added');
            return false;
        } else {
            this.hideError(error);
            return true;
        }
    }
    
    validateQuantity() {
        const input = document.getElementById('new-ingredient-quantity');
        const error = document.getElementById('quantity-error');
        if (!input || !error) return true;
        
        const value = parseFloat(input.value);
        
        if (!input.value) {
            this.showError(error, 'Quantity is required');
            return false;
        } else if (isNaN(value) || value <= 0) {
            this.showError(error, 'Quantity must be a positive number');
            return false;
        } else if (value > 10000) {
            this.showError(error, 'Quantity seems too large');
            return false;
        } else {
            this.hideError(error);
            return true;
        }
    }
    
    validateUnit() {
        const select = document.getElementById('new-ingredient-unit');
        const error = document.getElementById('unit-error');
        if (!select || !error) return true;
        
        if (!select.value) {
            this.showError(error, 'Unit selection is required');
            return false;
        } else {
            this.hideError(error);
            return true;
        }
    }
    
    validateAll() {
        return this.validateName() && this.validateQuantity() && this.validateUnit();
    }
    
    showError(errorElement, message) {
        errorElement.textContent = message;
        errorElement.classList.remove('hidden');
    }
    
    hideError(errorElement) {
        errorElement.classList.add('hidden');
    }
    
    isDuplicateIngredient(name) {
        return this.ingredients.some(ingredient => 
            ingredient.name.toLowerCase() === name.toLowerCase()
        );
    }
    
    addIngredient() {
        if (!this.validateAll()) return;
        
        const nameInput = document.getElementById('new-ingredient-name');
        const quantityInput = document.getElementById('new-ingredient-quantity');
        const unitSelect = document.getElementById('new-ingredient-unit');
        
        if (!nameInput || !quantityInput || !unitSelect) return;
        
        const name = nameInput.value.trim();
        const quantity = parseFloat(quantityInput.value);
        const unit = unitSelect.value;
        
        const ingredient = { name, quantity, unit, id: Date.now() };
        this.ingredients.push(ingredient);
        
        this.renderIngredient(ingredient);
        this.clearForm();
        this.updateIngredientCount();
        this.hideEmptyState();
        
        // Show success feedback
        this.showSuccessMessage(`Added ${name} to recipe`);
    }
    
    removeIngredient(element) {
        const index = parseInt(element.dataset.index);
        const ingredientName = element.querySelector('.ingredient-name').value;
        
        // Confirmation dialog
        if (confirm(`Remove "${ingredientName}" from the recipe?`)) {
            element.remove();
            this.ingredients = this.ingredients.filter((_, i) => i !== index);
            this.updateIngredientCount();
            
            if (this.ingredients.length === 0) {
                this.showEmptyState();
            }
            
            this.showSuccessMessage(`Removed ${ingredientName} from recipe`);
        }
    }
    
    renderIngredient(ingredient) {
        const container = document.getElementById('ingredients-container');
        if (!container) return;
        
        // Remove empty state if it exists
        this.hideEmptyState();
        
        const index = this.ingredients.length - 1;
        
        const template = `
            <div class="ingredient-item flex items-center justify-between p-3 bg-white border border-gray-200 rounded-lg hover:border-gray-300 transition-colors" data-index="${index}">
                <div class="flex items-center space-x-4 flex-1">
                    <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center">
                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                    </div>
                    <div class="flex-1">
                        <input type="text" name="ingredient_names[]" 
                               value="${ingredient.name}"
                               class="ingredient-name text-sm font-medium text-gray-900 bg-transparent border-none p-0 focus:ring-0"
                               readonly>
                    </div>
                    <div class="flex items-center space-x-2 text-sm text-gray-600">
                        <input type="number" name="ingredient_quantities[]" 
                               value="${ingredient.quantity}"
                               class="ingredient-quantity w-16 text-center bg-transparent border-none p-0 focus:ring-0"
                               readonly>
                        <select name="ingredient_units[]" class="ingredient-unit bg-transparent border-none p-0 focus:ring-0 text-gray-600" disabled>
                            <option value="g" ${ingredient.unit === 'g' ? 'selected' : ''}>g</option>
                            <option value="kg" ${ingredient.unit === 'kg' ? 'selected' : ''}>kg</option>
                            <option value="lb" ${ingredient.unit === 'lb' ? 'selected' : ''}>lb</option>
                            <option value="oz" ${ingredient.unit === 'oz' ? 'selected' : ''}>oz</option>
                            <option value="cup" ${ingredient.unit === 'cup' ? 'selected' : ''}>cup</option>
                            <option value="tbsp" ${ingredient.unit === 'tbsp' ? 'selected' : ''}>tbsp</option>
                            <option value="tsp" ${ingredient.unit === 'tsp' ? 'selected' : ''}>tsp</option>
                        </select>
                    </div>
                </div>
                <button type="button" class="remove-ingredient-btn w-8 h-8 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-full transition-colors flex items-center justify-center ml-3"
                        title="Remove ingredient">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        `;
        
        container.insertAdjacentHTML('beforeend', template);
    }
    
    clearForm() {
        const nameInput = document.getElementById('new-ingredient-name');
        const quantityInput = document.getElementById('new-ingredient-quantity');
        const unitSelect = document.getElementById('new-ingredient-unit');
        
        if (nameInput) nameInput.value = '';
        if (quantityInput) quantityInput.value = '';
        if (unitSelect) unitSelect.value = '';
        
        // Hide any error messages
        ['name-error', 'quantity-error', 'unit-error'].forEach(id => {
            const element = document.getElementById(id);
            if (element) element.classList.add('hidden');
        });
    }
    
    updateIngredientCount() {
        const count = this.ingredients.length;
        const countElement = document.getElementById('ingredient-count');
        if (countElement) {
            countElement.textContent = `${count} items`;
        }
        
        // Enable/disable calculate button
        const calcBtn = document.getElementById('calculate-nutrition-btn');
        if (calcBtn) {
            calcBtn.disabled = count === 0;
        }
        
        // Show/hide empty state
        if (count === 0) {
            this.showEmptyState();
        }
    }
    
    hideEmptyState() {
        const emptyState = document.getElementById('empty-state');
        if (emptyState) emptyState.remove();
    }
    
    showEmptyState() {
        const container = document.getElementById('ingredients-container');
        if (!container) return;
        
        container.innerHTML = `
            <div id="empty-state" class="text-center py-8 text-gray-400">
                <svg class="w-8 h-8 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <p class="text-sm">No ingredients added</p>
            </div>
        `;
    }
    
    loadExistingIngredients() {
        // Load existing ingredients from the page
        const existingItems = document.querySelectorAll('.ingredient-item');
        existingItems.forEach((item, index) => {
            const nameInput = item.querySelector('.ingredient-name');
            const quantityInput = item.querySelector('.ingredient-quantity');
            const unitSelect = item.querySelector('.ingredient-unit');
            
            if (nameInput && quantityInput && unitSelect) {
                const name = nameInput.value;
                const quantity = parseFloat(quantityInput.value);
                const unit = unitSelect.value;
                
                if (name && quantity && unit) {
                    this.ingredients.push({ name, quantity, unit, id: Date.now() + index });
                }
            }
        });
    }
    
    async checkApiStatus() {
        try {
            const response = await fetch('/api/search-food?query=apple', {
                headers: { 'Accept': 'application/json' }
            });
            
            if (response.ok) {
                this.apiStatus = 'connected';
                const indicator = document.getElementById('api-status-indicator');
                const text = document.getElementById('api-status-text');
                if (indicator) indicator.className = 'w-2 h-2 rounded-full bg-green-500';
                if (text) text.textContent = 'API';
            } else {
                throw new Error('API not responding');
            }
        } catch (error) {
            this.apiStatus = 'disconnected';
            const indicator = document.getElementById('api-status-indicator');
            const text = document.getElementById('api-status-text');
            if (indicator) indicator.className = 'w-2 h-2 rounded-full bg-red-500';
            if (text) text.textContent = 'API';
        }
    }
    
    async calculateNutrition() {
        if (this.ingredients.length === 0) {
            alert('Please add ingredients before calculating nutrition.');
            return;
        }
        
        if (this.apiStatus === 'disconnected') {
            alert('Nutrition API is currently unavailable. Please try again later.');
            return;
        }
        
        this.showLoading(true);
        
        try {
            const servingsInput = document.querySelector('input[name="servings"]');
            const servings = servingsInput ? parseInt(servingsInput.value) || 1 : 1;
            
            const response = await fetch('/api/calculate-recipe-nutrition', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                    
                },
                body: JSON.stringify({ 
                    ingredients: this.ingredients,
                    servings: servings
                })
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.displayNutritionResults(data, servings);
                this.autoFillNutritionFields(data);
            } else {
                throw new Error(data.message || 'Nutrition calculation failed');
            }
            
        } catch (error) {
            const statusElement = document.getElementById('analysis-status');
            if (statusElement) {
                statusElement.innerHTML = `<p class="text-red-600">Error: ${error.message}</p>`;
            }
        } finally {
            this.showLoading(false);
        }
    }
    
    displayNutritionResults(data, servings) {
        const total = data.total || {};
        const perServing = data.per_serving || {};
        
        // Update main panels
        const updateElement = (id, value) => {
            const element = document.getElementById(id);
            if (element) element.textContent = value;
        };
        
        updateElement('total-calories', Math.round(total.calories || 0));
        updateElement('total-protein', (total.protein || 0).toFixed(1) + 'g');
        updateElement('total-carbs', (total.carbs || 0).toFixed(1) + 'g');
        updateElement('total-fats', (total.fats || 0).toFixed(1) + 'g');
        
        // Additional nutrients
        updateElement('total-fiber', (total.fiber || 0).toFixed(1) + 'g');
        updateElement('total-sugar', (total.sugar || 0).toFixed(1) + 'g');
        updateElement('total-sodium', Math.round(total.sodium || 0) + 'mg');
        
        // Per serving
        updateElement('per-serving-calories', Math.round(perServing.calories || 0) + ' kcal');
        updateElement('per-serving-protein', (perServing.protein || 0).toFixed(1) + 'g');
        updateElement('per-serving-carbs', (perServing.carbs || 0).toFixed(1) + 'g');
        
        // Status
        const statusElement = document.getElementById('analysis-status');
        if (statusElement) {
            statusElement.innerHTML = `
                <p class="text-green-600 font-medium">✓ Analysis Complete</p>
                <p class="text-xs text-gray-500 mt-1">Based on ${this.ingredients.length} ingredients</p>
            `;
        }
        
        const resultsElement = document.getElementById('nutrition-results');
        if (resultsElement) {
            resultsElement.classList.remove('hidden');
        }
    }
    
    autoFillNutritionFields(data) {
        const perServing = data.per_serving || {};
        
        // Auto-fill form fields
        const fields = {
            'calories': Math.round(perServing.calories || 0),
            'protein': (perServing.protein || 0).toFixed(1),
            'carbs': (perServing.carbs || 0).toFixed(1),
            'fats': (perServing.fats || 0).toFixed(1),
            'fiber': (perServing.fiber || 0).toFixed(1),
            'sugar': (perServing.sugar || 0).toFixed(1),
            'sodium': Math.round(perServing.sodium || 0)
        };
        
        Object.entries(fields).forEach(([field, value]) => {
            const input = document.querySelector(`input[name="${field}"]`);
            if (input) {
                input.value = value;
                // Visual feedback
                input.style.background = '#f0f9ff';
                input.style.borderColor = '#0ea5e9';
                setTimeout(() => {
                    input.style.background = '';
                    input.style.borderColor = '';
                }, 2000);
            }
        });
    }
    
    showLoading(show) {
        const loading = document.getElementById('nutrition-loading');
        const button = document.getElementById('calculate-nutrition-btn');
        const buttonText = document.getElementById('calculate-btn-text');
        
        if (show) {
            if (loading) loading.classList.remove('hidden');
            if (button) button.disabled = true;
            if (buttonText) buttonText.textContent = 'Calculating...';
        } else {
            if (loading) loading.classList.add('hidden');
            if (button) button.disabled = false;
            if (buttonText) buttonText.textContent = 'Calculate Nutrition';
        }
    }
    
    showSuccessMessage(message) {
        // Create and show a temporary success message
        const toast = document.createElement('div');
        toast.className = 'fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded z-50';
        toast.textContent = message;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.remove();
        }, 3000);
    }
}

// Initialize the ingredient manager when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.ingredientManager = new IngredientManager();
    console.log('Comprehensive Ingredient Management System initialized');
});

// Legacy functions for backward compatibility
function addIngredient() {
    const template = `
        <div class="ingredient-row flex gap-3 items-start">
            <div class="flex-1">
                <input type="text" name="ingredient_names[]" placeholder="e.g. Chicken breast, Garlic" 
                       class="ingredient-name w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none"
                       onchange="validateIngredientRow(this);">
            </div>
            <div class="w-20">
                <input type="text" name="ingredient_quantities[]" placeholder="2" 
                       required
                       class="ingredient-quantity w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none text-center"
                       oninput="validateIngredientRow(this);">
            </div>
            <div class="w-20">
                <select name="ingredient_units[]" class="ingredient-unit w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none text-center">
                    <option value="">Unit</option>
                    <option value="kg">kg</option>
                    <option value="g">g</option>
                    <option value="lb">lb</option>
                    <option value="oz">oz</option>
                    <option value="cup">cup</option>
                    <option value="tbsp">tbsp</option>
                    <option value="tsp">tsp</option>
                    <option value="ml">ml</option>
                    <option value="l">l</option>
                </select>
            </div>
            <button type="button" onclick="removeIngredient(this)" 
                    class="w-8 h-8 flex items-center justify-center text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors border border-red-200 hover:border-red-300"
                    title="Remove ingredient">
                ×
            </button>
        </div>
    `;
    document.getElementById('ingredients-list').insertAdjacentHTML('beforeend', template);
}

function removeIngredient(button) {
    console.log('Remove ingredient button clicked', button);
    const row = button.closest('.ingredient-row');
    console.log('Found row to remove:', row);
    
    if (row) {
        row.remove();
        console.log('Ingredient row removed successfully');
        
        // Check if there are any ingredients left
        const remainingRows = document.querySelectorAll('.ingredient-row');
        console.log('Remaining ingredient rows:', remainingRows.length);
        
        // If no ingredients left, you could optionally show a message or add a default row
        // But the headers will remain visible regardless
    } else {
        console.error('Could not find ingredient row to remove');
    }
}

// Ensure function is globally accessible
window.removeIngredient = removeIngredient;
window.addIngredient = addIngredient;

console.log('Core functions loaded: addIngredient=', typeof addIngredient, 'removeIngredient=', typeof removeIngredient);
console.log('Window functions: window.addIngredient=', typeof window.addIngredient, 'window.removeIngredient=', typeof window.removeIngredient);

// Additional helper function for creating ingredient rows programmatically
function createIngredientRow(name = '', quantity = '', unit = '', price = '') {
    const wrapper = document.createElement('div');
    wrapper.className = 'ingredient-item grid grid-cols-12 gap-1.5 items-center';

    // Name input with autocomplete
    const nameInput = document.createElement('input');
    nameInput.type = 'text';
    nameInput.name = 'ingredient_names[]';
    nameInput.required = true;
    nameInput.maxLength = 100;
    nameInput.placeholder = 'e.g., Chicken, Garlic';
    nameInput.list = 'ingredients-list';
    nameInput.className = 'col-span-4 px-2 py-1.5 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-green-500 focus:border-transparent';
    nameInput.value = name;
    
    // Add market price fetching on ingredient name change


    const quantityInput = document.createElement('input');
    quantityInput.type = 'number';
    quantityInput.name = 'ingredient_quantities[]';
    quantityInput.required = true;
    quantityInput.step = '0.01';
    quantityInput.min = '0';
    quantityInput.placeholder = '2';
    quantityInput.className = 'col-span-2 px-2 py-1.5 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-green-500 focus:border-transparent';
    quantityInput.value = quantity;
    
    // Add cost calculation on quantity change
    quantityInput.addEventListener('input', function() {
        calculateRowCost(wrapper);
        updateTotalCost();
    });

    // Unit select dropdown with standard USDA API units
    const unitSelect = document.createElement('select');
    unitSelect.name = 'ingredient_units[]';
    unitSelect.required = true;
    unitSelect.className = 'col-span-2 px-2 py-1.5 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-green-500 focus:border-transparent';
    
    // Standard units supported by USDA API
    const unitOptions = [
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
    
    unitOptions.forEach(unitOption => {
        const option = document.createElement('option');
        option.value = unitOption.value;
        option.textContent = unitOption.text;
        if (unit === unitOption.value) {
            option.selected = true;
        }
        unitSelect.appendChild(option);
    });

    // Enhanced price input with market price indicator
    const priceWrapper = document.createElement('div');
    priceWrapper.className = 'col-span-3 relative';
    
    const priceInput = document.createElement('input');
    priceInput.type = 'number';
    priceInput.name = 'ingredient_prices[]';
    priceInput.step = '0.01';
    priceInput.min = '0';
    priceInput.placeholder = '0.00';
    priceInput.className = 'w-full px-2 py-1.5 pr-6 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-green-500 focus:border-transparent';
    priceInput.value = price;
    
    // Add cost calculation on price change
    priceInput.addEventListener('input', function() {
        calculateRowCost(wrapper);
        updateTotalCost();
    });
    
    // Market price indicator
    const priceIndicator = document.createElement('span');
    priceIndicator.className = 'absolute right-1 top-1/2 transform -translate-y-1/2 text-xs opacity-0 transition-opacity';
    priceIndicator.innerHTML = '⚡';
    priceIndicator.title = 'Live market price';
    
    priceWrapper.appendChild(priceInput);
    priceWrapper.appendChild(priceIndicator);

    const btnWrapper = document.createElement('div');
    btnWrapper.className = 'col-span-1 flex items-center justify-center';
    const btn = document.createElement('button');
    btn.type = 'button';
    btn.className = 'p-1 bg-red-50 hover:bg-red-100 text-red-600 rounded transition-colors';
    btn.setAttribute('aria-label', 'Remove ingredient');
    btn.addEventListener('click', function() { removeIngredient(btn); });
    btn.innerHTML = '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>';
    btnWrapper.appendChild(btn);

    wrapper.appendChild(nameInput);
    wrapper.appendChild(quantityInput);
    wrapper.appendChild(unitSelect);
    wrapper.appendChild(priceWrapper);
    wrapper.appendChild(btnWrapper);
    
    // Store references for easy access
    wrapper.nameInput = nameInput;
    wrapper.quantityInput = quantityInput;
    wrapper.unitInput = unitSelect;
    wrapper.priceInput = priceInput;
    wrapper.priceIndicator = priceIndicator;
    
    return wrapper;
}

// Simple ingredient management compatible with current structure
function addIngredient() {
    const template = `
        <div class="ingredient-row flex gap-3 items-start">
            <div class="flex-1">
                <input type="text" name="ingredient_names[]" placeholder="e.g. Chicken breast, Garlic" 
                       class="ingredient-name w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none"
                       onchange="validateIngredientRow(this);">
            </div>
            <div class="w-20">
                <input type="text" name="ingredient_quantities[]" placeholder="2" 
                       required
                       class="ingredient-quantity w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none text-center"
                       oninput="validateIngredientRow(this);">
            </div>
            <div class="w-20">
                <select name="ingredient_units[]" class="ingredient-unit w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none text-center">
                    <option value="">Unit</option>
                    <option value="kg">kg</option>
                    <option value="g">g</option>
                    <option value="lb">lb</option>
                    <option value="oz">oz</option>
                    <option value="cup">cup</option>
                    <option value="tbsp">tbsp</option>
                    <option value="tsp">tsp</option>
                    <option value="ml">ml</option>
                    <option value="l">l</option>
                </select>
            </div>
            <button type="button" onclick="removeIngredient(this)" 
                    class="w-8 h-8 flex items-center justify-center text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors border border-red-200 hover:border-red-300"
                    title="Remove ingredient">
                ×
            </button>
        </div>
    `;

console.log('Functions defined: addIngredient=', typeof addIngredient, 'removeIngredient=', typeof removeIngredient);

// Nutrition calculation logic
document.addEventListener('DOMContentLoaded', function () {
    console.log('DOM loaded, setting up nutrition calculator');
    const calcBtn = document.getElementById('calculate-nutrition-btn');
    if (calcBtn) {
        console.log('Found calculate nutrition button');
        calcBtn.addEventListener('click', async function () {
            console.log('Calculate nutrition button clicked');
            const rows = document.querySelectorAll('.ingredient-row');
            console.log('Found ingredient rows:', rows.length);
            
            let ingredients = [];
            rows.forEach((row, index) => {
                const name = row.querySelector('.ingredient-name')?.value?.trim();
                const qty = row.querySelector('.ingredient-quantity')?.value?.trim();
                const unit = row.querySelector('.ingredient-unit')?.value?.trim();
                console.log(`Row ${index}:`, { name, qty, unit });
                
                if (name && qty && unit) {
                    ingredients.push({ name, quantity: parseFloat(qty), unit });
                }
            });
            
            // Get servings from the form
            const servingsInput = document.querySelector('input[name="servings"]');
            const servings = servingsInput ? parseInt(servingsInput.value) || 1 : 1;
            
            console.log('Found ingredients:', ingredients);
            console.log('Servings:', servings);
            
            if (ingredients.length === 0) {
                document.getElementById('nutrition-results').innerHTML = '<span class="text-red-600">Please enter at least one valid ingredient with name, quantity, and unit.</span>';
                return;
            }
            
            document.getElementById('nutrition-results').innerHTML = '<div class="text-blue-600">🔄 Calculating nutrition...</div>';
            
            try {
                const response = await fetch('/api/calculate-recipe-nutrition', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ ingredients, servings })
                });
                
                const data = await response.json();
                console.log('API response:', data);
                
                if (data.success) {
                    // Auto-populate form fields with calculated nutrition
                    populateNutritionFields(data, servings);
                    
                    // Display results
                    displayNutritionResults(data, servings);
                } else {
                    document.getElementById('nutrition-results').innerHTML = '<span class="text-red-600">❌ ' + (data.message || 'Nutrition calculation failed.') + '</span>';
                }
            } catch (e) {
                console.error('Nutrition calculation error:', e);
                console.error('Error stack:', e.stack);
                document.getElementById('nutrition-results').innerHTML = '<span class="text-red-600">❌ Error calculating nutrition. Check browser console for details.</span>';
            }
        });
        
        // Function to populate nutrition form fields
        function populateNutritionFields(data, servings) {
            // Auto-fill the calories field in the main form (per serving)
            const caloriesInput = document.querySelector('input[name="calories"]');
            if (caloriesInput && data.per_serving) {
                caloriesInput.value = Math.round(data.per_serving.calories || 0);
            }
            
            // Auto-fill nutritional information fields (per serving)
            const nutritionData = data.per_serving || data.total;
            if (nutritionData) {
                const fields = {
                    'protein': nutritionData.protein,
                    'carbs': nutritionData.carbs,
                    'fats': nutritionData.fats,
                    'fiber': nutritionData.fiber,
                    'sugar': nutritionData.sugar,
                    'sodium': nutritionData.sodium
                };
                
                Object.entries(fields).forEach(([field, value]) => {
                    const input = document.querySelector(`input[name="${field}"]`);
                    if (input && value !== undefined && value !== null) {
                        input.value = parseFloat(value).toFixed(1);
                        // Add visual feedback
                        input.style.backgroundColor = '#f0f9ff';
                        input.style.borderColor = '#0ea5e9';
                        setTimeout(() => {
                            input.style.backgroundColor = '';
                            input.style.borderColor = '';
                        }, 2000);
                    }
                });
            }
            
            console.log('✅ Nutrition fields auto-populated');
        }
        
        // Function to display nutrition results
        function displayNutritionResults(data, servings) {
            let html = '<div class="space-y-4">';
            
            // Per serving nutrition (like Test 3)
            if (data.per_serving) {
                html += '<div class="bg-gradient-to-r from-green-50 to-blue-50 border border-green-200 rounded-lg p-4">';
                html += '<h4 class="font-semibold text-green-800 mb-3">✅ Nutrition Per Serving (' + servings + ' servings total)</h4>';
                html += '<div class="grid grid-cols-4 gap-3 text-sm">';
                html += '<div class="text-center"><div class="text-lg font-bold text-blue-600">' + Math.round(data.per_serving.calories || 0) + '</div><div class="text-xs text-gray-600">Calories</div></div>';
                html += '<div class="text-center"><div class="text-lg font-bold text-orange-600">' + (data.per_serving.protein || 0).toFixed(1) + 'g</div><div class="text-xs text-gray-600">Protein</div></div>';
                html += '<div class="text-center"><div class="text-lg font-bold text-yellow-600">' + (data.per_serving.carbs || 0).toFixed(1) + 'g</div><div class="text-xs text-gray-600">Carbs</div></div>';
                html += '<div class="text-center"><div class="text-lg font-bold text-purple-600">' + (data.per_serving.fats || 0).toFixed(1) + 'g</div><div class="text-xs text-gray-600">Fats</div></div>';
                html += '</div></div>';
            }
            
            // Total recipe nutrition
            if (data.total) {
                html += '<div class="bg-gray-50 border border-gray-200 rounded-lg p-3">';
                html += '<h4 class="font-medium text-gray-700 mb-2">📊 Total Recipe Nutrition</h4>';
                html += '<div class="text-sm text-gray-600 space-y-1">';
                html += '<div>Calories: <span class="font-medium">' + Math.round(data.total.calories || 0) + ' kcal</span></div>';
                html += '<div>Protein: <span class="font-medium">' + (data.total.protein || 0).toFixed(1) + 'g</span> | Carbs: <span class="font-medium">' + (data.total.carbs || 0).toFixed(1) + 'g</span> | Fats: <span class="font-medium">' + (data.total.fats || 0).toFixed(1) + 'g</span></div>';
                if (data.total.fiber || data.total.sugar || data.total.sodium) {
                    html += '<div>Fiber: <span class="font-medium">' + (data.total.fiber || 0).toFixed(1) + 'g</span> | Sugar: <span class="font-medium">' + (data.total.sugar || 0).toFixed(1) + 'g</span> | Sodium: <span class="font-medium">' + (data.total.sodium || 0).toFixed(0) + 'mg</span></div>';
                }
                html += '</div></div>';
            }
            
            html += '<div class="text-xs text-gray-500 mt-2">💡 Form fields have been automatically updated with per-serving values</div>';
            html += '</div>';
            
            document.getElementById('nutrition-results').innerHTML = html;
        }
    } else {
        console.log('Calculate nutrition button not found');
    }
    
    // Add event delegation for remove buttons (backup method)
    document.body.addEventListener('click', function(e) {
        const removeBtn = e.target.closest('button[onclick*="removeIngredient"]');
        if (removeBtn) {
            console.log('Event delegation caught remove button click');
            e.preventDefault();
            e.stopPropagation();
            removeIngredient(removeBtn);
            return false;
        }
    });
    
    console.log('Event delegation for remove buttons initialized');
});

// Test function to verify everything is working
window.testRemove = function() {
    console.log('=== Testing Remove Function ===');
    console.log('removeIngredient function exists:', typeof removeIngredient);
    console.log('window.removeIngredient exists:', typeof window.removeIngredient);
    
    const buttons = document.querySelectorAll('button[onclick*="removeIngredient"]');
    console.log('Found remove buttons with onclick:', buttons.length);
    buttons.forEach((btn, i) => {
        console.log(`Button ${i}:`, btn);
        console.log(`  - onclick attribute:`, btn.getAttribute('onclick'));
        console.log(`  - title:`, btn.getAttribute('title'));
    });
    
    const allRemoveButtons = document.querySelectorAll('button[title*="Remove"]');
    console.log('\nAll buttons with "Remove" in title:', allRemoveButtons.length);
    
    const ingredientRows = document.querySelectorAll('.ingredient-row');
    console.log('Total ingredient rows:', ingredientRows.length);
    
    console.log('\nTry clicking a remove button or run: removeIngredient(document.querySelector("button[onclick*=removeIngredient]"))');
    console.log('=== End Test ===');
}

// Quick test to remove first ingredient
window.testClick = function() {
    const firstButton = document.querySelector('button[onclick*="removeIngredient"]');
    if (firstButton) {
        console.log('Found first remove button, triggering click...');
        removeIngredient(firstButton);
    } else {
        console.log('No remove buttons found!');
    }
}

console.log('Helper functions available: testRemove(), testClick()');

// Validate individual ingredient row
function validateIngredientRow(input) {
    const row = input.closest('.ingredient-row');
    if (!row) return;
    
    const nameInput = row.querySelector('.ingredient-name');
    const qtyInput = row.querySelector('.ingredient-quantity');
    const unitInput = row.querySelector('.ingredient-unit');
    
    // Get current values
    const hasName = nameInput && nameInput.value.trim();
    const hasQty = qtyInput && qtyInput.value.trim();
    const hasUnit = unitInput && unitInput.value.trim();
    
    // If any field has value, all fields are required
    const anyFieldFilled = hasName || hasQty || hasUnit;
    
    if (anyFieldFilled) {
        // Validate name
        if (nameInput) {
            if (!hasName) {
                nameInput.classList.add('border-red-500', 'bg-red-50');
            } else {
                nameInput.classList.remove('border-red-500', 'bg-red-50');
            }
        }
        
        // Validate quantity
        if (qtyInput) {
            if (!hasQty) {
                qtyInput.classList.add('border-red-500', 'bg-red-50');
            } else {
                qtyInput.classList.remove('border-red-500', 'bg-red-50');
            }
        }
        
        // Validate unit
        if (unitInput) {
            if (!hasUnit) {
                unitInput.classList.add('border-red-500', 'bg-red-50');
            } else {
                unitInput.classList.remove('border-red-500', 'bg-red-50');
            }
        }
    } else {
        // No fields filled - clear all error states
        if (nameInput) nameInput.classList.remove('border-red-500', 'bg-red-50');
        if (qtyInput) qtyInput.classList.remove('border-red-500', 'bg-red-50');
        if (unitInput) unitInput.classList.remove('border-red-500', 'bg-red-50');
    }
}

// Validate all ingredients before form submission
function validateAllIngredients() {
    const rows = document.querySelectorAll('.ingredient-row');
    let hasErrors = false;
    let incompleteRows = [];
    
    rows.forEach((row, index) => {
        const nameInput = row.querySelector('input[name="ingredient_names[]"]');
        const qtyInput = row.querySelector('input[name="ingredient_quantities[]"]');
        const unitInput = row.querySelector('input[name="ingredient_units[]"]');
        
        const hasAnyValue = nameInput.value.trim() || qtyInput.value.trim() || unitInput.value.trim();
        const hasAllValues = nameInput.value.trim() && qtyInput.value.trim() && unitInput.value.trim();
        
        if (hasAnyValue && !hasAllValues) {
            hasErrors = true;
            incompleteRows.push(index + 1);
            
            // Highlight incomplete fields
            if (!nameInput.value.trim()) {
                nameInput.classList.add('border-red-500', 'bg-red-50');
                nameInput.focus();
            }
            if (!qtyInput.value.trim()) {
                qtyInput.classList.add('border-red-500', 'bg-red-50');
            }
            if (!unitInput.value.trim()) {
                unitInput.classList.add('border-red-500', 'bg-red-50');
            }
        }
    });
    
    if (hasErrors) {
        const message = `Please complete all fields for ingredient row(s): ${incompleteRows.join(', ')}. \nEach ingredient needs: Name, Quantity, and Unit.`;
        showNotification(message, 'error');
        return false;
    }
    
    return true;
}

// Form validation wrapper that prevents submission if validation fails
function validateForm(event) {
    const isValid = validateAllIngredients();
    if (!isValid) {
        event.preventDefault();
        return false;
    }
    return true;
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

/* DISABLED - This demo code was interfering with normal functionality
document.addEventListener('DOMContentLoaded', function() {
    showNotification('🎬 Starting market pricing demo...', 'info');
    
    // Clear existing ingredients first (keep at least one row)
    const ingredientRows = document.querySelectorAll('.ingredient-row');
    ingredientRows.forEach((row, index) => {
        if (index > 0) {
            row.remove();
        }
    });
    
    // Demo ingredients with their expected prices
    const demoIngredients = [
        { name: 'Carrots', quantity: '2', unit: 'kg' },
        { name: 'Pechay', quantity: '1', unit: 'kg' },
        { name: 'Bell Pepper', quantity: '0.5', unit: 'kg' },
        { name: 'Cabbage', quantity: '1', unit: 'kg' }
    ];
    
    // Clear the first row
    const firstRow = document.querySelector('.ingredient-row');
    if (firstRow) {
        firstRow.querySelector('input[name="ingredient_names[]"]').value = '';
        firstRow.querySelector('input[name="ingredient_quantities[]"]').value = '';
        firstRow.querySelector('input[name="ingredient_units[]"]').value = '';
        
        // Reset price display
        const priceContainer = firstRow.querySelector('.market-price');
        const priceText = priceContainer.querySelector('.price-text');
        const priceIndicator = firstRow.querySelector('.price-indicator');
        priceText.textContent = '--';
        priceText.className = 'price-text';
        priceContainer.className = 'market-price px-3 py-2 text-sm bg-gray-50 border border-gray-200 rounded-lg text-center text-gray-500 min-h-[38px] flex items-center justify-center';
        priceIndicator.classList.add('opacity-0');
        priceContainer.dataset.price = '0';
    }
    
    // Add ingredients one by one with a slight delay for visual effect
    for (let i = 0; i < demoIngredients.length; i++) {
        const ingredient = demoIngredients[i];
        
        let row;
        if (i === 0) {
            // Use the first existing row
            row = document.querySelector('.ingredient-row');
        } else {
            // Add new rows
            addIngredient();
            await new Promise(resolve => setTimeout(resolve, 300)); // Small delay
            row = document.querySelector('.ingredient-row:last-child');
        }
        
        if (row) {
            // Populate the ingredient data
            const nameInput = row.querySelector('input[name="ingredient_names[]"]');
            const quantityInput = row.querySelector('input[name="ingredient_quantities[]"]');
            const unitInput = row.querySelector('input[name="ingredient_units[]"]');
            
            nameInput.value = ingredient.name;
            quantityInput.value = ingredient.quantity;
            unitInput.value = ingredient.unit;
            
            // Trigger price fetch with a small delay
            setTimeout(() => {
                fetchMarketPrice(nameInput);
            }, 500 * (i + 1));
        }
}); */

document.addEventListener('DOMContentLoaded', function() {
    // Create units datalist
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
    
    // Create ingredients autocomplete datalist
    const ingredientsList = document.createElement('datalist');
    ingredientsList.id = 'ingredients-list';
    
    // Fetch available ingredients from database
    fetch('/api/ingredients-list')
        .then(response => response.json())
        .then(data => {
            if (data.success && data.ingredients) {
                data.ingredients.forEach(ingredient => {
                    const option = document.createElement('option');
                    option.value = ingredient.name;
                    option.setAttribute('data-price', ingredient.current_price || '0');
                    option.setAttribute('data-unit', ingredient.common_unit || 'kg');
                    ingredientsList.appendChild(option);
                });
            }
        })
        .catch(error => console.error('Error loading ingredients list:', error));
    
    document.body.appendChild(ingredientsList);
    
    // Add CSRF token meta tag if not exists
    if (!document.querySelector('meta[name="csrf-token"]')) {
        const csrfMeta = document.createElement('meta');
        csrfMeta.name = 'csrf-token';
        csrfMeta.content = '{{ csrf_token() }}';
        document.head.appendChild(csrfMeta);
    }

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
    } else if (recipeIngredients && recipeIngredients.length) {
        recipeIngredients.forEach(ing => {
            addIngredient(ing.name || '', ing.quantity || '', ing.unit || '', ing.price || '');
        });
    } else {
        addIngredient();
    }
    
    // Calculate initial total cost
    setTimeout(() => {
        updateTotalCost();
        
        // Auto-fetch market prices for existing ingredients
        const container = document.getElementById('ingredients-container');
        const rows = container.querySelectorAll('.ingredient-item');
    // CSRF token is now included in head section
    
    // Load market prices for existing ingredients
    setTimeout(() => {
        const ingredientInputs = document.querySelectorAll('.ingredient-name');
        ingredientInputs.forEach(input => {
            if (input.value.trim()) {
                fetchMarketPrice(input);
            }
        });
        
        // Add quantity change listeners for cost calculation
        const quantityInputs = document.querySelectorAll('input[name="ingredient_quantities[]"]');
        quantityInputs.forEach(input => {
            input.addEventListener('input', calculateTotalCost);
        });
    }, 500);
    
    // Character counting for description and instructions
    const descTextarea = document.querySelector('textarea[name="description"]');
    const instTextarea = document.querySelector('textarea[name="instructions"]');
    const descCounter = document.getElementById('desc-count');
    const instCounter = document.getElementById('inst-count');
    
    if (descTextarea && descCounter) {
        descTextarea.addEventListener('input', function() {
            const count = this.value.length;
            descCounter.textContent = count + '/200';
            descCounter.className = count > 180 ? 'text-xs text-red-500' : 'text-xs text-gray-400';
        });
    }
    
    if (instTextarea && instCounter) {
        instTextarea.addEventListener('input', function() {
            const count = this.value.length;
            instCounter.textContent = count + '/1000';
            instCounter.className = count > 900 ? 'text-xs text-red-500' : 'text-xs text-gray-400';
        });
    }

    // Form submission
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.textContent = 'Saving...';
            }
        });
    }
    
    // Initialize ingredient count on page load
    updateIngredientCount();
});
</script>

<style>
@keyframes slide-up {
    from {
        transform: translateY(100%);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}
.animate-slide-up {
    animation: slide-up 0.3s ease-out;
}
</style>
