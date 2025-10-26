@extends('layouts.admin')

@section('title', 'Edit Recipe - ' . $recipe->name)

@section('content')
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <h1 class="text-3xl font-bold text-gray-900">Edit Recipe</h1>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-blue-500 to-purple-500 text-white">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 4.5v15m6-15v15m-10.875 0h15.75c.621 0 1.125-.504 1.125-1.125V5.625c0-.621-.504-1.125-1.125-1.125H4.125C3.504 4.5 3 5.004 3 5.625v13.5c0 .621.504 1.125 1.125 1.125z"/>
                        </svg>
                        Two-Column Layout • Optimized for Editing
                    </span>
                </div>
                <p class="mt-2 text-gray-600">Update recipe information, ingredients, and instructions</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.recipes.show', $recipe) }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    View Recipe
                </a>
                <a href="{{ route('admin.recipes.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3"/>
                    </svg>
                    Back to Recipes
                </a>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.recipes.update', $recipe) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Two-Column Layout Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
                
                <!-- LEFT COLUMN (2/3 width) -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- Basic Information -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-blue-100">
                    <h2 class="text-lg font-semibold text-gray-900">Basic Information</h2>
                    <p class="text-sm text-gray-600 mt-1">Recipe name and description</p>
                </div>
                <div class="p-6">
                    <div class="space-y-6">
                        <!-- Recipe Name -->
                        <div>
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
                        <div>
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
                    </div>
                </div>
                    </div>

                    <!-- Ingredients & Nutrition -->
            @if($recipe->recipe)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-50 to-green-100">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">Ingredients & Nutrition</h2>
                            <p class="text-sm text-gray-600 mt-1">Manage ingredients and calculate nutrition</p>
                        </div>
                    </div>
                </div>
                <div class="p-6 space-y-6">
                    <!-- Ingredients Table -->
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <label class="block text-sm font-medium text-gray-700">Recipe Ingredients</label>
                            <span class="text-xs text-gray-500">Add all ingredients with quantities</span>
                        </div>
                        
                        <!-- Table Container -->
                        <div class="border border-gray-200 rounded-lg overflow-hidden">
                            <!-- Table Header -->
                            <div class="bg-gray-50 border-b border-gray-200">
                                <div class="grid grid-cols-12 gap-3 px-4 py-3">
                                    <div class="col-span-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                        </svg>
                                        Ingredient Name
                                    </div>
                                    <div class="col-span-2 text-xs font-semibold text-gray-600 uppercase tracking-wider text-center">Quantity</div>
                                    <div class="col-span-2 text-xs font-semibold text-gray-600 uppercase tracking-wider text-center">Unit</div>
                                    <div class="col-span-3 text-xs font-semibold text-gray-600 uppercase tracking-wider text-center">Price (₱)</div>
                                    <div class="col-span-1 text-xs font-semibold text-gray-600 uppercase tracking-wider text-center">Actions</div>
                                </div>
                            </div>
                            
                            <!-- Ingredients Container -->
                            <div id="ingredients-container" class="divide-y divide-gray-100">
                                <!-- Ingredients will be added here dynamically -->
                            </div>
                        </div>

                        <!-- Add Ingredient Button -->
                        <button type="button" 
                                onclick="addIngredientRow()"
                                class="mt-3 inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                            </svg>
                            Add New Ingredient
                        </button>

                        @error('ingredients')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        
                        <!-- Total Price Display -->
                        <div class="mt-4 p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg border border-green-200">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m-4-5V9a4 4 0 118 0v2.78"></path>
                                    </svg>
                                    <span class="text-sm font-medium text-gray-700">Total Estimated Cost:</span>
                                </div>
                                <div class="text-xl font-bold text-green-700" id="total-price-display">₱0.00</div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center gap-3 pt-4 border-t border-gray-200">
                        <button type="button" 
                                onclick="saveAndCalculateNutrition()"
                                class="inline-flex items-center px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                            </svg>
                            Save & Calculate Nutrition
                        </button>
                        <button type="button" 
                                onclick="calculateNutrition()"
                                class="inline-flex items-center px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                            Calculate Only
                        </button>
                        <span class="text-xs text-gray-500">Save first for best results</span>
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

                    <!-- Nutritional Information (Read-only Display) -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-teal-50 to-teal-100">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                            <div>
                                <h2 class="text-lg font-semibold text-gray-900">Nutritional Information</h2>
                                <p class="text-sm text-gray-600 mt-1">Auto-calculated from ingredients (per serving)</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-2xl font-bold text-teal-600" id="calculated-calories">{{ $recipe->nutritionalInfo->calories ?? 0 }}</div>
                            <div class="text-xs text-gray-500 uppercase tracking-wide">Total Calories</div>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <!-- Hidden inputs for form submission -->
                    <input type="hidden" name="protein" id="protein-hidden" value="{{ old('protein', $recipe->nutritionalInfo->protein ?? '') }}">
                    <input type="hidden" name="carbs" id="carbs-hidden" value="{{ old('carbs', $recipe->nutritionalInfo->carbs ?? '') }}">
                    <input type="hidden" name="fats" id="fats-hidden" value="{{ old('fats', $recipe->nutritionalInfo->fats ?? '') }}">
                    <input type="hidden" name="fiber" id="fiber-hidden" value="{{ old('fiber', $recipe->nutritionalInfo->fiber ?? '') }}">
                    <input type="hidden" name="sugar" id="sugar-hidden" value="{{ old('sugar', $recipe->nutritionalInfo->sugar ?? '') }}">
                    <input type="hidden" name="sodium" id="sodium-hidden" value="{{ old('sodium', $recipe->nutritionalInfo->sodium ?? '') }}">
                    
                    <!-- Read-only nutrition display -->
                    <div class="grid grid-cols-2 lg:grid-cols-3 gap-4">
                        <div class="text-center p-4 bg-blue-50 rounded-lg border-2 border-blue-100">
                            <div class="text-2xl font-bold text-blue-600" id="protein-display">{{ number_format($recipe->nutritionalInfo->protein ?? 0, 1) }}</div>
                            <div class="text-xs text-gray-600 mt-1 uppercase tracking-wide">Protein (g)</div>
                        </div>
                        <div class="text-center p-4 bg-green-50 rounded-lg border-2 border-green-100">
                            <div class="text-2xl font-bold text-green-600" id="carbs-display">{{ number_format($recipe->nutritionalInfo->carbs ?? 0, 1) }}</div>
                            <div class="text-xs text-gray-600 mt-1 uppercase tracking-wide">Carbs (g)</div>
                        </div>
                        <div class="text-center p-4 bg-orange-50 rounded-lg border-2 border-orange-100">
                            <div class="text-2xl font-bold text-orange-600" id="fats-display">{{ number_format($recipe->nutritionalInfo->fats ?? 0, 1) }}</div>
                            <div class="text-xs text-gray-600 mt-1 uppercase tracking-wide">Fats (g)</div>
                        </div>
                        <div class="text-center p-4 bg-purple-50 rounded-lg border-2 border-purple-100">
                            <div class="text-2xl font-bold text-purple-600" id="fiber-display">{{ number_format($recipe->nutritionalInfo->fiber ?? 0, 1) }}</div>
                            <div class="text-xs text-gray-600 mt-1 uppercase tracking-wide">Fiber (g)</div>
                        </div>
                        <div class="text-center p-4 bg-pink-50 rounded-lg border-2 border-pink-100">
                            <div class="text-2xl font-bold text-pink-600" id="sugar-display">{{ number_format($recipe->nutritionalInfo->sugar ?? 0, 1) }}</div>
                            <div class="text-xs text-gray-600 mt-1 uppercase tracking-wide">Sugar (g)</div>
                        </div>
                        <div class="text-center p-4 bg-red-50 rounded-lg border-2 border-red-100">
                            <div class="text-2xl font-bold text-red-600" id="sodium-display">{{ number_format($recipe->nutritionalInfo->sodium ?? 0, 1) }}</div>
                            <div class="text-xs text-gray-600 mt-1 uppercase tracking-wide">Saturated Fat (g)</div>
                        </div>
                    </div>
                    
                    <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                        <p class="text-sm text-gray-600 text-center">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Nutritional values are automatically calculated from ingredients using our nutrition API
                        </p>
                    </div>
                </div>
            </div>

                </div>
                <!-- END LEFT COLUMN -->

                <!-- RIGHT COLUMN (1/3 width) -->
                <div class="lg:col-span-1 space-y-6">

                    <!-- Recipe Image -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-purple-100">
                            <h2 class="text-lg font-semibold text-gray-900">Recipe Image</h2>
                            <p class="text-sm text-gray-600 mt-1">Upload recipe photo</p>
                        </div>
                        <div class="p-6">
                            @if($recipe->image_path)
                                <div class="mb-4">
                                    <img src="{{ $recipe->image_url }}" 
                                         alt="{{ $recipe->name }}" 
                                         class="w-full h-48 object-cover rounded-lg border border-gray-200">
                                    <p class="text-sm text-gray-600 mt-2 text-center">Current image</p>
                                </div>
                            @endif
                            <input type="file" 
                                   name="image" 
                                   accept="image/*"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('image') border-red-500 @enderror">
                            <p class="text-xs text-gray-600 mt-2">JPG, PNG, GIF - Max 2MB</p>
                            @error('image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Recipe Status -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-indigo-100">
                            <h2 class="text-lg font-semibold text-gray-900">Recipe Status</h2>
                            <p class="text-sm text-gray-600 mt-1">Feature this recipe</p>
                        </div>
                        <div class="p-6">
                            <div class="flex items-start">
                                <input type="hidden" name="is_featured" value="0">
                                <input type="checkbox" 
                                       name="is_featured" 
                                       value="1" 
                                       {{ old('is_featured', $recipe->is_featured) ? 'checked' : '' }}
                                       class="h-5 w-5 text-blue-600 border-gray-300 rounded focus:ring-2 focus:ring-blue-500 mt-0.5">
                                <div class="ml-3">
                                    <label class="text-sm font-medium text-gray-700">Featured Recipe</label>
                                    <p class="text-xs text-gray-600 mt-1">Highlighted on homepage and listings</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Details -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-pink-50 to-pink-100">
                            <h2 class="text-lg font-semibold text-gray-900">Quick Details</h2>
                            <p class="text-sm text-gray-600 mt-1">Category and metrics</p>
                        </div>
                        <div class="p-6 space-y-4">
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
                        </div>
                    </div>

                </div>
                <!-- END RIGHT COLUMN -->

            </div>
            <!-- END Two-Column Grid -->

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

<script>
// Ingredient management
let ingredientCounter = 0;

// Units dropdown options
const units = ['g', 'kg', 'ml', 'L', 'cup', 'tbsp', 'tsp', 'piece', 'slice', 'oz', 'lb'];

function addIngredientRow(name = '', quantity = '', unit = '', price = '') {
    const container = document.getElementById('ingredients-container');
    const row = document.createElement('div');
    row.className = 'grid grid-cols-12 gap-3 px-4 py-3 hover:bg-gray-50 transition-colors';
    row.dataset.ingredientId = ingredientCounter++;
    
    row.innerHTML = `
        <!-- Ingredient Name -->
        <div class="col-span-4">
            <div class="relative">
                <input type="text" 
                       name="ingredient_names[]" 
                       value="${escapeHtml(name)}"
                       required
                       placeholder="e.g., Chicken breast"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm">
            </div>
        </div>
        
        <!-- Quantity -->
        <div class="col-span-2">
            <input type="number" 
                   name="ingredient_quantities[]" 
                   value="${escapeHtml(quantity)}"
                   step="0.01"
                   min="0"
                   placeholder="1.5"
                   onchange="calculateTotalPrice()"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm text-center">
        </div>
        
        <!-- Unit -->
        <div class="col-span-2">
            <select name="ingredient_units[]" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm">
                <option value="">Select</option>
                ${units.map(u => `<option value="${u}" ${u === unit ? 'selected' : ''}>${u}</option>`).join('')}
            </select>
        </div>
        
        <!-- Price -->
        <div class="col-span-3">
            <input type="number" 
                   name="ingredient_prices[]" 
                   value="${escapeHtml(price)}"
                   step="0.01"
                   min="0"
                   placeholder="0.00"
                   onchange="calculateTotalPrice()"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm text-center">
        </div>
        
        <!-- Remove Button -->
        <div class="col-span-1 flex items-center justify-center">
            <button type="button" 
                    onclick="removeIngredientRow(this)"
                    class="p-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors"
                    title="Remove ingredient">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    `;
    
    container.appendChild(row);
    
    // Focus on the name input of the new row
    row.querySelector('input[name="ingredient_names[]"]').focus();
}

function removeIngredientRow(button) {
    const row = button.closest('[data-ingredient-id]');
    const container = document.getElementById('ingredients-container');
    
    // Always allow removal, even if it's the last row
    if (row) {
        row.remove();
    }
}

function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return String(text || '').replace(/[&<>"']/g, m => map[m]);
}

function saveAndCalculateNutrition() {
    // Validate form has required fields
    const recipeName = document.querySelector('input[name="name"]');
    
    if (!recipeName || !recipeName.value.trim()) {
        showNotification('⚠️ Please enter a recipe name before saving', 'warning');
        recipeName?.focus();
        return;
    }
    
    // Check if there are ingredients
    const names = document.querySelectorAll('input[name="ingredient_names[]"]');
    let hasIngredients = false;
    names.forEach(input => {
        if (input.value.trim()) {
            hasIngredients = true;
        }
    });
    
    if (!hasIngredients) {
        showNotification('⚠️ Please add at least one ingredient', 'warning');
        return;
    }
    
    // Show loading notification
    showNotification('💾 Saving recipe and calculating nutrition...', 'info');
    
    // Get the recipe form (not logout or other forms)
    const form = document.querySelector('form[action*="recipes"]');
    if (!form) {
        showNotification('❌ Could not find recipe form', 'error');
        return;
    }
    
    // ⚠️ CRITICAL FIX: Build FormData manually to ensure proper ingredient formatting
    const formData = new FormData();
    
    // Get the correct update URL from the form action
    const updateUrl = form.action;
    console.log('Update URL:', updateUrl);
    
    // Add CSRF token and method
    const csrfTokenInput = form.querySelector('input[name="_token"]');
    if (csrfTokenInput) {
        formData.append('_token', csrfTokenInput.value);
    }
    formData.append('_method', 'PUT');
    
    // Add basic recipe fields
    const basicFields = ['name', 'description', 'prep_time', 'cook_time', 'servings', 'instructions', 
                        'protein', 'carbs', 'fats', 'fiber', 'sugar', 'sodium', 'cuisine_type', 
                        'difficulty'];
    
    basicFields.forEach(fieldName => {
        const field = form.querySelector(`[name="${fieldName}"]`);
        if (field && field.value.trim()) {
            formData.append(fieldName, field.value.trim());
        }
    });
    
    // Handle calculated/displayed values - MUST be added after basic fields
    // Get calories from the display element (always present and updated by nutrition calculation)
    const caloriesDisplay = document.getElementById('calculated-calories');
    const caloriesValue = caloriesDisplay ? Math.round(parseFloat(caloriesDisplay.textContent.trim()) || 0) : 0;
    formData.append('calories', caloriesValue);
    console.log('Adding calories:', caloriesValue);
    
    // Get cost from the total price display element (always present and updated by price calculation)
    const totalPriceDisplay = document.getElementById('total-price-display');
    if (totalPriceDisplay) {
        const costText = totalPriceDisplay.textContent.replace(/[^\d.]/g, ''); // Remove ₱ and other non-numeric chars
        const costValue = parseFloat(costText) || 0;
        formData.append('cost', costValue.toFixed(2));
        console.log('Adding cost:', costValue.toFixed(2));
    } else {
        formData.append('cost', '0.00');
        console.log('Adding default cost: 0.00');
    }
    
    // Handle featured checkbox
    const isFeaturedCheckbox = form.querySelector('input[name="is_featured"]');
    formData.append('is_featured', isFeaturedCheckbox && isFeaturedCheckbox.checked ? '1' : '0');
    
    // Handle file uploads
    const imageField = form.querySelector('input[name="image"]');
    if (imageField && imageField.files.length > 0) {
        formData.append('image', imageField.files[0]);
    }
    
    const thumbnailField = form.querySelector('input[name="thumbnail_image"]');
    if (thumbnailField && thumbnailField.files.length > 0) {
        formData.append('thumbnail_image', thumbnailField.files[0]);
    }
    
    // ⚠️ CRITICAL: Collect and validate ingredients properly
    const ingredientNames = document.querySelectorAll('input[name="ingredient_names[]"]');
    const ingredientQuantities = document.querySelectorAll('input[name="ingredient_quantities[]"]');
    const ingredientUnits = document.querySelectorAll('select[name="ingredient_units[]"]');
    const ingredientPrices = document.querySelectorAll('input[name="ingredient_prices[]"]');
    
    // Filter out empty ingredients and validate
    const validIngredients = [];
    for (let i = 0; i < ingredientNames.length; i++) {
        const name = ingredientNames[i]?.value?.trim();
        const quantity = ingredientQuantities[i]?.value?.trim();
        const unit = ingredientUnits[i]?.value?.trim();
        const price = ingredientPrices[i]?.value?.trim() || '0';
        
        // Only include ingredients that have name, quantity AND unit
        if (name && quantity && unit) {
            validIngredients.push({ name, quantity, unit, price });
        }
    }
    
    console.log('Valid ingredients found:', validIngredients.length);
    
    // Add ingredients to FormData in correct format
    validIngredients.forEach((ingredient, index) => {
        formData.append(`ingredient_names[${index}]`, ingredient.name);
        formData.append(`ingredient_quantities[${index}]`, ingredient.quantity);
        formData.append(`ingredient_units[${index}]`, ingredient.unit);
        formData.append(`ingredient_prices[${index}]`, ingredient.price);
    });
    
    // Debug: Log FormData contents
    console.log('FormData contents:');
    for (let [key, value] of formData.entries()) {
        if (key !== 'image' && key !== 'thumbnail_image') {
            console.log(key, ':', value);
        }
    }
    
    // Validate we have at least one ingredient
    if (validIngredients.length === 0) {
        showNotification('❌ Please add at least one complete ingredient (name, quantity, and unit)', 'error');
        return;
    }
    
    // Get CSRF token for headers
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || 
                      formData.get('_token');
    
    console.log('CSRF Token:', csrfToken ? 'Present' : 'MISSING!');
    
    // Save via AJAX so we can calculate after
    fetch(updateUrl, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
        }
    })
    .then(response => {
        // Check if response is ok
        if (!response.ok) {
            return response.text().then(text => {
                throw new Error(`Server returned ${response.status}: ${text.substring(0, 100)}`);
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            showNotification('✅ Recipe saved successfully! Calculating nutrition...', 'success');
            
            // Wait a moment, then calculate nutrition
            setTimeout(() => {
                calculateNutrition();
            }, 500);
        } else {
            showNotification('❌ Failed to save recipe: ' + (data.message || 'Unknown error'), 'error');
        }
    })
    .catch(error => {
        console.error('Save error:', error);
        
        // Show specific error message
        let errorMessage = error.message || 'Unknown error';
        if (errorMessage.includes('419') || errorMessage.includes('CSRF')) {
            errorMessage = 'CSRF token mismatch. Please refresh the page and try again.';
        }
        
        showNotification('❌ Save failed: ' + errorMessage, 'error');
        
        // Even if save fails, ask if user wants to calculate anyway
        if (confirm('Could not save recipe. Calculate nutrition with current values anyway?')) {
            calculateNutrition();
        }
    });
}

function calculateNutrition(event = null) {
    // Get all ingredient names and quantities
    const names = document.querySelectorAll('input[name="ingredient_names[]"]');
    const quantities = document.querySelectorAll('input[name="ingredient_quantities[]"]');
    const units = document.querySelectorAll('select[name="ingredient_units[]"]');
    
    if (names.length === 0) {
        alert('Please add at least one ingredient before calculating nutrition.');
        return;
    }
    
    // Check if at least one ingredient has a name
    let hasIngredients = false;
    names.forEach(input => {
        if (input.value.trim()) {
            hasIngredients = true;
        }
    });
    
    if (!hasIngredients) {
        alert('Please enter ingredient names before calculating nutrition.');
        return;
    }
    
    // Show loading state (only if called from button click with event)
    const button = event?.target;
    const originalText = button.innerHTML;
    button.disabled = true;
    button.classList.add('opacity-75', 'cursor-not-allowed');
    button.innerHTML = `
        <svg class="w-5 h-5 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
        </svg>
        Calculating...
    `;
    
    // Prepare ingredient data
    const ingredients = [];
    names.forEach((nameInput, index) => {
        const name = nameInput.value.trim();
        if (name) {
            ingredients.push({
                name: name,
                quantity: quantities[index].value || '100',
                unit: units[index].value || 'g'
            });
        }
    });
    
    // Call nutrition API
    fetch('/api/nutrition/calculate', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            ingredients: ingredients,
            servings: 1  // Default to 1 serving for personal meal system
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update nutritional display with API results
            if (data.nutrition) {
                updateNutritionalDisplay(data.nutrition);
                
                // Scroll to nutrition section
                const nutritionSection = document.querySelector('#calculated-calories');
                if (nutritionSection) {
                    nutritionSection.scrollIntoView({ 
                        behavior: 'smooth', 
                        block: 'center' 
                    });
                }
                
                // Show success message
                showNotification('✅ Nutrition calculated successfully! Calories auto-calculated.', 'success');
            }
        } else {
            showNotification('⚠️ Could not calculate nutrition. Please try again.', 'warning');
        }
    })
    .catch(error => {
        console.error('Nutrition calculation error:', error);
        showNotification('❌ Error calculating nutrition. Using estimated values.', 'error');
        
        // Fallback: Use basic estimates
        estimateNutrition(ingredients);
    })
    .finally(() => {
        // Restore button (only if it exists)
        if (button) {
            button.disabled = false;
            button.classList.remove('opacity-75', 'cursor-not-allowed');
            button.innerHTML = originalText;
        }
    });
}

