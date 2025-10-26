@extends('layouts.app')

@section('title', 'Meal Plans')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-8 space-y-4 sm:space-y-0">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                <x-icon name="calendar-days" class="w-8 h-8 mr-3 text-green-600" />
                Meal Plans
            </h1>
            <p class="mt-2 text-gray-600">Plan and manage your daily meals with ease</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-4">
            <a href="{{ route('meal-plans.weekly') }}" 
               class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
                <x-icon name="calendar-days" class="w-4 h-4 mr-2" />
                Weekly View
            </a>
            <a href="{{ route('meal-plans.select', ['date' => $selectedDate->format('Y-m-d')]) }}" 
               class="inline-flex items-center justify-center px-4 py-2 border border-green-300 text-sm font-medium rounded-md text-green-700 bg-green-50 hover:bg-green-100 transition-colors duration-200">
                <x-icon name="rectangle-stack" class="w-4 h-4 mr-2" />
                Select Meal Type
            </a>
            <a href="{{ route('meal-plans.create') }}" 
               class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 transition-colors duration-200">
                <x-icon name="plus" class="w-4 h-4 mr-2" />
                Add Meal
            </a>
        </div>
    </div>

    <!-- Date Navigation -->
    <div class="bg-white shadow rounded-lg mb-8 border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
                <div class="flex items-center justify-center sm:justify-start space-x-4">
                    <a href="?date={{ $selectedDate->copy()->subDay()->format('Y-m-d') }}" 
                       class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-50 rounded-full transition-colors duration-200"
                       title="Previous day">
                        <x-icon name="chevron-left" class="w-5 h-5" />
                    </a>
                    <div class="text-center sm:text-left">
                        <h2 class="text-xl font-semibold text-gray-900">
                            {{ $selectedDate->format('l, F j, Y') }}
                        </h2>
                        <p class="text-sm text-gray-500 mt-1">
                            {{ $selectedDate->diffForHumans() }}
                        </p>
                    </div>
                    <a href="?date={{ $selectedDate->copy()->addDay()->format('Y-m-d') }}" 
                       class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-50 rounded-full transition-colors duration-200"
                       title="Next day">
                        <x-icon name="chevron-right" class="w-5 h-5" />
                    </a>
                </div>
                <div class="flex justify-center sm:justify-end">
                    <a href="?date={{ now()->format('Y-m-d') }}" 
                       class="inline-flex items-center px-3 py-2 text-sm text-green-600 hover:text-green-700 font-medium bg-green-50 hover:bg-green-100 rounded-md transition-colors duration-200">
                        <x-icon name="calendar-days" class="w-4 h-4 mr-2" />
                        Today
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Meal Types Grid -->
    @php
        $dailyBudget = auth()->user()->daily_budget ?? null;
        $totalCost = $mealPlans->sum('meal.cost');
        $budgetPct = $dailyBudget ? min(100, round(($totalCost / $dailyBudget) * 100)) : 0;
        $remaining = $dailyBudget ? ($dailyBudget - $totalCost) : 0;
        if ($dailyBudget) {
            if ($budgetPct < 60) { $budgetBarColor = 'bg-green-500'; }
            elseif ($budgetPct < 90) { $budgetBarColor = 'bg-amber-500'; }
            else { $budgetBarColor = 'bg-red-500'; }
            $remainingClass = $remaining <= 0 ? 'text-red-600 font-semibold' : 'text-gray-400';
        } else {
            $budgetBarColor = 'bg-gray-300';
            $remainingClass = 'text-gray-400';
        }
    @endphp

    @if($dailyBudget)
    <div class="mb-6" aria-label="Daily budget usage" role="region">
        <div class="flex items-center justify-between mb-1">
            <div class="flex items-center gap-2 text-sm font-medium text-gray-700">
                <x-icon name="currency-dollar" class="w-4 h-4 text-green-600" />
                <span>Daily Budget</span>
            </div>
            <div class="text-xs text-gray-500">
                ₱{{ number_format($totalCost,2) }} / ₱{{ number_format($dailyBudget,2) }}
                <span class="ml-2 {{ $remainingClass }}">
                    @if($remaining>0) ₱{{ number_format($remaining,2) }} left @else Over budget @endif
                </span>
            </div>
        </div>
        @php $pctWidth = (int)$budgetPct; @endphp
        <div class="h-2 w-full rounded-full bg-gray-100 overflow-hidden" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="{{ $pctWidth }}" aria-label="Budget used {{ $pctWidth }} percent">
            <div class="h-full {{ $budgetBarColor }} transition-all duration-500" data-pct="{{ $pctWidth }}"></div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                document.querySelectorAll('[data-pct]').forEach(el => {
                    const v = parseInt(el.getAttribute('data-pct')); if(!isNaN(v)){ el.style.width = v + '%'; }
                });
            });
        </script>
        @if($budgetPct >= 90)
            <p class="mt-2 text-xs text-red-600 flex items-center gap-1"><x-icon name="exclamation-circle" class="w-4 h-4" /> Approaching or exceeding budget threshold.</p>
        @elseif($budgetPct >= 60)
            <p class="mt-2 text-xs text-amber-600 flex items-center gap-1"><x-icon name="exclamation-circle" class="w-4 h-4" /> Budget more than half used.</p>
        @endif
    </div>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 lg:gap-7">
        @php
            $mealTypes = ['breakfast', 'lunch', 'dinner', 'snack'];
            $mealIcons = [
                'breakfast' => 'sun',
                'lunch' => 'fire',
                'dinner' => 'moon',
                'snack' => 'cake'
            ];
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

        @foreach($mealTypes as $mealType)
            @php
                $mealPlan = $mealPlans->where('meal_type', $mealType)->first();
            @endphp
            
            <div class="relative group bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-lg transition-all duration-300 focus-within:ring-2 focus-within:ring-green-300 flex flex-col overflow-hidden" tabindex="0" aria-labelledby="meal-heading-{{ $mealType }}">
                @php
                    $gradientClasses = match($mealType) {
                        'breakfast' => 'from-yellow-300 to-yellow-500',
                        'lunch' => 'from-orange-300 to-orange-500',
                        'dinner' => 'from-blue-300 to-blue-500',
                        default => 'from-purple-300 to-purple-500',
                    };
                @endphp
                <div class="absolute inset-x-0 top-0 h-1 opacity-75 bg-gradient-to-r {{ $gradientClasses }}"></div>
                <div class="px-6 py-4 border-b border-gray-100 {{ $mealBgColors[$mealType] }} relative">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center min-w-0">
                            <div class="flex items-center justify-center w-11 h-11 rounded-full bg-white shadow {{ $mealColors[$mealType] }} ring-1 ring-gray-200 group-hover:scale-105 transition-transform">
                                <x-icon name="{{ $mealIcons[$mealType] }}" class="w-6 h-6" />
                            </div>
                            <div class="ml-3">
                                <h3 id="meal-heading-{{ $mealType }}" class="text-base font-semibold text-gray-900 capitalize tracking-tight flex items-center gap-2">
                                    {{ $mealType }}
                                    @if($mealPlan)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-green-100 text-green-700 uppercase">Planned</span>
                                    @endif
                                </h3>
                                <p class="text-xs text-gray-500 capitalize mt-1">{{ $selectedDate->format('M d') }}</p>
                            </div>
                        </div>
                        @if(!$mealPlan)
                            <a href="{{ route('meal-plans.create') }}?date={{ $selectedDate->format('Y-m-d') }}&meal_type={{ $mealType }}" 
                               class="text-green-600 hover:text-green-700 p-1.5 rounded-full hover:bg-green-50 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-green-300"
                               aria-label="Add {{ $mealType }} meal">
                                <x-icon name="plus" class="w-5 h-5" />
                            </a>
                        @endif
                    </div>
                </div>

                <div class="p-5 flex-1 flex flex-col">
                    @if($mealPlan)
                        <div class="space-y-4 flex-1 flex flex-col">
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-100 flex-1 flex flex-col">
                                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3 flex-1">
                                    <div class="flex-1">
                                        <div class="flex items-center mb-2 gap-2">
                                            <div class="w-2.5 h-2.5 rounded-full {{ $mealColors[$mealType] }} bg-current animate-pulse"></div>
                                            <h4 class="font-medium text-gray-900 leading-snug line-clamp-1">{{ $mealPlan->meal->name }}</h4>
                                        </div>
                                        <p class="text-xs text-gray-600 mb-3 leading-relaxed line-clamp-3">{{ Str::limit($mealPlan->meal->description, 100) }}</p>
                                        
                                        @php
                                            $cost = $mealPlan->meal->cost;
                                            $pctOfBudget = ($dailyBudget && $cost) ? round(($cost / $dailyBudget) * 100) : null;
                                            $pctBadgeClass = '';
                                            if($pctOfBudget !== null){
                                                if($pctOfBudget < 25){ $pctBadgeClass = 'bg-green-100 text-green-700'; }
                                                elseif($pctOfBudget < 50){ $pctBadgeClass = 'bg-amber-100 text-amber-700'; }
                                                elseif($pctOfBudget < 80){ $pctBadgeClass = 'bg-orange-100 text-orange-700'; }
                                                else { $pctBadgeClass = 'bg-red-100 text-red-700'; }
                                            }
                                        @endphp
                                        <div class="flex flex-wrap items-center gap-3 text-[11px] font-medium">
                                            <div class="flex items-center text-gray-600">
                                                <x-icon name="fire" class="w-4 h-4 mr-1 text-orange-500" />
                                                <span>{{ $mealPlan->meal->nutritionalInfo->calories ?? 'N/A' }} cal</span>
                                            </div>
                                            <div class="flex items-center text-gray-600">
                                                <x-icon name="currency-dollar" class="w-4 h-4 mr-1 text-green-500" />
                                                <span>₱{{ $mealPlan->meal->cost }}</span>
                                            </div>
                                            @if($pctOfBudget !== null)
                                                <div class="flex items-center {{ $pctBadgeClass }} px-2 py-0.5 rounded-full text-[10px] tracking-wide">
                                                    {{ $pctOfBudget }}% of budget
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center space-x-2 sm:ml-4 sm:self-start">
                                        @if($mealPlan->is_completed)
                                            <div class="flex items-center text-green-600 text-sm font-medium">
                                                <x-icon name="check-circle" class="w-4 h-4 mr-1" variant="solid" />
                                                Done
                                            </div>
                                        @else
                                            <form method="POST" action="{{ route('meal-plans.toggle', $mealPlan) }}" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" 
                                                        class="text-gray-400 hover:text-green-600 p-1.5 rounded-full hover:bg-green-50 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-green-300"
                                                        title="Mark as completed">
                                                    <x-icon name="check-circle" class="w-5 h-5" />
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                                <div class="mt-auto">
                                    <div class="h-1.5 w-full bg-white rounded-full overflow-hidden mb-3">
                                        @php $barColor = $mealPlan->is_completed ? 'bg-green-500' : 'bg-gray-300'; @endphp
                                        <div class="h-full rounded-full {{ $barColor }} transition-colors"></div>
                                    </div>
                                    <div class="flex flex-col sm:flex-row sm:space-x-2 space-y-2 sm:space-y-0 pt-2">
                                    <a href="{{ route('recipes.show', $mealPlan->meal) }}" 
                                       class="flex-1 flex items-center justify-center px-3 py-2 text-xs font-medium text-green-700 bg-green-50 rounded-md hover:bg-green-100 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-green-300">
                                        <x-icon name="book-open" class="w-4 h-4 mr-2" />
                                        Recipe
                                    </a>
                                    <form method="POST" action="{{ route('meal-plans.destroy', $mealPlan) }}" class="inline flex-1">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="w-full flex items-center justify-center px-3 py-2 text-xs font-medium text-red-600 bg-red-50 rounded-md hover:bg-red-100 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-red-300"
                                                onclick="return confirm('Are you sure you want to remove this meal?')"
                                                title="Remove meal">
                                            <x-icon name="x-mark" class="w-4 h-4 mr-2" />
                                            Remove
                                        </button>
                                    </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="flex-1 flex flex-col items-center text-center justify-center py-10">
                            <div class="relative mb-5">
                                <div class="flex items-center justify-center w-20 h-20 mx-auto {{ $mealBgColors[$mealType] }} rounded-full shadow-inner">
                                    <x-icon name="{{ $mealIcons[$mealType] }}" class="w-9 h-9 {{ $mealColors[$mealType] }}" />
                                </div>
                                <div class="absolute inset-0 animate-ping rounded-full {{ $mealBgColors[$mealType] }} opacity-40"></div>
                            </div>
                            <p class="text-gray-500 mb-5 text-sm tracking-wide">No meal planned</p>
                            <a href="{{ route('meal-plans.create') }}?date={{ $selectedDate->format('Y-m-d') }}&meal_type={{ $mealType }}" 
                               class="inline-flex items-center px-4 py-2.5 border border-transparent text-xs font-semibold rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-300 transition-colors duration-200 shadow-sm">
                                <x-icon name="plus" class="w-4 h-4 mr-2" />
                                Add Meal
                            </a>
                        </div>
                    @endif
                </div>
                <div class="absolute inset-0 rounded-xl pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity duration-300 bg-gradient-to-br from-white/0 via-white/0 to-green-50/40"></div>
            </div>
        @endforeach
    </div>

    <!-- Daily Summary -->
    @if($mealPlans->count() > 0)
        <div class="mt-8">
            <div class="bg-white shadow rounded-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <x-icon name="clipboard-document-list" class="w-5 h-5 mr-2 text-gray-500" />
                        Daily Summary
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="text-center p-4 bg-gradient-to-br from-green-50 to-emerald-50 rounded-lg border border-green-100">
                            <div class="flex items-center justify-center w-12 h-12 mx-auto mb-3 bg-green-100 rounded-full">
                                <x-icon name="fire" class="w-6 h-6 text-green-600" />
                            </div>
                            <div class="text-2xl font-bold text-green-600">
                                {{ $mealPlans->sum('meal.nutritionalInfo.calories') ?? 0 }} cal
                            </div>
                            <div class="text-sm text-green-700 font-medium">Total Calories</div>
                        </div>
                        <div class="text-center p-4 bg-gradient-to-br from-blue-50 to-cyan-50 rounded-lg border border-blue-100">
                            <div class="flex items-center justify-center w-12 h-12 mx-auto mb-3 bg-blue-100 rounded-full">
                                <x-icon name="currency-dollar" class="w-6 h-6 text-blue-600" />
                            </div>
                            <div class="text-2xl font-bold text-blue-600">
                                ₱{{ $mealPlans->sum('meal.cost') ?? 0 }}
                            </div>
                            <div class="text-sm text-blue-700 font-medium">Total Cost</div>
                        </div>
                        <div class="text-center p-4 bg-gradient-to-br from-purple-50 to-violet-50 rounded-lg border border-purple-100">
                            <div class="flex items-center justify-center w-12 h-12 mx-auto mb-3 bg-purple-100 rounded-full">
                                <x-icon name="check-circle" class="w-6 h-6 text-purple-600" />
                            </div>
                            <div class="text-2xl font-bold text-purple-600">
                                {{ $mealPlans->where('is_completed', true)->count() }}/{{ $mealPlans->count() }}
                            </div>
                            <div class="text-sm text-purple-700 font-medium">Meals Completed</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

