@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">
                    @if($user->isFirstLogin())
                        Welcome, {{ $user->name }}! 👋
                    @else
                        Welcome back, {{ $user->name }}! 👋
                    @endif
                </h1>
                <p class="mt-2 text-gray-600">Here's your personalized meal planning dashboard</p>
            </div>
            <button onclick="startDashboardTour()" 
                    class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Take a Tour
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white shadow rounded-lg p-6 hover:shadow-md transition-shadow duration-200" 
             data-intro="This shows how many meals you have planned for today. Click to view meal details."
             data-step="1">
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

        <div class="bg-white shadow rounded-lg p-6 hover:shadow-md transition-shadow duration-200"
             data-intro="Your personalized daily calorie target based on PDRI (Philippine Dietary Reference Intake) standards, age, gender, and activity level."
             data-step="2">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Daily Calorie Target</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($user->getRecommendedDailyCalories()) }} cal</p>
                    @php
                        $pdri = $user->getPdriRecommendations();
                    @endphp
                    @if($pdri)
                        <p class="text-xs text-gray-500 mt-1">Based on PDRI for {{ ucfirst($user->activity_level ?? 'moderate') }} activity</p>
                    @endif
                </div>
                <div class="text-green-600 bg-green-100 p-2 rounded-lg">
                    <x-icon name="bolt" class="w-8 h-8" variant="outline" aria-label="Daily calorie target icon" />
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6 hover:shadow-md transition-shadow duration-200"
             data-intro="Set your daily budget to get meal recommendations that fit your finances. StudEats will suggest affordable recipes."
             data-step="3">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Daily Budget</p>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ $user->daily_budget ? number_format($user->daily_budget, 2) : 'Not set' }}
                    </p>
                </div>
                <div class="text-blue-600 bg-blue-100 p-2 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-philippine-peso-icon lucide-philippine-peso w-8 h-8">
                        <path d="M20 11H4"/>
                        <path d="M20 7H4"/>
                        <path d="M7 21V4a1 1 0 0 1 1-1h4a1 1 0 0 1 0 12H7"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6 hover:shadow-md transition-shadow duration-200"
             data-intro="Track your daily calories consumed today. Monitor if your planned meals meet your personalized calorie target!<br><br>🟢 <strong>Green</strong>: On target (within 50 calories)<br>🔵 <strong>Blue</strong>: Under target (calories remaining)<br>🟠 <strong>Orange</strong>: Over target (excess calories)"
             data-step="4">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Daily Calories</p>
                    <div class="flex items-baseline space-x-2">
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($dailySummary['totalCalories']) }}</p>
                        <span class="text-sm text-gray-500">/ {{ number_format($dailySummary['targetCalories']) }} cal</span>
                    </div>
                    @if($dailySummary['isOnTarget'])
                        <p class="text-xs text-green-600 mt-1 font-medium">✓ On target</p>
                    @elseif($dailySummary['isUnder'])
                        <p class="text-xs text-blue-600 mt-1 font-medium">{{ number_format(abs($dailySummary['difference'])) }} cal remaining</p>
                    @else
                        <p class="text-xs text-orange-600 mt-1 font-medium">{{ number_format($dailySummary['difference']) }} cal over</p>
                    @endif
                </div>
                <div class="text-purple-600 bg-purple-100 p-2 rounded-lg">
                    <x-icon name="bolt" class="w-8 h-8" variant="outline" aria-label="Daily calories icon" />
                </div>
            </div>
            <!-- Progress Bar -->
            <div class="mt-3">
                <div class="flex items-center justify-between text-xs text-gray-500 mb-1">
                    <span>Progress</span>
                    <span class="font-medium">{{ $dailySummary['percentage'] }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="h-2 rounded-full transition-all duration-300 
                        @if($dailySummary['isOnTarget']) bg-green-500
                        @elseif($dailySummary['isUnder']) bg-blue-500
                        @else bg-orange-500
                        @endif"
                        style="width: {{ min($dailySummary['percentage'], 100) }}%">
                    </div>
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
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
                <div class="text-2xl font-bold text-green-600">{{ number_format($bmiStatus['daily_calories']) }} cal</div>
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
        <div class="lg:col-span-2"
             data-intro="Discover our featured meal of the day! This is a specially selected recipe with great nutrition and affordable cost."
             data-step="6">
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
                                        <div class="text-lg font-bold text-green-700">{{ $featuredMeal->nutritionalInfo->calories ?? 'N/A' }} cal</div>
                                    </div>
                                    <div class="bg-blue-50 p-3 rounded-lg">
                                        <div class="text-sm text-blue-600 font-medium">Cost</div>
                                        <div class="text-lg font-bold text-blue-700">{{ $featuredMeal->cost ?? 'N/A' }}</div>
                                    </div>
                                </div>
                                
                                <div class="flex space-x-2">
                                    <x-loading-button 
                                        href="{{ route('recipes.show', $featuredMeal) }}"
                                        variant="secondary"
                                        size="sm"
                                        class="flex-1"
                                        loadingText="Loading..."
                                        loadingType="spinner">
                                        View Recipe
                                    </x-loading-button>
                                    <x-loading-button 
                                        href="{{ route('meal-plans.create') }}?meal_id={{ $featuredMeal->id }}"
                                        variant="success"
                                        size="sm"
                                        class="flex-1"
                                        loadingText="Adding..."
                                        loadingType="spinner">
                                        Add to Plan
                                    </x-loading-button>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <span class="text-6xl mb-4 block">🍽️</span>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No featured meal available</h3>
                            <p class="text-gray-500 mb-6">Check back later for today's featured meal</p>
                            <x-loading-button 
                                href="{{ route('recipes.index') }}"
                                variant="success"
                                size="sm"
                                loadingText="Loading..."
                                loadingType="spinner">
                                <x-icon name="book-open" class="w-4 h-4 mr-2" variant="outline" />
                                Browse All Recipes
                            </x-loading-button>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Today's Meals -->
        <div>
            <div class="bg-white shadow rounded-lg"
                 data-intro="View and manage your meals for today. You can mark meals as completed, edit them, or remove them from your plan."
                 data-step="5">
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
                                                    @if($mealPlan->meal_type === 'breakfast') 🍳
                                                    @elseif($mealPlan->meal_type === 'lunch') 🍽️
                                                    @elseif($mealPlan->meal_type === 'dinner') 🍴
                                                    @elseif($mealPlan->meal_type === 'snack') 🍪
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
                                                                <x-loading-button 
                                                                    type="submit"
                                                                    variant="secondary"
                                                                    size="xs"
                                                                    loadingText="Updating..."
                                                                    loadingType="spinner"
                                                                    class="border border-gray-200 hover:border-green-200"
                                                                    aria-label="Mark {{ $mealPlan->meal->name }} as completed">
                                                                    <x-icon name="clock" class="w-3 h-3 mr-1" variant="outline" />
                                                                    Pending
                                                                </x-loading-button>
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
                                                    <span class="font-medium">₱{{ $mealPlan->meal->cost }}</span>
                                                </div>
                                                @if($mealPlan->scheduled_time)
                                                    <div class="flex items-center text-gray-500">
                                                        <x-icon name="clock" class="w-3 h-3 mr-1" variant="outline" />
                                                        <span class="font-medium">{{ Carbon\Carbon::parse($mealPlan->scheduled_time)->format('g:i A') }}</span>
                                                    </div>
                                                @endif
                                            </div>                                                <!-- Quick Actions -->
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
                                                    <button type="button" 
                                                            onclick="showRemoveMealModal('{{ $mealPlan->id }}', '{{ $mealPlan->meal->name }}', '{{ $mealPlan->meal_type }}')" 
                                                            class="inline-flex items-center px-2 py-1 text-xs font-medium text-red-600 hover:text-red-700 hover:bg-red-50 rounded transition-colors duration-200"
                                                            aria-label="Remove {{ $mealPlan->meal->name }} from today's plan">
                                                        <x-icon name="trash" class="w-3 h-3 mr-1" variant="outline" />
                                                        Remove
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Hidden form for meal removal -->
                                    <form id="removeMealForm_{{ $mealPlan->id }}" method="POST" action="{{ route('meal-plans.destroy', $mealPlan) }}" class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Daily Summary Bar -->
                        @if($todayMeals->count() > 0)
                            <div class="mt-6 pt-4 border-t border-gray-200">
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div class="flex flex-wrap items-center gap-4 text-sm">
                                        <span class="font-medium text-gray-700">Daily Total:</span>
                                        <div class="flex items-center text-green-700">
                                            <x-icon name="bolt" class="w-4 h-4 mr-1" variant="outline" />
                                            <span class="font-semibold">{{ $todayMeals->sum('meal.nutritionalInfo.calories') ?? 0 }} cal</span>
                                        </div>
                                            <div class="flex items-center text-blue-700">
                                                <span class="font-semibold">₱{{ $todayMeals->sum('meal.cost') ?? 0 }}</span>
                                            </div>
                                        <div class="text-gray-500">
                                            {{ $todayMeals->where('is_completed', true)->count() }}/{{ $todayMeals->count() }} completed
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <x-loading-button 
                                href="{{ route('meal-plans.index') }}"
                                variant="success"
                                size="sm"
                                class="w-full"
                                loadingText="Loading..."
                                loadingType="spinner"
                                aria-label="View all meal plans">
                                View All Meal Plans
                            </x-loading-button>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="relative">
                                <div class="absolute inset-0 flex items-center justify-center opacity-10">
                                    <x-icon name="calendar-days" class="w-32 h-32 text-gray-300" variant="solid" />
                                </div>
                                <div class="relative">
                                    <div class="mx-auto w-20 h-20 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mb-6 shadow-inner border border-gray-300">
                                        <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2" stroke-width="2"></rect>
                                            <line x1="16" y1="2" x2="16" y2="6" stroke-width="2"></line>
                                            <line x1="8" y1="2" x2="8" y2="6" stroke-width="2"></line>
                                            <line x1="3" y1="10" x2="21" y2="10" stroke-width="2"></line>
                                            <circle cx="8" cy="14" r="1" fill="currentColor"></circle>
                                            <circle cx="12" cy="14" r="1" fill="currentColor"></circle>
                                            <circle cx="16" cy="14" r="1" fill="currentColor"></circle>
                                            <circle cx="8" cy="18" r="1" fill="currentColor"></circle>
                                            <circle cx="12" cy="18" r="1" fill="currentColor"></circle>
                                        </svg>
                                    </div>
                                    <h3 class="text-xl font-medium text-gray-900 mb-3">No meals planned for today</h3>
                                    <p class="text-gray-500 mb-6 max-w-sm mx-auto leading-relaxed">
                                        Start planning your meals to maintain a healthy diet and stay on budget. 
                                        It only takes a few minutes!
                                    </p>
                                    
                                    <div class="space-y-3">
                                        <x-loading-button 
                                            href="{{ route('meal-plans.create') }}"
                                            variant="success"
                                            size="lg"
                                            loadingText="Loading..."
                                            loadingType="spinner"
                                            class="shadow-lg hover:shadow-xl transform hover:scale-105">
                                            <x-icon name="plus" class="w-5 h-5 mr-2" variant="outline" />
                                            Plan Your First Meal
                                        </x-loading-button>
                                        <div class="text-sm text-gray-400">or</div>
                                        <x-loading-button 
                                            href="{{ route('recipes.index') }}"
                                            variant="secondary"
                                            size="sm"
                                            loadingText="Loading..."
                                            loadingType="spinner"
                                            class="border border-gray-300">
                                            <x-icon name="magnifying-glass" class="w-4 h-4 mr-2" variant="outline" />
                                            Explore Recipes First
                                        </x-loading-button>
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
                                    <span class="text-sm font-medium text-green-600">{{ $meal->cost }}</span>
                                </div>
                                
                                <p class="text-sm text-gray-600 mb-3">{{ Str::limit($meal->description, 60) }}</p>
                                
                                <div class="flex items-center justify-between mb-3">
                                    <span class="text-sm text-gray-500">
                                        {{ $meal->nutritionalInfo->calories ?? 'N/A' }} cal
                                    </span>
                                </div>

                                <div class="flex space-x-2">
                                    <x-loading-button 
                                        href="{{ route('recipes.show', $meal) }}"
                                        variant="secondary"
                                        size="sm"
                                        class="flex-1"
                                        loadingText="Loading..."
                                        loadingType="spinner">
                                        View Recipe
                                    </x-loading-button>
                                    <x-loading-button 
                                        href="{{ route('meal-plans.create') }}?meal_id={{ $meal->id }}"
                                        variant="success"
                                        size="sm"
                                        class="flex-1"
                                        loadingText="Adding..."
                                        loadingType="spinner">
                                        Add to Plan
                                    </x-loading-button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-6 pt-6 border-t border-gray-200 text-center">
                    <x-loading-button 
                        href="{{ route('recipes.index') }}"
                        variant="secondary"
                        size="sm"
                        loadingText="Loading..."
                        loadingType="spinner"
                        class="border border-gray-300">
                        Browse All Recipes
                        <x-icon name="chevron-right" class="w-4 h-4 ml-2" variant="outline" />
                    </x-loading-button>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Meal Removal Confirmation Modal -->
<div id="removeMealModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="hideRemoveMealModal()"></div>

        <!-- Center the modal contents -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <!-- Modal panel -->
        <div class="relative inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
            <div class="sm:flex sm:items-start">
                <!-- Warning Icon -->
                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                    <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                </div>
                
                <!-- Modal Content -->
                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left flex-1">
                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                        Remove Meal from Plan?
                    </h3>
                    <div class="mt-3">
                        <div class="bg-gray-50 rounded-lg p-4 mb-4">
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0">
                                    <span id="modalMealIcon" class="text-2xl">🍽️</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900" id="modalMealName">Meal Name</p>
                                    <p class="text-sm text-gray-500">
                                        <span class="capitalize" id="modalMealType">meal type</span> • Today
                                    </p>
                                </div>
                            </div>
                        </div>
                        <p class="text-sm text-gray-500">
                            This action cannot be undone. The meal will be permanently removed from your meal plan for today.
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Modal Actions -->
            <div class="mt-6 sm:mt-4 sm:flex sm:flex-row-reverse">
                <form id="removeMealForm" method="POST" action="" class="inline-flex w-full sm:w-auto">
                    @csrf
                    @method('DELETE')
                    <x-loading-button 
                        type="submit"
                        variant="error"
                        size="sm"
                        loadingText="Removing..."
                        loadingType="spinner"
                        class="w-full sm:w-auto sm:ml-3">
                        <svg class="-ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1-1H8a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Yes, Remove Meal
                    </x-loading-button>
                </form>
                <button type="button" 
                        onclick="hideRemoveMealModal()" 
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:mt-0 sm:w-auto sm:text-sm transition-colors duration-200">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for Modal Functionality -->
<script>
// Modal functionality
function showRemoveMealModal(mealPlanId, mealName, mealType) {
    // Update modal content
    document.getElementById('modalMealName').textContent = mealName;
    document.getElementById('modalMealType').textContent = mealType;
    document.getElementById('removeMealForm').action = `/meal-plans/${mealPlanId}`;
    
    // Update meal type icon
    const mealIcon = document.getElementById('modalMealIcon');
    switch(mealType) {
        case 'breakfast':
            mealIcon.textContent = '🍳';
            break;
        case 'lunch':
            mealIcon.textContent = '🍽️';
            break;
        case 'dinner':
            mealIcon.textContent = '🍴';
            break;
        case 'snack':
            mealIcon.textContent = '🍪';
            break;
        default:
            mealIcon.textContent = '🍽️';
    }
    
    // Show modal with animation
    const modal = document.getElementById('removeMealModal');
    modal.classList.remove('hidden');
    
    // Add entrance animation
    requestAnimationFrame(() => {
        const overlay = modal.querySelector('.bg-gray-500');
        const panel = modal.querySelector('.bg-white');
        
        overlay.style.opacity = '0';
        panel.style.transform = 'scale(0.95)';
        panel.style.opacity = '0';
        
        overlay.style.transition = 'opacity 300ms ease-out';
        panel.style.transition = 'all 300ms ease-out';
        
        requestAnimationFrame(() => {
            overlay.style.opacity = '0.75';
            panel.style.transform = 'scale(1)';
            panel.style.opacity = '1';
        });
    });
    
    // Focus on cancel button for accessibility
    setTimeout(() => {
        modal.querySelector('button[onclick="hideRemoveMealModal()"]').focus();
    }, 100);
}

function hideRemoveMealModal() {
    const modal = document.getElementById('removeMealModal');
    const overlay = modal.querySelector('.bg-gray-500');
    const panel = modal.querySelector('.bg-white');
    
    // Add exit animation
    overlay.style.transition = 'opacity 200ms ease-in';
    panel.style.transition = 'all 200ms ease-in';
    
    overlay.style.opacity = '0';
    panel.style.transform = 'scale(0.95)';
    panel.style.opacity = '0';
    
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 200);
}

