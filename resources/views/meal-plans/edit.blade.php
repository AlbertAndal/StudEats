@extends('layouts.app')

@section('title', 'Edit Meal Plan')

{{-- Enhanced Meal Plan Edit Page --}}

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Enhanced Breadcrumb Navigation -->
        <nav class="flex mb-8 bg-white rounded-lg shadow-sm border border-gray-200 p-4" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3 w-full">
                <li class="inline-flex items-center">
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-green-600 transition-colors duration-200 group">
                        <x-icon name="home" class="w-4 h-4 mr-2 group-hover:text-green-600" variant="outline" />
                        <span class="group-hover:underline">Dashboard</span>
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <x-icon name="chevron-right" class="w-4 h-4 text-gray-400 mx-2" variant="outline" />
                        <a href="{{ route('meal-plans.index') }}" class="text-sm font-medium text-gray-700 hover:text-green-600 transition-colors duration-200 hover:underline">Meal Plans</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <x-icon name="chevron-right" class="w-4 h-4 text-gray-400 mx-2" variant="outline" />
                        <span class="text-sm font-medium text-green-600 bg-green-50 px-2 py-1 rounded-md">Edit Meal Plan</span>
                    </div>
                </li>
                
                <!-- Action Buttons -->
                <li class="ml-auto hidden md:flex items-center space-x-2">
                    <a href="{{ route('meal-plans.show', $mealPlan) }}" 
                       class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-blue-600 bg-blue-50 rounded-md hover:bg-blue-100 transition-colors duration-200">
                        <x-icon name="eye" class="w-3 h-3 mr-1" variant="outline" />
                        View
                    </a>
                    <a href="{{ route('meal-plans.index') }}" 
                       class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-gray-600 bg-gray-50 rounded-md hover:bg-gray-100 transition-colors duration-200">
                        <x-icon name="arrow-left" class="w-3 h-3 mr-1" variant="outline" />
                        Back to List
                    </a>
                </li>
            </ol>
        </nav>

        <!-- Enhanced Header Section -->
        <div class="mb-8 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
                <div class="flex-1">
                    <div class="flex items-center space-x-3 mb-2">
                        <div class="p-2 bg-green-100 rounded-lg">
                            <x-icon name="pencil-square" class="w-6 h-6 text-green-600" variant="outline" />
                        </div>
                        <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">Edit Meal Plan</h1>
                    </div>
                    <p class="text-gray-600 mb-3">Update your scheduled meal for {{ $mealPlan->scheduled_date->format('F j, Y') }}</p>
                    
                    <!-- Progress Indicator -->
                    <div class="flex items-center space-x-4 text-sm">
                        <div class="flex items-center text-gray-500">
                            <x-icon name="calendar" class="w-4 h-4 mr-1" variant="outline" />
                            <span>{{ $mealPlan->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="flex items-center text-gray-500">
                            <x-icon name="clock" class="w-4 h-4 mr-1" variant="outline" />
                            <span>Last updated {{ $mealPlan->updated_at->diffForHumans() }}</span>
                        </div>
                        @if($mealPlan->notes)
                            <div class="flex items-center text-blue-600">
                                <x-icon name="chat-bubble-left-ellipsis" class="w-4 h-4 mr-1" variant="outline" />
                                <span>Has notes</span>
                            </div>
                        @endif
                    </div>
                </div>
                
                <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-3 sm:space-y-0 sm:space-x-3">
                    <!-- Meal Type Badge -->
                    <div class="flex items-center space-x-2">
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium
                            @if($mealPlan->meal_type === 'breakfast') bg-yellow-100 text-yellow-800
                            @elseif($mealPlan->meal_type === 'lunch') bg-orange-100 text-orange-800
                            @elseif($mealPlan->meal_type === 'dinner') bg-blue-100 text-blue-800
                            @elseif($mealPlan->meal_type === 'snack') bg-purple-100 text-purple-800
                            @else bg-gray-100 text-gray-800 @endif">
                            @if($mealPlan->meal_type === 'breakfast') 🍳
                            @elseif($mealPlan->meal_type === 'lunch') 🍽️
                            @elseif($mealPlan->meal_type === 'dinner') 🍴
                            @elseif($mealPlan->meal_type === 'snack') 🍪
                            @else 🍽️ @endif
                            {{ ucfirst($mealPlan->meal_type) }}
                        </span>
                    </div>
                    
                    <!-- Completion Status -->
                    <div class="flex items-center space-x-2">
                        @if($mealPlan->is_completed)
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <x-icon name="check-circle" class="w-4 h-4 mr-1" variant="solid" />
                                Completed
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-gray-100 text-gray-600">
                                <x-icon name="clock" class="w-4 h-4 mr-1" variant="outline" />
                                Pending
                            </span>
                        @endif
                    </div>
                    
                    <!-- Servings Badge -->
                    @if($mealPlan->servings && $mealPlan->servings > 1)
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
                            <x-icon name="users" class="w-4 h-4 mr-1" variant="outline" />
                            {{ $mealPlan->servings }} servings
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <form action="{{ route('meal-plans.update', $mealPlan) }}" method="POST" id="editMealForm" novalidate>
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Form Column -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Enhanced Current Meal Info Card -->
                    <div class="bg-white shadow-sm rounded-xl border border-gray-200 hover:shadow-md transition-shadow duration-200">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-50 to-emerald-50">
                            <div class="flex items-center justify-between">
                                <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                                    <x-icon name="utensils" class="w-5 h-5 mr-2 text-green-600" variant="outline" />
                                    Current Meal Selection
                                </h2>
                                <span class="text-xs text-gray-500 bg-white px-2 py-1 rounded-full">Original</span>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="flex flex-col lg:flex-row lg:items-start space-y-4 lg:space-y-0 lg:space-x-6">
                                <!-- Meal Image -->
                                <div class="flex-shrink-0">
                                    @if($mealPlan->meal->image_url)
                                        <img src="{{ $mealPlan->meal->image_url }}" alt="{{ $mealPlan->meal->name }}" 
                                             class="w-24 h-24 lg:w-28 lg:h-28 object-cover rounded-xl shadow-sm border border-gray-200">
                                    @else
                                        <div class="w-24 h-24 lg:w-28 lg:h-28 bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl flex items-center justify-center shadow-sm border border-gray-200">
                                            <span class="text-3xl">🍽️</span>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Meal Details -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between mb-3">
                                        <div class="flex-1">
                                            <h3 class="text-xl font-bold text-gray-900 mb-1">{{ $mealPlan->meal->name }}</h3>
                                            @if($mealPlan->meal->cuisine_type)
                                                <span class="inline-block px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-md mb-2">
                                                    {{ $mealPlan->meal->cuisine_type }}
                                                </span>
                                            @endif
                                        </div>
                                        @if($mealPlan->meal->difficulty)
                                            <span class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-full
                                                @if($mealPlan->meal->difficulty === 'easy') bg-green-100 text-green-800
                                                @elseif($mealPlan->meal->difficulty === 'medium') bg-yellow-100 text-yellow-800
                                                @else bg-red-100 text-red-800 @endif">
                                                {{ ucfirst($mealPlan->meal->difficulty) }}
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <p class="text-sm text-gray-600 mb-4 leading-relaxed">{{ Str::limit($mealPlan->meal->description, 150) }}</p>
                                    
                                    <!-- Enhanced Nutritional Grid -->
                                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-4">
                                        <div class="bg-orange-50 rounded-lg p-3 text-center border border-orange-100">
                                            <div class="flex items-center justify-center mb-1">
                                                <x-icon name="fire" class="w-4 h-4 text-orange-500" variant="outline" />
                                            </div>
                                            <div class="text-lg font-bold text-orange-700">{{ $mealPlan->meal->nutritionalInfo->calories ?? 'N/A' }}</div>
                                            <div class="text-xs text-orange-600">Calories</div>
                                        </div>
                                        <div class="bg-green-50 rounded-lg p-3 text-center border border-green-100">
                                            <div class="flex items-center justify-center mb-1">
                                                <span class="font-semibold text-green-500">₱</span>
                                            </div>
                                            <div class="text-lg font-bold text-green-700">{{ $mealPlan->meal->cost }}</div>
                                            <div class="text-xs text-green-600">Cost</div>
                                        </div>
                                        <div class="bg-blue-50 rounded-lg p-3 text-center border border-blue-100">
                                            <div class="flex items-center justify-center mb-1">
                                                <x-icon name="clock" class="w-4 h-4 text-blue-500" variant="outline" />
                                            </div>
                                            <div class="text-lg font-bold text-blue-700">{{ $mealPlan->meal->recipe->total_time ?? 'N/A' }}</div>
                                            <div class="text-xs text-blue-600">Minutes</div>
                                        </div>
                                        <div class="bg-purple-50 rounded-lg p-3 text-center border border-purple-100">
                                            <div class="flex items-center justify-center mb-1">
                                                <x-icon name="users" class="w-4 h-4 text-purple-500" variant="outline" />
                                            </div>
                                            <div class="text-lg font-bold text-purple-700">{{ $mealPlan->servings ?? 1 }}</div>
                                            <div class="text-xs text-purple-600">Servings</div>
                                        </div>
                                    </div>
                                    
                                    <!-- Quick Action Buttons -->
                                    <div class="flex flex-wrap gap-2">
                                        <a href="{{ route('recipes.show', $mealPlan->meal) }}" 
                                           class="inline-flex items-center px-3 py-2 text-sm font-medium text-blue-700 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors duration-200">
                                            <x-icon name="book-open" class="w-4 h-4 mr-1" variant="outline" />
                                            View Recipe
                                        </a>
                                        @if($mealPlan->meal->nutritionalInfo)
                                            <button type="button" onclick="showNutritionModal()" 
                                                    class="inline-flex items-center px-3 py-2 text-sm font-medium text-green-700 bg-green-50 rounded-lg hover:bg-green-100 transition-colors duration-200">
                                                <x-icon name="chart-bar" class="w-4 h-4 mr-1" variant="outline" />
                                                Nutrition Facts
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Enhanced Form Fields Card -->
                    <div class="bg-white shadow-sm rounded-xl border border-gray-200 hover:shadow-md transition-shadow duration-300">
                        <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-green-50 to-emerald-50">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="p-2 bg-green-100 rounded-lg mr-3">
                                        <x-icon name="pencil-square" class="w-5 h-5 text-green-600" variant="outline" />
                                    </div>
                                    <div>
                                        <h2 class="text-lg font-semibold text-gray-900">Update Meal Details</h2>
                                        <p class="text-sm text-gray-600 mt-1">Modify your meal plan settings and preferences</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                                        <x-icon name="clock" class="w-3 h-3 mr-1" variant="outline" />
                                        Editing
                                    </span>
                                    <button type="button" onclick="showFormTips()" class="text-xs text-green-600 hover:text-green-700 font-medium">
                                        <x-icon name="question-mark-circle" class="w-4 h-4" variant="outline" />
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="p-6 space-y-8">
                            <!-- Enhanced Date and Meal Type Section -->
                            <div class="bg-gray-50 rounded-xl p-5 border border-gray-200">
                                <h3 class="text-sm font-semibold text-gray-800 mb-4 flex items-center">
                                    <x-icon name="calendar" class="w-4 h-4 mr-2 text-blue-500" variant="outline" />
                                    Schedule & Type
                                </h3>
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                    <!-- Enhanced Date Selection -->
                                    <div class="space-y-2">
                                        <label for="scheduled_date" class="block text-sm font-medium text-gray-700">
                                            <x-icon name="calendar-days" class="w-4 h-4 inline mr-1 text-green-500" variant="outline" />
                                            Scheduled Date
                                        </label>
                                        <div class="relative">
                                            <input type="date" id="scheduled_date" name="scheduled_date" required
                                                   class="block w-full pl-10 pr-4 py-3 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm transition-all duration-200 hover:border-gray-400 @error('scheduled_date') border-red-300 @enderror"
                                                   value="{{ old('scheduled_date', $mealPlan->scheduled_date->format('Y-m-d')) }}"
                                                   min="{{ now()->format('Y-m-d') }}">
                                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                <x-icon name="calendar" class="w-4 h-4 text-gray-400" variant="outline" />
                                            </div>
                                        </div>
                                        @error('scheduled_date')
                                            <p class="mt-1 text-sm text-red-600 flex items-center">
                                                <x-icon name="exclamation-circle" class="w-4 h-4 mr-1" variant="outline" />
                                                {{ $message }}
                                            </p>
                                        @enderror
                                        <p class="text-xs text-gray-500 flex items-center">
                                            <x-icon name="information-circle" class="w-3 h-3 mr-1" variant="outline" />
                                            Current: {{ $mealPlan->scheduled_date->format('F j, Y') }}
                                        </p>
                                    </div>

                                    <!-- Enhanced Meal Type Selection -->
                                    <div class="space-y-2">
                                        <label for="meal_type" class="block text-sm font-medium text-gray-700">
                                            <x-icon name="sun" class="w-4 h-4 inline mr-1 text-orange-500" variant="outline" />
                                            Meal Type
                                        </label>
                                        <div class="relative">
                                            <select id="meal_type" name="meal_type" required
                                                    class="block w-full pl-10 pr-8 py-3 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm transition-all duration-200 hover:border-gray-400 @error('meal_type') border-red-300 @enderror appearance-none">
                                                <option value="" class="text-gray-400">Select meal type</option>
                                                <option value="breakfast" {{ old('meal_type', $mealPlan->meal_type) == 'breakfast' ? 'selected' : '' }}>🍳 Breakfast (Morning meal)</option>
                                                <option value="lunch" {{ old('meal_type', $mealPlan->meal_type) == 'lunch' ? 'selected' : '' }}>🍽️ Lunch (Midday meal)</option>
                                                <option value="dinner" {{ old('meal_type', $mealPlan->meal_type) == 'dinner' ? 'selected' : '' }}>🍴 Dinner (Evening meal)</option>
                                                <option value="snack" {{ old('meal_type', $mealPlan->meal_type) == 'snack' ? 'selected' : '' }}>🍪 Snack (Light meal)</option>
                                            </select>
                                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                <x-icon name="utensils" class="w-4 h-4 text-gray-400" variant="outline" />
                                            </div>
                                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                                <x-icon name="chevron-up-down" class="w-4 h-4 text-gray-400" variant="outline" />
                                            </div>
                                        </div>
                                        @error('meal_type')
                                            <p class="mt-1 text-sm text-red-600 flex items-center">
                                                <x-icon name="exclamation-circle" class="w-4 h-4 mr-1" variant="outline" />
                                                {{ $message }}
                                            </p>
                                        @enderror
                                        <p class="text-xs text-gray-500 flex items-center">
                                            <x-icon name="information-circle" class="w-3 h-3 mr-1" variant="outline" />
                                            Current: {{ ucfirst($mealPlan->meal_type) }}
                                        </p>
                                    </div>
                                </div>
                                
                                <!-- Smart Time Suggestion -->
                                <div id="timeSmartSuggestion" class="hidden mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                    <div class="flex items-start">
                                        <x-icon name="light-bulb" class="w-4 h-4 mr-2 text-blue-500 mt-0.5" variant="outline" />
                                        <div class="text-sm text-blue-700">
                                            <p class="font-medium">Smart Suggestion</p>
                                            <p id="timeSuggestionText" class="text-blue-600"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Enhanced Meal Selection Section -->
                            <div class="bg-gray-50 rounded-xl p-5 border border-gray-200">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center">
                                        <x-icon name="magnifying-glass" class="w-5 h-5 mr-2 text-green-600" variant="outline" />
                                        <div>
                                            <h3 class="text-sm font-semibold text-gray-800">Select New Meal</h3>
                                            <p class="text-xs text-gray-600">Choose from {{ count($meals) }} available meals</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <button type="button" id="toggleFilters" onclick="toggleFilters()" 
                                                class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-100 rounded-md hover:bg-blue-200 transition-colors duration-200">
                                            <x-icon name="adjustments-horizontal" class="w-3 h-3 mr-1" variant="outline" />
                                            Filters
                                        </button>
                                        <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium bg-gray-200 text-gray-700 rounded-full">
                                            {{ count($meals) }} total
                                        </span>
                                    </div>
                                </div>
                                
                                <!-- Enhanced Smart Filters Panel -->
                                <div id="filtersPanel" class="hidden mb-6 p-4 bg-white rounded-lg border border-gray-200 shadow-sm">
                                    <div class="flex items-center justify-between mb-3">
                                        <h4 class="text-sm font-medium text-gray-800 flex items-center">
                                            <x-icon name="funnel" class="w-4 h-4 mr-1 text-purple-500" variant="outline" />
                                            Advanced Filters
                                        </h4>
                                        <button type="button" onclick="clearAllFilters()" 
                                                class="text-xs text-gray-500 hover:text-gray-700 font-medium">
                                            Clear all
                                        </button>
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-2">🍽️ Cuisine Type</label>
                                            <select id="cuisineFilter" class="block w-full px-3 py-2 text-sm border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                                <option value="">All Cuisines</option>
                                                <option value="filipino">🇵🇭 Filipino</option>
                                                <option value="asian">🥢 Asian</option>
                                                <option value="western">🍴 Western</option>
                                                <option value="mediterranean">🫒 Mediterranean</option>
                                                <option value="italian">🍝 Italian</option>
                                                <option value="mexican">🌮 Mexican</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-2">💰 Max Budget</label>
                                            <select id="costFilter" class="block w-full px-3 py-2 text-sm border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                                <option value="">Any Budget</option>
                                                <option value="50">Under ₱50</option>
                                                <option value="100">Under ₱100</option>
                                                <option value="200">Under ₱200</option>
                                                <option value="500">Under ₱500</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-2">⚡ Difficulty</label>
                                            <select id="difficultyFilter" class="block w-full px-3 py-2 text-sm border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                                <option value="">Any Level</option>
                                                <option value="easy">🟢 Easy</option>
                                                <option value="medium">🟡 Medium</option>
                                                <option value="hard">🔴 Hard</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-2">🔥 Max Calories</label>
                                            <select id="calorieFilter" class="block w-full px-3 py-2 text-sm border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                                <option value="">Any Amount</option>
                                                <option value="300">Under 300</option>
                                                <option value="500">Under 500</option>
                                                <option value="700">Under 700</option>
                                                <option value="1000">Under 1000</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mt-4 flex items-center justify-between">
                                        <div class="flex items-center space-x-2">
                                            <span id="filteredCount" class="text-sm text-purple-600 font-medium"></span>
                                            <div id="filterIndicators" class="flex items-center space-x-1"></div>
                                        </div>
                                        <button type="button" onclick="resetToRecommended()" 
                                                class="text-xs text-green-600 hover:text-green-700 font-medium">
                                            Show Recommended
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="relative group">
                                    <!-- Enhanced Select Dropdown with Search -->
                                    <div class="relative">
                                        <input type="text" id="mealSearch" placeholder="🔍 Type to search meals..." 
                                               class="block w-full pl-10 pr-10 py-3 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm transition-all duration-200 hover:border-gray-400 mb-2" 
                                               oninput="handleMealSearch(this.value)" />
                                        <select id="meal_id" name="meal_id" required
                                                class="block w-full pl-10 pr-10 py-3 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm transition-all duration-200 hover:border-gray-400 @error('meal_id') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                                                size="5">
                                            <option value="" class="text-gray-500">Select a meal from the list below...</option>
                                            @foreach($meals as $meal)
                                                <option value="{{ $meal->id }}" 
                                                        data-cost="{{ $meal->cost }}"
                                                        data-calories="{{ $meal->nutritionalInfo->calories ?? 'N/A' }}"
                                                        data-time="{{ $meal->recipe->total_time ?? 'N/A' }}"
                                                        data-description="{{ $meal->description }}"
                                                        data-image="{{ $meal->image_url ?? '' }}"
                                                        data-cuisine="{{ $meal->cuisine_type ?? 'N/A' }}"
                                                        data-difficulty="{{ $meal->difficulty ?? 'medium' }}"
                                                        data-name="{{ strtolower($meal->name) }}"
                                                        data-keywords="{{ strtolower($meal->name . ' ' . ($meal->cuisine_type ?? 'filipino') . ' ' . ($meal->description ?? '')) }}"
                                                        {{ old('meal_id', $mealPlan->meal_id) == $meal->id ? 'selected' : '' }}>
                                                    🍽️ {{ $meal->name }} - ₱{{ $meal->cost }} ({{ $meal->nutritionalInfo->calories ?? 'N/A' }} cal) • {{ $meal->cuisine_type ?? 'Filipino' }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <!-- Left Icon -->
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <x-icon name="utensils" class="w-4 h-4 text-gray-400 group-focus-within:text-green-500 transition-colors duration-200" variant="outline" />
                                    </div>
                                    
                                    <!-- Right Icon -->
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <x-icon name="chevron-up-down" class="w-4 h-4 text-gray-400 group-focus-within:text-green-500 transition-colors duration-200" variant="outline" />
                                    </div>
                                </div>
                                @error('meal_id')
                                    <div class="mt-2 p-3 bg-red-50 border border-red-200 rounded-lg">
                                        <div class="flex items-center">
                                            <x-icon name="exclamation-triangle" class="w-4 h-4 mr-2 text-red-500" variant="outline" />
                                            <p class="text-sm text-red-600 font-medium">{{ $message }}</p>
                                        </div>
                                        <p class="text-xs text-red-500 mt-1 ml-6">Please select a meal from the dropdown above to continue.</p>
                                    </div>
                                @enderror
                                
                                <!-- Meal Selection Help -->
                                <div class="mt-2 p-3 bg-blue-50 border border-blue-200 rounded-lg hidden" id="mealSelectionHelp">
                                    <div class="flex items-center">
                                        <x-icon name="information-circle" class="w-4 h-4 mr-2 text-blue-500" variant="outline" />
                                        <p class="text-sm text-blue-600 font-medium">Search Tips</p>
                                    </div>
                                    <ul class="text-xs text-blue-500 mt-1 ml-6 space-y-1">
                                        <li>• Type the meal name, cuisine type, or ingredients</li>
                                        <li>• Use keywords like "chicken", "vegetarian", "quick" for better results</li>
                                        <li>• Results are filtered based on your budget preferences</li>
                                    </ul>
                                </div>
                                
                                <!-- Enhanced Selected Meal Preview -->
                                <div id="mealPreview" class="hidden mt-4 p-5 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-xl shadow-sm">
                                    <div class="flex items-center mb-3">
                                        <x-icon name="eye" class="w-5 h-5 mr-2 text-green-600" variant="outline" />
                                        <h4 class="font-semibold text-green-900">Selected Meal Preview</h4>
                                    </div>
                                    <div id="previewContent" class="text-sm text-green-800">
                                        <!-- Dynamic content will be inserted here -->
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Notes Section -->
                            <div class="pt-4 border-t border-gray-200">
                                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                                    <x-icon name="pencil" class="w-4 h-4 inline mr-1 text-green-600" variant="outline" />
                                    Personal Notes
                                    <span class="text-gray-500 font-normal">(Optional)</span>
                                </label>
                                <div class="relative">
                                    <textarea id="notes" name="notes" rows="3" 
                                              placeholder="Add any personal notes about this meal plan (e.g., preparation reminders, dietary modifications, shopping notes...)" 
                                              class="block w-full px-4 py-3 border-gray-300 rounded-lg shadow-sm resize-none focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm transition-all duration-200 hover:border-gray-400 placeholder-gray-400">{{ old('notes', $mealPlan->notes ?? '') }}</textarea>
                                    <div class="absolute bottom-3 right-3 text-xs text-gray-400 pointer-events-none">
                                        <x-icon name="chat-bubble-left-ellipsis" class="w-4 h-4" variant="outline" />
                                    </div>
                                </div>
                                <p class="mt-1 text-xs text-gray-500 flex items-center">
                                    <x-icon name="information-circle" class="w-3 h-3 mr-1" variant="outline" />
                                    These notes are private and will help you remember important details about this meal.
                                </p>
                            </div>

                            <!-- Enhanced Meal Planning Options -->
                            <div class="bg-gray-50 rounded-xl p-5 border border-gray-200">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center">
                                        <x-icon name="cog-6-tooth" class="w-5 h-5 mr-2 text-purple-600" variant="outline" />
                                        <div>
                                            <h3 class="text-sm font-semibold text-gray-800">Meal Planning Options</h3>
                                            <p class="text-xs text-gray-600">Customize your meal preferences</p>
                                        </div>
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium bg-purple-100 text-purple-800 rounded-full">
                                        <x-icon name="sparkles" class="w-3 h-3 mr-1" variant="outline" />
                                        Advanced
                                    </span>
                                </div>
                                
                                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                                    <!-- Enhanced Serving Size -->
                                    <div class="bg-white rounded-lg p-4 border border-gray-200">
                                        <label for="servings" class="block text-sm font-medium text-gray-700 mb-2">
                                            <x-icon name="users" class="w-4 h-4 inline mr-1 text-blue-500" variant="outline" />
                                            Servings
                                        </label>
                                        <select id="servings" name="servings" 
                                                class="block w-full px-3 py-2.5 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm transition-all duration-200">
                                            <option value="1" {{ old('servings', $mealPlan->servings ?? 1) == 1 ? 'selected' : '' }}>👤 1 person</option>
                                            <option value="2" {{ old('servings', $mealPlan->servings ?? 1) == 2 ? 'selected' : '' }}>👥 2 people</option>
                                            <option value="3" {{ old('servings', $mealPlan->servings ?? 1) == 3 ? 'selected' : '' }}>👨‍👩‍👧 3 people</option>
                                            <option value="4" {{ old('servings', $mealPlan->servings ?? 1) == 4 ? 'selected' : '' }}>👨‍👩‍👧‍👦 4 people</option>
                                            <option value="5" {{ old('servings', $mealPlan->servings ?? 1) == 5 ? 'selected' : '' }}>👥 5+ people</option>
                                        </select>
                                        <p class="mt-1 text-xs text-gray-500">Adjusts ingredient quantities</p>
                                    </div>
                                    
                                    <!-- Enhanced Preparation Reminder -->
                                    <div class="bg-white rounded-lg p-4 border border-gray-200">
                                        <label for="prep_reminder" class="block text-sm font-medium text-gray-700 mb-2">
                                            <x-icon name="bell" class="w-4 h-4 inline mr-1 text-yellow-500" variant="outline" />
                                            Prep Reminder
                                        </label>
                                        <select id="prep_reminder" name="prep_reminder" 
                                                class="block w-full px-3 py-2.5 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm transition-all duration-200">
                                            <option value="none" {{ old('prep_reminder', $mealPlan->prep_reminder ?? 'none') == 'none' ? 'selected' : '' }}>🔕 No reminder</option>
                                            <option value="30min" {{ old('prep_reminder', $mealPlan->prep_reminder ?? 'none') == '30min' ? 'selected' : '' }}>⏰ 30 minutes before</option>
                                            <option value="1hour" {{ old('prep_reminder', $mealPlan->prep_reminder ?? 'none') == '1hour' ? 'selected' : '' }}>🕐 1 hour before</option>
                                            <option value="2hours" {{ old('prep_reminder', $mealPlan->prep_reminder ?? 'none') == '2hours' ? 'selected' : '' }}>🕑 2 hours before</option>
                                            <option value="1day" {{ old('prep_reminder', $mealPlan->prep_reminder ?? 'none') == '1day' ? 'selected' : '' }}>📅 1 day before</option>
                                        </select>
                                        <p class="mt-1 text-xs text-gray-500">Get notified when to start cooking</p>
                                    </div>
                                    
                                    <!-- New Priority Level -->
                                    <div class="bg-white rounded-lg p-4 border border-gray-200">
                                        <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">
                                            <x-icon name="flag" class="w-4 h-4 inline mr-1 text-red-500" variant="outline" />
                                            Priority Level
                                        </label>
                                        <select id="priority" name="priority" 
                                                class="block w-full px-3 py-2.5 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm transition-all duration-200">
                                            <option value="normal" {{ old('priority', $mealPlan->priority ?? 'normal') == 'normal' ? 'selected' : '' }}>📄 Normal</option>
                                            <option value="high" {{ old('priority', $mealPlan->priority ?? 'normal') == 'high' ? 'selected' : '' }}>🔺 High Priority</option>
                                            <option value="urgent" {{ old('priority', $mealPlan->priority ?? 'normal') == 'urgent' ? 'selected' : '' }}>🚨 Urgent</option>
                                        </select>
                                        <p class="mt-1 text-xs text-gray-500">How important is this meal?</p>
                                    </div>
                                </div>
                                
                                <!-- Meal Planning Tips -->
                                <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                    <div class="flex items-start">
                                        <x-icon name="light-bulb" class="w-4 h-4 mr-2 text-blue-500 mt-0.5" variant="outline" />
                                        <div class="text-sm text-blue-700">
                                            <p class="font-medium mb-1">Pro Tips for Better Meal Planning</p>
                                            <ul class="text-xs text-blue-600 space-y-1">
                                                <li>• Set reminders for complex dishes that need prep time</li>
                                                <li>• Adjust servings based on your household size</li>
                                                <li>• Mark priority meals to focus on what matters most</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Enhanced Sidebar -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Quick Actions -->
                    <div class="bg-white shadow-sm rounded-xl border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
                        </div>
                        <div class="p-6 space-y-3">
                            <a href="{{ route('meal-plans.show', $mealPlan) }}" 
                               class="inline-flex items-center w-full px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-50 border border-gray-300 rounded-lg hover:bg-gray-100 transition-all duration-200 group">
                                <x-icon name="eye" class="w-4 h-4 mr-2 group-hover:text-green-600" variant="outline" />
                                <span class="flex-1 text-left">View Meal Plan</span>
                                <x-icon name="external-link" class="w-3 h-3 text-gray-400" variant="outline" />
                            </a>
                            <a href="{{ route('recipes.show', $mealPlan->meal) }}" 
                               class="inline-flex items-center w-full px-4 py-2.5 text-sm font-medium text-blue-700 bg-blue-50 border border-blue-300 rounded-lg hover:bg-blue-100 transition-all duration-200 group">
                                <x-icon name="book-open" class="w-4 h-4 mr-2 group-hover:text-blue-800" variant="outline" />
                                <span class="flex-1 text-left">View Recipe</span>
                                <x-icon name="external-link" class="w-3 h-3 text-blue-400" variant="outline" />
                            </a>
                            <button type="button" onclick="duplicateMealPlan()" 
                                    class="inline-flex items-center w-full px-4 py-2.5 text-sm font-medium text-purple-700 bg-purple-50 border border-purple-300 rounded-lg hover:bg-purple-100 transition-all duration-200 group">
                                <x-icon name="document-duplicate" class="w-4 h-4 mr-2 group-hover:text-purple-800" variant="outline" />
                                <span class="flex-1 text-left">Duplicate to Another Date</span>
                            </button>
                            <a href="{{ route('meal-plans.create') }}" 
                               class="inline-flex items-center w-full px-4 py-2.5 text-sm font-medium text-green-700 bg-green-50 border border-green-300 rounded-lg hover:bg-green-100 transition-all duration-200 group">
                                <x-icon name="plus" class="w-4 h-4 mr-2 group-hover:text-green-800" variant="outline" />
                                <span class="flex-1 text-left">Add New Meal Plan</span>
                            </a>
                        </div>
                    </div>

                    <!-- Meal Information -->
                    <div class="bg-white shadow-sm rounded-xl border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Nutritional Information</h3>
                        </div>
                        <div class="p-6">
                            @if($mealPlan->meal->nutritionalInfo)
                                <div class="space-y-3">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">Calories</span>
                                        <span class="text-sm font-medium text-gray-900">{{ $mealPlan->meal->nutritionalInfo->calories ?? 'N/A' }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">Protein</span>
                                        <span class="text-sm font-medium text-gray-900">{{ $mealPlan->meal->nutritionalInfo->protein ?? 'N/A' }}g</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">Carbs</span>
                                        <span class="text-sm font-medium text-gray-900">{{ $mealPlan->meal->nutritionalInfo->carbs ?? 'N/A' }}g</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">Fat</span>
                                        <span class="text-sm font-medium text-gray-900">{{ $mealPlan->meal->nutritionalInfo->fats ?? 'N/A' }}g</span>
                                    </div>
                                </div>
                            @else
                                <p class="text-sm text-gray-500">No nutritional information available</p>
                            @endif
                        </div>
                    </div>

                    <!-- Form Actions (Sticky) -->
                    <div class="bg-white shadow-sm rounded-xl border border-gray-200 sticky top-8">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <x-icon name="check-circle" class="w-5 h-5 mr-2 text-green-600" variant="outline" />
                                Ready to Save?
                            </h3>
                            <p class="text-sm text-gray-600 mt-1">Review your changes before updating</p>
                        </div>
                        <div class="p-6 space-y-4">
                            <button type="submit" id="submitBtn"
                                    class="w-full inline-flex items-center justify-center px-4 py-3 border border-transparent text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98] disabled:opacity-75 disabled:cursor-not-allowed disabled:transform-none">
                                <x-icon name="check" class="w-4 h-4 mr-2" variant="outline" />
                                <span class="btn-text">Update Meal Plan</span>
                            </button>
                            
                            <div class="flex space-x-3">
                                <a href="{{ route('meal-plans.index') }}" 
                                   class="flex-1 inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                                    <x-icon name="arrow-left" class="w-4 h-4 mr-1" variant="outline" />
                                    Back to List
                                </a>
                                <button type="button" onclick="resetForm()" 
                                        class="flex-1 inline-flex items-center justify-center px-4 py-2 border border-orange-300 text-sm font-medium rounded-lg text-orange-700 bg-orange-50 hover:bg-orange-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors duration-200">
                                    <x-icon name="arrow-path" class="w-4 h-4 mr-1" variant="outline" />
                                    Reset
                                </button>
                            </div>
                            
                            <!-- Form Validation Status -->
                            <div id="formStatus" class="hidden">
                                <div class="p-3 bg-green-50 border border-green-200 rounded-lg">
                                    <div class="flex items-center">
                                        <x-icon name="check-circle" class="w-4 h-4 mr-2 text-green-500" variant="outline" />
                                        <p class="text-sm text-green-600 font-medium">All fields validated</p>
                                    </div>
                                    <p class="text-xs text-green-500 mt-1 ml-6">Your meal plan is ready to be updated.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Enhanced JavaScript with Loading Indicators and Better UX -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Import loading utilities if available
    const LoadingUtils = window.LoadingUtils || null;
    const LoadingMessages = window.LoadingMessages || null;
    
    // DOM Elements
    const form = document.getElementById('editMealForm');
    const mealSelect = document.getElementById('meal_id');
    const mealSearch = document.getElementById('mealSearch');
    const mealPreview = document.getElementById('mealPreview');
    const previewContent = document.getElementById('previewContent');
    const notesTextarea = document.getElementById('notes');
    const submitBtn = document.getElementById('submitBtn');
    const formStatus = document.getElementById('formStatus');
    const mealSelectionHelp = document.getElementById('mealSelectionHelp');
    const requiredFields = form.querySelectorAll('[required]');
    
    // Meal search functionality
    let originalOptions = [];
    if (mealSearch && mealSelect) {
        // Store original options
        Array.from(mealSelect.options).forEach(option => {
            if (option.value) {
                originalOptions.push({
                    element: option.cloneNode(true),
                    keywords: option.getAttribute('data-keywords') || '',
                    name: option.getAttribute('data-name') || ''
                });
            }
        });
        
        mealSearch.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            const selectElement = mealSelect;
            
            // Clear existing options except the first placeholder
            while (selectElement.options.length > 1) {
                selectElement.removeChild(selectElement.lastChild);
            }
            
            if (searchTerm === '') {
                // Restore all options
                originalOptions.forEach(optionData => {
                    selectElement.appendChild(optionData.element.cloneNode(true));
                });
                mealSelectionHelp.classList.add('hidden');
            } else {
                // Filter options
                const filteredOptions = originalOptions.filter(optionData => 
                    optionData.keywords.includes(searchTerm) || 
                    optionData.name.includes(searchTerm)
                );
                
                filteredOptions.forEach(optionData => {
                    selectElement.appendChild(optionData.element.cloneNode(true));
                });
                
                // Show help if no results
                if (filteredOptions.length === 0) {
                    mealSelectionHelp.classList.remove('hidden');
                } else {
                    mealSelectionHelp.classList.add('hidden');
                }
            }
        });
        
        // Show help on focus
        mealSearch.addEventListener('focus', () => {
            if (mealSearch.value.trim() === '') {
                mealSelectionHelp.classList.remove('hidden');
            }
        });
        
        mealSearch.addEventListener('blur', () => {
            setTimeout(() => mealSelectionHelp.classList.add('hidden'), 150);
        });
    }
    
    // Enhanced meal selection with improved preview
    if (mealSelect && mealPreview && previewContent) {
        mealSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            
            if (selectedOption.value) {
                const cost = selectedOption.getAttribute('data-cost');
                const calories = selectedOption.getAttribute('data-calories');
                const time = selectedOption.getAttribute('data-time');
                const description = selectedOption.getAttribute('data-description');
                const cuisine = selectedOption.getAttribute('data-cuisine');
                const image = selectedOption.getAttribute('data-image');
                
                let imageHtml = '';
                if (image) {
                    imageHtml = `<img src="${image}" alt="Meal image" class="w-16 h-16 object-cover rounded-lg mr-4 flex-shrink-0" loading="lazy">`;
                } else {
                    imageHtml = `<div class="w-16 h-16 bg-green-100 rounded-lg mr-4 flex-shrink-0 flex items-center justify-center text-2xl">🍽️</div>`;
                }
                
                previewContent.innerHTML = `
                    <div class="flex items-start space-x-4">
                        ${imageHtml}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between mb-2">
                                <h5 class="font-semibold text-green-900 text-base">${selectedOption.text.split(' - ')[0].replace('🍽️ ', '')}</h5>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    ${cuisine || 'Filipino'}
                                </span>
                            </div>
                            <p class="text-sm text-green-700 mb-3 leading-relaxed">${description || 'No description available.'}</p>
                            <div class="grid grid-cols-3 gap-4 text-sm">
                                <div class="flex items-center text-green-800">
                                    <span class="w-5 h-5 mr-1 text-lg">💰</span>
                                    <span class="font-medium">₱${cost}</span>
                                </div>
                                <div class="flex items-center text-green-800">
                                    <span class="w-5 h-5 mr-1 text-lg">🔥</span>
                                    <span class="font-medium">${calories} cal</span>
                                </div>
                                <div class="flex items-center text-green-800">
                                    <span class="w-5 h-5 mr-1 text-lg">⏱️</span>
                                    <span class="font-medium">${time}m</span>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                
                // Smooth reveal animation
                mealPreview.classList.remove('hidden');
                mealPreview.style.opacity = '0';
                mealPreview.style.transform = 'translateY(-10px)';
                requestAnimationFrame(() => {
                    mealPreview.style.transition = 'all 0.3s ease-out';
                    mealPreview.style.opacity = '1';
                    mealPreview.style.transform = 'translateY(0)';
                });
            } else {
                // Smooth hide animation
                mealPreview.style.transition = 'all 0.3s ease-in';
                mealPreview.style.opacity = '0';
                mealPreview.style.transform = 'translateY(-10px)';
                setTimeout(() => {
                    mealPreview.classList.add('hidden');
                }, 300);
            }
            
            // Validate form after selection
            validateForm();
        });
    }
    
    // Auto-resize textarea and character counter
    if (notesTextarea) {
        function autoResize() {
            notesTextarea.style.height = 'auto';
            notesTextarea.style.height = Math.min(notesTextarea.scrollHeight, 120) + 'px';
        }
        
        notesTextarea.addEventListener('input', autoResize);
        
        // Character counter
        const maxLength = 500;
        const counter = document.createElement('div');
        counter.className = 'text-xs text-gray-400 text-right mt-1 transition-colors duration-200';
        counter.id = 'notesCounter';
        notesTextarea.parentNode.appendChild(counter);
        
        function updateCounter() {
            const length = notesTextarea.value.length;
            const remaining = maxLength - length;
            counter.textContent = `${length}/${maxLength} characters`;
            
            if (remaining < 50) {
                counter.className = 'text-xs text-orange-500 text-right mt-1 transition-colors duration-200';
            } else if (remaining < 20) {
                counter.className = 'text-xs text-red-500 text-right mt-1 transition-colors duration-200';
            } else {
                counter.className = 'text-xs text-gray-400 text-right mt-1 transition-colors duration-200';
            }
        }
        
        notesTextarea.addEventListener('input', updateCounter);
        notesTextarea.setAttribute('maxlength', maxLength);
        updateCounter();
        autoResize();
    }
    
    // Enhanced form validation
    function validateField(field) {
        const isValid = field.value.trim() !== '';
        const parent = field.closest('div');
        let validationIcon = parent.querySelector('.validation-icon');
        
        // Remove existing icon
        if (validationIcon) {
            validationIcon.remove();
        }
        
        if (isValid && field.type !== 'search') {
            // Add success icon
            validationIcon = document.createElement('div');
            validationIcon.className = 'absolute right-8 top-1/2 transform -translate-y-1/2 validation-icon z-10';
            validationIcon.innerHTML = '<svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>';
            
            if (parent.style.position !== 'relative') {
                parent.style.position = 'relative';
            }
            parent.appendChild(validationIcon);
            
            field.classList.remove('border-red-300', 'focus:border-red-500', 'focus:ring-red-500');
            field.classList.add('border-green-300', 'focus:border-green-500');
        } else if (!isValid) {
            field.classList.remove('border-green-300', 'focus:border-green-500');
            field.classList.add('border-red-300', 'focus:border-red-500', 'focus:ring-red-500');
        }
        
        return isValid;
    }
    
    function validateForm() {
        let isFormValid = true;
        const validationResults = [];
        
        requiredFields.forEach(field => {
            const isValid = validateField(field);
            validationResults.push(isValid);
            if (!isValid) isFormValid = false;
        });
        
        // Update form status
        if (formStatus) {
            if (isFormValid && validationResults.length > 0) {
                formStatus.classList.remove('hidden');
            } else {
                formStatus.classList.add('hidden');
            }
        }
        
        return isFormValid;
    }
    
    // Bind validation events
    requiredFields.forEach(field => {
        field.addEventListener('blur', () => {
            validateField(field);
            validateForm();
        });
        
        field.addEventListener('input', () => {
            if (field.value.trim() !== '') {
                validateField(field);
            }
            validateForm();
        });
    });
    
    // Enhanced form submission with loading indicators
    if (form && submitBtn) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validate form
            if (!validateForm()) {
                // Show validation error
                const firstInvalidField = Array.from(requiredFields).find(field => !field.value.trim());
                if (firstInvalidField) {
                    firstInvalidField.focus();
                    firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
                
                showNotification('Please fill in all required fields.', 'error');
                return false;
            }
            
            // Show loading state using LoadingUtils if available
            let restoreButton;
            if (LoadingUtils) {
                restoreButton = LoadingUtils.showOnButton(submitBtn, {
                    text: 'Updating Meal Plan...',
                    size: 'medium'
                });
                
                // Show overlay with meal planning messages
                const overlay = LoadingUtils.showOverlay({
                    messages: LoadingMessages?.MEAL_PLANNING || ['Updating your meal plan...', 'Please wait...'],
                    variant: 'progress',
                    theme: 'light'
                });
                
                // Store overlay for cleanup if needed
                window.currentLoadingOverlay = overlay;
            } else {
                // Fallback loading state
                const originalContent = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = `
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Updating Meal Plan...
                `;
                
                restoreButton = () => {
                    submitBtn.innerHTML = originalContent;
                    submitBtn.disabled = false;
                };
            }
            
            // Submit form
            setTimeout(() => {
                form.submit();
            }, 300);
            
            // Failsafe: restore button after 15 seconds
            setTimeout(() => {
                if (restoreButton) restoreButton();
                if (window.currentLoadingOverlay) {
                    window.currentLoadingOverlay.hide();
                }
            }, 15000);
        });
    }
    
    // Reset form function
    window.resetForm = function() {
        const confirmed = confirm('Are you sure you want to reset all changes? This will restore the form to its original state.');
        if (confirmed) {
            form.reset();
            
            // Clear validation states
            document.querySelectorAll('.validation-icon').forEach(icon => icon.remove());
            document.querySelectorAll('input, select, textarea').forEach(field => {
                field.classList.remove('border-green-300', 'border-red-300', 'focus:border-green-500', 'focus:border-red-500', 'focus:ring-red-500');
            });
            
            // Hide preview and status
            if (mealPreview) mealPreview.classList.add('hidden');
            if (formStatus) formStatus.classList.add('hidden');
            
            // Reset search
            if (mealSearch) {
                mealSearch.value = '';
                mealSearch.dispatchEvent(new Event('input'));
            }
            
            // Reset textarea height
            if (notesTextarea) {
                notesTextarea.style.height = 'auto';
                document.getElementById('notesCounter')?.remove();
            }
            
            showNotification('Form has been reset to original values.', 'info');
        }
    };
    
    // Notification system
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        const colors = {
            error: 'bg-red-100 border-red-400 text-red-700',
            success: 'bg-green-100 border-green-400 text-green-700',
            info: 'bg-blue-100 border-blue-400 text-blue-700',
            warning: 'bg-yellow-100 border-yellow-400 text-yellow-700'
        };
        
        notification.className = `fixed top-4 right-4 ${colors[type]} px-4 py-3 rounded-lg shadow-lg z-50 flex items-center max-w-md transform translate-x-full transition-transform duration-300`;
        notification.innerHTML = `
            <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
            </svg>
            <span class="flex-1">${message}</span>
            <button onclick="this.parentElement.remove()" class="ml-2 text-current opacity-70 hover:opacity-100">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        `;
        
        document.body.appendChild(notification);
        
        // Slide in
        requestAnimationFrame(() => {
            notification.style.transform = 'translateX(0)';
        });
        
        // Auto remove
        setTimeout(() => {
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => notification.remove(), 300);
        }, 5000);
    }
    
    // Add subtle animations to form elements
    const formElements = document.querySelectorAll('input:not([type="search"]), select, textarea');
    formElements.forEach(element => {
        element.addEventListener('focus', function() {
            this.style.transform = 'scale(1.01)';
            this.style.transition = 'transform 0.2s ease-out';
        });
        
        element.addEventListener('blur', function() {
            this.style.transform = 'scale(1)';
        });
    });
    
    // Initialize filters
    const filtersPanel = document.getElementById('filtersPanel');
    if (filtersPanel) {
        // Bind filter events
        document.getElementById('cuisineFilter')?.addEventListener('change', applyFilters);
        document.getElementById('costFilter')?.addEventListener('change', applyFilters);
        document.getElementById('difficultyFilter')?.addEventListener('change', applyFilters);
        
        // Auto-hide filters on mobile after selection
        if (window.innerWidth < 768) {
            [document.getElementById('cuisineFilter'), 
             document.getElementById('costFilter'), 
             document.getElementById('difficultyFilter')].forEach(filter => {
                filter?.addEventListener('change', () => {
                    setTimeout(() => {
                        filtersPanel.classList.add('hidden');
                        document.getElementById('toggleFilters').innerHTML = '<svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"></path></svg>Filters';
                    }, 1000);
                });
            });
        }
    }
    
    // Auto-save functionality (draft mode)
    let autoSaveTimeout;
    const formFields = ['scheduled_date', 'meal_type', 'meal_id', 'notes', 'servings', 'prep_reminder'];
    
    formFields.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (field) {
            field.addEventListener('input', () => {
                clearTimeout(autoSaveTimeout);
                autoSaveTimeout = setTimeout(() => {
                    // Auto-save draft (could be implemented with AJAX)
                    console.log('Auto-saving draft...');
                }, 2000);
            });
        }
    });
    
    // Enhanced meal type suggestions and smart filtering
    const mealTypeSelect = document.getElementById('meal_type');
    if (mealTypeSelect) {
        mealTypeSelect.addEventListener('change', () => {
            updateMealSuggestions(mealTypeSelect.value);
            updateTimeSmartSuggestion(mealTypeSelect.value);
        });
    }
    
    // Initialize calorie filter if it exists
    const calorieFilter = document.getElementById('calorieFilter');
    if (calorieFilter) {
        calorieFilter.addEventListener('change', applyFilters);
    }
    
    function updateMealSuggestions(mealType) {
        // Filter meal options based on meal type appropriateness
        const now = new Date();
        const hour = now.getHours();
        
        // Add subtle hints based on time
        if (mealType === 'breakfast' && hour > 11) {
            showNotification('Tip: It\'s past typical breakfast time. Consider lunch instead?', 'info', 3000);
        } else if (mealType === 'lunch' && (hour < 10 || hour > 15)) {
            showNotification('Tip: Consider if lunch is the right meal type for this time.', 'info', 3000);
        } else if (mealType === 'dinner' && hour < 16) {
            showNotification('Tip: Planning dinner early? Great for meal prep!', 'success', 3000);
        }
    }
    
    // Keyboard shortcuts
    document.addEventListener('keydown', (e) => {
        // Ctrl/Cmd + S to save
        if ((e.ctrlKey || e.metaKey) && e.key === 's') {
            e.preventDefault();
            form.dispatchEvent(new Event('submit'));
        }
        
        // Ctrl/Cmd + / to focus search
        if ((e.ctrlKey || e.metaKey) && e.key === '/') {
            e.preventDefault();
            mealSearch?.focus();
        }
    });
    
    // Initial validation
    validateForm();
    
    // Show success message if form was submitted successfully
    if (window.location.search.includes('success')) {
        showNotification('Meal plan updated successfully!', 'success');
    }
    
    // Enhanced notification function with duration support
    function showNotification(message, type = 'info', duration = 5000) {
        const notification = document.createElement('div');
        const colors = {
            error: 'bg-red-100 border-red-400 text-red-700',
            success: 'bg-green-100 border-green-400 text-green-700',
            info: 'bg-blue-100 border-blue-400 text-blue-700',
            warning: 'bg-yellow-100 border-yellow-400 text-yellow-700'
        };
        
        notification.className = `fixed top-4 right-4 ${colors[type]} px-4 py-3 rounded-lg shadow-lg z-50 flex items-center max-w-md transform translate-x-full transition-transform duration-300`;
        notification.innerHTML = `
            <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
            </svg>
            <span class="flex-1">${message}</span>
            <button onclick="this.parentElement.remove()" class="ml-2 text-current opacity-70 hover:opacity-100">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        `;
        
        document.body.appendChild(notification);
        
        // Slide in
        requestAnimationFrame(() => {
            notification.style.transform = 'translateX(0)';
        });
        
        // Auto remove
        setTimeout(() => {
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => notification.remove(), 300);
        }, duration);
    }
    
    // Make showNotification globally available
    window.showNotification = showNotification;
});

// Enhanced JavaScript functionality (global functions)

// Smart suggestions functionality
function selectSuggestedMeal(mealId, mealName) {
    window.showLoading('Selecting meal...');
    
    const mealSelect = document.getElementById('meal_id');
    if (mealSelect) {
        // Find and select the suggested meal
        const option = mealSelect.querySelector(`option[value="${mealId}"]`);
        if (option) {
            mealSelect.value = mealId;
            mealSelect.dispatchEvent(new Event('change'));
            
            // Show success feedback
            setTimeout(() => {
                window.hideLoading();
                if (window.showNotification) {
                    window.showNotification(`Selected: ${mealName}`, 'success');
                }
            }, 300);
            
            // Scroll to meal preview
            const preview = document.getElementById('mealPreview');
            if (preview) {
                preview.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
        }
    }
}

function showMoreSuggestions() {
    // Focus on meal selection dropdown
    const mealSelect = document.getElementById('meal_id');
    if (mealSelect) {
        mealSelect.focus();
        mealSelect.click();
    }
}

function duplicateMealPlan() {
    // Show simple notification for now
    if (window.showNotification) {
        window.showNotification('Meal plan duplication feature coming soon!', 'info');
    }
}

// Filters functionality
function toggleFilters() {
    if (window.showLoadingDelayed) {
        window.showLoadingDelayed('Loading filters...', 100);
    }
    
    const panel = document.getElementById('filtersPanel');
    const button = document.getElementById('toggleFilters');
    
    setTimeout(() => {
        if (panel && panel.classList.contains('hidden')) {
            panel.classList.remove('hidden');
            if (button) {
                button.innerHTML = '<svg class="w-3 h-3 inline mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"></path></svg>Hide';
            }
        } else if (panel) {
            panel.classList.add('hidden');
            if (button) {
                button.innerHTML = '<svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"></path></svg>Filters';
            }
        }
        if (window.hideLoading) window.hideLoading();
    }, 150);
}

function clearAllFilters() {
    window.showLoading('Clearing filters...');
    
    const cuisine = document.getElementById('cuisineFilter');
    const cost = document.getElementById('costFilter');
    const difficulty = document.getElementById('difficultyFilter');
    const calorie = document.getElementById('calorieFilter');
    
    if (cuisine) cuisine.value = '';
    if (cost) cost.value = '';
    if (difficulty) difficulty.value = '';
    if (calorie) calorie.value = '';
    
    setTimeout(() => {
        applyFilters();
    }, 200);
}

function applyFilters() {
    const cuisine = document.getElementById('cuisineFilter')?.value || '';
    const maxCost = document.getElementById('costFilter')?.value || '';
    const difficulty = document.getElementById('difficultyFilter')?.value || '';
    
    const mealSelect = document.getElementById('meal_id');
    if (!mealSelect) return;
    
    const options = mealSelect.querySelectorAll('option[value]:not([value=""])');
    let visibleCount = 0;
    
    options.forEach(option => {
        let show = true;
        
        if (cuisine && option.dataset.cuisine && !option.dataset.cuisine.toLowerCase().includes(cuisine.toLowerCase())) {
            show = false;
        }
        
        if (maxCost && option.dataset.cost && parseFloat(option.dataset.cost) > parseFloat(maxCost)) {
            show = false;
        }
        
        if (difficulty && option.dataset.difficulty && option.dataset.difficulty !== difficulty) {
            show = false;
        }
        
        option.style.display = show ? '' : 'none';
        if (show) visibleCount++;
    });
    
    const countElement = document.getElementById('filteredCount');
    if (countElement) {
        countElement.textContent = `${visibleCount} meals match filters`;
    }
}

function showNutritionModal() {
    // Show detailed nutrition information modal
    if (window.StudEatsModal) {
        StudEatsModal.show('nutritionModal', {
            title: 'Detailed Nutrition Information',
            size: 'lg'
        });
    } else if (window.showNotification) {
        window.showNotification('Detailed nutrition modal coming soon!', 'info');
    }
}

function showFormTips() {
    // Show helpful tips about form usage
    if (window.showNotification) {
        window.showNotification('💡 Use filters to narrow down meal options. Set reminders for complex dishes!', 'info', 4000);
    }
}

function resetToRecommended() {
    // Clear filters and show recommended meals based on meal type
    clearAllFilters();
    
    const mealType = document.getElementById('meal_type').value;
    if (mealType === 'breakfast') {
        document.getElementById('costFilter').value = '100'; // Budget breakfast
        document.getElementById('difficultyFilter').value = 'easy'; // Easy morning prep
    } else if (mealType === 'lunch') {
        document.getElementById('costFilter').value = '200'; // Moderate lunch budget
    } else if (mealType === 'dinner') {
        document.getElementById('cuisineFilter').value = 'filipino'; // Traditional dinner
    }
    
    applyFilters();
    if (window.showNotification) {
        window.showNotification(`Showing recommended ${mealType || 'meals'}`, 'success');
    }
}

// Enhanced filter functionality with calories support
function applyFilters() {
    // Show loading for filter operations
    window.showLoadingDelayed('Filtering meals...', 200);
    
    const cuisine = document.getElementById('cuisineFilter')?.value || '';
    const maxCost = document.getElementById('costFilter')?.value || '';
    const difficulty = document.getElementById('difficultyFilter')?.value || '';
    const maxCalories = document.getElementById('calorieFilter')?.value || '';
    
    const mealSelect = document.getElementById('meal_id');
    if (!mealSelect) {
        window.hideLoading();
        return;
    }
    
    const options = mealSelect.querySelectorAll('option[value]:not([value=""])');
    let visibleCount = 0;
    const activeFilters = [];
    
    options.forEach(option => {
        let show = true;
        
        if (cuisine && option.dataset.cuisine && !option.dataset.cuisine.toLowerCase().includes(cuisine.toLowerCase())) {
            show = false;
        }
        
        if (maxCost && option.dataset.cost && parseFloat(option.dataset.cost) > parseFloat(maxCost)) {
            show = false;
        }
        
        if (difficulty && option.dataset.difficulty && option.dataset.difficulty !== difficulty) {
            show = false;
        }
        
        if (maxCalories && option.dataset.calories && option.dataset.calories !== 'N/A' && parseFloat(option.dataset.calories) > parseFloat(maxCalories)) {
            show = false;
        }
        
        option.style.display = show ? '' : 'none';
        if (show) visibleCount++;
    });
    
    // Update filter indicators
    if (cuisine) activeFilters.push(`🍽️ ${cuisine}`);
    if (maxCost) activeFilters.push(`💰 ≤₱${maxCost}`);
    if (difficulty) activeFilters.push(`⚡ ${difficulty}`);
    if (maxCalories) activeFilters.push(`🔥 ≤${maxCalories}cal`);
    
    const countElement = document.getElementById('filteredCount');
    const indicatorsElement = document.getElementById('filterIndicators');
    
    if (countElement) {
        countElement.textContent = `${visibleCount} meals found`;
    }
    
    if (indicatorsElement) {
        indicatorsElement.innerHTML = activeFilters.map(filter => 
            `<span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-purple-100 text-purple-800 rounded-full">${filter}</span>`
        ).join('');
    }
    
    // Hide loading after filter operation completes
    setTimeout(() => {
        window.clearLoadingTimeout();
        window.hideLoading();
    }, 100);
}

// Smart time-based suggestions
function updateTimeSmartSuggestion(mealType) {
    const suggestionElement = document.getElementById('timeSmartSuggestion');
    const textElement = document.getElementById('timeSuggestionText');
    
    if (!suggestionElement || !textElement) return;
    
    const now = new Date();
    const hour = now.getHours();
    let suggestionText = '';
    
    if (mealType === 'breakfast' && hour > 11) {
        suggestionText = "It's past typical breakfast time. Consider switching to lunch or a light brunch option.";
        suggestionElement.classList.remove('hidden');
    } else if (mealType === 'lunch' && (hour < 10 || hour > 15)) {
        suggestionText = "Consider if lunch is the right meal type for this time of day.";
        suggestionElement.classList.remove('hidden');
    } else if (mealType === 'dinner' && hour < 16) {
        suggestionText = "Planning dinner early? Perfect for meal prep! Consider easy-to-reheat options.";
        suggestionElement.classList.remove('hidden');
    } else if (mealType === 'snack') {
        suggestionText = "Snacks are great anytime! Consider healthy options for sustained energy.";
        suggestionElement.classList.remove('hidden');
    } else {
        suggestionElement.classList.add('hidden');
    }
    
    textElement.textContent = suggestionText;
}

// Comprehensive Global Loading System
function initializeGlobalLoadingSystem() {
    // Create loading overlay if it doesn't exist
    if (!document.getElementById('globalLoadingOverlay')) {
        const loadingOverlay = document.createElement('div');
        loadingOverlay.id = 'globalLoadingOverlay';
        loadingOverlay.className = 'fixed inset-0 flex items-center justify-center z-50 pointer-events-none';
        loadingOverlay.style.cssText = `
            opacity: 0;
            background: transparent;
            transition: opacity 0.4s cubic-bezier(0.4, 0.0, 0.2, 1);
        `;
        
        // Create ultra-minimalist loading animation
        loadingOverlay.innerHTML = `
            <div class="loading-container" style="
                position: relative;
                width: 60px;
                height: 60px;
                opacity: 0;
                transform: scale(0.8);
                transition: all 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            ">
                <!-- Primary Ring -->
                <div class="loading-ring loading-ring-primary" style="
                    position: absolute;
                    width: 60px;
                    height: 60px;
                    border: 3px solid;
                    border-color: transparent;
                    border-radius: 50%;
                    animation: minimal-spin 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
                "></div>
                
                <!-- Secondary Ring -->
                <div class="loading-ring loading-ring-secondary" style="
                    position: absolute;
                    width: 40px;
                    height: 40px;
                    top: 10px;
                    left: 10px;
                    border: 2px solid;
                    border-color: transparent;
                    border-radius: 50%;
                    animation: minimal-spin 0.8s cubic-bezier(0.5, 0, 0.5, 1) infinite reverse;
                "></div>
                
                <!-- Center Dot -->
                <div class="loading-center" style="
                    position: absolute;
                    width: 8px;
                    height: 8px;
                    top: 26px;
                    left: 26px;
                    border-radius: 50%;
                    transform: scale(0);
                    animation: minimal-pulse 1.5s cubic-bezier(0.4, 0, 0.6, 1) infinite;
                "></div>
            </div>
            
            <!-- Hidden accessible text -->
            <span class="sr-only" id="loadingMessage">Loading content, please wait</span>
            
            <style>
                @keyframes minimal-spin {
                    0% { transform: rotate(0deg); }
                    100% { transform: rotate(360deg); }
                }
                
                @keyframes minimal-pulse {
                    0%, 100% { transform: scale(0); opacity: 0; }
                    50% { transform: scale(1); opacity: 1; }
                }
                
                /* System color adaptation */
                @media (prefers-color-scheme: light) {
                    .loading-ring-primary {
                        border-top-color: color-mix(in srgb, AccentColor 80%, #10b981) !important;
                        border-right-color: color-mix(in srgb, AccentColor 40%, #10b981) !important;
                    }
                    .loading-ring-secondary {
                        border-bottom-color: color-mix(in srgb, AccentColor 60%, #10b981) !important;
                        border-left-color: color-mix(in srgb, AccentColor 20%, #10b981) !important;
                    }
                    .loading-center {
                        background: color-mix(in srgb, AccentColor 90%, #10b981) !important;
                    }
                }
                
                @media (prefers-color-scheme: dark) {
                    .loading-ring-primary {
                        border-top-color: color-mix(in srgb, AccentColor 70%, #34d399) !important;
                        border-right-color: color-mix(in srgb, AccentColor 35%, #34d399) !important;
                    }
                    .loading-ring-secondary {
                        border-bottom-color: color-mix(in srgb, AccentColor 55%, #34d399) !important;
                        border-left-color: color-mix(in srgb, AccentColor 25%, #34d399) !important;
                    }
                    .loading-center {
                        background: color-mix(in srgb, AccentColor 80%, #34d399) !important;
                    }
                }
                
                /* Fallback for browsers without color-mix support */
                @supports not (color: color-mix(in srgb, red, blue)) {
                    .loading-ring-primary {
                        border-top-color: #10b981 !important;
                        border-right-color: rgba(16, 185, 129, 0.4) !important;
                    }
                    .loading-ring-secondary {
                        border-bottom-color: rgba(16, 185, 129, 0.6) !important;
                        border-left-color: rgba(16, 185, 129, 0.2) !important;
                    }
                    .loading-center {
                        background: #10b981 !important;
                    }
                }
                
                /* High contrast mode support */
                @media (prefers-contrast: high) {
                    .loading-ring-primary {
                        border-top-color: #059669 !important;
                        border-right-color: #059669 !important;
                        border-width: 4px !important;
                    }
                    .loading-ring-secondary {
                        border-bottom-color: #047857 !important;
                        border-left-color: #047857 !important;
                        border-width: 3px !important;
                    }
                    .loading-center {
                        background: #065f46 !important;
                        width: 10px !important;
                        height: 10px !important;
                        top: 25px !important;
                        left: 25px !important;
                    }
                }
                
                /* Spin animation utility */
                @keyframes spin {
                    from { transform: rotate(0deg); }
                    to { transform: rotate(360deg); }
                }
                
                .animate-spin {
                    animation: spin 1s linear infinite;
                }
                
                /* Reduced motion support */
                @media (prefers-reduced-motion: reduce) {
                    .loading-ring {
                        animation: minimal-pulse 2s ease-in-out infinite !important;
                    }
                    .loading-center {
                        animation: none !important;
                        transform: scale(1) !important;
                        opacity: 1 !important;
                    }
                    .animate-spin {
                        animation: none !important;
                        transform: none !important;
                    }
                }
            </style>
        `;
        document.body.appendChild(loadingOverlay);
    }
    
    // Global loading functions
    window.showLoading = function(message = 'Loading...') {
        const overlay = document.getElementById('globalLoadingOverlay');
        const messageElement = document.getElementById('loadingMessage');
        if (overlay && messageElement) {
            messageElement.textContent = message;
            
            // Start transition from transparent
            overlay.style.pointerEvents = 'auto';
            overlay.style.opacity = '0';
            
            // Smooth transition to visible state
            requestAnimationFrame(() => {
                overlay.style.opacity = '1';
                
                // Animate loading container
                const container = overlay.querySelector('.loading-container');
                if (container) {
                    setTimeout(() => {
                        container.style.opacity = '1';
                        container.style.transform = 'scale(1)';
                    }, 100);
                }
            });
        }
    };
    
    window.hideLoading = function() {
        const overlay = document.getElementById('globalLoadingOverlay');
        if (overlay) {
            const container = overlay.querySelector('.loading-container');
            
            // Animate container out first
            if (container) {
                container.style.opacity = '0';
                container.style.transform = 'scale(0.8)';
            }
            
            // Then fade out overlay
            setTimeout(() => {
                overlay.style.opacity = '0';
                
                // Remove pointer events after fade
                setTimeout(() => {
                    overlay.style.pointerEvents = 'none';
                    
                    // Reset container for next time
                    if (container) {
                        container.style.opacity = '0';
                        container.style.transform = 'scale(0.8)';
                    }
                }, 400);
            }, 200);
        }
    };
    
    // Auto-show loading for slow operations with timeout management
    let loadingTimeouts = new Set();
    
    window.showLoadingDelayed = function(message = 'Processing...', delay = 500) {
        const timeoutId = setTimeout(() => {
            window.showLoading(message);
            loadingTimeouts.delete(timeoutId);
        }, delay);
        loadingTimeouts.add(timeoutId);
        return timeoutId;
    };
    
    window.clearAllLoadingTimeouts = function() {
        loadingTimeouts.forEach(timeoutId => clearTimeout(timeoutId));
        loadingTimeouts.clear();
    };
    
    // Enhanced form submission monitoring with spin animation
    document.addEventListener('submit', function(e) {
        const form = e.target;
        if (form.tagName === 'FORM') {
            const submitButton = form.querySelector('button[type="submit"], input[type="submit"]');
            if (submitButton) {
                submitButton.disabled = true;
                const originalText = submitButton.textContent;
                const originalHTML = submitButton.innerHTML;
                
                // Add spinning icon and processing text
                submitButton.innerHTML = `
                    <svg class="mr-2 h-4 w-4 animate-spin" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8h8a8 8 0 01-8 8V12H4z"></path>
                    </svg>
                    Processing...
                `;
                
                // Style the button for processing state
                submitButton.classList.add('opacity-75', 'cursor-not-allowed');
                submitButton.classList.remove('hover:bg-green-700');
                
                // Store original content for potential restoration
                submitButton.setAttribute('data-original-html', originalHTML);
                submitButton.setAttribute('data-original-text', originalText);
            }
            window.showLoadingDelayed('Saving your changes...', 200);
        }
    });
    
    // Enhanced navigation monitoring
    document.addEventListener('click', function(e) {
        const link = e.target.closest('a[href]');
        if (link && !link.getAttribute('href').startsWith('#') && 
            !link.getAttribute('href').startsWith('javascript:') &&
            !link.getAttribute('href').startsWith('mailto:') &&
            !link.getAttribute('href').startsWith('tel:')) {
            
            // Check if it's an external link
            const isExternal = link.hostname && link.hostname !== window.location.hostname;
            const message = isExternal ? 'Opening external link...' : 'Navigating to page...';
            
            window.showLoadingDelayed(message, 300);
        }
    });
    
    // Page lifecycle management
    window.addEventListener('beforeunload', function() {
        window.clearAllLoadingTimeouts();
        window.hideLoading();
    });
    
    window.addEventListener('pagehide', function() {
        window.clearAllLoadingTimeouts();
        window.hideLoading();
    });
    
    // Auto-hide loading as failsafe (prevent stuck loading)
    let autoHideTimeout;
    const originalShowLoading = window.showLoading;
    window.showLoading = function(message) {
        originalShowLoading(message);
        clearTimeout(autoHideTimeout);
        autoHideTimeout = setTimeout(() => {
            console.warn('Auto-hiding stuck loading overlay');
            window.hideLoading();
        }, 15000); // Auto-hide after 15 seconds
    };
    
    // Enhanced network request monitoring
    if (window.fetch) {
        const originalFetch = window.fetch;
        window.fetch = function(...args) {
            const loadingId = window.showLoadingDelayed('Loading data...', 300);
            return originalFetch.apply(this, arguments)
                .finally(() => {
                    clearTimeout(loadingId);
                    window.hideLoading();
                });
        };
    }
    
    // XMLHttpRequest monitoring for AJAX calls
    if (window.XMLHttpRequest) {
        const originalXHROpen = XMLHttpRequest.prototype.open;
        XMLHttpRequest.prototype.open = function(...args) {
            let loadingId;
            this.addEventListener('loadstart', () => {
                loadingId = window.showLoadingDelayed('Processing request...', 400);
            });
            this.addEventListener('loadend', () => {
                if (loadingId) clearTimeout(loadingId);
                window.hideLoading();
            });
            return originalXHROpen.apply(this, arguments);
        };
    }
}

// Function to restore button state
function restoreButtonState(button) {
    if (button) {
        const originalHTML = button.getAttribute('data-original-html');
        const originalText = button.getAttribute('data-original-text');
        
        if (originalHTML) {
            button.innerHTML = originalHTML;
        } else if (originalText) {
            button.textContent = originalText;
        }
        
        button.disabled = false;
        button.classList.remove('opacity-75', 'cursor-not-allowed');
        button.classList.add('hover:bg-green-700');
        button.removeAttribute('data-original-html');
        button.removeAttribute('data-original-text');
    }
}

// Enhanced meal search with loading
function handleMealSearch(searchTerm) {
    window.showLoadingDelayed('Searching meals...', 200);
    
    const mealSelect = document.getElementById('meal_id');
    if (!mealSelect) {
        window.hideLoading();
        return;
    }
    
    const options = mealSelect.querySelectorAll('option');
    let visibleCount = 0;
    
    setTimeout(() => {
        options.forEach(option => {
            if (option.value === '') {
                option.style.display = '';
                return;
            }
            
            const keywords = option.dataset.keywords || '';
            const name = option.dataset.name || '';
            const isVisible = !searchTerm || 
                            keywords.toLowerCase().includes(searchTerm.toLowerCase()) ||
                            name.toLowerCase().includes(searchTerm.toLowerCase());
            
            option.style.display = isVisible ? '' : 'none';
            if (isVisible && option.value) visibleCount++;
        });
        
        // Update search results feedback
        const preview = document.getElementById('mealPreview');
        if (preview && searchTerm) {
            if (visibleCount === 0) {
                preview.innerHTML = `
                    <div class="text-center text-gray-500 py-4">
                        <div class="text-4xl mb-2">🔍</div>
                        <p>No meals found for "${searchTerm}"</p>
                        <p class="text-sm">Try different keywords or clear filters</p>
                    </div>
                `;
                preview.classList.remove('hidden');
            } else {
                preview.innerHTML = `
                    <div class="text-center text-green-600 py-2">
                        <p class="font-medium">${visibleCount} meal${visibleCount !== 1 ? 's' : ''} found for "${searchTerm}"</p>
                    </div>
                `;
                preview.classList.remove('hidden');
            }
        } else if (preview && !searchTerm) {
            preview.classList.add('hidden');
        }
        
        window.hideLoading();
    }, 150);
}

// Enhanced system integration and accessibility
function enhanceLoadingAccessibility() {
    const overlay = document.getElementById('globalLoadingOverlay');
    if (!overlay) return;
    
    // Add ARIA attributes for accessibility
    overlay.setAttribute('role', 'dialog');
    overlay.setAttribute('aria-label', 'Loading content');
    overlay.setAttribute('aria-live', 'polite');
    
    // Detect system accent color if available
    if (window.CSS && CSS.supports('color', 'AccentColor')) {
        console.log('System accent color support detected');
    }
    
    // Listen for color scheme changes
    const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
    const handleColorSchemeChange = (e) => {
        console.log('Color scheme changed to:', e.matches ? 'dark' : 'light');
        // Colors will automatically adapt via CSS media queries
    };
    
    mediaQuery.addListener(handleColorSchemeChange);
    
    // Listen for reduced motion preference changes
    const motionQuery = window.matchMedia('(prefers-reduced-motion: reduce)');
    const handleMotionChange = (e) => {
        console.log('Motion preference:', e.matches ? 'reduced' : 'normal');
    };
    
    motionQuery.addListener(handleMotionChange);
    
    // Add keyboard support for loading overlay
    overlay.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            // Allow escape to dismiss loading after 3 seconds minimum
            setTimeout(() => {
                if (overlay.style.opacity === '1') {
                    window.hideLoading();
                }
            }, 3000);
        }
    });
}

// Initialize loading system when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    initializeGlobalLoadingSystem();
    enhanceLoadingAccessibility();
});

</script>
@endsection

