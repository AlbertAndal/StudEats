@props([
    'title',
    'description',
    'image',
    'prep' => null,
    'skill' => null,
    'servings' => '1 person',
    'category' => null,
])

<article class="bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow duration-300 group">
    <div class="relative w-full h-40 sm:h-48 md:h-56 bg-gray-100 overflow-hidden">
        <img src="{{ $image }}" alt="{{ $title }}" loading="lazy" decoding="async" 
             class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
        @if($category)
            <div class="absolute top-3 left-3">
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-600 text-white shadow-sm">
                    {{ $category }}
                </span>
            </div>
        @endif
    </div>
    <div class="p-5 sm:p-6">
        <h3 class="text-lg sm:text-xl font-semibold text-gray-900 group-hover:text-green-600 transition-colors">{{ $title }}</h3>
        <p class="mt-2 text-sm sm:text-base text-gray-700 leading-relaxed">{{ $description }}</p>
        
        <div class="mt-4 flex flex-wrap items-center gap-3 text-xs sm:text-sm">
            @if($prep)
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-blue-50 text-blue-700 rounded-full">
                    <span>⏱️</span><span>{{ $prep }}</span>
                </span>
            @endif
            @if($skill)
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-orange-50 text-orange-700 rounded-full">
                    <span>👩‍🍳</span><span>{{ $skill }}</span>
                </span>
            @endif
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-gray-50 text-gray-700 rounded-full">
                <span>🍽️</span><span>{{ $servings }}</span>
            </span>
        </div>
        
        <div class="mt-4 pt-4 border-t border-gray-100">
            <button class="text-green-600 hover:text-green-700 text-sm font-medium group-hover:underline transition-colors">
                View Recipe →
            </button>
        </div>
    </div>
</article>