// Close modal with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const modal = document.getElementById('removeMealModal');
        if (!modal.classList.contains('hidden')) {
            hideRemoveMealModal();
        }
    }
});

// Loading state for form submission
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('removeMealForm');
    const LoadingUtils = window.LoadingUtils || null;
    
    form.addEventListener('submit', function(e) {
        const submitBtn = form.querySelector('button[type="submit"]');
        
        // Show loading state
        if (LoadingUtils) {
            LoadingUtils.showOnButton(submitBtn, {
                text: 'Removing...',
                size: 'small'
            });
        } else {
            // Fallback loading state
            const originalContent = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Removing...
            `;
        }
    });
});

// Intro.js Tour Configuration
function startDashboardTour() {
    introJs().setOptions({
        steps: [
            {
                title: 'Welcome to StudEats! 🎉',
                intro: 'Let me show you around your personalized meal planning dashboard. Click "Next" to continue!'
            },
            {
                element: document.querySelector('[data-step="1"]'),
                intro: 'This shows how many meals you have planned for today. Stay organized with your daily meal schedule!',
                position: 'bottom'
            },
            {
                element: document.querySelector('[data-step="2"]'),
                intro: 'Your personalized daily calorie target based on PDRI (Philippine Dietary Reference Intake) standards, considering your age, gender, and activity level.',
                position: 'bottom'
            },
            {
                element: document.querySelector('[data-step="3"]'),
                intro: 'Set your daily budget here to get meal recommendations that fit your finances. StudEats suggests affordable Filipino recipes!',
                position: 'bottom'
            },
            {
                element: document.querySelector('[data-step="4"]'),
                intro: 'Track your total calories consumed this week. Monitor your progress and stay on track with your health goals.',
                position: 'bottom'
            },
            {
                element: document.querySelector('[data-step="5"]'),
                intro: 'View and manage all your meals for today. Mark them as completed, edit details, or remove them from your plan.',
                position: 'top'
            },
            {
                element: document.querySelector('[data-step="6"]'),
                intro: 'Check out our featured meal of the day! Carefully selected for great nutrition, taste, and affordability.',
                position: 'top'
            }
        ],
        showProgress: true,
        showBullets: true,
        exitOnOverlayClick: false,
        disableInteraction: true,
        scrollToElement: true,
        scrollPadding: 30,
        tooltipClass: 'customTooltip',
        highlightClass: 'customHighlight',
        doneLabel: 'Got it!',
        nextLabel: 'Next →',
        prevLabel: '← Back',
        hidePrev: true,
        hideNext: false,
        showStepNumbers: true
    }).start();
}

// Auto-start tour for first-time visitors
document.addEventListener('DOMContentLoaded', function() {
    const hasSeenTour = localStorage.getItem('dashboardTourCompleted');
    if (!hasSeenTour) {
        setTimeout(() => {
            startDashboardTour();
            localStorage.setItem('dashboardTourCompleted', 'true');
        }, 1000); // Wait 1 second for page to fully load
    }
});
</script>

@endsection