function estimateNutrition(ingredients) {
    // Simple estimation logic (fallback when API is unavailable)
    // This provides reasonable estimates based on common ingredients
    const estimates = {
        protein: ingredients.length * 5,
        carbs: ingredients.length * 15,
        fats: ingredients.length * 3,
        fiber: ingredients.length * 2,
        sugar: ingredients.length * 1,
        sodium: ingredients.length * 50
    };
    
    // Update nutritional display with estimates
    updateNutritionalDisplay(estimates);
    
    // Scroll to nutrition section
    const nutritionSection = document.querySelector('#calculated-calories');
    if (nutritionSection) {
        nutritionSection.scrollIntoView({ 
            behavior: 'smooth', 
            block: 'center' 
        });
    }
    
    showNotification('⚠️ Using estimated nutrition values. API calculation recommended.', 'warning');
}

function calculateCaloriesFromMacros() {
    // Get values from hidden inputs (since displays are read-only)
    const protein = parseFloat(document.getElementById('protein-hidden')?.value) || 0;
    const carbs = parseFloat(document.getElementById('carbs-hidden')?.value) || 0;
    const fats = parseFloat(document.getElementById('fats-hidden')?.value) || 0;
    
    // Calculate calories: Protein(4 cal/g) + Carbs(4 cal/g) + Fats(9 cal/g)
    const calculatedCalories = Math.round((protein * 4) + (carbs * 4) + (fats * 9));
    
    if (calculatedCalories > 0) {
        // Update calorie display and hidden input
        const caloriesDisplay = document.getElementById('calculated-calories');
        const caloriesInput = document.querySelector('input[name="calories"]');
        
        if (caloriesDisplay) {
            caloriesDisplay.textContent = calculatedCalories;
            caloriesDisplay.classList.add('animate-pulse');
            setTimeout(() => {
                caloriesDisplay.classList.remove('animate-pulse');
            }, 1000);
        }
        
        if (caloriesInput) {
            caloriesInput.value = calculatedCalories;
        }
        
        // Show notification
        showNotification(`✅ Calories auto-calculated: ${calculatedCalories} kcal`, 'success');
    }
}

