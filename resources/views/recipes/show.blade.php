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
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-600">₱{{ $meal->cost }}</div>
                            <div class="text-sm text-gray-500">Cost</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600">{{ $meal->nutritionalInfo->calories ?? 'N/A' }}</div>
                            <div class="text-sm text-gray-500">Calories</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-purple-600">{{ $meal->recipe->total_time ?? 'N/A' }}m</div>
                            <div class="text-sm text-gray-500">Total Time</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-orange-600">{{ $meal->difficulty }}</div>
                            <div class="text-sm text-gray-500">Difficulty</div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex space-x-3">
                        <a href="{{ route('meal-plans.create') }}?meal_id={{ $meal->id }}" 
                           class="flex-1 inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Add to Meal Plan
                        </a>
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
            <div class="bg-white shadow rounded-lg mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Ingredients</h3>
                    <p class="text-sm text-gray-600">Serves {{ $meal->recipe->servings ?? 1 }} person(s)</p>
                </div>
                <div class="p-6">
                    <ul class="space-y-3">
                        @foreach($meal->recipe->ingredients as $ingredient)
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-gray-700">{{ $ingredient }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            <!-- Nutritional Information -->
            @if($meal->nutritionalInfo)
            <div class="bg-white shadow rounded-lg mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Nutritional Information</h3>
                    <p class="text-sm text-gray-600">Per serving</p>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Calories</span>
                            <span class="font-medium">{{ $meal->nutritionalInfo->calories ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Protein</span>
                            <span class="font-medium">{{ $meal->nutritionalInfo->protein ?? 'N/A' }}g</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Carbohydrates</span>
                            <span class="font-medium">{{ $meal->nutritionalInfo->carbs ?? 'N/A' }}g</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Fat</span>
                            <span class="font-medium">{{ $meal->nutritionalInfo->fats ?? 'N/A' }}g</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Fiber</span>
                            <span class="font-medium">{{ $meal->nutritionalInfo->fiber ?? 'N/A' }}g</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Sugar</span>
                            <span class="font-medium">{{ $meal->nutritionalInfo->sugar ?? 'N/A' }}g</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Sodium</span>
                            <span class="font-medium">{{ $meal->nutritionalInfo->sodium ?? 'N/A' }}mg</span>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Cooking Time -->
            @if($meal->recipe)
            <div class="bg-white shadow rounded-lg mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Cooking Time</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Prep Time</span>
                            <span class="font-medium">{{ $meal->recipe->prep_time ?? 'N/A' }} minutes</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Cook Time</span>
                            <span class="font-medium">{{ $meal->recipe->cook_time ?? 'N/A' }} minutes</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Total Time</span>
                            <span class="font-medium">{{ $meal->recipe->total_time ?? 'N/A' }} minutes</span>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Local Alternatives -->
            @if($meal->recipe && $meal->recipe->local_alternatives)
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Local Alternatives</h3>
                    <p class="text-sm text-gray-600">Budget-friendly substitutes</p>
                </div>
                <div class="p-6">
                    <ul class="space-y-2">
                        @foreach($meal->recipe->local_alternatives as $alternative)
                            <li class="text-sm text-gray-700">• {{ $alternative }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Similar Recipes -->
    @if($similarMeals->count() > 0)
    <div class="mt-12">
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Similar Recipes</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($similarMeals as $similarMeal)
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="font-semibold text-gray-900">{{ $similarMeal->name }}</h3>
                                <span class="text-sm text-gray-500">₱{{ $similarMeal->cost }}</span>
                            </div>
                            <p class="text-sm text-gray-600 mb-3">{{ Str::limit($similarMeal->description, 60) }}</p>
                            <div class="flex items-center justify-between">
                                <div class="text-sm text-gray-500">
                                    {{ $similarMeal->nutritionalInfo->calories ?? 'N/A' }} cal
                                </div>
                                <a href="{{ route('recipes.show', $similarMeal) }}" 
                                   class="text-sm text-green-600 hover:text-green-700 font-medium">
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

