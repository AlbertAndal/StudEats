@extends('layouts.app')

@section('title', $meal->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <nav class="flex mb-8" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('recipes.index') }}" class="text-gray-700 hover:text-green-600">
                    Recipes
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-1 text-gray-500 md:ml-2">{{ $meal->name }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <!-- Recipe Header -->
            <div class="bg-white shadow rounded-lg mb-8">
                <div class="relative">
                    @if($meal->image_url)
                        <img src="{{ $meal->image_url }}" alt="{{ $meal->name }}" class="w-full h-64 object-cover rounded-t-lg" loading="lazy">
                    @else
                        <div class="w-full h-64 bg-gray-100 flex items-center justify-center rounded-t-lg text-gray-400">
                            <span class="text-6xl">🍽️</span>
                        </div>
                    @endif
                    <div class="absolute top-4 right-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            {{ $meal->cuisine_type }}
                        </span>
                    </div>
                </div>
                
                <div class="p-6">
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $meal->name }}</h1>
                    <p class="text-gray-600 mb-6">{{ $meal->description }}</p>
                    
                    <!-- Quick Info -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <!-- Cost Section - More Prominent -->
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg p-4 border-2 border-green-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    @php
                                        $displayCost = $meal->getDisplayCost('NCR');
                                        $hasRealTimePricing = $meal->hasRealTimePricing('NCR');
                                        
                                        // Calculate total from ingredient relations if available
                                        $totalEstimatedCost = 0;
                                        if($meal->recipe && $meal->recipe->ingredientRelations->count() > 0) {
                                            foreach($meal->recipe->ingredientRelations as $ingredient) {
                                                $quantity = $ingredient->pivot->quantity;
                                                $price = $ingredient->getPriceForRegion('NCR');
                                                $estimatedCost = $ingredient->pivot->estimated_cost;
                                                $cost = $price ? ($quantity * $price) : $estimatedCost;
                                                $totalEstimatedCost += $cost;
                                            }
                                        } elseif($meal->recipe && $meal->recipe->ingredients) {
                                            // Use ingredients array (already cast from JSON)
                                            $ingredients = $meal->recipe->ingredients;
                                            if(is_array($ingredients)) {
                                                foreach($ingredients as $ingredient) {
                                                    $amount = floatval($ingredient['amount'] ?? 0);
                                                    $price = floatval($ingredient['price'] ?? 0);
                                                    $totalEstimatedCost += $amount * $price;
                                                }
                                            }
                                        } else {
                                            $totalEstimatedCost = $displayCost;
                                        }
                                    @endphp
                                    <div class="text-3xl font-bold text-green-700">₱{{ number_format($totalEstimatedCost, 2) }}</div>
                                    <div class="text-sm font-medium text-green-600 mt-1">
                                        Total Estimated Cost
                                        @if($hasRealTimePricing)
                                            <span class="text-blue-600 inline-flex items-center ml-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                                </svg>
                                                Live Pricing
                                            </span>
                                        @endif
                                    </div>
                                    <div class="text-xs text-gray-600 mt-1">
                                        For {{ $meal->recipe->servings ?? 1 }} {{ ($meal->recipe->servings ?? 1) == 1 ? 'serving' : 'servings' }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Other Quick Stats -->
                        <div class="grid grid-cols-3 gap-3">
                            <div class="flex flex-col items-center justify-center bg-blue-50 rounded-lg p-4">
                                <div class="text-2xl font-bold text-blue-600 mb-1">{{ $meal->nutritionalInfo->calories ?? 'N/A' }}</div>
                                <div class="text-sm text-gray-600 font-medium">Calories</div>
                            </div>
                            <div class="flex flex-col items-center justify-center bg-purple-50 rounded-lg p-4">
                                <div class="text-2xl font-bold text-purple-600 mb-1">{{ $meal->recipe->total_time ?? 'N/A' }}m</div>
                                <div class="text-sm text-gray-600 font-medium">Total Time</div>
                            </div>
                            <div class="flex flex-col items-center justify-center bg-orange-50 rounded-lg p-4">
                                <div class="text-2xl font-bold text-orange-600 mb-1">{{ $meal->difficulty }}</div>
                                <div class="text-sm text-gray-600 font-medium">Difficulty</div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex space-x-3">
                        @auth
                            <a href="{{ route('meal-plans.create') }}?meal_id={{ $meal->id }}" 
                               class="flex-1 inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Add to Meal Plan
                            </a>
                        @else
                            <a href="{{ route('login') }}" 
                               class="flex-1 inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                </svg>
                                Login to Add to Plan
                            </a>
                        @endauth
                        <button onclick="window.print()" 
                                class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                            </svg>
                            Print Recipe
                        </button>
                    </div>
                </div>
            </div>

            <!-- Recipe Instructions -->
            @if($meal->recipe)
            <div class="bg-white shadow rounded-lg mb-8">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900">Cooking Instructions</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($meal->recipe->formatted_instructions as $index => $instruction)
                            <div class="flex">
                                <div class="flex-shrink-0 w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-4">
                                    <span class="text-sm font-medium text-green-600">{{ $index + 1 }}</span>
                                </div>
                                <div class="flex-1">
                                    <p class="text-gray-700">{{ $instruction }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Ingredients -->
            @if($meal->recipe && $meal->recipe->ingredients)
            <div class="bg-white shadow-sm rounded-2xl mb-8 border border-gray-100 overflow-hidden">
                <!-- Header Section -->
                <div class="px-8 py-6 bg-gradient-to-r from-gray-50 to-white border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 tracking-tight">Ingredients</h3>
                            <p class="text-sm text-gray-500 mt-1 font-medium">Serves {{ $meal->recipe->servings ?? 1 }} {{ ($meal->recipe->servings ?? 1) == 1 ? 'person' : 'people' }}</p>
                        </div>
                        <div class="w-10 h-10 bg-green-50 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3-6h.008v.008H15.75V12zm0 3h.008v.008H15.75V15zm0 3h.008v.008H15.75V18zm-12-3h3.75m0 0h3.75m0 0v3.75M5.25 15V9.75M5.25 15a2.25 2.25 0 01-2.25-2.25V9.75A2.25 2.25 0 015.25 7.5h3.75"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Ingredients List -->
                <div class="px-8 py-6">
                    <div class="space-y-4">
                        @foreach($meal->recipe->ingredients as $index => $ingredient)
                            <div class="group flex items-center space-x-4 p-4 rounded-xl hover:bg-gray-50 transition-all duration-200">
                                <!-- Index Number -->
                                <div class="flex-shrink-0 w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center group-hover:bg-green-100 transition-colors duration-200">
                                    <span class="text-sm font-semibold text-gray-600 group-hover:text-green-700">{{ $index + 1 }}</span>
                                </div>
                                
                                <!-- Ingredient Details -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-baseline space-x-3">
                                        <h4 class="text-base font-medium text-gray-900 leading-tight">
                                            {{ $ingredient['name'] ?? $ingredient }}
                                        </h4>
                                        @if(is_array($ingredient) && isset($ingredient['amount']))
                                            <div class="flex items-center space-x-1">
                                                <span class="text-lg font-semibold text-green-600">{{ $ingredient['amount'] }}</span>
                                                <span class="text-sm text-gray-500 font-medium uppercase tracking-wide">{{ $ingredient['unit'] ?? '' }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- Check Icon -->
                                <div class="flex-shrink-0 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                    <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center">
                                        <svg class="w-3.5 h-3.5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>
            @endif

            <!-- Nutritional Information -->
            @if($meal->nutritionalInfo)
            <div class="bg-gray-50 shadow rounded-lg mb-6 border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-300">
                    <h3 class="text-lg font-semibold text-gray-700">Nutritional Information</h3>
                    <p class="text-sm text-gray-500">Per serving</p>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Calories</span>
                            <span class="font-medium text-gray-800">{{ $meal->nutritionalInfo->calories ?? 'N/A' }} cal</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Protein</span>
                            <span class="font-medium text-gray-800">{{ $meal->nutritionalInfo->protein ?? 'N/A' }}g</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Carbohydrates</span>
                            <span class="font-medium text-gray-800">{{ $meal->nutritionalInfo->carbs ?? 'N/A' }}g</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Fat</span>
                            <span class="font-medium text-gray-800">{{ $meal->nutritionalInfo->fats ?? 'N/A' }}g</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Fiber</span>
                            <span class="font-medium text-gray-800">{{ $meal->nutritionalInfo->fiber ?? 'N/A' }}g</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Sugar</span>
                            <span class="font-medium text-gray-800">{{ $meal->nutritionalInfo->sugar ?? 'N/A' }}g</span>
                        </div>
                    </div>
                </div>
            </div>
            @endif


        </div>
    </div>

    <!-- Similar Recipes -->
    @if($similarMeals->count() > 0)
    <div class="mt-12">
        <div class="bg-gray-50 shadow rounded-lg border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-300">
                <h2 class="text-xl font-semibold text-gray-700">Similar Recipes</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($similarMeals as $similarMeal)
                        <div class="bg-gray-100 rounded-lg p-4 border border-gray-200">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="font-semibold text-gray-800">{{ $similarMeal->name }}</h3>
                                <span class="text-sm text-gray-600">₱{{ $similarMeal->cost }}</span>
                            </div>
                            <p class="text-sm text-gray-600 mb-3">{{ Str::limit($similarMeal->description, 60) }}</p>
                            <div class="flex items-center justify-between">
                                <div class="text-sm text-gray-600">
                                    {{ $similarMeal->nutritionalInfo->calories ?? 'N/A' }} cal
                                </div>
                                <a href="{{ route('recipes.show', $similarMeal) }}" 
                                   class="text-sm text-green-700 hover:text-green-800 font-medium">
                                    View Recipe →
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