function calculateTotalPrice() {
    const quantities = document.querySelectorAll('input[name="ingredient_quantities[]"]');
    const prices = document.querySelectorAll('input[name="ingredient_prices[]"]');
    let total = 0;
    
    for (let i = 0; i < quantities.length; i++) {
        const quantity = parseFloat(quantities[i].value) || 0;
        const price = parseFloat(prices[i].value) || 0;
        total += quantity * price;
    }
    
    const totalDisplay = document.getElementById('total-price-display');
    if (totalDisplay) {
        totalDisplay.textContent = `₱${total.toFixed(2)}`;
        
        // Add visual feedback
        totalDisplay.classList.add('animate-pulse', 'text-green-800');
        setTimeout(() => {
            totalDisplay.classList.remove('animate-pulse', 'text-green-800');
        }, 1000);
    }
    
    return total;
}

function updateNutritionalDisplay(nutritionData) {
    // Update display values
    const fields = ['protein', 'carbs', 'fats', 'fiber', 'sugar', 'sodium'];
    fields.forEach(field => {
        const display = document.getElementById(`${field}-display`);
        const hiddenInput = document.getElementById(`${field}-hidden`);
        
        if (display && nutritionData[field] !== undefined) {
            const value = parseFloat(nutritionData[field]).toFixed(1);
            display.textContent = value;
            
            // Update hidden input for form submission
            if (hiddenInput) {
                hiddenInput.value = value;
            }
            
            // Add animation
            display.classList.add('animate-pulse');
            setTimeout(() => {
                display.classList.remove('animate-pulse');
            }, 1000);
        }
    });
    
    // Calculate and update calories
    setTimeout(() => {
        calculateCaloriesFromMacros();
    }, 100);
}

