@php
    $displayCost = $meal->getDisplayCost('NCR');
    $hasRealTimePricing = $meal->hasRealTimePricing('NCR');
@endphp

<div class="group border-2 border-gray-200 rounded-lg p-4 cursor-pointer hover:border-green-400 hover:shadow-lg transition-all duration-300 bg-white hover:bg-green-50"
     data-meal-id="{{ $meal->id }}"
     data-meal-type="{{ $mealType }}"
     data-meal-name="{{ $meal->name }}"
     data-meal-cost="{{ $displayCost }}"
     data-meal-calories="{{ optional($meal->nutritionalInfo)->calories ?? 0 }}"
     aria-label="Select {{ $meal->name }} for {{ $mealType }}">
    
    <!-- Meal Image/Icon -->
    <div class="relative mb-3">
        @if($meal->image_path)
            <div class="w-full h-24 bg-gray-100 rounded-md overflow-hidden">
                <img src="{{ $meal->image_url }}" 
                     alt="{{ $meal->name }}" 
                     class="w-full h-full object-cover"
                     loading="lazy"
                     onerror="this.onerror=null; this.style.display='none'; const fallback = this.nextElementSibling; if(fallback) fallback.style.display='flex';">
                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-orange-400 to-pink-500" style="display:none;">
                    <span class="text-white font-bold text-lg">{{ strtoupper(substr($meal->name, 0, 2)) }}</span>
                </div>
            </div>
        @else
            <div class="w-full h-24 bg-gradient-to-br from-green-100 to-blue-100 rounded-md flex items-center justify-center">
                <span class="text-3xl opacity-70">🍽️</span>
            </div>
        @endif
        
        <!-- Price Badge -->
        <div class="absolute top-1 right-1 bg-green-600 group-hover:bg-green-700 text-white px-1.5 py-0.5 rounded-md shadow-md">
            <span class="text-xs font-bold">₱{{ number_format($displayCost, 0) }}</span>
        </div>
    </div>

    <!-- Meal Info -->
    <div class="space-y-2">
        <h3 class="text-sm font-bold text-gray-900 group-hover:text-green-700 transition-colors line-clamp-2">
            {{ $meal->name }}
        </h3>
        
        <div class="flex items-center justify-between text-xs">
            <div class="flex items-center gap-1">
                <span class="text-orange-600">🔥</span>
                <span class="text-orange-600 font-medium">{{ optional($meal->nutritionalInfo)->calories ?? '300' }} cal</span>
            </div>
            <div class="flex items-center gap-1">
                <span class="text-blue-600">⏱️</span>
                <span class="text-blue-600 font-medium">{{ optional($meal->recipe)->total_time ?? $meal->cooking_time ?? '30' }}min</span>
            </div>
        </div>
        
        <div class="text-xs text-gray-600 line-clamp-2">
            {{ Str::limit($meal->description, 60) }}
        </div>
    </div>

    <!-- Selection Indicator -->
    <div class="selected-indicator absolute inset-0 pointer-events-none opacity-0 transition-opacity duration-300">
        <div class="absolute top-2 left-2 w-6 h-6 bg-green-500 rounded-full flex items-center justify-center">
            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
            </svg>
        </div>
    </div>
</div>