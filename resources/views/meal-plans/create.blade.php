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
                
                <div class="bg-white shadow-sm border border-gray-200 rounded-lg mb-6 overflow-hidden hover:shadow-md transition-shadow duration-200">
                    <div class="px-6 py-4 bg-gradient-to-r from-green-50/50 to-white border-b border-gray-200">
                        <div class="flex items-center gap-2">
                            <div class="flex-shrink-0">
                                <div class="h-8 w-8 bg-green-500/10 rounded-lg flex items-center justify-center">
                                    <svg class="h-4 w-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <h2 class="text-base font-semibold text-gray-900 leading-5">Schedule Details</h2>
                                <p class="text-xs text-gray-500">When would you like to have this meal?</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-6 space-y-6">
                        <!-- Date & Time Selection -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="scheduled_date" class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">
                                    Date
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <input type="date" id="scheduled_date" name="scheduled_date" required
                                           class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm transition-all duration-200 @error('scheduled_date') border-red-300 focus:ring-red-500 focus:border-red-300 @enderror"
                                           value="{{ request('date', now()->format('Y-m-d')) }}">
                                </div>
                                @error('scheduled_date')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div>
                                <label for="scheduled_time" class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">
                                    Time <span class="text-gray-400 normal-case">(Optional)</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <input type="time" id="scheduled_time" name="scheduled_time"
                                           class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm transition-all duration-200"
                                           placeholder="Select time">
                                </div>
                                <p class="mt-1.5 text-xs text-gray-500">Choose when you plan to eat this meal</p>
                            </div>
                        </div>

                        <!-- Meal Type Selection -->
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-3">
                                Meal Type
                            </label>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                @php
                                    $mealTypes = [
                                        'breakfast' => ['icon' => '🍳', 'label' => 'Breakfast', 'color' => 'yellow', 'time' => '08:00'],
                                        'lunch' => ['icon' => '🍽️', 'label' => 'Lunch', 'color' => 'orange', 'time' => '12:00'],
                                        'dinner' => ['icon' => '🍴', 'label' => 'Dinner', 'color' => 'purple', 'time' => '18:00'],
                                        'snack' => ['icon' => '🍪', 'label' => 'Snack', 'color' => 'green', 'time' => '15:00']
                                    ];
                                @endphp
                                @foreach($mealTypes as $type => $details)
                                    @php
                                        $isExisting = in_array($type, $existingMealTypes ?? []);
                                    @endphp
                                    <label class="relative block {{ $isExisting ? 'cursor-not-allowed opacity-60' : 'cursor-pointer' }} group">
                                        <input type="radio" name="meal_type" value="{{ $type }}" 
                                               class="sr-only peer" 
                                               data-suggested-time="{{ $details['time'] }}"
                                               {{ request('meal_type') == $type ? 'checked' : '' }}
                                               {{ $isExisting ? 'disabled' : '' }} required>
                                        
                                        <!-- Meal type card -->
                                        <!-- Meal type card -->
                                        <div class="meal-type-card relative border-2 rounded-lg p-4 text-center transition-all duration-200 peer-disabled:opacity-60
                                                    {{ $isExisting ? 'border-red-300 bg-red-50 opacity-60' : 'border-gray-200 hover:shadow-md' }}
                                                    @if(!$isExisting)
                                                        @if($type === 'breakfast')
                                                            hover:border-yellow-300
                                                            {{ request('meal_type') == $type ? 'border-yellow-500 bg-yellow-50 ring-2 ring-yellow-200 shadow-lg' : '' }}
                                                        @elseif($type === 'lunch')
                                                            hover:border-orange-300
                                                            {{ request('meal_type') == $type ? 'border-orange-500 bg-orange-50 ring-2 ring-orange-200 shadow-lg' : '' }}
                                                        @elseif($type === 'dinner')
                                                            hover:border-purple-300
                                                            {{ request('meal_type') == $type ? 'border-purple-500 bg-purple-50 ring-2 ring-purple-200 shadow-lg' : '' }}
                                                        @elseif($type === 'snack')
                                                            hover:border-green-300
                                                            {{ request('meal_type') == $type ? 'border-green-500 bg-green-50 ring-2 ring-green-200 shadow-lg' : '' }}
                                                        @endif
                                                    @endif" 
                                             data-meal-type="{{ $type }}">
                                            <div class="text-3xl mb-2">{{ $details['icon'] }}</div>
                                            <div class="meal-type-label text-sm font-semibold
                                                        {{ $isExisting ? 'text-red-700' : 'text-gray-700' }}
                                                        @if(!$isExisting && request('meal_type') == $type)
                                                            @if($type === 'breakfast')
                                                                text-yellow-700
                                                            @elseif($type === 'lunch')
                                                                text-orange-700
                                                            @elseif($type === 'dinner')
                                                                text-purple-700
                                                            @elseif($type === 'snack')
                                                                text-green-700
                                                            @endif
                                                        @endif">{{ $details['label'] }}</div>
                                            <div class="meal-type-time text-xs mt-1
                                                        {{ $isExisting ? 'text-red-600' : 'text-gray-500' }}
                                                        @if(!$isExisting && request('meal_type') == $type)
                                                            @if($type === 'breakfast')
                                                                text-yellow-600
                                                            @elseif($type === 'lunch')
                                                                text-orange-600
                                                            @elseif($type === 'dinner')
                                                                text-purple-600
                                                            @elseif($type === 'snack')
                                                                text-green-600
                                                            @endif
                                                        @endif">
                                                {{ $isExisting ? 'Already scheduled' : $details['time'] }}
                                            </div>
                                        </div>
                                        
                                        @if($isExisting)
                                            <!-- Already scheduled indicator -->
                                            <div class="absolute -top-2 -right-2 w-7 h-7 bg-red-500 rounded-full flex items-center justify-center shadow-lg border-2 border-white z-10">
                                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                        @else
                                            <!-- Selection checkmark -->
                                            @php
                                                $checkmarkBg = match($type) {
                                                    'breakfast' => 'bg-yellow-500',
                                                    'lunch' => 'bg-orange-500',
                                                    'dinner' => 'bg-purple-500',
                                                    'snack' => 'bg-green-500',
                                                    default => 'bg-gray-500'
                                                };
                                            @endphp
                                            <div class="absolute -top-2 -right-2 w-7 h-7 {{ $checkmarkBg }} rounded-full shadow-lg border-2 border-white z-10 hidden peer-checked:flex items-center justify-center opacity-0 peer-checked:opacity-100 transition-opacity duration-200">
                                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                        @endif
                                    </label>
                                @endforeach
                            </div>
                            @error('meal_type')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                        <x-loading-button 
                            href="{{ route('meal-plans.index') }}"
                            variant="secondary"
                            size="sm"
                            loadingText="Loading..."
                            loadingType="spinner"
                            class="border border-gray-300">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Cancel
                        </x-loading-button>
                        <button type="submit" id="submit-btn" disabled
                                class="inline-flex items-center px-6 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-gray-400 cursor-not-allowed transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            <span id="submit-btn-text">Add to Plan</span>
                            <span id="submit-btn-spinner" class="hidden loading loading-spinner loading-sm ml-2"></span>
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
                                    @if(request('meal_type'))
                                        @php
                                            $filterTypes = [
                                                'breakfast' => ['icon' => '🍳', 'color' => 'bg-yellow-100 text-yellow-700'],
                                                'lunch' => ['icon' => '🍽️', 'color' => 'bg-orange-100 text-orange-700'],
                                                'dinner' => ['icon' => '🍴', 'color' => 'bg-purple-100 text-purple-700'],
                                                'snack' => ['icon' => '🍪', 'color' => 'bg-green-100 text-green-700']
                                            ];
                                            $selectedType = request('meal_type');
                                            $filterInfo = $filterTypes[$selectedType] ?? ['icon' => '🍽️', 'color' => 'bg-gray-100 text-gray-700'];
                                        @endphp
                                        <span class="ml-2 inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $filterInfo['color'] }}">
                                            <span class="mr-1">{{ $filterInfo['icon'] }}</span>
                                            <span class="capitalize">{{ $selectedType }} only</span>
                                        </span>
                                    @endif
                                </h2>
                                <p class="text-sm text-gray-600 mt-1">
                                    @if(request('meal_type'))
                                        Showing meals suitable for {{ request('meal_type') }}. Select a different meal type above to see other options.
                                    @else
                                        Click on a meal to select it for your plan
                                    @endif
                                </p>
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
                                    @php
                                        $displayCost = $meal->getDisplayCost('NCR');
                                        $hasRealTimePricing = $meal->hasRealTimePricing('NCR');
                                    @endphp
                                    <div class="group border-2 border-gray-200 rounded-lg p-4 cursor-pointer hover:border-green-400 hover:shadow-lg transition-all duration-300 meal-option bg-white hover:bg-gradient-to-br hover:from-green-50 hover:to-white"
                                         data-meal-id="{{ $meal->id }}"
                                         data-meal-name="{{ $meal->name }}"
                                         data-meal-description="{{ $meal->description }}"
                                         data-meal-calories="{{ $meal->nutritionalInfo->calories ?? 'N/A' }}"
                                         data-meal-cost="₱{{ number_format($displayCost, 2) }}"
                                         aria-label="Select {{ $meal->name }} for your meal plan">
                                        
                                        <!-- Meal Image/Icon -->
                                        <div class="relative mb-3">
                                            @if($meal->image_path)
                                                <div class="w-full h-32 bg-gray-100 rounded-md overflow-hidden group-hover:shadow-md transition-shadow duration-300">
                                                    <img src="{{ $meal->image_url }}" 
                                                         alt="{{ $meal->name }}" 
                                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                                         loading="lazy"
                                                         onerror="this.src='{{ asset('images/placeholder-meal.jpg') }}'">
                                                </div>
                                            @else
                                                <div class="w-full h-32 bg-gradient-to-br from-green-100 to-blue-100 rounded-md flex items-center justify-center group-hover:shadow-md transition-shadow duration-300">
                                                    <span class="text-4xl opacity-70">🍽️</span>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Meal Info -->
                                        <div class="space-y-2">
                                            <!-- Meal Title -->
                                            <div class="text-center">
                                                <h3 class="text-lg font-bold text-gray-900 group-hover:text-green-700 transition-colors duration-300 mb-1 line-clamp-2">
                                                    {{ $meal->name }}
                                                </h3>
                                                <div class="flex items-center justify-center gap-2 mb-1">
                                                    <div class="inline-flex items-center px-2 py-0.5 bg-gray-100 group-hover:bg-green-100 rounded-full text-xs font-medium text-gray-600 group-hover:text-green-700 transition-colors duration-300">
                                                        <span class="capitalize">{{ $meal->cuisine_type }}</span>
                                                    </div>
                                                    @php
                                                        $mealTypeColors = [
                                                            'breakfast' => 'bg-yellow-100 text-yellow-700 group-hover:bg-yellow-200',
                                                            'lunch' => 'bg-orange-100 text-orange-700 group-hover:bg-orange-200',
                                                            'dinner' => 'bg-purple-100 text-purple-700 group-hover:bg-purple-200',
                                                            'snack' => 'bg-green-100 text-green-700 group-hover:bg-green-200'
                                                        ];
                                                        $mealTypeIcons = [
                                                            'breakfast' => '🍳',
                                                            'lunch' => '🍽️',
                                                            'dinner' => '🍴',
                                                            'snack' => '🍪'
                                                        ];
                                                        $colorClass = $mealTypeColors[$meal->meal_type] ?? 'bg-gray-100 text-gray-700';
                                                        $icon = $mealTypeIcons[$meal->meal_type] ?? '🍽️';
                                                    @endphp
                                                    <div class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium transition-colors duration-300 {{ $colorClass }}">
                                                        <span class="mr-1">{{ $icon }}</span>
                                                        <span class="capitalize">{{ $meal->meal_type }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Description -->
                                            <p class="text-xs text-gray-600 text-center line-clamp-2 leading-relaxed px-1">
                                                {{ Str::limit($meal->description, 80) }}
                                            </p>

                                            <!-- Meal Stats -->
                                            <div class="bg-gray-50 group-hover:bg-green-50/50 rounded-md p-3 transition-colors duration-300">
                                                <div class="grid grid-cols-2 gap-4 text-center">
                                                    <!-- Calories -->
                                                    <div class="space-y-0.5">
                                                        <div class="w-6 h-6 bg-orange-100 group-hover:bg-orange-200 rounded-full flex items-center justify-center mx-auto transition-colors duration-300">
                                                            <span class="text-sm">🔥</span>
                                                        </div>
                                                        @if(isset($bmiStatus) && $bmiStatus['calorie_multiplier'] != 1)
                                                            @php
                                                                $originalCalories = $meal->nutritionalInfo->calories ?? 0;
                                                                $adjustedCalories = round($originalCalories * $bmiStatus['calorie_multiplier']);
                                                            @endphp
                                                            <div class="text-xs font-bold text-orange-600">{{ $adjustedCalories > 0 ? $adjustedCalories : '300' }}</div>
                                                            <div class="text-xs text-orange-500">cal</div>
                                                        @else
                                                            <div class="text-xs font-bold text-orange-600">{{ $meal->nutritionalInfo->calories ?? '300' }}</div>
                                                            <div class="text-xs text-orange-500">cal</div>
                                                        @endif
                                                    </div>
                                                    
                                                    <!-- Prep Time -->
                                                    <div class="space-y-0.5">
                                                        <div class="w-6 h-6 bg-blue-100 group-hover:bg-blue-200 rounded-full flex items-center justify-center mx-auto transition-colors duration-300">
                                                            <span class="text-sm">⏱️</span>
                                                        </div>
                                                        <div class="text-xs font-bold text-blue-600">{{ $meal->prep_time ?? '30' }}</div>
                                                        <div class="text-xs text-blue-500">min</div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Selection Indicator -->
                                            <div class="mt-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                                <button type="button" class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-3 rounded-md transition-colors duration-200 flex items-center justify-center space-x-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                                    </svg>
                                                    <span class="text-sm">Select Meal</span>
                                                </button>
                                            </div>
                                            
                                            <!-- Selected State -->
                                            <div class="selected-indicator hidden absolute inset-0">
                                                <!-- Green border outline -->
                                                <div class="absolute inset-0 bg-transparent border-2 border-green-500 rounded-lg shadow-lg"></div>
                                                <!-- Check circle -->
                                                <div class="absolute -top-2 -right-2 w-8 h-8 bg-green-500 rounded-full flex items-center justify-center shadow-lg border-2 border-white z-10">
                                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
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
                                <x-loading-button 
                                    href="{{ route('recipes.index') }}"
                                    variant="success"
                                    size="sm"
                                    loadingText="Loading..."
                                    loadingType="spinner">
                                    Browse Recipes
                                </x-loading-button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Meal Preview Sidebar -->
        <div class="lg:col-span-1">
            <div class="sticky top-8 space-y-6">
                <!-- Quick Actions -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
                    </div>
                    <div class="p-6 space-y-3">
                        <x-loading-button 
                            href="{{ route('recipes.index') }}"
                            variant="secondary"
                            size="sm"
                            loadingText="Loading..."
                            loadingType="spinner"
                            class="w-full bg-gray-50 hover:bg-gray-100">
                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Browse All Recipes
                        </x-loading-button>
                        <x-loading-button 
                            href="{{ route('meal-plans.index') }}"
                            variant="secondary"
                            size="sm"
                            loadingText="Loading..."
                            loadingType="spinner"
                            class="w-full bg-gray-50 hover:bg-gray-100">
                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            View Meal Plans
                        </x-loading-button>
                    </div>
                </div>
                
                <!-- BMI Status / Health Profile -->
                @if(isset($bmiStatus) && $bmiStatus['bmi'])
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/>
                            </svg>
                            Your Health Profile
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-600">BMI Score</span>
                                <span class="text-lg font-bold text-gray-900">{{ $bmiStatus['bmi'] }}</span>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-600">Category</span>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium border {{ $bmiStatus['colors'][0] }} {{ $bmiStatus['colors'][1] }} {{ $bmiStatus['colors'][2] }}">
                                    {{ $bmiStatus['category_label'] }}
                                </span>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-600">Daily Calories</span>
                                <span class="text-lg font-bold text-green-600">{{ number_format($bmiStatus['daily_calories']) }} cal</span>
                            </div>
                            
                            <div class="pt-3 border-t border-gray-200">
                                <div class="flex items-start">
                                    <svg class="w-4 h-4 text-blue-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <div>
                                        <p class="text-xs text-gray-600">{{ $bmiStatus['recommendation'] }}</p>
                                        @if($bmiStatus['calorie_multiplier'] != 1)
                                            <p class="text-xs text-blue-600 mt-1">
                                                Meal calories are {{ $bmiStatus['calorie_multiplier'] > 1 ? 'increased' : 'reduced' }} by {{ round(abs(($bmiStatus['calorie_multiplier'] - 1) * 100)) }}% for your health goals.
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Selected Meal Preview -->
                <div id="meal-preview" class="bg-white shadow rounded-lg min-h-0">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span id="preview-title">Selected Meal</span>
                        </h3>
                    </div>
                    <div class="p-6">
                        <!-- Default state (no meal selected) -->
                        <div id="no-meal-selected" class="text-center py-8">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                            </div>
                            <h4 class="font-medium text-gray-900 mb-2">No meal selected</h4>
                            <p class="text-sm text-gray-600">Click on a meal from the Available Meals section to see its details here.</p>
                        </div>

                        <!-- Selected meal content -->
                        <div id="meal-selected-content" class="hidden">
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
                </div>
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
    const mealTypeInputs = document.querySelectorAll('input[name=\"meal_type\"]');
    const dateInput = document.getElementById('scheduled_date');
    
    let selectedMealId = mealIdInput.value;
    
    // Function to filter meals by type
    function filterMealsByType(mealType) {
        // Build URL with current parameters
        const url = new URL(window.location.href);
        url.searchParams.set('meal_type', mealType);
        
        // Preserve existing date parameter if present
        const currentDate = dateInput.value;
        if (currentDate) {
            url.searchParams.set('date', currentDate);
        }
        
        // Show loading indicator
        const mealsGrid = document.querySelector('.grid.grid-cols-1.md\\:grid-cols-2.xl\\:grid-cols-3');
        if (mealsGrid) {
            mealsGrid.innerHTML = '<div class="col-span-full text-center py-8"><div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-green-600"></div><p class="mt-2 text-gray-600">Loading meals for ' + mealType + '...</p></div>';
        }
        
        // Navigate to filtered URL
        window.location.href = url.toString();
    }
    
    // Initialize pre-selected meal type from URL
    const preSelectedMealType = document.querySelector('input[name=\"meal_type\"]:checked');
    if (preSelectedMealType && !preSelectedMealType.disabled) {
        // Force visual selection by manually applying styles
        const mealTypeContainer = preSelectedMealType.closest('label').querySelector('div');
        const mealTypeValue = preSelectedMealType.value;
        
        // Remove default border and add selected styling based on meal type
        mealTypeContainer.classList.remove('border-gray-200');
        if (mealTypeValue === 'breakfast') {
            mealTypeContainer.classList.add('border-yellow-500', 'bg-yellow-50');
        } else if (mealTypeValue === 'lunch') {
            mealTypeContainer.classList.add('border-orange-500', 'bg-orange-50');
        } else if (mealTypeValue === 'dinner') {
            mealTypeContainer.classList.add('border-purple-500', 'bg-purple-50');
        } else if (mealTypeValue === 'snack') {
            mealTypeContainer.classList.add('border-green-500', 'bg-green-50');
        }
        
        // Show the checkmark indicator
        const checkmark = preSelectedMealType.closest('label').querySelector('.absolute.-top-2.-right-2');
        if (checkmark && !checkmark.classList.contains('bg-red-500')) {
            checkmark.classList.remove('hidden');
        }
        
        // Auto-fill suggested time to match the meal type
        const timeInput = document.getElementById('scheduled_time');
        const suggestedTime = preSelectedMealType.dataset.suggestedTime;
        
        // Always set the time to match the meal type, even if time input has a value
        if (suggestedTime) {
            timeInput.value = suggestedTime;
            // Add visual feedback to show time was auto-filled
            timeInput.classList.add('border-green-300', 'bg-green-50');
            setTimeout(() => {
                timeInput.classList.remove('border-green-300', 'bg-green-50');
            }, 2000);
        }
    }

    // Add form submission handler with loading indicator
    const form = document.getElementById('meal-plan-form');
    form.addEventListener('submit', function(e) {
        // Validate form before showing loading
        if (!selectedMealId) {
            e.preventDefault();
            alert('Please select a meal first.');
            return;
        }
        
        if (!document.querySelector('input[name=\"meal_type\"]:checked')) {
            e.preventDefault();
            alert('Please select a meal type.');
            return;
        }
        
        // Form is valid, show loading state on button
        const btnText = document.getElementById('submit-btn-text');
        const btnSpinner = document.getElementById('submit-btn-spinner');
        const btnIcon = submitBtn.querySelector('svg');
        
        if (btnText && btnSpinner && btnIcon) {
            btnIcon.classList.add('hidden');
            btnText.textContent = 'Adding to Plan...';
            btnSpinner.classList.remove('hidden');
            submitBtn.disabled = true;
        }
    });

    // Handle meal option clicks
    mealOptions.forEach(option => {
        option.addEventListener('click', function() {
            // Remove selected indicator from all options
            mealOptions.forEach(opt => {
                opt.querySelector('.selected-indicator')?.classList.add('hidden');
            });
            
            // Show selected indicator on clicked option
            this.querySelector('.selected-indicator')?.classList.remove('hidden');
            
            // Update form
            const mealId = this.dataset.mealId;
            mealIdInput.value = mealId;
            selectedMealId = mealId;
            updateMealPreview();
            validateForm();
        });
    });

    // Handle meal type changes
    mealTypeInputs.forEach(input => {
        input.addEventListener('change', function() {
            if (!this.disabled) {
                const mealType = this.value;
                
                // Remove selected styling from all meal type cards
                mealTypeInputs.forEach(inp => {
                    const card = inp.parentElement.querySelector('.meal-type-card');
                    const label = inp.parentElement.querySelector('.meal-type-label');
                    const time = inp.parentElement.querySelector('.meal-type-time');
                    
                    if (inp !== this) {
                        inp.checked = false;
                        // Remove all selected styling
                        card.classList.remove(
                            'border-yellow-500', 'bg-yellow-50', 'ring-2', 'ring-yellow-200', 'shadow-lg',
                            'border-orange-500', 'bg-orange-50', 'ring-orange-200',
                            'border-purple-500', 'bg-purple-50', 'ring-purple-200',
                            'border-green-500', 'bg-green-50', 'ring-green-200'
                        );
                        label.classList.remove('text-yellow-700', 'text-orange-700', 'text-purple-700', 'text-green-700');
                        time.classList.remove('text-yellow-600', 'text-orange-600', 'text-purple-600', 'text-green-600');
                        
                        // Reset to default
                        card.classList.add('border-gray-200');
                        label.classList.add('text-gray-700');
                        time.classList.add('text-gray-500');
                    }
                });
                
                // Apply selected styling to current meal type
                const selectedCard = this.parentElement.querySelector('.meal-type-card');
                const selectedLabel = this.parentElement.querySelector('.meal-type-label');
                const selectedTime = this.parentElement.querySelector('.meal-type-time');
                
                selectedCard.classList.remove('border-gray-200');
                selectedLabel.classList.remove('text-gray-700');
                selectedTime.classList.remove('text-gray-500');
                
                switch(mealType) {
                    case 'breakfast':
                        selectedCard.classList.add('border-yellow-500', 'bg-yellow-50', 'ring-2', 'ring-yellow-200', 'shadow-lg');
                        selectedLabel.classList.add('text-yellow-700');
                        selectedTime.classList.add('text-yellow-600');
                        break;
                    case 'lunch':
                        selectedCard.classList.add('border-orange-500', 'bg-orange-50', 'ring-2', 'ring-orange-200', 'shadow-lg');
                        selectedLabel.classList.add('text-orange-700');
                        selectedTime.classList.add('text-orange-600');
                        break;
                    case 'dinner':
                        selectedCard.classList.add('border-purple-500', 'bg-purple-50', 'ring-2', 'ring-purple-200', 'shadow-lg');
                        selectedLabel.classList.add('text-purple-700');
                        selectedTime.classList.add('text-purple-600');
                        break;
                    case 'snack':
                        selectedCard.classList.add('border-green-500', 'bg-green-50', 'ring-2', 'ring-green-200', 'shadow-lg');
                        selectedLabel.classList.add('text-green-700');
                        selectedTime.classList.add('text-green-600');
                        break;
                }
                
                this.checked = true;
                validateForm();
                
                // Always update time to match the selected meal type
                const timeInput = document.getElementById('scheduled_time');
                const suggestedTime = this.dataset.suggestedTime;
                
                if (suggestedTime) {
                    timeInput.value = suggestedTime;
                    // Add visual feedback
                    timeInput.classList.add('border-green-300', 'bg-green-50');
                    setTimeout(() => {
                        timeInput.classList.remove('border-green-300', 'bg-green-50');
                    }, 1000);
                }
                
                // Filter meals based on selected meal type
                filterMealsByType(mealType);
            }
        });
        
        // Prevent clicks on disabled meal types
        if (input.disabled) {
            input.parentElement.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                // Show tooltip or message
                const label = this.querySelector('.text-xs');
                if (label) {
                    const originalText = label.textContent;
                    label.textContent = 'Meal already scheduled!';
                    label.classList.add('font-bold');
                    setTimeout(() => {
                        label.textContent = originalText;
                        label.classList.remove('font-bold');
                    }, 2000);
                }
            });
        }
    });
    
    // Enhanced time input functionality
    const timeInput = document.getElementById('scheduled_time');
    
    // Enhanced mobile touch handling
    timeInput.addEventListener('focus', function() {
        if (window.innerWidth < 768) {
            // Scroll input into view on mobile
            setTimeout(() => {
                this.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }, 300);
        }
    });
    
    // Time input validation and formatting
    timeInput.addEventListener('change', function() {
        if (this.value) {
            // Provide visual feedback when time is selected
            this.classList.add('border-green-300', 'bg-green-50');
            setTimeout(() => {
                this.classList.remove('border-green-300', 'bg-green-50');
            }, 1000);
        }
    });

    function updateMealPreview() {
        const noMealSelected = document.getElementById('no-meal-selected');
        const mealSelectedContent = document.getElementById('meal-selected-content');
        const previewTitle = document.getElementById('preview-title');
        
        if (selectedMealId) {
            const selectedMeal = Array.from(mealOptions).find(option => 
                option.dataset.mealId === selectedMealId
            );

            if (selectedMeal) {
                document.getElementById('preview-name').textContent = selectedMeal.dataset.mealName;
                document.getElementById('preview-description').textContent = selectedMeal.dataset.mealDescription;
                document.getElementById('preview-calories').textContent = selectedMeal.dataset.mealCalories;
                document.getElementById('preview-cost').textContent = selectedMeal.dataset.mealCost;
                
                // Update title and show selected content
                previewTitle.textContent = 'Selected Meal';
                noMealSelected.classList.add('hidden');
                mealSelectedContent.classList.remove('hidden');
            }
        } else {
            // Show default state
            previewTitle.textContent = 'Meal Preview';
            mealSelectedContent.classList.add('hidden');
            noMealSelected.classList.remove('hidden');
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
            option.querySelector('.selected-indicator')?.classList.add('hidden');
        });
        
        // Update preview to show default state
        updateMealPreview();
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
            preSelectedMeal.querySelector('.selected-indicator')?.classList.remove('hidden');
        }
    }
    
    // Handle date changes - reload page to check for existing meals on new date
    if (dateInput) {
        dateInput.addEventListener('change', function() {
            const currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('date', this.value);
            // Preserve meal_id if present
            const mealId = mealIdInput.value;
            if (mealId) {
                currentUrl.searchParams.set('meal_id', mealId);
            }
            window.location.href = currentUrl.toString();
        });
    }
    
    // Initialize form validation
    validateForm();
    
    // Initialize meal preview (always show the preview container)
    updateMealPreview();
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
    
    /* Fix for Schedule Details positioning */
    #scheduled_date, #scheduled_time {
        position: relative !important;
        transform: none !important;
        left: auto !important;
        top: auto !important;
        margin: 0 !important;
    }
    
    /* Ensure form container is properly positioned */
    #meal-plan-form {
        position: relative;
    }
    
    /* Schedule Details container fix */
    .schedule-details-container {
        position: relative;
        z-index: 1;
    }
    
    /* Enhanced time input styling */
    #scheduled_time {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='%236b7280'%3e%3cpath stroke-linecap='round' stroke-linejoin='round' d='M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0Z' /%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 12px center;
        background-size: 16px 16px;
        padding-right: 40px;
    }
    
    /* Mobile optimization for time input */
    @media (max-width: 768px) {
        #scheduled_time {
            font-size: 16px; /* Prevents zoom on iOS */
            padding: 12px 40px 12px 16px;
        }
        
        #scheduled_date {
            font-size: 16px; /* Prevents zoom on iOS */
            padding: 12px 16px;
        }
    }
    
    /* Enhanced focus states */
    #scheduled_time:focus,
    #scheduled_date:focus {
        box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
        border-color: #22c55e;
        outline: none;
    }
    
    /* Hover effects */
    #scheduled_time:hover:not(:focus),
    #scheduled_date:hover:not(:focus) {
        border-color: #9ca3af;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
    }
    
    /* Time picker enhancement for webkit browsers */
    #scheduled_time::-webkit-calendar-picker-indicator {
        background: transparent;
        bottom: 0;
        color: transparent;
        cursor: pointer;
        height: auto;
        left: 0;
        position: absolute;
        right: 0;
        top: 0;
        width: auto;
    }
    
    /* Custom styling for time input in different browsers */
    #scheduled_time::-webkit-datetime-edit-fields-wrapper {
        padding: 0;
    }
    
    #scheduled_time::-webkit-datetime-edit-text {
        color: #6b7280;
        padding: 0 2px;
    }
    
    #scheduled_time::-webkit-datetime-edit-hour-field,
    #scheduled_time::-webkit-datetime-edit-minute-field {
        background-color: transparent;
        border: none;
        color: #374151;
        font-weight: 500;
    }
    
    /* Accessibility improvements */
    #scheduled_time:focus-visible,
    #scheduled_date:focus-visible {
        outline: 2px solid #22c55e;
        outline-offset: 2px;
    }
`;
document.head.appendChild(style);
</script>
@endsection

