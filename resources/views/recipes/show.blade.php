<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $meal->name }}
            </h2>
            <a href="{{ route('recipes.index') }}" class="text-gray-600 hover:text-gray-900 text-sm">
                ← Back to Recipes
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Recipe Image -->
                    @if($meal->recipe_image)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <img src="{{ Storage::url($meal->recipe_image) }}" 
                                 alt="{{ $meal->name }}" 
                                 class="w-full h-96 object-cover">
                        </div>
                    @endif

                    <!-- Description -->
                    @if($meal->description)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Description</h3>
                            <p class="text-gray-700 leading-relaxed">{{ $meal->description }}</p>
                        </div>
                    @endif

                    <!-- Ingredients -->
                    @if($meal->recipe && $meal->recipe->ingredients)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Ingredients</h3>
                            <ul class="space-y-2">
                                @foreach(json_decode($meal->recipe->ingredients, true) ?? [] as $ingredient)
                                    <li class="flex items-start">
                                        <svg class="w-5 h-5 text-green-600 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-gray-700">
                                            @if(is_array($ingredient))
                                                <strong>{{ $ingredient['quantity'] ?? '' }} {{ $ingredient['unit'] ?? '' }}</strong> {{ $ingredient['name'] ?? '' }}
                                            @else
                                                {{ $ingredient }}
                                            @endif
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Instructions -->
                    @if($meal->recipe && $meal->recipe->instructions)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Instructions</h3>
                            <ol class="space-y-4">
                                @foreach(json_decode($meal->recipe->instructions, true) ?? [] as $index => $step)
                                    <li class="flex">
                                        <span class="flex-shrink-0 w-8 h-8 bg-green-600 text-white rounded-full flex items-center justify-center font-semibold mr-3">
                                            {{ $index + 1 }}
                                        </span>
                                        <p class="text-gray-700 pt-1">{{ $step }}</p>
                                    </li>
                                @endforeach
                            </ol>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Recipe Info -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Recipe Info</h3>
                        <dl class="space-y-3">
                            @if($meal->cuisine_type)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Cuisine Type</dt>
                                    <dd class="text-base text-gray-900 mt-1">{{ ucfirst($meal->cuisine_type) }}</dd>
                                </div>
                            @endif

                            @if($meal->difficulty)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Difficulty</dt>
                                    <dd class="text-base text-gray-900 mt-1">{{ ucfirst($meal->difficulty) }}</dd>
                                </div>
                            @endif

                            @if($meal->recipe && $meal->recipe->prep_time)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Prep Time</dt>
                                    <dd class="text-base text-gray-900 mt-1">{{ $meal->recipe->prep_time }} mins</dd>
                                </div>
                            @endif

                            @if($meal->recipe && $meal->recipe->cook_time)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Cook Time</dt>
                                    <dd class="text-base text-gray-900 mt-1">{{ $meal->recipe->cook_time }} mins</dd>
                                </div>
                            @endif

                            @if($meal->recipe && $meal->recipe->servings)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Servings</dt>
                                    <dd class="text-base text-gray-900 mt-1">{{ $meal->recipe->servings }}</dd>
                                </div>
                            @endif
                        </dl>
                    </div>

                    <!-- Nutritional Information -->
                    @if($meal->nutritionalInfo)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Nutrition Facts</h3>
                            <dl class="space-y-3">
                                <div class="border-b pb-3">
                                    <dt class="text-sm font-medium text-gray-500">Calories</dt>
                                    <dd class="text-2xl font-bold text-green-600 mt-1">
                                        {{ number_format($meal->nutritionalInfo->calories) }} kcal
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Protein</dt>
                                    <dd class="text-base text-gray-900 mt-1">{{ number_format($meal->nutritionalInfo->protein, 1) }}g</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Carbohydrates</dt>
                                    <dd class="text-base text-gray-900 mt-1">{{ number_format($meal->nutritionalInfo->carbohydrates, 1) }}g</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Fat</dt>
                                    <dd class="text-base text-gray-900 mt-1">{{ number_format($meal->nutritionalInfo->fat, 1) }}g</dd>
                                </div>
                                @if($meal->nutritionalInfo->fiber)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Fiber</dt>
                                        <dd class="text-base text-gray-900 mt-1">{{ number_format($meal->nutritionalInfo->fiber, 1) }}g</dd>
                                    </div>
                                @endif
                            </dl>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Similar Recipes -->
            @if($similarRecipes->count() > 0)
                <div class="mt-12">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">Similar Recipes</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach($similarRecipes as $similar)
                            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition">
                                <a href="{{ route('recipes.show', $similar->id) }}" class="block">
                                    @if($similar->recipe_image)
                                        <img src="{{ Storage::url($similar->recipe_image) }}" 
                                             alt="{{ $similar->name }}" 
                                             class="w-full h-48 object-cover">
                                    @else
                                        <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                            <svg class="w-16 h-16 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                                            </svg>
                                        </div>
                                    @endif
                                    
                                    <div class="p-4">
                                        <h4 class="text-lg font-semibold text-gray-900 mb-2">{{ $similar->name }}</h4>
                                        @if($similar->nutritionalInfo)
                                            <p class="text-sm text-green-600 font-medium">
                                                {{ number_format($similar->nutritionalInfo->calories) }} cal
                                            </p>
                                        @endif
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
