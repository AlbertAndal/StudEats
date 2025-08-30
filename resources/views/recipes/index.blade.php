@extends('layouts.app')

@section('title', 'Recipes')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Recipe Collection</h1>
        <p class="mt-2 text-gray-600">Discover affordable, Filipino-friendly recipes</p>
    </div>

    <!-- Enhanced Search and Filters -->
    <div class="bg-white shadow rounded-lg mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <h2 class="text-lg font-semibold text-gray-900">Find Your Perfect Recipe</h2>
            </div>
            <p class="text-sm text-gray-600 mt-1">Search and filter recipes to match your preferences and budget</p>
        </div>
        
        <div class="p-6">
            <form method="GET" action="{{ route('recipes.index') }}" class="space-y-6" id="recipe-search-form">
                <!-- Primary Search Row -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Enhanced Search Recipes Field -->
                    <div class="lg:col-span-2">
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                            <svg class="w-4 h-4 inline mr-1 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Search Recipes
                            @if(request('search'))
                                <span class="ml-1 px-2 py-0.5 text-xs font-medium bg-green-100 text-green-800 rounded-full">Active</span>
                            @endif
                        </label>
                        <div class="relative">
                            <input type="text" 
                                   id="search" 
                                   name="search" 
                                   class="block w-full pl-10 pr-10 py-3 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 sm:text-sm transition-all duration-200 {{ request('search') ? 'bg-green-50 border-green-300' : '' }}"
                                   placeholder="Search by recipe name, ingredients, or description..."
                                   value="{{ request('search') }}"
                                   autocomplete="off">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            @if(request('search'))
                                <button type="button" 
                                        onclick="clearSearchField()"
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition-colors">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            @endif
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Type to search across recipe names, ingredients, and descriptions</p>
                    </div>

                    <!-- Quick Search Button -->
                    <div class="flex items-end">
                        <button type="submit" 
                                class="w-full inline-flex items-center justify-center px-6 py-3 border border-transparent text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 shadow-sm hover:shadow-md">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Search Recipes
                        </button>
                    </div>
                </div>

                <!-- Advanced Filters Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Enhanced Cuisine Type Field -->
                    <div>
                        <label for="cuisine_type" class="block text-sm font-medium text-gray-700 mb-2">
                            <svg class="w-4 h-4 inline mr-1 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945"/>
                            </svg>
                            Cuisine Type
                            @if(request('cuisine_type'))
                                <span class="ml-1 px-2 py-0.5 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">{{ request('cuisine_type') }}</span>
                            @endif
                        </label>
                        <div class="relative">
                            <select id="cuisine_type" 
                                    name="cuisine_type" 
                                    class="block w-full pl-10 pr-10 py-3 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm appearance-none transition-all duration-200 {{ request('cuisine_type') ? 'bg-blue-50 border-blue-300' : '' }}">
                                <option value="">All Cuisines</option>
                                <option value="Filipino" {{ request('cuisine_type') == 'Filipino' ? 'selected' : '' }}>🇵🇭 Filipino</option>
                                <option value="Asian" {{ request('cuisine_type') == 'Asian' ? 'selected' : '' }}>🥢 Asian</option>
                                <option value="Western" {{ request('cuisine_type') == 'Western' ? 'selected' : '' }}>🍕 Western</option>
                                <option value="Mediterranean" {{ request('cuisine_type') == 'Mediterranean' ? 'selected' : '' }}>🫒 Mediterranean</option>
                                <option value="Mexican" {{ request('cuisine_type') == 'Mexican' ? 'selected' : '' }}>🌮 Mexican</option>
                                <option value="Indian" {{ request('cuisine_type') == 'Indian' ? 'selected' : '' }}>🍛 Indian</option>
                            </select>
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945"/>
                                </svg>
                            </div>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Filter by culinary tradition or regional style</p>
                    </div>

                    <!-- Enhanced Max Cost Field -->
                    <div>
                        <label for="max_cost" class="block text-sm font-medium text-gray-700 mb-2">
                            <svg class="w-4 h-4 inline mr-1 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                            </svg>
                            Max Cost (₱)
                            @if(request('max_cost'))
                                <span class="ml-1 px-2 py-0.5 text-xs font-medium bg-purple-100 text-purple-800 rounded-full">Under ₱{{ request('max_cost') }}</span>
                            @endif
                        </label>
                        <div class="relative">
                            <input type="number" 
                                   id="max_cost" 
                                   name="max_cost" 
                                   min="0" 
                                   step="10"
                                   class="block w-full pl-10 pr-16 py-3 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 sm:text-sm transition-all duration-200 {{ request('max_cost') ? 'bg-purple-50 border-purple-300' : '' }}"
                                   placeholder="Enter maximum cost..."
                                   value="{{ request('max_cost') }}"
                                   oninput="updateCostDisplay(this.value)">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">₱</span>
                            </div>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <div class="flex space-x-1">
                                    <button type="button" onclick="setCostFilter(100)" class="px-2 py-1 text-xs bg-gray-100 hover:bg-gray-200 rounded text-gray-600 transition-colors">₱100</button>
                                    <button type="button" onclick="setCostFilter(200)" class="px-2 py-1 text-xs bg-gray-100 hover:bg-gray-200 rounded text-gray-600 transition-colors">₱200</button>
                                    <button type="button" onclick="setCostFilter(500)" class="px-2 py-1 text-xs bg-gray-100 hover:bg-gray-200 rounded text-gray-600 transition-colors">₱500</button>
                                </div>
                            </div>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Set your budget limit to find affordable recipes</p>
                    </div>
                </div>

                <!-- Action Buttons and Results -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 pt-4 border-t border-gray-200">
                    <div class="flex flex-wrap items-center gap-3">
                        <button type="submit" 
                                class="inline-flex items-center px-6 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 shadow-sm hover:shadow-md">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Apply Filters
                        </button>
                        
                        @if(request()->hasAny(['search', 'cuisine_type', 'max_cost']))
                            <a href="{{ route('recipes.index') }}" 
                               class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Clear All Filters
                            </a>
                        @endif

                        <!-- Active Filters Display -->
                        @if(request()->hasAny(['search', 'cuisine_type', 'max_cost']))
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="text-xs text-gray-500">Active filters:</span>
                                @if(request('search'))
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Search: "{{ Str::limit(request('search'), 20) }}"
                                        <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}" class="ml-1 text-green-600 hover:text-green-800">×</a>
                                    </span>
                                @endif
                                @if(request('cuisine_type'))
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ request('cuisine_type') }}
                                        <a href="{{ request()->fullUrlWithQuery(['cuisine_type' => null]) }}" class="ml-1 text-blue-600 hover:text-blue-800">×</a>
                                    </span>
                                @endif
                                @if(request('max_cost'))
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        Under ₱{{ request('max_cost') }}
                                        <a href="{{ request()->fullUrlWithQuery(['max_cost' => null]) }}" class="ml-1 text-purple-600 hover:text-purple-800">×</a>
                                    </span>
                                @endif
                            </div>
                        @endif
                    </div>
                    
                    <!-- Results Counter -->
                    <div class="flex items-center text-sm text-gray-500 bg-gray-50 px-3 py-2 rounded-lg">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <strong>{{ number_format($meals->total()) }}</strong> recipes found
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Recipes Grid -->
    @if($meals->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($meals as $meal)
                <div class="bg-white shadow rounded-lg overflow-hidden hover:shadow-lg transition-shadow">
                    <!-- Recipe Image -->
                    <div class="aspect-w-16 aspect-h-9 bg-gray-100">
                        @if($meal->image_url)
                            <img src="{{ $meal->image_url }}" alt="{{ $meal->name }}" class="w-full h-48 object-cover" loading="lazy">
                        @else
                            <div class="w-full h-48 flex items-center justify-center text-gray-400">
                                <span class="text-4xl">🍽️</span>
                            </div>
                        @endif
                    </div>

                    <!-- Recipe Info -->
                    <div class="p-4">
                        <div class="flex items-start justify-between mb-2">
                            <h3 class="font-semibold text-gray-900 text-lg">{{ $meal->name }}</h3>
                            <span class="text-sm font-medium text-green-600">₱{{ $meal->cost }}</span>
                        </div>
                        
                        <p class="text-sm text-gray-600 mb-3">{{ Str::limit($meal->description, 80) }}</p>
                        
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center space-x-4 text-sm text-gray-500">
                                <span>{{ $meal->nutritionalInfo->calories ?? 'N/A' }} cal</span>
                                <span class="capitalize">{{ $meal->cuisine_type }}</span>
                            </div>
                            <span class="text-xs px-2 py-1 bg-gray-100 text-gray-600 rounded-full capitalize">
                                {{ $meal->difficulty }}
                            </span>
                        </div>

                        <div class="flex space-x-2">
                            <a href="{{ route('recipes.show', $meal) }}" 
                               class="flex-1 text-center px-3 py-2 text-sm font-medium text-green-600 bg-green-50 rounded-md hover:bg-green-100">
                                View Recipe
                            </a>
                            <a href="{{ route('meal-plans.create') }}?meal_id={{ $meal->id }}" 
                               class="flex-1 text-center px-3 py-2 text-sm font-medium text-gray-600 bg-gray-50 rounded-md hover:bg-gray-100">
                                Add to Plan
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($meals->hasPages())
            <div class="mt-8">
                {{ $meals->links() }}
            </div>
        @endif
    @else
        <div class="text-center py-12">
            <span class="text-6xl mb-4 block">🍽️</span>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No recipes found</h3>
            <p class="text-gray-500 mb-6">Try adjusting your search criteria or browse all recipes</p>
            <a href="{{ route('recipes.index') }}" 
               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                Browse All Recipes
            </a>
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit form on filter changes (optional - can be removed if too aggressive)
    const cuisineSelect = document.getElementById('cuisine_type');
    const searchInput = document.getElementById('search');
    const maxCostInput = document.getElementById('max_cost');
    
    // Add debounced search for better UX
    let searchTimeout;
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            // Optional: Auto-submit after typing stops for 1 second
            // document.getElementById('recipe-search-form').submit();
        }, 1000);
    });
    
    // Clear search functionality
    window.clearSearchField = function() {
        searchInput.value = '';
        searchInput.focus();
        // Optional: Auto-submit after clearing
        // document.getElementById('recipe-search-form').submit();
    };
    
    // Set cost filter buttons
    window.setCostFilter = function(amount) {
        maxCostInput.value = amount;
        maxCostInput.focus();
        // Highlight the selected button briefly
        event.target.classList.add('bg-purple-200', 'text-purple-800');
        setTimeout(() => {
            event.target.classList.remove('bg-purple-200', 'text-purple-800');
        }, 300);
    };
    
    // Update cost display
    window.updateCostDisplay = function(value) {
        // You can add real-time cost formatting or validation here
        if (value && value > 0) {
            maxCostInput.classList.add('bg-purple-50', 'border-purple-300');
        } else {
            maxCostInput.classList.remove('bg-purple-50', 'border-purple-300');
        }
    };
    
    // Add loading state to search button
    const form = document.getElementById('recipe-search-form');
    const searchButtons = form.querySelectorAll('button[type="submit"]');
    
    form.addEventListener('submit', function() {
        searchButtons.forEach(button => {
            button.disabled = true;
            button.innerHTML = `
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Searching...
            `;
        });
    });
    
    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Ctrl/Cmd + K to focus search
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            searchInput.focus();
            searchInput.select();
        }
        
        // Enter in search field submits form
        if (e.key === 'Enter' && document.activeElement === searchInput) {
            form.submit();
        }
    });
    
    // Add search hint
    const searchHint = document.createElement('div');
    searchHint.className = 'absolute top-2 right-2 text-xs text-gray-400 pointer-events-none';
    searchHint.innerHTML = 'Ctrl+K';
    searchInput.parentElement.appendChild(searchHint);
});
</script>
@endsection

