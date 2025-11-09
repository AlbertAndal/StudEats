<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <x-loading-button 
                    href="{{ route('recipes.index') }}"
                    variant="secondary"
                    size="sm"
                    square
                    loadingType="spinner"
                    class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100"
                    aria-label="Back to recipes">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                </x-loading-button>
                <div>
                    <p class="text-xs font-semibold text-green-600 uppercase tracking-wider mb-1">Recipe Menu</p>
                    <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                        {{ $meal->name }}
                    </h2>
                    @if($meal->cuisine_type)
                        <p class="text-sm text-gray-500 mt-0.5">{{ ucfirst($meal->cuisine_type) }} Cuisine</p>
                    @endif
                </div>
            </div>
            <div class="flex items-center gap-2">
                @if($meal->is_featured)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                        ⭐ Featured
                    </span>
                @endif
                <x-loading-button 
                    href="{{ route('meal-plans.create', ['meal_id' => $meal->id]) }}"
                    variant="success"
                    size="sm"
                    loadingText="Loading..."
                    loadingType="spinner">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Add to Plan
                </x-loading-button>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Recipe Image -->
                    @if($meal->image_path)
                        <div class="relative bg-white overflow-hidden shadow-sm rounded-lg group">
                            <img src="{{ $meal->image_url }}" 
                                 alt="{{ $meal->name }}" 
                                 class="w-full h-96 object-cover group-hover:scale-105 transition-transform duration-300">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/0 to-black/0"></div>
                            @if($meal->nutritionalInfo)
                                <div class="absolute bottom-4 left-4 right-4 flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="bg-white/90 backdrop-blur-sm rounded-lg px-3 py-2">
                                            <div class="text-xs text-gray-600 font-medium">Calories</div>
                                            <div class="text-lg font-bold text-green-600">{{ number_format($meal->nutritionalInfo->calories) }}</div>
                                        </div>
                                        <div class="bg-white/90 backdrop-blur-sm rounded-lg px-3 py-2">
                                            <div class="text-xs text-gray-600 font-medium">Protein</div>
                                            <div class="text-lg font-bold text-blue-600">{{ number_format($meal->nutritionalInfo->protein, 1) }}g</div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- Description -->
                    @if($meal->description)
                        <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200 hover:shadow-md transition-shadow">
                            <div class="px-6 py-4 bg-gradient-to-r from-blue-50/50 to-white border-b border-gray-200">
                                <div class="flex items-center gap-2">
                                    <div class="h-8 w-8 bg-blue-500/10 rounded-lg flex items-center justify-center">
                                        <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"/>
                                        </svg>
                                    </div>
                                    <h3 class="text-base font-semibold text-gray-900">Description</h3>
                                </div>
                            </div>
                            <div class="p-6">
                                <p class="text-gray-700 leading-relaxed">{{ $meal->description }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Ingredients -->
                    @if($meal->recipe && $meal->recipe->ingredients)
                        <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200 hover:shadow-md transition-shadow">
                            <div class="px-6 py-4 bg-gradient-to-r from-green-50/50 to-white border-b border-gray-200">
                                <div class="flex items-center gap-2">
                                    <div class="h-8 w-8 bg-green-500/10 rounded-lg flex items-center justify-center">
                                        <svg class="h-4 w-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-base font-semibold text-gray-900">Ingredients</h3>
                                        <p class="text-xs text-gray-500">{{ count($meal->recipe->ingredients ?? []) }} items</p>
                                    </div>
                                </div>
                            </div>
                            <div class="p-6">
                                <div class="space-y-3">
                                    @php
                                        $totalCost = 0;
                                    @endphp
                                    @foreach($meal->recipe->ingredients ?? [] as $ingredient)
                                        @php
                                            $price = 0;
                                            if(is_array($ingredient)) {
                                                $quantity = floatval($ingredient['quantity'] ?? $ingredient['amount'] ?? 0);
                                                $unitPrice = floatval($ingredient['price'] ?? 0);
                                                $price = $quantity * $unitPrice;
                                                $totalCost += $price;
                                            }
                                        @endphp
                                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-green-50 transition-colors">
                                            <div class="flex items-start flex-1">
                                                <div class="flex-shrink-0 w-6 h-6 bg-green-500 rounded-full flex items-center justify-center mr-3 mt-0.5">
                                                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                    </svg>
                                                </div>
                                                <div class="flex-1">
                                                    <span class="text-sm text-gray-700">
                                                        @if(is_array($ingredient))
                                                            <strong class="text-gray-900">{{ $ingredient['quantity'] ?? $ingredient['amount'] ?? '' }} {{ $ingredient['unit'] ?? '' }}</strong> {{ $ingredient['name'] ?? '' }}
                                                        @else
                                                            {{ $ingredient }}
                                                        @endif
                                                    </span>
                                                    @if(is_array($ingredient) && isset($ingredient['price']) && $ingredient['price'] > 0)
                                                        <div class="text-xs text-gray-500 mt-1">
                                                            ₱{{ number_format($ingredient['price'], 2) }} per {{ $ingredient['unit'] ?? 'unit' }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            @if($price > 0)
                                                <span class="text-sm font-semibold text-green-600 ml-3">₱{{ number_format($price, 2) }}</span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                                
                                @if($totalCost > 0)
                                    <div class="mt-4 pt-4 border-t border-gray-200">
                                        <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                                            <span class="text-sm font-medium text-gray-700">Total Ingredients Cost:</span>
                                            <span class="text-lg font-bold text-green-600">₱{{ number_format($totalCost, 2) }}</span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Instructions -->
                    @if($meal->recipe && $meal->recipe->instructions)
                        <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200 hover:shadow-md transition-shadow">
                            <div class="px-6 py-4 bg-gradient-to-r from-purple-50/50 to-white border-b border-gray-200">
                                <div class="flex items-center gap-2">
                                    <div class="h-8 w-8 bg-purple-500/10 rounded-lg flex items-center justify-center">
                                        <svg class="h-4 w-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-base font-semibold text-gray-900">Cooking Instructions</h3>
                                        <p class="text-xs text-gray-500">{{ count(json_decode($meal->recipe->instructions, true) ?? []) }} steps</p>
                                    </div>
                                </div>
                            </div>
                            <div class="p-6">
                                <ol class="space-y-4">
                                    @foreach(json_decode($meal->recipe->instructions, true) ?? [] as $index => $step)
                                        <li class="flex gap-4 p-4 bg-gradient-to-r from-purple-50/30 to-white rounded-lg hover:shadow-sm transition-shadow">
                                            <span class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-full flex items-center justify-center font-bold text-sm shadow-sm">
                                                {{ $index + 1 }}
                                            </span>
                                            <p class="text-gray-700 pt-2 leading-relaxed">{{ $step }}</p>
                                        </li>
                                    @endforeach
                                </ol>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Recipe Info -->
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200 hover:shadow-md transition-shadow">
                        <div class="px-6 py-4 bg-gradient-to-r from-indigo-50/50 to-white border-b border-gray-200">
                            <div class="flex items-center gap-2">
                                <div class="h-8 w-8 bg-indigo-500/10 rounded-lg flex items-center justify-center">
                                    <svg class="h-4 w-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <h3 class="text-base font-semibold text-gray-900">Quick Info</h3>
                            </div>
                        </div>
                        <div class="p-6">
                            <dl class="space-y-3">
                                @if($meal->cuisine_type)
                                    <div class="flex items-center justify-between p-3 bg-indigo-50 rounded-lg">
                                        <div class="flex items-center gap-3">
                                            <div class="h-10 w-10 bg-indigo-500/10 rounded-lg flex items-center justify-center">
                                                <span class="text-xl">🍽️</span>
                                            </div>
                                            <div>
                                                <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Cuisine</dt>
                                                <dd class="text-sm font-semibold text-gray-900">{{ ucfirst($meal->cuisine_type) }}</dd>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if($meal->difficulty)
                                    <div class="flex items-center justify-between p-3 bg-orange-50 rounded-lg">
                                        <div class="flex items-center gap-3">
                                            <div class="h-10 w-10 bg-orange-500/10 rounded-lg flex items-center justify-center">
                                                <span class="text-xl">⚡</span>
                                            </div>
                                            <div>
                                                <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Difficulty</dt>
                                                <dd class="text-sm font-semibold text-gray-900">{{ ucfirst($meal->difficulty) }}</dd>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Cost Display -->
                                <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                                    <div class="flex items-center gap-3">
                                        <div class="h-10 w-10 bg-green-500/10 rounded-lg flex items-center justify-center">
                                            <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <span class="text-lg font-bold text-green-600">₱</span>
                                        </div>
                                        <div>
                                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Cost</dt>
                                            <dd class="text-sm font-semibold text-green-700">{{ number_format($meal->cost, 2) }}</dd>
                                        </div>
                                    </div>
                                </div>

                                @if($meal->recipe && ($meal->recipe->prep_time || $meal->recipe->cook_time))
                                    <div class="grid grid-cols-2 gap-3">
                                        @if($meal->recipe->prep_time)
                                            <div class="p-3 bg-blue-50 rounded-lg text-center">
                                                <svg class="h-5 w-5 text-blue-600 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                <dt class="text-xs font-medium text-blue-600 uppercase tracking-wider">Prep</dt>
                                                <dd class="text-sm font-bold text-blue-700">{{ $meal->recipe->prep_time }}m</dd>
                                            </div>
                                        @endif

                                        @if($meal->recipe->cook_time)
                                            <div class="p-3 bg-red-50 rounded-lg text-center">
                                                <span class="text-xl block mb-1">🔥</span>
                                                <dt class="text-xs font-medium text-red-600 uppercase tracking-wider">Cook</dt>
                                                <dd class="text-sm font-bold text-red-700">{{ $meal->recipe->cook_time }}m</dd>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </dl>
                        </div>
                    </div>

                    <!-- Nutritional Information -->
                    @if($meal->nutritionalInfo)
                        <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200 hover:shadow-md transition-shadow">
                            <div class="px-6 py-4 bg-gradient-to-r from-green-50/50 to-white border-b border-gray-200">
                                <div class="flex items-center gap-2">
                                    <div class="h-8 w-8 bg-green-500/10 rounded-lg flex items-center justify-center">
                                        <svg class="h-4 w-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                        </svg>
                                    </div>
                                    <h3 class="text-base font-semibold text-gray-900">Nutrition Facts</h3>
                                </div>
                            </div>
                            <div class="p-6">
                                <dl class="space-y-3">
                                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-200 rounded-lg p-4 text-center">
                                        <dt class="text-xs font-medium text-green-600 uppercase tracking-wider">Calories</dt>
                                        <dd class="text-3xl font-bold text-green-700 mt-1">
                                            {{ number_format($meal->nutritionalInfo->calories) }}
                                            <span class="text-base font-normal text-green-600">kcal</span>
                                        </dd>
                                    </div>
                                    <div class="grid grid-cols-2 gap-3 mt-4">
                                        <div class="bg-blue-50 rounded-lg p-3 text-center">
                                            <dt class="text-xs font-medium text-blue-600 uppercase tracking-wider">Protein</dt>
                                            <dd class="text-xl font-bold text-blue-700 mt-1">{{ number_format($meal->nutritionalInfo->protein, 1) }}<span class="text-sm">g</span></dd>
                                        </div>
                                        <div class="bg-orange-50 rounded-lg p-3 text-center">
                                            <dt class="text-xs font-medium text-orange-600 uppercase tracking-wider">Carbohydrates</dt>
                                            <dd class="text-xl font-bold text-orange-700 mt-1">{{ number_format($meal->nutritionalInfo->carbs, 1) }}<span class="text-sm">g</span></dd>
                                        </div>
                                        <div class="bg-yellow-50 rounded-lg p-3 text-center">
                                            <dt class="text-xs font-medium text-yellow-600 uppercase tracking-wider">Fats</dt>
                                            <dd class="text-xl font-bold text-yellow-700 mt-1">{{ number_format($meal->nutritionalInfo->fats, 1) }}<span class="text-sm">g</span></dd>
                                        </div>
                                        <div class="bg-purple-50 rounded-lg p-3 text-center">
                                            <dt class="text-xs font-medium text-purple-600 uppercase tracking-wider">Fiber</dt>
                                            <dd class="text-xl font-bold text-purple-700 mt-1">{{ number_format($meal->nutritionalInfo->fiber, 1) }}<span class="text-sm">g</span></dd>
                                        </div>
                                        <div class="bg-pink-50 rounded-lg p-3 text-center">
                                            <dt class="text-xs font-medium text-pink-600 uppercase tracking-wider">Sugar</dt>
                                            <dd class="text-xl font-bold text-pink-700 mt-1">{{ number_format($meal->nutritionalInfo->sugar, 1) }}<span class="text-sm">g</span></dd>
                                        </div>
                                        <div class="bg-indigo-50 rounded-lg p-3 text-center">
                                            <dt class="text-xs font-medium text-indigo-600 uppercase tracking-wider">Sodium</dt>
                                            <dd class="text-xl font-bold text-indigo-700 mt-1">{{ number_format($meal->nutritionalInfo->sodium, 0) }}<span class="text-sm">mg</span></dd>
                                        </div>
                                    </div>

                                    @if(auth()->check() && !empty($nutrientWarnings))
                                        <div class="mt-4 space-y-2">
                                            <p class="text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">Health Indicators</p>
                                            @foreach($nutrientWarnings as $warning)
                                                <div class="flex items-center gap-2 px-3 py-2 rounded-lg {{ $warning['type'] === 'danger' ? 'bg-red-50 border border-red-200' : 'bg-yellow-50 border border-yellow-200' }}">
                                                    <svg class="w-4 h-4 {{ $warning['type'] === 'danger' ? 'text-red-600' : 'text-yellow-600' }}" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                    </svg>
                                                    <span class="text-sm font-medium {{ $warning['type'] === 'danger' ? 'text-red-800' : 'text-yellow-800' }}">
                                                        {{ $warning['message'] }}
                                                    </span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </dl>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Similar Recipes -->
            @if($similarRecipes->count() > 0)
                <div class="mt-12">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="h-10 w-10 bg-green-500/10 rounded-lg flex items-center justify-center">
                            <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">Similar Recipes</h3>
                            <p class="text-sm text-gray-500">You might also like these</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach($similarRecipes as $similar)
                            <div class="group bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200 hover:shadow-lg hover:border-green-300 transition-all">
                                <a href="{{ route('recipes.show', $similar->id) }}" class="block">
                                    @if($similar->image_path)
                                        <div class="relative overflow-hidden">
                                            <img src="{{ $similar->image_url }}" 
                                                 alt="{{ $similar->name }}" 
                                                 class="w-full h-48 object-cover group-hover:scale-110 transition-transform duration-300">
                                            <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                        </div>
                                    @else
                                        <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                            <svg class="w-16 h-16 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                                            </svg>
                                        </div>
                                    @endif
                                    
                                    <div class="p-4">
                                        <h4 class="text-lg font-semibold text-gray-900 mb-2 group-hover:text-green-600 transition-colors">{{ $similar->name }}</h4>
                                        <div class="flex items-center justify-between">
                                            @if($similar->nutritionalInfo)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                                    🔥 {{ number_format($similar->nutritionalInfo->calories) }} cal
                                                </span>
                                            @endif
                                            @if($similar->cuisine_type)
                                                <span class="text-xs text-gray-500">{{ ucfirst($similar->cuisine_type) }}</span>
                                            @endif
                                        </div>
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