function showNotification(message, type = 'info') {
    const colors = {
        success: 'bg-green-100 border-green-400 text-green-700',
        warning: 'bg-yellow-100 border-yellow-400 text-yellow-700',
        error: 'bg-red-100 border-red-400 text-red-700',
        info: 'bg-blue-100 border-blue-400 text-blue-700'
    };
    
    const notification = document.createElement('div');
    notification.className = `fixed bottom-4 right-4 ${colors[type]} px-6 py-4 rounded-lg shadow-lg max-w-md z-50 animate-fade-in`;
    notification.innerHTML = `
        <div class="flex items-center">
            <span class="text-sm font-medium">${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-lg font-bold">&times;</button>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 5000);
}

// Load existing ingredients on page load
document.addEventListener('DOMContentLoaded', function() {
    const existingIngredients = @json($recipe->recipe ? $recipe->recipe->ingredients : []);
    const container = document.getElementById('ingredients-container');
    
    if (existingIngredients && existingIngredients.length > 0) {
        container.innerHTML = ''; // Clear placeholder
        existingIngredients.forEach(ingredient => {
            if (typeof ingredient === 'object' && ingredient.name) {
                addIngredientRow(
                    ingredient.name || '',
                    ingredient.amount || '',
                    ingredient.unit || '',
                    ingredient.price || ''
                );
            } else if (typeof ingredient === 'string') {
                // Parse "Name - Quantity Unit" format
                const parts = ingredient.split(' - ');
                const name = parts[0] || '';
                const amountUnit = parts[1] || '';
                const amountParts = amountUnit.trim().split(' ');
                const quantity = amountParts[0] || '';
                const unit = amountParts[1] || '';
                
                addIngredientRow(name, quantity, unit, '');
            }
        });
    } else {
        // Add one empty row if no ingredients
        container.innerHTML = '';
        addIngredientRow();
    }
    
    // Calculate initial total price
    setTimeout(() => {
        calculateTotalPrice();
    }, 500);
    
    // Add event listeners for automatic calorie calculation
    const nutritionInputs = ['protein', 'carbs', 'fats'];
    nutritionInputs.forEach(field => {
        const input = document.querySelector(`input[name="${field}"]`);
        if (input) {
            input.addEventListener('input', function() {
                // Small delay to allow user to finish typing
                clearTimeout(this.calcTimeout);
                this.calcTimeout = setTimeout(() => {
                    calculateCaloriesFromMacros();
                }, 500);
            });
            
            input.addEventListener('blur', function() {
                calculateCaloriesFromMacros();
            });
        }
    });
});
</script>
@endsection
