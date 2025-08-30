@extends('layouts.app')

@section('title', 'Edit Meal Plan')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Edit Meal Plan</h1>
        <p class="mt-2 text-gray-600">Update your scheduled meal</p>
    </div>

    <form action="{{ route('meal-plans.update', $mealPlan) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Meal Details</h2>
            </div>
            
            <div class="p-6 space-y-6">
                <!-- Date Selection -->
                <div>
                    <label for="scheduled_date" class="block text-sm font-medium text-gray-700">Date</label>
                    <input type="date" id="scheduled_date" name="scheduled_date" required
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm @error('scheduled_date') border-red-300 @enderror"
                           value="{{ old('scheduled_date', $mealPlan->scheduled_date->format('Y-m-d')) }}">
                    @error('scheduled_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Meal Type Selection -->
                <div>
                    <label for="meal_type" class="block text-sm font-medium text-gray-700">Meal Type</label>
                    <select id="meal_type" name="meal_type" required
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm @error('meal_type') border-red-300 @enderror">
                        <option value="">Select meal type</option>
                        <option value="breakfast" {{ old('meal_type', $mealPlan->meal_type) == 'breakfast' ? 'selected' : '' }}>🌅 Breakfast</option>
                        <option value="lunch" {{ old('meal_type', $mealPlan->meal_type) == 'lunch' ? 'selected' : '' }}>☀️ Lunch</option>
                        <option value="dinner" {{ old('meal_type', $mealPlan->meal_type) == 'dinner' ? 'selected' : '' }}>🌙 Dinner</option>
                        <option value="snack" {{ old('meal_type', $mealPlan->meal_type) == 'snack' ? 'selected' : '' }}>🍎 Snack</option>
                    </select>
                    @error('meal_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Meal Selection -->
                <div>
                    <label for="meal_id" class="block text-sm font-medium text-gray-700">Select Meal</label>
                    <select id="meal_id" name="meal_id" required
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm @error('meal_id') border-red-300 @enderror">
                        <option value="">Choose a meal</option>
                        @foreach($meals as $meal)
                            <option value="{{ $meal->id }}" {{ old('meal_id', $mealPlan->meal_id) == $meal->id ? 'selected' : '' }}>
                                {{ $meal->name }} - ₱{{ $meal->cost }} ({{ $meal->nutritionalInfo->calories ?? 'N/A' }} cal)
                            </option>
                        @endforeach
                    </select>
                    @error('meal_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Current Meal Info -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="font-medium text-gray-900 mb-2">Current Meal</h3>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-900">{{ $mealPlan->meal->name }}</p>
                            <p class="text-sm text-gray-600">{{ $mealPlan->meal->description }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-900">₱{{ $mealPlan->meal->cost }}</p>
                            <p class="text-sm text-gray-600">{{ $mealPlan->meal->nutritionalInfo->calories ?? 'N/A' }} cal</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('meal-plans.index') }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit" 
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                Update Meal Plan
            </button>
        </div>
    </form>
</div>
@endsection

