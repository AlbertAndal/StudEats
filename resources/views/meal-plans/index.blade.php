                    
                    
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
        <div class="flex flex-col sm:flex-row gap-2">
            <a href="{{ route('meal-plans.weekly') }}" 
               class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
                <x-icon name="calendar-days" class="w-4 h-4 mr-2" />
                Weekly View
            </a>
            <a href="{{ route('meal-plans.bulk-create') }}" 
               class="inline-flex items-center justify-center px-4 py-2 border border-green-600 text-sm font-medium rounded-md text-green-600 bg-white hover:bg-green-50 transition-colors duration-200">
                <x-icon name="calendar-plus" class="w-4 h-4 mr-2" />
                Plan Full Day
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
    <div class="mb-6 bg-white rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow p-6" aria-label="Daily budget usage" role="region">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
                <div class="h-10 w-10 bg-green-500/10 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><rect x="2" y="7" width="20" height="10" rx="2"/><path d="M16 13a2 2 0 1 0 0-4"/></svg>
                </div>
                <div>
                    <h3 class="text-base font-semibold text-gray-900">Daily Budget</h3>
                    <p class="text-xs text-gray-500">Track your spending for today</p>
                </div>
            </div>
            <div class="text-right">
                <div class="text-2xl font-bold text-gray-900">₱{{ number_format($totalCost, 2) }}</div>
                <div class="text-xs text-gray-500">of ₱{{ number_format($dailyBudget, 2) }}</div>
            </div>
        </div>
        
        @php $pctWidth = (int)$budgetPct; @endphp
        <div class="relative">
            <div class="h-3 w-full rounded-full bg-gray-100 overflow-hidden shadow-inner" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="{{ $pctWidth }}" aria-label="Budget used {{ $pctWidth }} percent">
                <div class="h-full {{ $budgetBarColor }} transition-all duration-500 rounded-full" data-pct="{{ $pctWidth }}"></div>
            </div>
            <div class="flex items-center justify-between mt-2">
                <span class="text-xs font-medium text-gray-600">{{ $pctWidth }}% used</span>
                <span class="text-xs font-semibold {{ $remainingClass }}">
                    @if($remaining > 0)
                        <span class="inline-flex items-center gap-1">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            ₱{{ number_format($remaining, 2) }} remaining
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            Over budget by ₱{{ number_format(abs($remaining), 2) }}
                        </span>
                    @endif
                </span>
            </div>
        </div>
        
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                document.querySelectorAll('[data-pct]').forEach(el => {
                    const v = parseInt(el.getAttribute('data-pct')); if(!isNaN(v)){ el.style.width = v + '%'; }
                });
            });
        </script>
        
        @if($budgetPct >= 90)
            <div class="mt-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                <p class="text-sm text-red-700 flex items-center gap-2">
                    <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <span><strong>Budget Alert:</strong> You're approaching or exceeding your daily budget limit.</span>
                </p>
            </div>
        @elseif($budgetPct >= 60)
            <div class="mt-4 p-3 bg-amber-50 border border-amber-200 rounded-lg">
                <p class="text-sm text-amber-700 flex items-center gap-2">
                    <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <span><strong>Reminder:</strong> You've used more than half of your daily budget.</span>
                </p>
            </div>
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
                                    <button type="button" 
                                            onclick="showRemoveMealModal('{{ $mealPlan->id }}', '{{ $mealPlan->meal->name }}', '{{ $mealPlan->meal_type }}', '{{ $selectedDate->format('M j, Y') }}')" 
                                            class="flex-1 flex items-center justify-center px-3 py-2 text-xs font-medium text-red-600 bg-red-50 rounded-md hover:bg-red-100 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-red-300"
                                            title="Remove meal">
                                        <x-icon name="x-mark" class="w-4 h-4 mr-2" />
                                        Remove
                                    </button>
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
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><rect x="2" y="7" width="20" height="10" rx="2"/><path d="M16 13a2 2 0 1 0 0-4"/></svg>
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
                                        <span class="capitalize" id="modalMealType">meal type</span> • <span id="modalMealDate">Date</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <p class="text-sm text-gray-500">
                            This action cannot be undone. The meal will be permanently removed from your meal plan for this date.
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Modal Actions -->
            <div class="mt-6 sm:mt-4 sm:flex sm:flex-row-reverse">
                <form id="removeMealForm" method="POST" action="" class="inline-flex w-full sm:w-auto">
                    @csrf
                    @method('DELETE')
                    <button type="submit" id="confirmRemoveBtn"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200">
                        <svg class="-ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1-1H8a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        <span class="btn-text">Yes, Remove Meal</span>
                    </button>
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
// Modal functionality for meal removal
function showRemoveMealModal(mealPlanId, mealName, mealType, mealDate) {
    // Update modal content
    document.getElementById('modalMealName').textContent = mealName;
    document.getElementById('modalMealType').textContent = mealType;
    document.getElementById('modalMealDate').textContent = mealDate;
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
        const submitBtn = document.getElementById('confirmRemoveBtn');
        const btnText = submitBtn.querySelector('.btn-text');
        
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
</script>

@endsection

