@extends('layouts.admin')

@section('title', $recipe->name . ' - Recipe Details')

@section('content')
    <div class="max-w-5xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $recipe->name }}</h1>
                <p class="mt-2 text-gray-600">{{ $recipe->description }}</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.recipes.edit', $recipe) }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                    </svg>
                    Edit Recipe
                </a>
                <a href="{{ route('admin.recipes.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3"/>
                    </svg>
                    Back to Recipes
                </a>
            </div>
        </div>

        <!-- Recipe Image & Basic Info -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Image -->
                    <div class="lg:col-span-1">
                        @if($recipe->image_path)
                            <img src="{{ $recipe->image_url }}" 
                                 alt="{{ $recipe->name }}" 
                                 class="w-full h-64 object-cover rounded-lg border border-gray-200">
                        @else
                            <div class="w-full h-64 bg-gradient-to-br from-orange-400 to-pink-500 rounded-lg flex items-center justify-center text-white">
                                <div class="text-center">
                                    <svg class="w-16 h-16 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/>
                                    </svg>
                                    <p class="text-lg font-bold">{{ strtoupper(substr($recipe->name, 0, 2)) }}</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Basic Info -->
                    <div class="lg:col-span-2">
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Cuisine Type</h3>
                                <p class="mt-1 text-lg font-semibold text-gray-900">{{ ucfirst($recipe->cuisine_type) }}</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Difficulty</h3>
                                <span class="mt-1 inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                    @if($recipe->difficulty === 'easy') bg-green-100
                                    @elseif($recipe->difficulty === 'medium') text-yellow-800
                                    @else
                                    @endif">
                                    {{ ucfirst($recipe->difficulty) }}
                                </span>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Calories</h3>
                                <p class="mt-1 text-lg font-semibold text-gray-900">{{ $recipe->calories }} kcal</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Cost</h3>
                                <p class="mt-1 text-lg font-semibold text-gray-900">₱{{ number_format($recipe->cost, 2) }}</p>
                            </div>
                        </div>

                        @if($recipe->is_featured)
                            <div class="mt-6">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/>
                                    </svg>
                                    Featured Recipe
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Recipe Details -->
        @if($recipe->recipe)
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Timing & Servings -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="text-lg font-semibold text-gray-900">Cooking Information</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @if($recipe->recipe->prep_time)
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div>
                                    <p class="text-sm text-gray-500">Prep Time</p>
                                    <p class="font-semibold">{{ $recipe->recipe->prep_time }} minutes</p>
                                </div>
                            </div>
                        @endif

                        @if($recipe->recipe->cook_time)
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.362 5.214A8.252 8.252 0 0112 21 8.25 8.25 0 016.038 7.048 8.287 8.287 0 009 9.6a8.983 8.983 0 013.361-6.867 8.21 8.21 0 003 2.48z"/>
                                </svg>
                                <div>
                                    <p class="text-sm text-gray-500">Cook Time</p>
                                    <p class="font-semibold">{{ $recipe->recipe->cook_time }} minutes</p>
                                </div>
                            </div>
                        @endif

                        @if($recipe->recipe->servings)
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                                </svg>
                                <div>
                                    <p class="text-sm text-gray-500">Servings</p>
                                    <p class="font-semibold">{{ $recipe->recipe->servings }} people</p>
                                </div>
                            </div>
                        @endif

                        @if($recipe->recipe->prep_time && $recipe->recipe->cook_time)
                            <div class="pt-4 border-t border-gray-200">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <div>
                                        <p class="text-sm text-gray-500">Total Time</p>
                                        <p class="font-semibold text-lg">{{ $recipe->recipe->prep_time + $recipe->recipe->cook_time }} minutes</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Nutritional Info -->
            @if($recipe->nutritionalInfo)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="text-lg font-semibold text-gray-900">Nutritional Information</h2>
                    <p class="text-sm text-gray-600 mt-1">Per serving</p>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-center">
                            <p class="text-2xl font-bold text-blue-600">{{ number_format($recipe->nutritionalInfo->protein, 1) }}g</p>
                            <p class="text-sm text-gray-500">Protein</p>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-bold text-green-600">{{ number_format($recipe->nutritionalInfo->carbs, 1) }}g</p>
                            <p class="text-sm text-gray-500">Carbs</p>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-bold text-yellow-600">{{ number_format($recipe->nutritionalInfo->fats, 1) }}g</p>
                            <p class="text-sm text-gray-500">Fats</p>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-bold text-purple-600">{{ number_format($recipe->nutritionalInfo->fiber, 1) }}g</p>
                            <p class="text-sm text-gray-500">Fiber</p>
                        </div>
                        @if($recipe->nutritionalInfo->sugar > 0)
                        <div class="text-center">
                            <p class="text-2xl font-bold text-pink-600">{{ number_format($recipe->nutritionalInfo->sugar, 1) }}g</p>
                            <p class="text-sm text-gray-500">Sugar</p>
                        </div>
                        @endif
                        @if($recipe->nutritionalInfo->sodium > 0)
                        <div class="text-center">
                            <p class="text-2xl font-bold text-red-600">{{ number_format($recipe->nutritionalInfo->sodium, 1) }}mg</p>
                            <p class="text-sm text-gray-500">Sodium</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Ingredients & Instructions -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Ingredients -->
            @if($recipe->recipe->ingredients)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="text-lg font-semibold text-gray-900">Ingredients</h2>
                </div>
                <div class="p-6">
                    <ul class="space-y-2">
                        @foreach(is_array($recipe->recipe->ingredients) ? $recipe->recipe->ingredients : explode("\n", $recipe->recipe->ingredients) as $ingredient)
                            @if(is_array($ingredient))
                                @if(trim($ingredient['name'] ?? ''))
                                    <li class="flex items-start">
                                        <svg class="w-5 h-5 text-green-500 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <div>
                                            <span class="font-medium">{{ trim($ingredient['name']) }}</span>
                                            <span class="text-gray-500 ml-2">{{ $ingredient['amount'] }} {{ $ingredient['unit'] }}</span>
                                        </div>
                                    </li>
                                @endif
                            @else
                                @if(trim($ingredient))
                                    <li class="flex items-start">
                                        <svg class="w-5 h-5 text-green-500 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span>{{ trim($ingredient) }}</span>
                                    </li>
                                @endif
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            <!-- Local Alternatives -->
            @if($recipe->recipe->local_alternatives)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="text-lg font-semibold text-gray-900">Local Alternatives</h2>
                    <p class="text-sm text-gray-600 mt-1">Filipino ingredient substitutes</p>
                </div>
                <div class="p-6">
                    <ul class="space-y-3">
                        @foreach(is_array($recipe->recipe->local_alternatives) ? $recipe->recipe->local_alternatives : explode("\n", $recipe->recipe->local_alternatives) as $alternative)
                            @if(is_array($alternative))
                                <li class="bg-gray-50 p-3 rounded-lg">
                                    <div class="flex items-start justify-between mb-1">
                                        <span class="font-medium text-gray-900">{{ $alternative['original'] }}</span>
                                        <svg class="w-4 h-4 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3"/>
                                        </svg>
                                    </div>
                                    <div class="text-blue-600 font-medium mb-1">{{ $alternative['alternative'] }}</div>
                                    @if(isset($alternative['notes']))
                                        <div class="text-xs text-gray-600">{{ $alternative['notes'] }}</div>
                                    @endif
                                </li>
                            @else
                                @if(trim($alternative))
                                    <li class="flex items-start">
                                        <svg class="w-5 h-5 text-blue-500 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5"/>
                                        </svg>
                                        <span>{{ trim($alternative) }}</span>
                                    </li>
                                @endif
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif
        </div>

        <!-- Instructions -->
        @if($recipe->recipe->instructions)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mt-8">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-900">Instructions</h2>
                <p class="text-sm text-gray-600 mt-1">Step-by-step cooking guide</p>
            </div>
            <div class="p-6">
                <div class="prose max-w-none">
                    <div class="whitespace-pre-wrap text-gray-700 leading-relaxed">{{ $recipe->recipe->instructions }}</div>
                </div>
            </div>
        </div>
        @endif
        @endif

        <!-- Recipe Meta -->
        <div class="bg-gray-50 rounded-xl p-6 mt-8">
            <div class="flex items-center justify-between text-sm text-gray-600">
                <div>
                    <span class="font-medium">Created:</span> {{ $recipe->created_at->format('F j, Y \a\t g:i A') }}
                </div>
                <div>
                    <span class="font-medium">Last Updated:</span> {{ $recipe->updated_at->format('F j, Y \a\t g:i A') }}
                </div>
            </div>
        </div>
    </div>
@endsection
