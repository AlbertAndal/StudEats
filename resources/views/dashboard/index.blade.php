@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Welcome back, {{ $user->name }}! 👋</h1>
        <p class="mt-2 text-gray-600">Here's your personalized meal planning dashboard</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white shadow rounded-lg p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Today's Meals</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $todayMeals->count() }}</p>
                </div>
                <div class="text-green-600 bg-green-100 p-2 rounded-lg">
                    <x-icon name="clock" class="w-8 h-8" variant="outline" aria-label="Today's meals icon" />
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Daily Budget</p>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ $user->daily_budget ? '₱' . number_format($user->daily_budget, 2) : 'Not set' }}
                    </p>
                </div>
                <div class="text-blue-600 bg-blue-100 p-2 rounded-lg">
                    <x-icon name="currency-dollar" class="w-8 h-8" variant="outline" aria-label="Daily budget icon" />
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Weekly Calories</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($weeklySummary['totalCalories']) }}</p>
                </div>
                <div class="text-purple-600 bg-purple-100 p-2 rounded-lg">
                    <x-icon name="bolt" class="w-8 h-8" variant="outline" aria-label="Weekly calories icon" />
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Weekly Meals</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $weeklySummary['mealCount'] }}</p>
                </div>
                <div class="text-orange-600 bg-orange-100 p-2 rounded-lg">
                    <x-icon name="clipboard-document-list" class="w-8 h-8" variant="outline" aria-label="Weekly meals icon" />
                </div>
            </div>
        </div>
    </div>

    <!-- BMI Status Card -->
    @if(isset($bmiStatus) && $bmiStatus['bmi'])
    <div class="bg-white shadow rounded-lg p-6 mb-8">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center">
                <div class="bg-blue-100 p-2 rounded-lg mr-4">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v4"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Your Health Profile</h3>
                    <p class="text-sm text-gray-600">Personalized meal recommendations based on your BMI</p>
                </div>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- BMI Value -->
            <div class="text-center">
                <div class="text-2xl font-bold text-gray-900">{{ $bmiStatus['bmi'] }}</div>
                <div class="text-sm text-gray-600">BMI Score</div>
            </div>
            
            <!-- BMI Category -->
            <div class="text-center">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium border {{ $bmiStatus['colors'][0] }} {{ $bmiStatus['colors'][1] }} {{ $bmiStatus['colors'][2] }}">
                    {{ $bmiStatus['category_label'] }}
                </span>
                <div class="text-sm text-gray-600 mt-1">Category</div>
            </div>
            
            <!-- Daily Calories -->
            <div class="text-center">
                <div class="text-2xl font-bold text-green-600">{{ number_format($bmiStatus['daily_calories']) }}</div>
                <div class="text-sm text-gray-600">Daily Calories</div>
            </div>
            
            <!-- Calorie Adjustment -->
            <div class="text-center">
                <div class="text-2xl font-bold {{ $bmiStatus['calorie_multiplier'] > 1 ? 'text-blue-600' : ($bmiStatus['calorie_multiplier'] < 1 ? 'text-orange-600' : 'text-green-600') }}">
                    {{ $bmiStatus['calorie_multiplier'] > 1 ? '+' : '' }}{{ round(($bmiStatus['calorie_multiplier'] - 1) * 100) }}%
                </div>
                <div class="text-sm text-gray-600">Calorie Adjustment</div>
            </div>
        </div>
        
        <div class="mt-4 p-4 bg-gray-50 rounded-lg">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-blue-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <h4 class="text-sm font-medium text-gray-900 mb-1">Personalized Recommendation</h4>
                    <p class="text-sm text-gray-600">{{ $bmiStatus['recommendation'] }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Featured Meal -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Featured Meal of the Day</h2>
                </div>
                <div class="p-6">
                    @if($featuredMeal)
                        <div class="flex flex-col md:flex-row gap-6">
                            <div class="md:w-1/3">
                                <div class="aspect-w-16 aspect-h-9 bg-gray-200 rounded-lg overflow-hidden">
                                    @if($featuredMeal->image_url)
                                        <img src="{{ $featuredMeal->image_url }}" alt="{{ $featuredMeal->name }}" class="w-full h-48 object-cover rounded-lg">
                                    @else
                                        <div class="w-full h-48 flex items-center justify-center bg-gradient-to-br from-green-50 to-emerald-100">
                                            <div class="text-center">
                                                <span class="text-4xl block mb-2">🍽️</span>
                                                <span class="text-sm text-gray-600 font-medium">{{ $featuredMeal->name }}</span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="md:w-2/3">
                                <h3 class="font-semibold text-gray-900 text-lg mb-2">{{ $featuredMeal->name }}</h3>
                                <p class="text-sm text-gray-600 mb-4">{{ $featuredMeal->description }}</p>
                                
                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div class="bg-green-50 p-3 rounded-lg">
                                        <div class="text-sm text-green-600 font-medium">Calories</div>
                                        <div class="text-lg font-bold text-green-700">{{ $featuredMeal->nutritionalInfo->calories ?? 'N/A' }}</div>
                                    </div>
                                    <div class="bg-blue-50 p-3 rounded-lg">
                                        <div class="text-sm text-blue-600 font-medium">Cost</div>
                                        <div class="text-lg font-bold text-blue-700">₱{{ $featuredMeal->cost ?? 'N/A' }}</div>
                                    </div>
                                </div>
                                
                                <div class="flex space-x-2">
                                    <a href="{{ route('recipes.show', $featuredMeal) }}" 
                                       class="flex-1 text-center px-3 py-2 text-sm font-medium text-green-600 bg-green-50 rounded-md hover:bg-green-100">
                                        View Recipe
                                    </a>
                                    <a href="{{ route('meal-plans.create') }}?meal_id={{ $featuredMeal->id }}" 
                                       class="flex-1 text-center px-3 py-2 text-sm font-medium text-gray-600 bg-gray-50 rounded-md hover:bg-gray-100">
                                        Add to Plan
                                    </a>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <span class="text-6xl mb-4 block">🍽️</span>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No featured meal available</h3>
                            <p class="text-gray-500 mb-6">Check back later for today's featured meal</p>
                            <a href="{{ route('recipes.index') }}" 
                               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                                <x-icon name="book-open" class="w-4 h-4 mr-2" variant="outline" />
                                Browse All Recipes
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Today's Meals -->
        <div>
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <h2 class="text-lg font-semibold text-gray-900">Today's Meals</h2>
                            @if($todayMeals->count() > 0)
                                <span class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    {{ $todayMeals->count() }} planned
                                </span>
                            @endif
                        </div>
                        <div class="text-xs text-gray-500">
                            {{ now()->format('M j, Y') }}
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    @if($todayMeals->count() > 0)
                        <div class="space-y-3">
                            @foreach($todayMeals as $mealPlan)
                                <div class="group border border-gray-200 hover:border-green-300 rounded-lg p-4 transition-all duration-200 hover:shadow-sm">
                                    <div class="flex items-start justify-between">
                                        <div class="flex items-start space-x-4 flex-1">
                                            <!-- Meal Type Icon -->
                                            <div class="flex-shrink-0 w-12 h-12 rounded-full flex items-center justify-center
                                                {{ $mealPlan->meal_type === 'breakfast' ? 'bg-yellow-100' : '' }}
                                                {{ $mealPlan->meal_type === 'lunch' ? 'bg-orange-100' : '' }}
                                                {{ $mealPlan->meal_type === 'dinner' ? 'bg-purple-100' : '' }}
                                                {{ $mealPlan->meal_type === 'snack' ? 'bg-green-100' : '' }}
                                            ">
                                                <span class="text-xl">
                                                    @if($mealPlan->meal_type === 'breakfast') 🌅
                                                    @elseif($mealPlan->meal_type === 'lunch') ☀️
                                                    @elseif($mealPlan->meal_type === 'dinner') 🌙
                                                    @elseif($mealPlan->meal_type === 'snack') 🍎
                                                    @else 🍽️
                                                    @endif
                                                </span>
                                            </div>
                                            
                                            <!-- Meal Information -->
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-start justify-between">
                                                    <div class="flex-1">
                                                        <h4 class="font-medium text-gray-900 group-hover:text-green-700 transition-colors">
                                                            {{ $mealPlan->meal->name }}
                                                        </h4>
                                                        <p class="text-sm text-gray-500 mt-0.5 capitalize font-medium">
                                                            {{ $mealPlan->meal_type }}
                                                        </p>
                                                        @if($mealPlan->meal->description)
                                                            <p class="text-xs text-gray-400 mt-1 line-clamp-1">
                                                                {{ Str::limit($mealPlan->meal->description, 80) }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                    
                                                    <!-- Status Badge -->
                                                    <div class="ml-4 flex-shrink-0">
                                                        @if($mealPlan->is_completed)
                                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                                                <x-icon name="check" class="w-3 h-3 mr-1" variant="solid" />
                                                                Completed
                                                            </span>
                                                        @else
                                                            <form method="POST" action="{{ route('meal-plans.toggle', $mealPlan) }}" class="inline">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button type="submit" 
                                                                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600 hover:bg-green-100 hover:text-green-700 border border-gray-200 hover:border-green-200 transition-all duration-200 cursor-pointer"
                                                                        aria-label="Mark {{ $mealPlan->meal->name }} as completed">
                                                                    <x-icon name="clock" class="w-3 h-3 mr-1" variant="outline" />
                                                                    Pending
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </div>
                                                
                                                <!-- Nutritional Info -->
                                                <div class="flex items-center space-x-4 mt-3 text-xs">
                                                    <div class="flex items-center text-gray-500">
                                                        <x-icon name="bolt" class="w-3 h-3 mr-1" variant="outline" />
                                                        <span class="font-medium">{{ $mealPlan->meal->nutritionalInfo->calories ?? 'N/A' }}</span>
                                                        <span class="ml-0.5">cal</span>
                                                    </div>
                                                    <div class="flex items-center text-gray-500">
                                                        <x-icon name="currency-dollar" class="w-3 h-3 mr-1" variant="outline" />
                                                        <span class="font-medium">₱{{ $mealPlan->meal->cost }}</span>
                                                    </div>
                                                    @if($mealPlan->scheduled_time)
                                                        <div class="flex items-center text-gray-500">
                                                            <x-icon name="clock" class="w-3 h-3 mr-1" variant="outline" />
                                                            <span class="font-medium">{{ Carbon\Carbon::parse($mealPlan->scheduled_time)->format('g:i A') }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                                
                                                <!-- Quick Actions -->
                                                <div class="flex items-center space-x-2 mt-3 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                                    <a href="{{ route('recipes.show', $mealPlan->meal) }}" 
                                                       class="inline-flex items-center px-2 py-1 text-xs font-medium text-green-600 hover:text-green-700 hover:bg-green-50 rounded transition-colors duration-200"
                                                       aria-label="View recipe for {{ $mealPlan->meal->name }}">
                                                        <x-icon name="eye" class="w-3 h-3 mr-1" variant="outline" />
                                                        Recipe
                                                    </a>
                                                    <a href="{{ route('meal-plans.edit', $mealPlan) }}" 
                                                       class="inline-flex items-center px-2 py-1 text-xs font-medium text-gray-600 hover:text-gray-700 hover:bg-gray-50 rounded transition-colors duration-200"
                                                       aria-label="Edit meal plan for {{ $mealPlan->meal->name }}">
                                                        <x-icon name="pencil" class="w-3 h-3 mr-1" variant="outline" />
                                                        Edit
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Daily Summary Bar -->
                        @if($todayMeals->count() > 0)
                            <div class="mt-6 pt-4 border-t border-gray-200">
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 text-sm">
                                        <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-6">
                                            <div class="flex items-center">
                                                <span class="font-medium text-gray-700">Daily Total:</span>
                                            </div>
                                            <div class="flex items-center gap-4">
                                                <div class="flex items-center text-green-700">
                                                    <x-icon name="bolt" class="w-4 h-4 mr-1" variant="outline" />
                                                    <span class="font-semibold">{{ $todayMeals->sum('meal.nutritionalInfo.calories') ?? 0 }} cal</span>
                                                </div>
                                                <div class="flex items-center text-blue-700">
                                                    <x-icon name="currency-dollar" class="w-4 h-4 mr-1" variant="outline" />
                                                    <span class="font-semibold">₱{{ $todayMeals->sum('meal.cost') ?? 0 }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-gray-500 text-sm">
                                            {{ $todayMeals->where('is_completed', true)->count() }}/{{ $todayMeals->count() }} completed
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <a href="{{ route('meal-plans.index') }}" 
                               class="w-full text-center block px-4 py-2 text-sm font-medium text-green-600 bg-green-50 hover:bg-green-100 rounded-md transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                               aria-label="View all meal plans">
                                View All Meal Plans
                            </a>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="relative">
                                <div class="absolute inset-0 flex items-center justify-center opacity-10">
                                    <x-icon name="calendar-days" class="w-32 h-32 text-gray-300" variant="solid" />
                                </div>
                                <div class="relative">
                                    <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-6">
                                        <span class="text-3xl">📅</span>
                                    </div>
                                    <h3 class="text-xl font-medium text-gray-900 mb-3">No meals planned for today</h3>
                                    <p class="text-gray-500 mb-6 max-w-sm mx-auto leading-relaxed">
                                        Start planning your meals to maintain a healthy diet and stay on budget. 
                                        It only takes a few minutes!
                                    </p>
                                    
                                    <div class="space-y-3">
                                        <a href="{{ route('meal-plans.create') }}" 
                                           class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                                            <x-icon name="plus" class="w-5 h-5 mr-2" variant="outline" />
                                            Plan Your First Meal
                                        </a>
                                        <div class="text-sm text-gray-400">or</div>
                                        <a href="{{ route('recipes.index') }}" 
                                           class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all duration-200">
                                            <x-icon name="magnifying-glass" class="w-4 h-4 mr-2" variant="outline" />
                                            Explore Recipes First
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Suggested Meals -->
    @if($suggestedMeals->count() > 0)
        <div class="bg-white shadow rounded-lg mb-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Personalized Meal Suggestions</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($suggestedMeals->take(6) as $meal)
                        <div class="bg-white shadow rounded-lg overflow-hidden hover:shadow-lg transition-shadow">
                            <!-- Recipe Image -->
                            <div class="aspect-w-16 aspect-h-9 bg-gray-200">
                                @if($meal->image_url)
                                    <img src="{{ $meal->image_url }}" alt="{{ $meal->name }}" class="w-full h-32 object-cover" loading="lazy">
                                @else
                                    <div class="w-full h-32 flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200">
                                        <span class="text-4xl">🍽️</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Recipe Info -->
                            <div class="p-4">
                                <div class="flex items-start justify-between mb-2">
                                    <h4 class="font-medium text-gray-900">{{ $meal->name }}</h4>
                                    <span class="text-sm font-medium text-green-600">₱{{ $meal->cost }}</span>
                                </div>
                                
                                <p class="text-sm text-gray-600 mb-3">{{ Str::limit($meal->description, 60) }}</p>
                                
                                <div class="flex items-center justify-between mb-3">
                                    <span class="text-sm text-gray-500">
                                        {{ $meal->nutritionalInfo->calories ?? 'N/A' }} cal
                                    </span>
                                </div>

                                <div class="flex space-x-2">
                                    <a href="{{ route('recipes.show', $meal) }}" 
                                       class="flex-1 text-center px-3 py-2 text-sm font-medium text-green-600 bg-green-50 rounded-md hover:bg-green-100">
                                        View Recipe
                                    </a>
                                    <a href="{{ route('meal-plans.create') }}?meal_id={{ $meal->id }}" 
                                       class="flex-1 text-center px-3 py-2 text-sm font-medium text-gray-600 bg-gray-50 rounded-md hover:bg-gray-100">
                                        Add to Plan
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-6 pt-6 border-t border-gray-200 text-center">
                    <a href="{{ route('recipes.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Browse All Recipes
                        <x-icon name="chevron-right" class="w-4 h-4 ml-2" variant="outline" />
                    </a>
                </div>
            </div>
        </div>
    @endif

    <!-- Quick Actions -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="{{ route('meal-plans.create') }}" 
                   class="flex items-center justify-center gap-2 border border-gray-300 text-gray-700 px-4 py-3 rounded-md hover:bg-gray-50 hover:border-green-400 hover:text-green-700 transition-all duration-200 group">
                    <x-icon name="plus" class="w-5 h-5 group-hover:text-green-600" variant="outline" />
                    Add Meal Plan
                </a>
                <a href="{{ route('recipes.index') }}" 
                   class="flex items-center justify-center gap-2 border border-gray-300 text-gray-700 px-4 py-3 rounded-md hover:bg-gray-50 hover:border-blue-400 hover:text-blue-700 transition-all duration-200 group">
                    <x-icon name="book-open" class="w-5 h-5 group-hover:text-blue-600" variant="outline" />
                    Browse Recipes
                </a>
                <a href="{{ route('meal-plans.weekly') }}" 
                   class="flex items-center justify-center gap-2 border border-gray-300 text-gray-700 px-4 py-3 rounded-md hover:bg-gray-50 hover:border-purple-400 hover:text-purple-700 transition-all duration-200 group">
                    <x-icon name="calendar-days" class="w-5 h-5 group-hover:text-purple-600" variant="outline" />
                    Weekly View
                </a>
                <a href="{{ route('profile.edit') }}" 
                   class="flex items-center justify-center gap-2 border border-gray-300 text-gray-700 px-4 py-3 rounded-md hover:bg-gray-50 hover:border-orange-400 hover:text-orange-700 transition-all duration-200 group">
                    <x-icon name="cog-6-tooth" class="w-5 h-5 group-hover:text-orange-600" variant="outline" />
                    Settings
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
