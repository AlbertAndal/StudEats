<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Search Results') }}
            @if($query)
                <span class="text-gray-600 font-normal">for "{{ $query }}"</span>
            @endif
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Search Form -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('recipes.search') }}" class="flex gap-4">
                        <input type="text" name="q" value="{{ $query }}" 
                               placeholder="Search recipes..." 
                               class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                               autofocus>
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-md transition">
                            Search
                        </button>
                        <a href="{{ route('recipes.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2 px-6 rounded-md transition">
                            View All
                        </a>
                    </form>
                </div>
            </div>

            <!-- Results Count -->
            @if($query)
                <div class="mb-4">
                    <p class="text-gray-600">
                        Found <strong>{{ $recipes->total() }}</strong> {{ Str::plural('recipe', $recipes->total()) }}
                    </p>
                </div>
            @endif

            <!-- Recipes Grid -->
            @if($recipes->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                    @foreach($recipes as $recipe)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition">
                            <a href="{{ route('recipes.show', $recipe->id) }}" class="block">
                                @if($recipe->recipe_image)
                                    <img src="{{ Storage::url($recipe->recipe_image) }}" 
                                         alt="{{ $recipe->name }}" 
                                         class="w-full h-48 object-cover">
                                @else
                                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                        <svg class="w-16 h-16 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                                        </svg>
                                    </div>
                                @endif
                                
                                <div class="p-4">
                                    <div class="flex justify-between items-start mb-2">
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $recipe->name }}</h3>
                                        @if($recipe->is_featured)
                                            <span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-800 rounded-full">Featured</span>
                                        @endif
                                    </div>
                                    
                                    @if($recipe->description)
                                        <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ $recipe->description }}</p>
                                    @endif
                                    
                                    <div class="flex items-center justify-between text-sm text-gray-500">
                                        @if($recipe->cuisine_type)
                                            <span class="flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 2a8 8 0 100 16 8 8 0 000-16zm0 14a6 6 0 110-12 6 6 0 010 12z"/>
                                                </svg>
                                                {{ ucfirst($recipe->cuisine_type) }}
                                            </span>
                                        @endif
                                        
                                        @if($recipe->nutritionalInfo)
                                            <span class="font-medium text-green-600">
                                                {{ number_format($recipe->nutritionalInfo->calories) }} cal
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    {{ $recipes->appends(['q' => $query])->links() }}
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-12 text-center">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No recipes found</h3>
                        <p class="text-gray-600 mb-4">
                            We couldn't find any recipes matching "{{ $query }}"
                        </p>
                        <a href="{{ route('recipes.index') }}" class="text-green-600 hover:text-green-700 font-medium">
                            View all recipes
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
