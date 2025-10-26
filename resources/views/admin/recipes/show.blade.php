@extends('layouts.admin')

@section('title', $recipe->name . ' - Recipe Details')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <!-- Hero Section -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-6">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $recipe->name }}</h1>
                    <p class="text-gray-600 leading-relaxed">{{ $recipe->description }}</p>
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
        </div>



        <!-- Recipe Details -->
        @if($recipe->recipe)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- LEFT COLUMN - Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Recipe Image -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    @if($recipe->image_path)
                        <img src="{{ $recipe->image_url }}" 
                             alt="{{ $recipe->name }}" 
                             class="w-full h-64 object-cover rounded-lg border border-gray-200 mb-4">
                    @else
                        <div class="w-full h-64 bg-gradient-to-br from-orange-400 to-pink-500 rounded-lg flex items-center justify-center text-white mb-4">
                            <div class="text-center">
                                <svg class="w-16 h-16 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/>
                                </svg>
                                <p class="text-lg font-bold">{{ strtoupper(substr($recipe->name, 0, 2)) }}</p>
                            </div>
                        </div>
                    @endif
                </div>
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

            <!-- Ingredients & Price Calculation -->
            @if($recipe->recipe->ingredients)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">Recipe Ingredients</h2>
                            <p class="text-sm text-gray-500 mt-1">{{ count(is_array($recipe->recipe->ingredients) ? $recipe->recipe->ingredients : explode("\n", $recipe->recipe->ingredients)) }} ingredients total</p>
                        </div>
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3-6h.008v.008H15.75V12zm0 3h.008v.008H15.75V15zm0 3h.008v.008H15.75V18zm-12-3h3.75m0 0h3.75m0 0v3.75M5.25 15V9.75M5.25 15a2.25 2.25 0 01-2.25-2.25V9.75A2.25 2.25 0 015.25 7.5h3.75"/>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200 bg-gray-50">
                                <th class="text-left py-3 px-4 text-sm font-medium text-gray-600">#</th>
                                <th class="text-left py-3 px-4 text-sm font-medium text-gray-600">INGREDIENT NAME</th>
                                <th class="text-center py-3 px-4 text-sm font-medium text-gray-600">QUANTITY</th>
                                <th class="text-left py-3 px-4 text-sm font-medium text-gray-600">UNIT</th>
                                <th class="text-right py-3 px-4 text-sm font-medium text-gray-600">PRICE (₱)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @php $totalPrice = 0; @endphp
                            @foreach(is_array($recipe->recipe->ingredients) ? $recipe->recipe->ingredients : explode("\n", $recipe->recipe->ingredients) as $index => $ingredient)
                                @php
                                    if (is_array($ingredient)) {
                                        $name = trim($ingredient['name'] ?? '');
                                        $quantity = $ingredient['amount'] ?? '';
                                        $unit = $ingredient['unit'] ?? '';
                                        $price = isset($ingredient['price']) && is_numeric($ingredient['price']) ? floatval($ingredient['price']) : 0;
                                    } else {
                                        $ingredientText = trim($ingredient);
                                        $parts = explode(' - ', $ingredientText, 2);
                                        $name = $parts[0] ?? '';
                                        $amountUnit = $parts[1] ?? '';
                                        $amountParts = explode(' ', trim($amountUnit), 2);
                                        $quantity = $amountParts[0] ?? '';
                                        $unit = $amountParts[1] ?? '';
                                        $price = 0;
                                    }
                                    $ingredientTotal = ($quantity && $price) ? floatval($quantity) * floatval($price) : $price;
                                    $totalPrice += $ingredientTotal;
                                @endphp
                                @if($name)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="py-3 px-4 text-sm text-gray-500">{{ $loop->iteration }}</td>
                                        <td class="py-3 px-4">
                                            <span class="text-base font-medium text-gray-900">{{ $name }}</span>
                                        </td>
                                        <td class="py-3 px-4 text-center">
                                            @if($quantity)
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-700">
                                                    {{ $quantity }}
                                                </span>
                                            @else
                                                <span class="text-gray-400 text-sm">—</span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-4">
                                            @if($unit)
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700 uppercase tracking-wider">
                                                    {{ $unit }}
                                                </span>
                                            @else
                                                <span class="text-gray-400 text-sm">—</span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-4 text-right">
                                            @if($price)
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">
                                                    ₱{{ number_format($ingredientTotal, 2) }}
                                                </span>
                                            @else
                                                <span class="text-gray-400 text-xs">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="py-3 px-4 text-right font-semibold text-gray-700">Total Price:</td>
                                <td class="py-3 px-4 text-right font-bold text-green-700 bg-green-50">₱{{ number_format($totalPrice, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            @endif
                
                <!-- Instructions -->
                @if($recipe->recipe->instructions)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-lg font-semibold text-gray-900">Instructions</h3>
                        <p class="text-sm text-gray-600 mt-1">Step-by-step cooking guide</p>
                    </div>
                    <div class="p-6">
                        @php
                            $steps = explode("\n", $recipe->recipe->instructions);
                            $steps = array_filter(array_map('trim', $steps));
                        @endphp
                        @if(count($steps) > 1)
                            <ol class="space-y-4">
                                @foreach($steps as $index => $step)
                                    @if($step)
                                    <li class="flex gap-4">
                                        <span class="flex-shrink-0 w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center font-semibold text-sm">{{ $index + 1 }}</span>
                                        <p class="text-gray-700 leading-relaxed">{{ $step }}</p>
                                    </li>
                                    @endif
                                @endforeach
                            </ol>
                        @else
                            <div class="prose max-w-none">
                                <div class="text-gray-700 leading-relaxed whitespace-pre-wrap">{{ $recipe->recipe->instructions }}</div>
                            </div>
                        @endif
                    </div>
                </div>
                @endif

            </div>

            <!-- RIGHT COLUMN - Sidebar (Consolidated) -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Nutritional & Meal Calculation Info -->
                @if($recipe->nutritionalInfo)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-lg font-semibold text-gray-900">Nutritional & Meal Calculation</h3>
                        <p class="text-sm text-gray-600 mt-1">Per serving</p>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-center p-4 bg-blue-50 rounded-lg">
                                <p class="text-2xl font-bold text-blue-600">{{ number_format($recipe->nutritionalInfo->protein, 1) }}g</p>
                                <p class="text-xs text-gray-600 mt-1 uppercase tracking-wide">Protein</p>
                            </div>
                            <div class="text-center p-4 bg-green-50 rounded-lg">
                                <p class="text-2xl font-bold text-green-600">{{ number_format($recipe->nutritionalInfo->carbs, 1) }}g</p>
                                <p class="text-xs text-gray-600 mt-1 uppercase tracking-wide">Carbs</p>
                            </div>
                            <div class="text-center p-4 bg-orange-50 rounded-lg">
                                <p class="text-2xl font-bold text-orange-600">{{ number_format($recipe->nutritionalInfo->fats, 1) }}g</p>
                                <p class="text-xs text-gray-600 mt-1 uppercase tracking-wide">Fats</p>
                            </div>
                            <div class="text-center p-4 bg-purple-50 rounded-lg">
                                <p class="text-2xl font-bold text-purple-600">{{ number_format($recipe->nutritionalInfo->fiber, 1) }}g</p>
                                <p class="text-xs text-gray-600 mt-1 uppercase tracking-wide">Fiber</p>
                            </div>
                            @if($recipe->nutritionalInfo->sugar > 0)
                            <div class="text-center p-4 bg-pink-50 rounded-lg col-span-2">
                                <p class="text-2xl font-bold text-pink-600">{{ number_format($recipe->nutritionalInfo->sugar, 1) }}g</p>
                                <p class="text-xs text-gray-600 mt-1 uppercase tracking-wide">Sugar</p>
                            </div>
                            @endif
                            @if($recipe->nutritionalInfo->sodium > 0)
                            <div class="text-center p-4 bg-red-50 rounded-lg col-span-2">
                                <p class="text-2xl font-bold text-red-600">{{ number_format($recipe->nutritionalInfo->sodium, 1) }}mg</p>
                                <p class="text-xs text-gray-600 mt-1 uppercase tracking-wide">Sodium</p>
                            </div>
                            @endif
                        </div>
                        <div class="mt-6 border-t pt-4">
                            <div class="flex flex-col gap-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs text-gray-500 uppercase tracking-wide">Calories per Serving</span>
                                    <span class="font-semibold text-gray-900">{{ $recipe->calories }} kcal</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-xs text-gray-500 uppercase tracking-wide">Total Price</span>
                                    <span class="font-semibold text-green-700">₱{{ number_format($totalPrice ?? $recipe->cost, 2) }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-xs text-gray-500 uppercase tracking-wide">Cost per Serving</span>
                                    <span class="font-semibold text-green-700">₱{{ $recipe->recipe->servings > 0 ? number_format(($totalPrice ?? $recipe->cost) / $recipe->recipe->servings, 2) : number_format($totalPrice ?? $recipe->cost, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        @endif

        <!-- Footer -->
        <div class="mt-8 text-sm text-gray-500 flex justify-between">
            <span>Created: {{ $recipe->created_at->format('F j, Y \a\t g:i A') }}</span>
            <span>Last Updated: {{ $recipe->updated_at->format('F j, Y \a\t g:i A') }}</span>
        </div>
    </div>
@endsection
