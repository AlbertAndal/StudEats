@extends('layouts.app')

@section('title', 'Add Meal to Plan')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center">
            <a href="{{ route('meal-plans.index') }}" 
               class="mr-4 p-2 text-gray-400 hover:text-gray-600 transition-colors"
               aria-label="Back to meal plans">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Add Meal to Plan</h1>
                <p class="mt-2 text-gray-600">Select a meal and schedule it for your plan</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Meal Selection Form -->
        <div class="lg:col-span-2">
            <form action="{{ route('meal-plans.store') }}" method="POST" id="meal-plan-form">
                @csrf
                
                <div class="bg-white shadow rounded-lg mb-6">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <h2 class="text-lg font-semibold text-gray-900">Schedule Details</h2>
                        </div>
                    </div>
                    
                    <div class="p-6 space-y-6">
                        <!-- Date & Time Selection -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="scheduled_date" class="block text-sm font-medium text-gray-700 mb-2">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    Date
                                </label>
                                <input type="date" id="scheduled_date" name="scheduled_date" required
                                       class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 sm:text-sm transition-all duration-200 @error('scheduled_date') border-red-300 focus:ring-red-500 focus:border-red-300 @enderror"
                                       value="{{ request('date', now()->format('Y-m-d')) }}">
                                @error('scheduled_date')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div>
                                <label for="scheduled_time" class="block text-sm font-medium text-gray-700 mb-2">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Time (Optional)
                                </label>
                                <input type="time" id="scheduled_time" name="scheduled_time"
                                       class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 sm:text-sm transition-all duration-200">
                            </div>
                        </div>

                        <!-- Meal Type Selection -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                                Meal Type
                            </label>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                @php
                                    $mealTypes = [
                                        'breakfast' => ['icon' => '🌅', 'label' => 'Breakfast', 'color' => 'yellow'],
                                        'lunch' => ['icon' => '☀️', 'label' => 'Lunch', 'color' => 'orange'],
                                        'dinner' => ['icon' => '🌙', 'label' => 'Dinner', 'color' => 'purple'],
                                        'snack' => ['icon' => '🍎', 'label' => 'Snack', 'color' => 'green']
                                    ];
                                @endphp
                                @foreach($mealTypes as $type => $details)
                                    <label class="relative cursor-pointer">
                                        <input type="radio" name="meal_type" value="{{ $type }}" 
                                               class="sr-only peer" 
                                               {{ request('meal_type') == $type ? 'checked' : '' }} required>
                                        <div class="border-2 border-gray-200 rounded-lg p-4 text-center hover:border-{{ $details['color'] }}-300 peer-checked:border-{{ $details['color'] }}-500 peer-checked:bg-{{ $details['color'] }}-50 transition-all duration-200">
                                            <div class="text-2xl mb-2">{{ $details['icon'] }}</div>
                                            <div class="text-sm font-medium text-gray-700 peer-checked:text-{{ $details['color'] }}-700">{{ $details['label'] }}</div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            @error('meal_type')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Hidden meal_id input -->
                <input type="hidden" id="meal_id" name="meal_id" value="{{ request('meal_id') }}">

                <!-- Action Buttons -->
                <div class="bg-white shadow rounded-lg p-6">
                    <div class="flex justify-between items-center">
                        <a href="{{ route('meal-plans.index') }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Cancel
                        </a>
                        <button type="submit" id="submit-btn" disabled
                                class="inline-flex items-center px-6 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-gray-400 cursor-not-allowed transition-all duration-200 disabled:opacity-50">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Add to Plan
                        </button>
                    </div>
                    <div id="form-validation" class="mt-4 text-sm text-gray-500">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Please select a meal type and choose a meal from the available options.
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Meal Preview Sidebar -->
        <div class="lg:col-span-1">
            <div class="sticky top-8">
                <!-- Selected Meal Preview -->
                <div id="meal-preview" class="bg-white shadow rounded-lg mb-6 hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Selected Meal
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="text-center mb-4">
                            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <span class="text-2xl">🍽️</span>
                            </div>
                            <h4 class="font-semibold text-gray-900 text-lg" id="preview-name"></h4>
                            <p class="text-sm text-gray-600 mt-2" id="preview-description"></p>
                        </div>
                        
                        <div class="grid grid-cols-1 gap-3 mb-4">
                            <div class="bg-green-50 p-3 rounded-lg text-center">
                                <div class="text-xs text-green-600 font-medium uppercase tracking-wide">Calories</div>
                                <div class="text-lg font-bold text-green-700" id="preview-calories"></div>
                            </div>
                            <div class="bg-blue-50 p-3 rounded-lg text-center">
                                <div class="text-xs text-blue-600 font-medium uppercase tracking-wide">Cost</div>
                                <div class="text-lg font-bold text-blue-700" id="preview-cost"></div>
                            </div>
                        </div>
                        
                        <button type="button" onclick="clearSelection()" 
                                class="w-full px-3 py-2 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors duration-200">
                            Change Selection
                        </button>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
                    </div>
                    <div class="p-6 space-y-3">
                        <a href="{{ route('recipes.index') }}" 
                           class="w-full flex items-center px-4 py-3 text-sm font-medium text-gray-700 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Browse All Recipes
                        </a>
                        <a href="{{ route('meal-plans.index') }}" 
                           class="w-full flex items-center px-4 py-3 text-sm font-medium text-gray-700 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            View Meal Plans
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Available Meals Grid -->
    <div class="mt-8">
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                            Available Meals
                        </h2>
                        <p class="text-sm text-gray-600 mt-1">Click on a meal to select it for your plan</p>
                    </div>
                    <div class="text-sm text-gray-500">
                        {{ $meals->count() }} meals available
                    </div>
                </div>
            </div>
            <div class="p-6">
                @if($meals->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                        @foreach($meals as $meal)
                            <div class="group border-2 border-gray-200 rounded-lg p-6 cursor-pointer hover:border-green-400 hover:shadow-lg transition-all duration-300 meal-option transform hover:-translate-y-1"
                                 data-meal-id="{{ $meal->id }}"
                                 data-meal-name="{{ $meal->name }}"
                                 data-meal-description="{{ $meal->description }}"
                                 data-meal-calories="{{ $meal->nutritionalInfo->calories ?? 'N/A' }}"
                                 data-meal-cost="₱{{ $meal->cost }}"
                                 aria-label="Select {{ $meal->name }} for your meal plan">
                                
                                <!-- Meal Image/Icon -->
                                <div class="flex items-center justify-center w-16 h-16 bg-gray-100 group-hover:bg-green-100 rounded-full mx-auto mb-4 transition-colors duration-300">
                                    @if($meal->image_path)
                                        <img src="{{ $meal->image_path }}" alt="{{ $meal->name }}" class="w-full h-full object-cover rounded-full">
                                    @else
                                        <span class="text-2xl">🍽️</span>
                                    @endif
                                </div>

                                <!-- Meal Info -->
                                <div class="text-center">
                                    <div class="flex items-start justify-between mb-3">
                                        <h3 class="font-semibold text-gray-900 group-hover:text-green-700 transition-colors duration-300 text-left flex-1">
                                            {{ $meal->name }}
                                        </h3>
                                        <span class="text-lg font-bold text-green-600 ml-2">₱{{ $meal->cost }}</span>
                                    </div>
                                    
                                    <p class="text-sm text-gray-600 mb-4 text-left line-clamp-2">
                                        {{ Str::limit($meal->description, 100) }}
                                    </p>
                                    
                                    <!-- Meal Stats -->
                                    <div class="grid grid-cols-2 gap-3 mb-4">
                                        <div class="bg-gray-50 group-hover:bg-green-50 rounded-lg p-3 transition-colors duration-300">
                                            <div class="flex items-center justify-center text-gray-500 group-hover:text-green-600">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                                </svg>
                                                <span class="text-xs font-medium">{{ $meal->nutritionalInfo->calories ?? 'N/A' }} cal</span>
                                            </div>
                                        </div>
                                        <div class="bg-gray-50 group-hover:bg-green-50 rounded-lg p-3 transition-colors duration-300">
                                            <div class="flex items-center justify-center text-gray-500 group-hover:text-green-600">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                                </svg>
                                                <span class="text-xs font-medium capitalize">{{ $meal->cuisine_type }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Selection Indicator -->
                                    <div class="flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        <div class="flex items-center text-green-600">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                            </svg>
                                            <span class="text-sm font-medium">Select This Meal</span>
                                        </div>
                                    </div>
                                    
                                    <!-- Selected State -->
                                    <div class="selected-indicator hidden">
                                        <div class="absolute -top-2 -right-2 w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No meals available</h3>
                        <p class="text-gray-500 mb-6">There are no meals in the system yet.</p>
                        <a href="{{ route('recipes.index') }}" 
                           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700">
                            Browse Recipes
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const mealIdInput = document.getElementById('meal_id');
    const mealPreview = document.getElementById('meal-preview');
    const mealOptions = document.querySelectorAll('.meal-option');
    const submitBtn = document.getElementById('submit-btn');
    const formValidation = document.getElementById('form-validation');
    const mealTypeInputs = document.querySelectorAll('input[name="meal_type"]');
    
    let selectedMealId = mealIdInput.value;

    // Handle meal option clicks
    mealOptions.forEach(option => {
        option.addEventListener('click', function() {
            // Remove selected class from all options
            mealOptions.forEach(opt => {
                opt.classList.remove('border-green-500', 'bg-green-50', 'ring-2', 'ring-green-200');
                opt.classList.add('border-gray-200');
                opt.querySelector('.selected-indicator')?.classList.add('hidden');
            });
            
            // Add selected class to clicked option
            this.classList.remove('border-gray-200');
            this.classList.add('border-green-500', 'bg-green-50', 'ring-2', 'ring-green-200');
            this.querySelector('.selected-indicator')?.classList.remove('hidden');
            
            // Update form
            const mealId = this.dataset.mealId;
            mealIdInput.value = mealId;
            selectedMealId = mealId;
            updateMealPreview();
            validateForm();
            
            // Scroll to preview on mobile
            if (window.innerWidth < 1024) {
                mealPreview.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
        });
    });

    // Handle meal type changes
    mealTypeInputs.forEach(input => {
        input.addEventListener('change', validateForm);
    });

    function updateMealPreview() {
        if (selectedMealId) {
            const selectedMeal = Array.from(mealOptions).find(option => 
                option.dataset.mealId === selectedMealId
            );

            if (selectedMeal) {
                document.getElementById('preview-name').textContent = selectedMeal.dataset.mealName;
                document.getElementById('preview-description').textContent = selectedMeal.dataset.mealDescription;
                document.getElementById('preview-calories').textContent = selectedMeal.dataset.mealCalories;
                document.getElementById('preview-cost').textContent = selectedMeal.dataset.mealCost;
                mealPreview.classList.remove('hidden');
                
                // Add smooth reveal animation
                setTimeout(() => {
                    mealPreview.style.opacity = '1';
                    mealPreview.style.transform = 'translateY(0)';
                }, 100);
            }
        } else {
            mealPreview.classList.add('hidden');
        }
    }

    function validateForm() {
        const selectedMealType = document.querySelector('input[name="meal_type"]:checked');
        const isValid = selectedMealType && selectedMealId;
        
        if (isValid) {
            submitBtn.disabled = false;
            submitBtn.classList.remove('bg-gray-400', 'cursor-not-allowed');
            submitBtn.classList.add('bg-green-600', 'hover:bg-green-700', 'cursor-pointer');
            formValidation.innerHTML = `
                <div class="flex items-center text-green-600">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Ready to add "${document.getElementById('preview-name').textContent}" to your ${selectedMealType.value} plan.
                </div>
            `;
        } else {
            submitBtn.disabled = true;
            submitBtn.classList.add('bg-gray-400', 'cursor-not-allowed');
            submitBtn.classList.remove('bg-green-600', 'hover:bg-green-700', 'cursor-pointer');
            
            let message = 'Please ';
            const missing = [];
            if (!selectedMealType) missing.push('select a meal type');
            if (!selectedMealId) missing.push('choose a meal');
            message += missing.join(' and ') + '.';
            
            formValidation.innerHTML = `
                <div class="flex items-center text-gray-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    ${message}
                </div>
            `;
        }
    }

    function clearSelection() {
        selectedMealId = null;
        mealIdInput.value = '';
        
        // Clear visual selection
        mealOptions.forEach(option => {
            option.classList.remove('border-green-500', 'bg-green-50', 'ring-2', 'ring-green-200');
            option.classList.add('border-gray-200');
            option.querySelector('.selected-indicator')?.classList.add('hidden');
        });
        
        mealPreview.classList.add('hidden');
        validateForm();
    }

    // Make clearSelection globally available
    window.clearSelection = clearSelection;

    // Initialize preview if meal is pre-selected
    if (selectedMealId) {
        updateMealPreview();
        
        // Highlight pre-selected meal
        const preSelectedMeal = Array.from(mealOptions).find(option => 
            option.dataset.mealId === selectedMealId
        );
        if (preSelectedMeal) {
            preSelectedMeal.classList.remove('border-gray-200');
            preSelectedMeal.classList.add('border-green-500', 'bg-green-50', 'ring-2', 'ring-green-200');
            preSelectedMeal.querySelector('.selected-indicator')?.classList.remove('hidden');
        }
    }
    
    // Initialize form validation
    validateForm();
    
    // Add smooth transitions for preview
    mealPreview.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
    mealPreview.style.opacity = '0';
    mealPreview.style.transform = 'translateY(-10px)';
});

// Add some CSS for smooth transitions
const style = document.createElement('style');
style.textContent = `
    .meal-option {
        position: relative;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .meal-option:hover {
        transform: translateY(-4px);
    }
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
`;
document.head.appendChild(style);
</script>
@endsection

