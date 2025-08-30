@extends('layouts.app')

@section('title', 'Weekly Meal Plan')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Weekly Meal Plan</h1>
            <p class="mt-2 text-gray-600">Plan your meals for the entire week</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('meal-plans.index') }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                <x-icon name="bars-3" class="w-4 h-4 mr-2" />
                Daily View
            </a>
            <a href="{{ route('meal-plans.create') }}" 
               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                <x-icon name="plus" class="w-4 h-4 mr-2" />
                Add Meal
            </a>
        </div>
    </div>

    <!-- Week Navigation -->
    <div class="bg-white shadow rounded-lg mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="?start_date={{ $startDate->copy()->subWeek()->format('Y-m-d') }}" 
                       class="p-2 text-gray-400 hover:text-gray-600">
                        <x-icon name="chevron-left" class="w-5 h-5" />
                    </a>
                    <h2 class="text-xl font-semibold text-gray-900">
                        Week of {{ $startDate->format('M j') }} - {{ $startDate->copy()->addDays(6)->format('M j, Y') }}
                    </h2>
                    <a href="?start_date={{ $startDate->copy()->addWeek()->format('Y-m-d') }}" 
                       class="p-2 text-gray-400 hover:text-gray-600">
                        <x-icon name="chevron-right" class="w-5 h-5" />
                    </a>
                </div>
                <a href="?start_date={{ now()->startOfWeek()->format('Y-m-d') }}" 
                   class="text-sm text-green-600 hover:text-green-700 font-medium">
                    This Week
                </a>
            </div>
        </div>
    </div>

    <!-- Weekly Calendar -->
    <div class="grid grid-cols-1 lg:grid-cols-7 gap-4">
        @php
            $mealTypes = ['breakfast', 'lunch', 'dinner', 'snack'];
            // Map meal types to heroicon names used by <x-icon>
            $mealIcons = [
                'breakfast' => 'sun',
                'lunch' => 'fire',
                'dinner' => 'moon',
                'snack' => 'cake'
            ];
            // Color accents (align with daily view palette)
            $mealColors = [
                'breakfast' => 'text-yellow-600',
                'lunch' => 'text-orange-600',
                'dinner' => 'text-blue-600',
                'snack' => 'text-purple-600'
            ];
            $mealBgColors = [
                'breakfast' => 'bg-yellow-50',
                'lunch' => 'bg-orange-50',
                'dinner' => 'bg-blue-50',
                'snack' => 'bg-purple-50'
            ];
        @endphp

        @foreach($weekDays as $day)
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-3 border-b border-gray-200">
                    <div class="text-center">
                        <div class="text-sm font-medium text-gray-500">
                            {{ $day['date']->format('D') }}
                        </div>
                        <div class="text-lg font-semibold text-gray-900">
                            {{ $day['date']->format('j') }}
                        </div>
                        @if($day['date']->isToday())
                            <div class="mt-1">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Today
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
                
                <div class="p-4">
                    @foreach($mealTypes as $mealType)
                        @php
                            $mealPlan = $day['meals']->where('meal_type', $mealType)->first();
                        @endphp
                        
                        <div class="mb-4 last:mb-0">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center">
                                    <span class="mr-2 inline-flex items-center justify-center w-7 h-7 rounded-full ring-1 ring-current/10 {{ $mealBgColors[$mealType] }} {{ $mealColors[$mealType] }}">
                                        <x-icon name="{{ $mealIcons[$mealType] }}" class="w-4 h-4" />
                                    </span>
                                    <span class="text-sm font-medium capitalize {{ $mealColors[$mealType] }}">{{ $mealType }}</span>
                                </div>
                                @if(!$mealPlan)
                                    <a href="{{ route('meal-plans.create') }}?date={{ $day['date']->format('Y-m-d') }}&meal_type={{ $mealType }}" 
                                       class="text-green-600 hover:text-green-700">
                                        <x-icon name="plus" class="w-4 h-4" />
                                    </a>
                                @endif
                            </div>
                            
                            @if($mealPlan)
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <h4 class="font-medium text-gray-900 text-sm">{{ $mealPlan->meal->name }}</h4>
                                            <div class="flex items-center space-x-2 mt-1">
                                                <span class="text-xs text-gray-500">
                                                    {{ $mealPlan->meal->nutritionalInfo->calories ?? 'N/A' }} cal
                                                </span>
                                                <span class="text-xs text-gray-500">₱{{ $mealPlan->meal->cost }}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="flex items-center space-x-1 ml-2">
                                            @if($mealPlan->is_completed)
                                                <span class="text-green-600 text-xs">✓</span>
                                            @else
                                                <form method="POST" action="{{ route('meal-plans.toggle', $mealPlan) }}" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="text-gray-400 hover:text-green-600">
                                                        <x-icon name="check-circle" class="w-4 h-4" />
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="flex space-x-1 mt-2">
                                        <a href="{{ route('recipes.show', $mealPlan->meal) }}" 
                                           class="flex-1 text-center px-2 py-1 text-xs font-medium text-green-600 bg-green-50 rounded hover:bg-green-100">
                                            Recipe
                                        </a>
                                        <form method="POST" action="{{ route('meal-plans.destroy', $mealPlan) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="flex-1 px-2 py-1 text-xs font-medium text-red-600 bg-red-50 rounded hover:bg-red-100"
                                                    onclick="return confirm('Are you sure you want to remove this meal?')">
                                                Remove
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <span class="inline-flex items-center justify-center w-12 h-12 mx-auto rounded-full {{ $mealBgColors[$mealType] }} {{ $mealColors[$mealType] }} ring-1 ring-current/10 mb-2">
                                        <x-icon name="{{ $mealIcons[$mealType] }}" class="w-5 h-5" />
                                    </span>
                                    <p class="text-xs text-gray-400">No meal planned</p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    <!-- Weekly Summary -->
    @php
        $totalMeals = $weekDays->sum(function($day) { return $day['meals']->count(); });
        $completedMeals = $weekDays->sum(function($day) { return $day['meals']->where('is_completed', true)->count(); });
        $totalCalories = $weekDays->sum(function($day) { 
            return $day['meals']->sum(function($mealPlan) { 
                return $mealPlan->meal->nutritionalInfo->calories ?? 0; 
            }); 
        });
        $totalCost = $weekDays->sum(function($day) { 
            return $day['meals']->sum(function($mealPlan) { 
                return $mealPlan->meal->cost ?? 0; 
            }); 
        });
    @endphp

    @if($totalMeals > 0)
        <div class="mt-8">
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Weekly Summary</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-600">{{ $totalMeals }}</div>
                            <div class="text-sm text-gray-500">Total Meals</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600">{{ $completedMeals }}/{{ $totalMeals }}</div>
                            <div class="text-sm text-gray-500">Meals Completed</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-purple-600">{{ number_format($totalCalories) }}</div>
                            <div class="text-sm text-gray-500">Total Calories</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-orange-600">₱{{ number_format($totalCost, 2) }}</div>
                            <div class="text-sm text-gray-500">Total Cost</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

