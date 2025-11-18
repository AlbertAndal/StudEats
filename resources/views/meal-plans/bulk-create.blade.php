@extends('layouts.app')

@section('title', 'Plan Your Day - Bulk Meal Planning')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <a href="{{ route('meal-plans.index') }}" 
                   class="mr-4 p-2 text-gray-400 hover:text-gray-600 transition-colors"
                   aria-label="Back to meal plans">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Plan Your Day</h1>
                    <p class="mt-2 text-gray-600">Create a complete meal plan for the entire day at once</p>
                </div>
            </div>
            <div class="text-sm text-gray-500">
                <span class="font-semibold text-gray-900">{{ $meals->count() }}</span> meals available
            </div>
        </div>
    </div>

    <form action="{{ route('meal-plans.bulk-store') }}" method="POST" id="bulk-meal-plan-form">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
            <!-- Date Selection Sidebar -->
            <div class="lg:col-span-1">
                <div class="sticky top-4 space-y-4">
                    <!-- Date Selection -->
                    <div class="bg-white shadow rounded-lg p-4">
                        <h3 class="text-base font-semibold text-gray-900 mb-3">Planning Date</h3>
                        <div class="relative">
                            <input type="date" 
                                   id="scheduled_date" 
                                   name="scheduled_date" 
                                   required
                                   value="{{ request('date', now()->format('Y-m-d')) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm transition-all duration-200">
                        </div>
                    </div>

                    <!-- Progress Tracker -->
                    <div class="bg-white shadow rounded-lg p-4">
                        <h3 class="text-base font-semibold text-gray-900 mb-3">Progress</h3>
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-gray-600">Breakfast</span>
                                <span id="breakfast-status" class="text-xs px-2 py-1 bg-gray-100 text-gray-600 rounded-full">Not selected</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-gray-600">Lunch</span>
                                <span id="lunch-status" class="text-xs px-2 py-1 bg-gray-100 text-gray-600 rounded-full">Not selected</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-gray-600">Dinner</span>
                                <span id="dinner-status" class="text-xs px-2 py-1 bg-gray-100 text-gray-600 rounded-full">Not selected</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-gray-600">Snack</span>
                                <span id="snack-status" class="text-xs px-2 py-1 bg-gray-100 text-gray-600 rounded-full">Optional</span>
                            </div>
                        </div>
                        
                        <div class="mt-3 pt-3 border-t border-gray-200">
                            <div class="flex items-center justify-between text-xs">
                                <span class="font-medium text-gray-700">Total Cost</span>
                                <span id="total-cost" class="font-bold text-green-600">₱0</span>
                            </div>
                            <div class="flex items-center justify-between text-xs mt-1">
                                <span class="font-medium text-gray-700">Total Calories</span>
                                <span id="total-calories" class="font-bold text-orange-600">0 cal</span>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                            id="submit-bulk-plan" 
                            disabled
                            class="w-full bg-gray-400 text-white font-semibold py-2 px-4 rounded-lg cursor-not-allowed transition-all duration-200 text-sm">
                        Create Meal Plan
                    </button>
                </div>
            </div>

            <!-- Meal Selection Grid -->
            <div class="lg:col-span-4">
                <!-- Meal Type Sections -->
                <div class="space-y-6">
                    <!-- Breakfast Section -->
                    <div class="bg-white shadow rounded-lg overflow-hidden">
                        <div class="px-4 py-3 bg-yellow-50 border-b border-yellow-100">
                            <div class="flex items-center gap-2">
                                <div class="h-8 w-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                                    <span class="text-lg">🍳</span>
                                </div>
                                <div>
                                    <h2 class="text-base font-semibold text-gray-900">Breakfast</h2>
                                    <p class="text-xs text-gray-600">Start your day right</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-4">
                            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3" id="breakfast-meals">
                                @foreach($meals->where('meal_type', 'breakfast') as $meal)
                                    @include('meal-plans.partials.bulk-meal-card', ['meal' => $meal, 'mealType' => 'breakfast'])
                                @endforeach
                                @if($meals->where('meal_type', 'breakfast')->isEmpty())
                                    <div class="col-span-full text-center py-6 text-gray-500">
                                        <span class="text-3xl mb-2 block">🍳</span>
                                        <span class="text-sm">No breakfast meals available</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Lunch Section -->
                    <div class="bg-white shadow rounded-lg overflow-hidden">
                        <div class="px-4 py-3 bg-orange-50 border-b border-orange-100">
                            <div class="flex items-center gap-2">
                                <div class="h-8 w-8 bg-orange-100 rounded-lg flex items-center justify-center">
                                    <span class="text-lg">🍽️</span>
                                </div>
                                <div>
                                    <h2 class="text-base font-semibold text-gray-900">Lunch</h2>
                                    <p class="text-xs text-gray-600">Fuel your afternoon</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-4">
                            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3" id="lunch-meals">
                                @foreach($meals->where('meal_type', 'lunch') as $meal)
                                    @include('meal-plans.partials.bulk-meal-card', ['meal' => $meal, 'mealType' => 'lunch'])
                                @endforeach
                                @if($meals->where('meal_type', 'lunch')->isEmpty())
                                    <div class="col-span-full text-center py-6 text-gray-500">
                                        <span class="text-3xl mb-2 block">🍽️</span>
                                        <span class="text-sm">No lunch meals available</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Dinner Section -->
                    <div class="bg-white shadow rounded-lg overflow-hidden">
                        <div class="px-4 py-3 bg-purple-50 border-b border-purple-100">
                            <div class="flex items-center gap-2">
                                <div class="h-8 w-8 bg-purple-100 rounded-lg flex items-center justify-center">
                                    <span class="text-lg">🍴</span>
                                </div>
                                <div>
                                    <h2 class="text-base font-semibold text-gray-900">Dinner</h2>
                                    <p class="text-xs text-gray-600">End your day well</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-4">
                            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3" id="dinner-meals">
                                @foreach($meals->where('meal_type', 'dinner') as $meal)
                                    @include('meal-plans.partials.bulk-meal-card', ['meal' => $meal, 'mealType' => 'dinner'])
                                @endforeach
                                @if($meals->where('meal_type', 'dinner')->isEmpty())
                                    <div class="col-span-full text-center py-6 text-gray-500">
                                        <span class="text-3xl mb-2 block">🍴</span>
                                        <span class="text-sm">No dinner meals available</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Snack Section -->
                    <div class="bg-white shadow rounded-lg overflow-hidden">
                        <div class="px-4 py-3 bg-green-50 border-b border-green-100">
                            <div class="flex items-center gap-2">
                                <div class="h-8 w-8 bg-green-100 rounded-lg flex items-center justify-center">
                                    <span class="text-lg">🍪</span>
                                </div>
                                <div>
                                    <h2 class="text-base font-semibold text-gray-900">Snack <span class="text-xs text-gray-500">(Optional)</span></h2>
                                    <p class="text-xs text-gray-600">A little treat</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-4">
                            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3" id="snack-meals">
                                @foreach($meals->where('meal_type', 'snack') as $meal)
                                    @include('meal-plans.partials.bulk-meal-card', ['meal' => $meal, 'mealType' => 'snack'])
                                @endforeach
                                @if($meals->where('meal_type', 'snack')->isEmpty())
                                    <div class="col-span-full text-center py-6 text-gray-500">
                                        <span class="text-3xl mb-2 block">🍪</span>
                                        <span class="text-sm">No snack options available</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hidden inputs for selected meals -->
        <input type="hidden" name="breakfast_meal_id" id="breakfast_meal_id">
        <input type="hidden" name="lunch_meal_id" id="lunch_meal_id">
        <input type="hidden" name="dinner_meal_id" id="dinner_meal_id">
        <input type="hidden" name="snack_meal_id" id="snack_meal_id">
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectedMeals = {
        breakfast: null,
        lunch: null,
        dinner: null,
        snack: null
    };

    function updateProgressTracker() {
        let totalCost = 0;
        let totalCalories = 0;

        // Update status indicators
        Object.keys(selectedMeals).forEach(mealType => {
            const statusElement = document.getElementById(`${mealType}-status`);
            const meal = selectedMeals[mealType];
            
            if (meal) {
                statusElement.textContent = 'Selected';
                statusElement.className = 'text-xs px-2 py-1 bg-green-100 text-green-700 rounded-full';
                totalCost += parseFloat(meal.cost);
                totalCalories += parseInt(meal.calories);
            } else if (mealType === 'snack') {
                statusElement.textContent = 'Optional';
                statusElement.className = 'text-xs px-2 py-1 bg-gray-100 text-gray-600 rounded-full';
            } else {
                statusElement.textContent = 'Not selected';
                statusElement.className = 'text-xs px-2 py-1 bg-gray-100 text-gray-600 rounded-full';
            }
        });

        // Update totals
        document.getElementById('total-cost').textContent = `₱${totalCost.toFixed(0)}`;
        document.getElementById('total-calories').textContent = `${totalCalories} cal`;

        // Update submit button
        const submitBtn = document.getElementById('submit-bulk-plan');
        const requiredSelected = selectedMeals.breakfast && selectedMeals.lunch && selectedMeals.dinner;
        
        if (requiredSelected) {
            submitBtn.disabled = false;
            submitBtn.className = 'w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition-all duration-200 text-sm';
            submitBtn.textContent = 'Create Meal Plan';
        } else {
            submitBtn.disabled = true;
            submitBtn.className = 'w-full bg-gray-400 text-white font-semibold py-2 px-4 rounded-lg cursor-not-allowed transition-all duration-200 text-sm';
            submitBtn.textContent = 'Select Breakfast, Lunch & Dinner';
        }

        // Update hidden inputs
        document.getElementById('breakfast_meal_id').value = selectedMeals.breakfast?.id || '';
        document.getElementById('lunch_meal_id').value = selectedMeals.lunch?.id || '';
        document.getElementById('dinner_meal_id').value = selectedMeals.dinner?.id || '';
        document.getElementById('snack_meal_id').value = selectedMeals.snack?.id || '';
    }

    function selectMeal(mealType, mealId, mealName, mealCost, mealCalories) {
        // Clear previous selection
        const previousCard = document.querySelector(`[data-meal-type="${mealType}"] .selected`);
        if (previousCard) {
            previousCard.classList.remove('selected', 'border-green-500', 'bg-green-50');
            previousCard.classList.add('border-gray-200', 'bg-white');
        }

        // Select new meal
        const newCard = document.querySelector(`[data-meal-id="${mealId}"][data-meal-type="${mealType}"]`);
        if (newCard) {
            newCard.classList.add('selected', 'border-green-500', 'bg-green-50');
            newCard.classList.remove('border-gray-200', 'bg-white');
        }

        selectedMeals[mealType] = {
            id: mealId,
            name: mealName,
            cost: mealCost,
            calories: mealCalories
        };

        updateProgressTracker();
    }

    // Add click event listeners to all meal cards
    document.querySelectorAll('[data-meal-id]').forEach(card => {
        card.addEventListener('click', function() {
            const mealId = this.dataset.mealId;
            const mealType = this.dataset.mealType;
            const mealName = this.dataset.mealName;
            const mealCost = this.dataset.mealCost;
            const mealCalories = this.dataset.mealCalories;

            selectMeal(mealType, mealId, mealName, mealCost, mealCalories);
        });
    });

    // Form submission
    document.getElementById('bulk-meal-plan-form').addEventListener('submit', function(e) {
        if (!selectedMeals.breakfast || !selectedMeals.lunch || !selectedMeals.dinner) {
            e.preventDefault();
            alert('Please select at least breakfast, lunch, and dinner meals.');
            return false;
        }
    });

    // Initialize
    updateProgressTracker();
});
</script>

@endsection