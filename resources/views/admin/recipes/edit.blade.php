@extends('layouts.admin')

@section('title', 'Edit Recipe - ' . $recipe->name)

@section('content')
    <div class="max-w-5xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Edit Recipe</h1>
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

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Ingredients -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Ingredients</label>
                            <textarea name="ingredients" 
                                      rows="8"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('ingredients') border-red-500 @enderror"
                                      placeholder="Enter ingredients (one per line)...">{{ old('ingredients', $recipe->recipe ? collect($recipe->recipe->ingredients)->map(function($ingredient) {
                                          if (is_array($ingredient) && isset($ingredient['name'])) {
                                              $text = $ingredient['name'];
                                              if (isset($ingredient['amount'])) {
                                                  $text .= ' - ' . $ingredient['amount'];
                                                  if (isset($ingredient['unit'])) {
                                                      $text .= ' ' . $ingredient['unit'];
                                                  }
                                              }
                                              return $text;
                                          }
                                          return is_string($ingredient) ? $ingredient : '';
                                      })->join("\n") : '') }}</textarea>
                            <p class="text-sm text-gray-600 mt-1">Enter one ingredient per line</p>
                            @error('ingredients')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Local Alternatives -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Local Alternatives</label>
                            <textarea name="local_alternatives" 
                                      rows="8"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('local_alternatives') border-red-500 @enderror"
                                      placeholder="Enter local ingredient alternatives (one per line)...">{{ old('local_alternatives', $recipe->recipe ? collect($recipe->recipe->local_alternatives)->map(function($alternative) {
                                          if (is_array($alternative) && isset($alternative['original'])) {
                                              $text = $alternative['original'];
                                              if (isset($alternative['alternative'])) {
                                                  $text .= ' → ' . $alternative['alternative'];
                                              }
                                              if (isset($alternative['notes'])) {
                                                  $text .= ' (' . $alternative['notes'] . ')';
                                              }
                                              return $text;
                                          }
                                          return is_string($alternative) ? $alternative : '';
                                      })->join("\n") : '') }}</textarea>
                            <p class="text-sm text-gray-600 mt-1">Suggest local Filipino alternatives for imported ingredients</p>
                            @error('local_alternatives')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
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
