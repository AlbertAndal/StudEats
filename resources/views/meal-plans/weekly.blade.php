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
    <div class="grid grid-cols-7 gap-3">
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
            <div class="bg-white shadow rounded-lg hover:shadow-md transition-shadow duration-200">
                <div class="px-4 py-3 border-b border-gray-200 bg-gradient-to-r from-green-50 to-blue-50">
                    <div class="text-center">
                        <div class="text-xs font-semibold text-gray-600 uppercase tracking-wide">
                            {{ $day['date']->format('D') }}
                        </div>
                        <div class="text-xl font-bold text-gray-900 mt-0.5">
                            {{ $day['date']->format('j') }}
                        </div>
                        @if($day['date']->isToday())
                            <div class="mt-1">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                    <span class="w-1 h-1 bg-green-500 rounded-full mr-1 animate-pulse"></span>
                                    Today
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
                
                <div class="p-3 space-y-3">
                    @foreach($mealTypes as $mealType)
                        @php
                            $mealPlan = $day['meals']->where('meal_type', $mealType)->first();
                        @endphp
                        
                        <div class="border-l-3 {{ $mealPlan ? 'border-' . str_replace('text-', '', $mealColors[$mealType]) : 'border-gray-200' }} pl-2 py-0.5">
                            <div class="flex items-center justify-between mb-1.5">
                                <div class="flex items-center space-x-1.5">
                                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-md ring-1 ring-current/20 {{ $mealBgColors[$mealType] }} {{ $mealColors[$mealType] }}">
                                        <x-icon name="{{ $mealIcons[$mealType] }}" class="w-3.5 h-3.5" />
                                    </span>
                                    <span class="text-xs font-semibold capitalize {{ $mealColors[$mealType] }}">{{ $mealType }}</span>
                                </div>
                                @if(!$mealPlan)
                                    <a href="{{ route('meal-plans.create') }}?date={{ $day['date']->format('Y-m-d') }}&meal_type={{ $mealType }}" 
                                       class="inline-flex items-center px-1.5 py-0.5 text-xs font-medium text-green-700 bg-green-50 rounded hover:bg-green-100 transition-colors duration-200">
                                        <x-icon name="plus" class="w-3 h-3" />
                                    </a>
                                @endif
                            </div>
                            
                            @if($mealPlan)
                                @php
                                    $displayCost = $mealPlan->meal->getDisplayCost('NCR');
                                    $mealImage = $mealPlan->meal->image_path ?? null;
                                @endphp
                                <div class="bg-gray-50 rounded-md p-2 border border-gray-100 hover:border-gray-200 transition-all duration-200">
                                    <!-- Meal Image -->
                                    @if($mealImage)
                                        <div class="mb-1.5 rounded overflow-hidden">
                                            <img src="{{ $mealPlan->meal->image_url }}" 
                                                 alt="{{ $mealPlan->meal->name }}"
                                                 class="w-full h-24 object-cover"
                                                 loading="lazy"
                                                 onerror="this.onerror=null; this.style.display='none'; const fallback = this.nextElementSibling; if(fallback) fallback.style.display='flex';">
                                            <div class="w-full h-24 flex items-center justify-center bg-gradient-to-br from-orange-400 to-pink-500" style="display:none;">
                                                <span class="text-white font-bold text-lg">{{ strtoupper(substr($mealPlan->meal->name, 0, 2)) }}</span>
                                            </div>
                                        </div>
                                    @else
                                        <div class="mb-1.5 rounded overflow-hidden bg-gradient-to-br {{ $mealBgColors[$mealType] }} border border-current/10">
                                            <div class="w-full h-24 flex items-center justify-center">
                                                <span class="text-4xl opacity-60">
                                                    @if($mealType === 'breakfast') 🍳
                                                    @elseif($mealType === 'lunch') 🍽️
                                                    @elseif($mealType === 'dinner') 🌙
                                                    @else 🍪
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    <div class="flex items-start justify-between mb-1">
                                        <div class="flex-1 min-w-0 pr-1">
                                            <h4 class="font-medium text-gray-900 text-xs leading-tight mb-1 line-clamp-2">{{ $mealPlan->meal->name }}</h4>
                                            <div class="flex flex-wrap gap-1">
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs bg-orange-100 text-orange-700">
                                                    <span class="mr-0.5">🔥</span>
                                                    {{ $mealPlan->meal->nutritionalInfo->calories ?? 'N/A' }}
                                                </span>
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs bg-green-100 text-green-700">
                                                    <span class="mr-0.5">💰</span>
                                                    ₱{{ number_format($displayCost, 2) }}
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <div class="flex items-center">
                                            @if($mealPlan->is_completed)
                                                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-green-100 text-green-600">
                                                    <x-icon name="check" class="w-3.5 h-3.5" />
                                                </span>
                                            @else
                                                <form method="POST" action="{{ route('meal-plans.toggle', $mealPlan) }}" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" 
                                                            class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-gray-200 text-gray-400 hover:bg-green-100 hover:text-green-600 transition-colors duration-200">
                                                        <x-icon name="check-circle" class="w-3.5 h-3.5" />
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="flex gap-1 mt-1.5">
                                        <a href="{{ route('recipes.show', $mealPlan->meal) }}" 
                                           class="flex-1 text-center px-1.5 py-1 text-xs font-medium text-green-700 bg-green-50 rounded hover:bg-green-100 transition-colors duration-200">
                                            Recipe
                                        </a>
                                        <form method="POST" action="{{ route('meal-plans.destroy', $mealPlan) }}" class="flex-1">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="w-full px-1.5 py-1 text-xs font-medium text-red-700 bg-red-50 rounded hover:bg-red-100 transition-colors duration-200"
                                                    onclick="return confirm('Remove this meal?')">
                                                Remove
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-3 px-2 bg-gray-50 rounded border border-dashed border-gray-200">
                                    <span class="inline-flex items-center justify-center w-10 h-10 mx-auto rounded-lg {{ $mealBgColors[$mealType] }} {{ $mealColors[$mealType] }} ring-1 ring-current/20 mb-1.5">
                                        <x-icon name="{{ $mealIcons[$mealType] }}" class="w-5 h-5" />
                                    </span>
                                    <p class="text-xs text-gray-500 mb-1">No meal</p>
                                    <a href="{{ route('meal-plans.create') }}?date={{ $day['date']->format('Y-m-d') }}&meal_type={{ $mealType }}" 
                                       class="inline-flex items-center text-xs text-green-600 hover:text-green-700 font-medium">
                                        <x-icon name="plus-circle" class="w-3 h-3 mr-0.5" />
                                        Add
                                    </a>
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
        <div class="mt-6">
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-5 py-3 bg-gradient-to-r from-green-50 to-blue-50 border-b border-gray-200">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-gradient-to-br from-green-500 to-blue-500 rounded-lg flex items-center justify-center mr-2.5">
                            <x-icon name="chart-bar" class="w-4 h-4 text-white" />
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-gray-900">Weekly Summary</h3>
                        </div>
                    </div>
                </div>
                <div class="p-5">
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
                        <div class="bg-gray-50 rounded-lg p-3 border border-gray-100 text-center">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                                <span class="text-lg">🍽️</span>
                            </div>
                            <div class="text-xl font-bold text-green-600">{{ $totalMeals }}</div>
                            <div class="text-xs text-gray-600">Meals</div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3 border border-gray-100 text-center">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                                <span class="text-lg">✓</span>
                            </div>
                            <div class="text-xl font-bold text-blue-600">{{ $completedMeals }}/{{ $totalMeals }}</div>
                            <div class="text-xs text-gray-600">Done</div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3 border border-gray-100 text-center">
                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                                <span class="text-lg">🔥</span>
                            </div>
                            <div class="text-xl font-bold text-purple-600">{{ number_format($totalCalories) }}</div>
                            <div class="text-xs text-gray-600">Calories</div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3 border border-gray-100 text-center">
                            <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                                <span class="text-lg">💰</span>
                            </div>
                            <div class="text-xl font-bold text-orange-600">₱{{ number_format($totalCost, 2) }}</div>
                            <div class="text-xs text-gray-600">Cost</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

