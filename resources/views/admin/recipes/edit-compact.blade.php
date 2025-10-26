@extends('layouts.admin')

@section('title', 'Edit Recipe - ' . $recipe->name)

@section('content')
    <!-- Sticky Header -->
    <div class="sticky top-0 z-30 bg-white border-b border-gray-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-14">
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.recipes.index') }}" 
                       class="inline-flex items-center text-gray-600 hover:text-gray-900 transition-colors"
                       title="Back to Recipes">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-base font-bold text-gray-900">Edit Recipe</h1>
                        <p class="text-xs text-gray-500 truncate max-w-xs">{{ $recipe->name }}</p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.recipes.show', $recipe) }}" 
                       class="inline-flex items-center px-3 py-1.5 text-xs bg-gray-100 text-gray-700 font-medium rounded-md hover:bg-gray-200 transition-colors">
                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                        </svg>
                        Preview
                    </a>
                    <button type="submit" 
                            form="recipe-form"
                            class="inline-flex items-center px-4 py-1.5 text-xs bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-md hover:from-blue-700 hover:to-purple-700 transition-all shadow-sm">
                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        Save
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <form id="recipe-form" method="POST" action="{{ route('admin.recipes.update', $recipe) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                <!-- Left Column (Main Form - 2/3 width) -->
                <div class="lg:col-span-2 space-y-4">
                    <!-- Basic Information -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-4 py-2.5 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                            <h3 class="text-sm font-semibold text-gray-900 flex items-center">
                                <svg class="w-4 h-4 mr-1.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/>
                                </svg>
                                Basic Info
                            </h3>
                        </div>
                        <div class="p-4 space-y-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Recipe Name *</label>
                                <input type="text" name="name" value="{{ old('name', $recipe->name) }}" required
                                       class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="Enter recipe name">
                                @error('name')<p class="mt-0.5 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Description *</label>
                                <textarea name="description" rows="2" required
                                          class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                                          placeholder="Describe the recipe...">{{ old('description', $recipe->description) }}</textarea>
                                @error('description')<p class="mt-0.5 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div class="grid grid-cols-4 gap-2">
                                <div class="col-span-2">
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Cuisine *</label>
                                    <select name="cuisine_type" required class="w-full px-2 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="">Select</option>
                                        @foreach($cuisineTypes as $cuisine)
                                            <option value="{{ $cuisine }}" {{ old('cuisine_type', $recipe->cuisine_type) === $cuisine ? 'selected' : '' }}>{{ ucfirst($cuisine) }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-span-2">
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Difficulty *</label>
                                    <select name="difficulty" required class="w-full px-2 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="">Select</option>
                                        @foreach($difficulties as $difficulty)
                                            <option value="{{ $difficulty }}" {{ old('difficulty', $recipe->difficulty) === $difficulty ? 'selected' : '' }}>{{ ucfirst($difficulty) }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Calories *</label>
                                    <input type="number" name="calories" value="{{ old('calories', $recipe->calories) }}" min="1" required
                                           class="w-full px-2 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="300">
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Cost (₱) *</label>
                                    <input type="number" name="cost" value="{{ old('cost', $recipe->cost) }}" min="0" step="0.01" required
                                           class="w-full px-2 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="0.00">
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Prep (min)</label>
                                    <input type="number" name="prep_time" value="{{ old('prep_time', $recipe->recipe->prep_time ?? '') }}" min="0"
                                           class="w-full px-2 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="15">
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Cook (min)</label>
                                    <input type="number" name="cook_time" value="{{ old('cook_time', $recipe->recipe->cook_time ?? '') }}" min="0"
                                           class="w-full px-2 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="30">
                                </div>
                            </div>

                            <div class="pt-1">
                                <label class="flex items-center cursor-pointer group">
                                    <input type="hidden" name="is_featured" value="0">
                                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $recipe->is_featured) ? 'checked' : '' }}
                                           class="h-3.5 w-3.5 text-blue-600 border-gray-300 rounded focus:ring-2 focus:ring-blue-500">
                                    <span class="ml-2 text-xs font-medium text-gray-700 group-hover:text-blue-600">⭐ Feature this recipe</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Ingredients Card -->
                    @if($recipe->recipe)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-4 py-2.5 border-b border-gray-200 bg-gradient-to-r from-green-50 to-emerald-50 flex items-center justify-between">
                            <h3 class="text-sm font-semibold text-gray-900 flex items-center">
                                <svg class="w-4 h-4 mr-1.5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z"/>
                                </svg>
                                Ingredients
                            </h3>
                            <button type="button" onclick="addIngredient()"
                                    class="inline-flex items-center px-2 py-1 text-xs bg-green-600 hover:bg-green-700 text-white font-medium rounded transition-colors">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                                </svg>
                                Add
                            </button>
                        </div>
                        <div class="p-3 bg-gray-50/50">
                            <div class="grid grid-cols-12 gap-1.5 mb-2 text-xs font-medium text-gray-600 px-1">
                                <div class="col-span-4">Name</div>
                                <div class="col-span-2">Qty</div>
                                <div class="col-span-2">Unit</div>
                                <div class="col-span-3">Price (₱)</div>
                                <div class="col-span-1"></div>
                            </div>
                            <div id="ingredients-container" class="space-y-1.5 max-h-64 overflow-y-auto pr-1">
                                <!-- Ingredients will be added here dynamically -->
                            </div>
                        </div>
                    </div>

                    <!-- Instructions Card -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-4 py-2.5 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-pink-50">
                            <h3 class="text-sm font-semibold text-gray-900 flex items-center">
                                <svg class="w-4 h-4 mr-1.5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/>
                                </svg>
                                Instructions
                            </h3>
                        </div>
                        <div class="p-4">
                            <textarea name="instructions" rows="5"
                                      class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                                      placeholder="Step-by-step cooking instructions...">{{ old('instructions', $recipe->recipe->instructions ?? '') }}</textarea>
                            @error('instructions')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <!-- Nutritional Information (Collapsible) -->
                    @if($recipe->nutritionalInfo)
                    <details class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden" open>
                        <summary class="px-4 py-2.5 border-b border-gray-200 bg-gradient-to-r from-orange-50 to-amber-50 cursor-pointer hover:bg-orange-100/50 transition-colors">
                            <h3 class="text-sm font-semibold text-gray-900 inline-flex items-center">
                                <svg class="w-4 h-4 mr-1.5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5M9 11.25v1.5M12 9v3.75m3-6v6"/>
                                </svg>
                                Nutrition Facts
                            </h3>
                        </summary>
                        <div class="p-4">
                            <div class="grid grid-cols-3 gap-2">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Protein (g)</label>
                                    <input type="number" name="protein" value="{{ old('protein', $recipe->nutritionalInfo->protein ?? 0) }}" min="0" step="0.1"
                                           class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Carbs (g)</label>
                                    <input type="number" name="carbs" value="{{ old('carbs', $recipe->nutritionalInfo->carbs ?? 0) }}" min="0" step="0.1"
                                           class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Fats (g)</label>
                                    <input type="number" name="fats" value="{{ old('fats', $recipe->nutritionalInfo->fats ?? 0) }}" min="0" step="0.1"
                                           class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Fiber (g)</label>
                                    <input type="number" name="fiber" value="{{ old('fiber', $recipe->nutritionalInfo->fiber ?? 0) }}" min="0" step="0.1"
                                           class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Sugar (g)</label>
                                    <input type="number" name="sugar" value="{{ old('sugar', $recipe->nutritionalInfo->sugar ?? 0) }}" min="0" step="0.1"
                                           class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Sodium (mg)</label>
                                    <input type="number" name="sodium" value="{{ old('sodium', $recipe->nutritionalInfo->sodium ?? 0) }}" min="0" step="0.1"
                                           class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>
                        </div>
                    </details>
                    @endif
                    @endif
                </div>

                <!-- Right Sidebar (Image & Meta - 1/3 width) -->
                <div class="lg:col-span-1 space-y-4">
                    <!-- Recipe Image -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden sticky top-20">
                        <div class="px-4 py-2.5 border-b border-gray-200 bg-gradient-to-r from-rose-50 to-red-50">
                            <h3 class="text-sm font-semibold text-gray-900 flex items-center">
                                <svg class="w-4 h-4 mr-1.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/>
                                </svg>
                                Recipe Image
                            </h3>
                        </div>
                        <div class="p-4">
                            @if($recipe->image_path)
                                <div class="mb-3">
                                    <img src="{{ $recipe->image_url }}" 
                                         alt="{{ $recipe->name }}" 
                                         class="w-full h-40 object-cover rounded-md border border-gray-200">
                                    <p class="text-xs text-gray-500 mt-1.5">Current image</p>
                                </div>
                            @endif
                            <input type="file" name="image" accept="image/*"
                                   class="w-full text-xs file:mr-2 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                            <p class="text-xs text-gray-500 mt-1.5">Max 2MB (JPG, PNG, GIF)</p>
                            @error('image')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <!-- Quick Stats -->
                        <div class="px-4 py-3 border-t border-gray-200 bg-gray-50 space-y-2">
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-gray-600">Last updated:</span>
                                <span class="font-medium text-gray-900">{{ $recipe->updated_at->diffForHumans() }}</span>
                            </div>
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-gray-600">Created:</span>
                                <span class="font-medium text-gray-900">{{ $recipe->created_at->format('M d, Y') }}</span>
                            </div>
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-gray-600">Status:</span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $recipe->is_featured ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $recipe->is_featured ? '⭐ Featured' : 'Standard' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Error Alert -->
    @if($errors->any())
        <div class="fixed bottom-4 right-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg shadow-lg max-w-md z-50 animate-slide-up">
            <div class="flex items-start">
                <svg class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"/>
                </svg>
                <div class="flex-1">
                    <strong class="text-sm font-semibold">Please fix the following errors:</strong>
                    <ul class="mt-1.5 list-disc list-inside text-xs space-y-0.5">
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
    wrapper.className = 'ingredient-item grid grid-cols-12 gap-1.5 items-center';

    const nameInput = document.createElement('input');
    nameInput.type = 'text';
    nameInput.name = 'ingredient_names[]';
    nameInput.required = true;
    nameInput.maxLength = 100;
    nameInput.placeholder = 'e.g., Chicken, Garlic';
    nameInput.className = 'col-span-4 px-2 py-1.5 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-green-500 focus:border-transparent';
    nameInput.value = name;

    const quantityInput = document.createElement('input');
    quantityInput.type = 'number';
    quantityInput.name = 'ingredient_quantities[]';
    quantityInput.required = true;
    quantityInput.step = '0.01';
    quantityInput.min = '0';
    quantityInput.placeholder = '2';
    quantityInput.className = 'col-span-2 px-2 py-1.5 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-green-500 focus:border-transparent';
    quantityInput.value = quantity;

    const unitInput = document.createElement('input');
    unitInput.type = 'text';
    unitInput.name = 'ingredient_units[]';
    unitInput.required = true;
    unitInput.maxLength = 50;
    unitInput.placeholder = 'kg';
    unitInput.list = 'units-list';
    unitInput.className = 'col-span-2 px-2 py-1.5 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-green-500 focus:border-transparent';
    unitInput.value = unit;

    const priceInput = document.createElement('input');
    priceInput.type = 'number';
    priceInput.name = 'ingredient_prices[]';
    priceInput.step = '0.01';
    priceInput.min = '0';
    priceInput.placeholder = '0.00';
    priceInput.className = 'col-span-3 px-2 py-1.5 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-green-500 focus:border-transparent';
    priceInput.value = price;

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
    const datalist = document.createElement('datalist');
    datalist.id = 'units-list';
    const units = ['kg', 'g', 'lb', 'oz', 'L', 'mL', 'cup', 'cups', 'tbsp', 'tsp', 'pcs', 'pieces', 'can', 'pack', 'bunch', 'cloves', 'head'];
    units.forEach(unit => {
        const option = document.createElement('option');
        option.value = unit;
        datalist.appendChild(option);
    });
    document.body.appendChild(datalist);

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

    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-75');
                submitBtn.innerHTML = '<svg class="animate-spin w-3.5 h-3.5 inline mr-1" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Saving...';
            }
        });
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
