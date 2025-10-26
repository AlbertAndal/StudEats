@extends('layouts.admin')

@section('title', 'Market Prices - Admin')

@push('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <!-- Main Content -->
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Page Header with Branding -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white lucide lucide-trending-up" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="22,7 13.5,15.5 8.5,10.5 2,17"/>
                            <polyline points="16,7 22,7 22,13"/>
                        </svg>
                    </div>
                    Market Prices Management
                </h1>
                <p class="mt-2 text-gray-600">Real-time ingredient prices from Bantay Presyo with automated updates</p>
            </div>
            <div class="flex items-center gap-4">
                
                <button 
                    onclick="showUpdateModal()"
                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-medium rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <svg class="w-5 h-5 mr-2 lucide lucide-refresh-cw" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/>
                        <path d="M21 3v5h-5"/>
                        <path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/>
                        <path d="M3 21v-5h5"/>
                    </svg>
                    Update Market Prices
                </button>
                <div class="text-right hidden sm:block">
                    <div class="text-sm text-gray-500">Live Data Source</div>
                    <div class="font-semibold text-gray-900 flex items-center">
                        <span class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></span>
                        Bantay Presyo
                    </div>
                </div>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if (session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400 lucide lucide-check-circle" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                            <path d="m9 11 3 3L22 4"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if (session('warning'))
            <div class="mb-6 bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400 lucide lucide-alert-triangle" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"/>
                            <path d="M12 9v4"/>
                            <path d="M12 17h.01"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">{{ session('warning') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400 lucide lucide-x-circle" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="m15 9-6 6"/>
                            <path d="m9 9 6 6"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600 lucide lucide-package" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m7.5 4.27 9 5.15"/>
                            <path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/>
                            <path d="m3.3 7 8.7 5 8.7-5"/>
                            <path d="M12 22V12"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_ingredients']) }}</p>
                        <p class="text-sm text-gray-600">Total Ingredients</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['with_prices']) }}</p>
                        <p class="text-sm text-gray-600">With Prices</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $stats['total_ingredients'] > 0 ? round(($stats['with_prices'] / $stats['total_ingredients']) * 100, 1) : 0 }}% coverage</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 rounded-lg">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['active_ingredients']) }}</p>
                        <p class="text-sm text-gray-600">Active Ingredients</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-100 rounded-lg">
                        <svg class="w-6 h-6 text-yellow-600 lucide lucide-clock" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <polyline points="12 6 12 12 16 14"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['stale_prices']) }}</p>
                        <p class="text-sm text-gray-600">Stale Prices</p>
                        <p class="text-xs text-gray-500 mt-1">&gt;7 days old</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filter Controls -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <!-- Search Bar -->
                <div class="flex-1 max-w-md">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                            </svg>
                        </div>
                        <input id="searchInput" type="text" 
                               class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500" 
                               placeholder="Search ingredients..." 
                               autocomplete="off">
                        <div id="searchClear" class="absolute inset-y-0 right-0 pr-3 items-center cursor-pointer hidden"
                            <svg class="h-4 w-4 text-gray-400 hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Filter Controls -->
                <div class="flex flex-wrap gap-3">
                    <!-- Category Filter -->
                    <div class="min-w-0">
                        <select id="categoryFilter" class="block w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                            <option value="">All Categories</option>
                            <option value="rice">Rice</option>
                            <option value="meat">Meat</option>
                            <option value="vegetables">Vegetables</option>
                            <option value="fish">Fish</option>
                            <option value="fruits">Fruits</option>
                            <option value="others">Others</option>
                        </select>
                    </div>

                    <!-- Region Filter -->
                    <div class="min-w-0">
                        <select id="regionFilter" class="block w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                            <option value="">All Regions</option>
                            @foreach ($regions as $code => $name)
                                <option value="{{ $code }}">{{ str_replace('_', ' ', $code) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Price Status Filter -->
                    <div class="min-w-0">
                        <select id="priceStatusFilter" class="block w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                            <option value="">All Prices</option>
                            <option value="fresh">Fresh (&lt; 7 days)</option>
                            <option value="stale">Stale (&gt; 7 days)</option>
                            <option value="no_price">No Price</option>
                        </select>
                    </div>

                    <!-- Sort Options -->
                    <div class="min-w-0">
                        <select id="sortFilter" class="block w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                            <option value="name_asc">Name (A-Z)</option>
                            <option value="name_desc">Name (Z-A)</option>
                            <option value="price_asc">Price (Low-High)</option>
                            <option value="price_desc">Price (High-Low)</option>
                            <option value="updated_desc">Recently Updated</option>
                            <option value="updated_asc">Oldest Updated</option>
                        </select>
                    </div>

                    <!-- Clear Filters Button -->
                    <button id="clearFilters" 
                            class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <svg class="w-4 h-4 inline mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Clear
                    </button>
                </div>
            </div>

            <!-- Active Filters Display -->
            <div id="activeFilters" class="mt-4 flex-wrap gap-2 hidden">
                <span class="text-sm text-gray-600">Active filters:</span>
                <!-- Dynamic filter tags will be inserted here -->
            </div>

            <!-- Search Results Summary -->
            <div id="searchSummary" class="mt-4 text-sm text-gray-600 hidden">
                <!-- Search results count will be displayed here -->
            </div>
        </div>

        <!-- Last Update Info -->
        @if ($lastUpdate)
            <div class="bg-gradient-to-r from-green-50 via-emerald-50 to-teal-50 border border-green-200 rounded-xl p-6 mb-8 shadow-sm">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-emerald-600 rounded-lg flex items-center justify-center mr-4">
                        <svg class="w-5 h-5 text-white lucide lucide-clock" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <polyline points="12 6 12 12 16 14"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-green-900 mb-1">Last Market Price Update</p>
                        <p class="text-sm text-green-700 flex items-center">
                            <span class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></span>
                            {{ $lastUpdate->format('F d, Y \at g:i A') }} 
                            <span class="text-green-600 ml-2 font-medium">({{ $lastUpdate->diffForHumans() }})</span>
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Market Prices Table with Search Results -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-600 lucide lucide-database" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <ellipse cx="12" cy="5" rx="9" ry="3"/>
                            <path d="M3 5V19A9 3 0 0 0 21 19V5"/>
                            <path d="M3 12A9 3 0 0 0 21 12"/>
                        </svg>
                        Market Prices Database
                        <span class="text-sm font-normal text-gray-500 ml-2" id="tableResultsCount">
                            (Loading...)
                        </span>
                    </h3>
                <div class="flex items-center gap-3">
                    <!-- Loading Indicator -->
                    <div id="loadingIndicator" class="items-center text-sm text-gray-500 hidden">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Loading...
                    </div>
                    <!-- Results Per Page -->
                    <div class="flex items-center text-sm text-gray-600">
                        <span class="mr-2">Show:</span>
                        <select id="perPageSelect" class="border border-gray-300 rounded px-2 py-1 text-sm focus:outline-none focus:border-blue-500">
                            <option value="20">20</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="all">All</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" data-sort="name">
                                <div class="flex items-center gap-1">
                                    Ingredient
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/>
                                    </svg>
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" data-sort="price">
                                <div class="flex items-center gap-1">
                                    Price
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/>
                                    </svg>
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Category
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Region
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" data-sort="updated">
                                <div class="flex items-center gap-1">
                                    Last Updated
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/>
                                    </svg>
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody id="pricesTableBody" class="bg-white divide-y divide-gray-200">
                        <!-- Content will be populated via JavaScript -->
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div id="paginationContainer" class="bg-gray-50 px-6 py-3 border-t border-gray-200">
                <!-- Pagination will be inserted here -->
            </div>

            <!-- No Results State -->
            <div id="noResultsState" class="hidden px-6 py-12 text-center">
                <div class="flex flex-col items-center">
                    <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No ingredients found</h3>
                    <p class="text-gray-500 mb-6">Try adjusting your search criteria or filters to find what you're looking for.</p>
                    <button onclick="marketPriceSearch.clearAllFilters()" 
                           class="inline-flex items-center px-4 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Clear All Filters
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Modal -->
    <div id="updateModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center p-4">
        <div class="bg-white rounded-xl max-w-md w-full p-6 shadow-2xl">
            <div class="flex items-center mb-6">
                <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-emerald-600 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-5 h-5 text-white lucide lucide-refresh-cw" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/>
                        <path d="M21 3v5h-5"/>
                        <path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/>
                        <path d="M3 21v-5h5"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900">Update Market Prices</h3>
            </div>
                
                <form action="{{ route('admin.market-prices.update') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="region" class="block text-sm font-medium text-gray-700 mb-2">Select Region</label>
                        <select name="region" id="region" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            @foreach ($regions as $code => $value)
                                <option value="{{ $code }}" {{ $code === 'NCR' ? 'selected' : '' }}>
                                    {{ str_replace('_', ' ', $code) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Select Commodities (Optional)
                        </label>
                        <div class="space-y-2 max-h-48 overflow-y-auto border border-gray-200 rounded-lg p-3">
                            @foreach ($commodities as $name => $id)
                                <label class="flex items-center">
                                    <input type="checkbox" name="commodities[]" value="{{ $id }}" class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                                    <span class="ml-2 text-sm text-gray-700">{{ str_replace('_', ' ', $name) }}</span>
                                </label>
                            @endforeach
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Leave unchecked to fetch all commodities</p>
                    </div>

                    <div class="flex gap-3">
                        <button 
                            type="button"
                            onclick="hideUpdateModal()"
                            class="flex-1 px-4 py-2 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors">
                            Cancel
                        </button>
                        <button 
                            type="submit"
                            class="flex-1 px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-medium rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            Update Prices
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

<script>
class MarketPriceSearch {
    constructor() {
        this.allData = [];
        this.filteredData = [];
        this.currentPage = 1;
        this.perPage = 20;
        this.sortBy = 'name';
        this.sortOrder = 'asc';
        this.filters = {
            search: '',
            category: '',
            region: '',
            priceStatus: ''
        };
        
        this.init();
    }
    
    async init() {
        this.bindEvents();
        await this.loadData();
        this.applyFilters();
    }
    
    bindEvents() {
        // Search input
        const searchInput = document.getElementById('searchInput');
        const searchClear = document.getElementById('searchClear');
        
        searchInput.addEventListener('input', this.debounce((e) => {
            this.filters.search = e.target.value;
            this.currentPage = 1;
            this.applyFilters();
            this.toggleClearButton();
        }, 300));
        
        searchClear.addEventListener('click', () => {
            searchInput.value = '';
            this.filters.search = '';
            this.currentPage = 1;
            this.applyFilters();
            this.toggleClearButton();
        });
        
        // Filter dropdowns
        ['categoryFilter', 'regionFilter', 'priceStatusFilter', 'sortFilter'].forEach(id => {
            document.getElementById(id).addEventListener('change', (e) => {
                if (id === 'sortFilter') {
                    const [sortBy, sortOrder] = e.target.value.split('_');
                    this.sortBy = sortBy;
                    this.sortOrder = sortOrder;
                } else {
                    const filterKey = id.replace('Filter', '').replace('priceStatus', 'priceStatus');
                    this.filters[filterKey] = e.target.value;
                }
                this.currentPage = 1;
                this.applyFilters();
            });
        });
        
        // Clear filters
        document.getElementById('clearFilters').addEventListener('click', () => {
            this.clearAllFilters();
        });
        
        // Per page selector
        document.getElementById('perPageSelect').addEventListener('change', (e) => {
            this.perPage = e.target.value === 'all' ? this.filteredData.length : parseInt(e.target.value);
            this.currentPage = 1;
            this.renderTable();
            this.renderPagination();
        });
        
        // Table sorting
        document.querySelectorAll('[data-sort]').forEach(th => {
            th.addEventListener('click', () => {
                const sortField = th.dataset.sort;
                if (this.sortBy === sortField) {
                    this.sortOrder = this.sortOrder === 'asc' ? 'desc' : 'asc';
                } else {
                    this.sortBy = sortField;
                    this.sortOrder = 'asc';
                }
                this.applyFilters();
            });
        });
    }
    
    async loadData() {
        this.showLoading();
        try {
            const response = await fetch('/api/market-prices/search');
            const data = await response.json();
            this.allData = data.ingredients || [];
            this.filteredData = [...this.allData];
        } catch (error) {
            console.error('Error loading market prices:', error);
            this.allData = [];
            this.filteredData = [];
        }
        this.hideLoading();
    }
    
    applyFilters() {
        this.filteredData = this.allData.filter(item => {
            // Text search
            if (this.filters.search) {
                const searchTerm = this.filters.search.toLowerCase();
                const searchableText = `${item.name} ${item.bantay_presyo_name || ''} ${item.category}`.toLowerCase();
                if (!searchableText.includes(searchTerm)) {
                    return false;
                }
            }
            
            // Category filter
            if (this.filters.category && item.category !== this.filters.category) {
                return false;
            }
            
            // Region filter (check price history)
            if (this.filters.region) {
                const hasRegionPrice = item.price_history && 
                    item.price_history.some(p => p.region_code === this.filters.region);
                if (!hasRegionPrice) {
                    return false;
                }
            }
            
            // Price status filter
            if (this.filters.priceStatus) {
                if (this.filters.priceStatus === 'no_price' && item.current_price) {
                    return false;
                }
                if (this.filters.priceStatus === 'fresh' && item.is_stale) {
                    return false;
                }
                if (this.filters.priceStatus === 'stale' && !item.is_stale) {
                    return false;
                }
            }
            
            return true;
        });
        
        this.sortData();
        this.renderTable();
        this.renderPagination();
        this.updateSearchSummary();
        this.updateActiveFilters();
    }
    
    sortData() {
        this.filteredData.sort((a, b) => {
            let aVal, bVal;
            
            switch (this.sortBy) {
                case 'name':
                    aVal = a.name.toLowerCase();
                    bVal = b.name.toLowerCase();
                    break;
                case 'price':
                    aVal = parseFloat(a.current_price) || 0;
                    bVal = parseFloat(b.current_price) || 0;
                    break;
                case 'updated':
                    aVal = new Date(a.price_updated_at || 0);
                    bVal = new Date(b.price_updated_at || 0);
                    break;
                default:
                    return 0;
            }
            
            if (this.sortOrder === 'asc') {
                return aVal < bVal ? -1 : aVal > bVal ? 1 : 0;
            } else {
                return aVal > bVal ? -1 : aVal < bVal ? 1 : 0;
            }
        });
    }
    
    renderTable() {
        const tbody = document.getElementById('pricesTableBody');
        const noResultsState = document.getElementById('noResultsState');
        
        if (this.filteredData.length === 0) {
            tbody.innerHTML = '';
            noResultsState.classList.remove('hidden');
            return;
        }
        
        noResultsState.classList.add('hidden');
        
        const startIndex = (this.currentPage - 1) * this.perPage;
        const endIndex = this.perPage === this.filteredData.length ? this.filteredData.length : startIndex + this.perPage;
        const pageData = this.filteredData.slice(startIndex, endIndex);
        
        tbody.innerHTML = pageData.map(item => {
            const latestPrice = item.price_history && item.price_history.length > 0 ? 
                item.price_history[0] : null;
            
            return `
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">${item.name}</div>
                        <div class="text-xs text-gray-500">${item.bantay_presyo_name || 'No mapping'}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        ${item.current_price ? `
                            <div class="text-sm font-semibold text-green-600">₱${parseFloat(item.current_price).toLocaleString('en-US', {minimumFractionDigits: 2})}</div>
                            <div class="text-xs text-gray-500">per ${item.unit || 'unit'}</div>
                        ` : `
                            <span class="text-sm text-gray-400">No price</span>
                        `}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full ${
                            this.getCategoryColor(item.category)
                        }">
                            ${item.category || 'Others'}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        ${latestPrice ? `
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                ${latestPrice.region_code}
                            </span>
                        ` : `
                            <span class="text-xs text-gray-400">No region data</span>
                        `}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        ${item.price_updated_at ? `
                            <div>${new Date(item.price_updated_at).toLocaleDateString('en-US', {month: 'short', day: 'numeric', year: 'numeric'})}</div>
                            <div class="text-xs ${item.is_stale ? 'text-red-400' : 'text-gray-400'}">
                                ${this.timeAgo(item.price_updated_at)}
                                ${item.is_stale ? ' (stale)' : ''}
                            </div>
                        ` : `
                            <span class="text-gray-400">Never updated</span>
                        `}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <button onclick="refreshSinglePrice(${item.id})" 
                                class="text-blue-600 hover:text-blue-900 transition-colors" 
                                title="Refresh price">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                        </button>
                    </td>
                </tr>
            `;
        }).join('');
    }
    
    renderPagination() {
        const container = document.getElementById('paginationContainer');
        
        if (this.perPage >= this.filteredData.length) {
            container.innerHTML = `
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        Showing ${this.filteredData.length} results
                    </div>
                </div>
            `;
            return;
        }
        
        const totalPages = Math.ceil(this.filteredData.length / this.perPage);
        const startIndex = (this.currentPage - 1) * this.perPage + 1;
        const endIndex = Math.min(this.currentPage * this.perPage, this.filteredData.length);
        
        let paginationHTML = `
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Showing ${startIndex} to ${endIndex} of ${this.filteredData.length} results
                </div>
                <div class="flex items-center space-x-2">
        `;
        
        // Previous button
        paginationHTML += `
            <button onclick="marketPriceSearch.goToPage(${this.currentPage - 1})" 
                    class="px-3 py-1 text-sm bg-white border border-gray-300 rounded hover:bg-gray-50 ${
                        this.currentPage === 1 ? 'opacity-50 cursor-not-allowed' : ''
                    }" 
                    ${this.currentPage === 1 ? 'disabled' : ''}>
                Previous
            </button>
        `;
        
        // Page numbers
        const showPages = this.getPageNumbers(this.currentPage, totalPages);
        showPages.forEach(page => {
            if (page === '...') {
                paginationHTML += '<span class="px-3 py-1 text-sm text-gray-500">...</span>';
            } else {
                paginationHTML += `
                    <button onclick="marketPriceSearch.goToPage(${page})" 
                            class="px-3 py-1 text-sm rounded ${
                                page === this.currentPage 
                                    ? 'bg-blue-600 text-white' 
                                    : 'bg-white border border-gray-300 hover:bg-gray-50'
                            }">
                        ${page}
                    </button>
                `;
            }
        });
        
        // Next button
        paginationHTML += `
            <button onclick="marketPriceSearch.goToPage(${this.currentPage + 1})" 
                    class="px-3 py-1 text-sm bg-white border border-gray-300 rounded hover:bg-gray-50 ${
                        this.currentPage === totalPages ? 'opacity-50 cursor-not-allowed' : ''
                    }" 
                    ${this.currentPage === totalPages ? 'disabled' : ''}>
                Next
            </button>
                </div>
            </div>
        `;
        
        container.innerHTML = paginationHTML;
    }
    
    updateSearchSummary() {
        const summary = document.getElementById('searchSummary');
        const resultsCount = document.getElementById('tableResultsCount');
        const total = this.filteredData.length;
        const hasFilters = Object.values(this.filters).some(f => f !== '');
        
        // Update table header count
        if (resultsCount) {
            resultsCount.textContent = `(${total} ingredients)`;
        }
        
        if (hasFilters || total !== this.allData.length) {
            summary.classList.remove('hidden');
            summary.innerHTML = `Found ${total} ingredient${total !== 1 ? 's' : ''} ${hasFilters ? 'matching your criteria' : ''}`;
        } else {
            summary.classList.add('hidden');
        }
    }
    
    updateActiveFilters() {
        const container = document.getElementById('activeFilters');
        const activeFilters = [];
        
        Object.entries(this.filters).forEach(([key, value]) => {
            if (value) {
                let label = '';
                switch (key) {
                    case 'search':
                        label = `Search: "${value}"`;
                        break;
                    case 'category':
                        label = `Category: ${value}`;
                        break;
                    case 'region':
                        label = `Region: ${value.replace('_', ' ')}`;
                        break;
                    case 'priceStatus':
                        label = `Status: ${value.replace('_', ' ')}`;
                        break;
                }
                activeFilters.push({ key, label });
            }
        });
        
        if (activeFilters.length > 0) {
            container.classList.remove('hidden');
            container.classList.add('flex');
            const filtersHTML = activeFilters.map(filter => `
                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 border border-green-200">
                    ${filter.label}
                    <button onclick="marketPriceSearch.removeFilter('${filter.key}')" class="ml-2 hover:text-green-600 transition-colors">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </span>
            `).join('');
            container.innerHTML = '<span class="text-sm text-gray-600 font-medium">Active filters:</span>' + filtersHTML;
        } else {
            container.classList.add('hidden');
            container.classList.remove('flex');
        }
    }
    
    removeFilter(filterKey) {
        this.filters[filterKey] = '';
        
        // Update UI
        const element = document.getElementById(filterKey + 'Filter') || document.getElementById('searchInput');
        if (element) {
            element.value = '';
        }
        
        this.currentPage = 1;
        this.applyFilters();
        this.toggleClearButton();
    }
    
    clearAllFilters() {
        // Reset filters
        this.filters = {
            search: '',
            category: '',
            region: '',
            priceStatus: ''
        };
        
        // Reset UI
        document.getElementById('searchInput').value = '';
        document.getElementById('categoryFilter').value = '';
        document.getElementById('regionFilter').value = '';
        document.getElementById('priceStatusFilter').value = '';
        document.getElementById('sortFilter').value = 'name_asc';
        
        // Reset sorting
        this.sortBy = 'name';
        this.sortOrder = 'asc';
        
        this.currentPage = 1;
        this.applyFilters();
        this.toggleClearButton();
    }
    
    goToPage(page) {
        const totalPages = Math.ceil(this.filteredData.length / this.perPage);
        if (page >= 1 && page <= totalPages) {
            this.currentPage = page;
            this.renderTable();
            this.renderPagination();
        }
    }
    
    toggleClearButton() {
        const clearButton = document.getElementById('searchClear');
        const searchInput = document.getElementById('searchInput');
        
        if (searchInput.value.trim()) {
            clearButton.classList.remove('hidden');
            clearButton.classList.add('flex');
        } else {
            clearButton.classList.add('hidden');
            clearButton.classList.remove('flex');
        }
    }
    
    showLoading() {
        const indicator = document.getElementById('loadingIndicator');
        indicator.classList.remove('hidden');
        indicator.classList.add('flex');
    }
    
    hideLoading() {
        const indicator = document.getElementById('loadingIndicator');
        indicator.classList.add('hidden');
        indicator.classList.remove('flex');
    }
    
    getCategoryColor(category) {
        const colors = {
            'rice': 'bg-yellow-100 text-yellow-800',
            'meat': 'bg-red-100 text-red-800',
            'vegetables': 'bg-green-100 text-green-800',
            'fish': 'bg-blue-100 text-blue-800',
            'fruits': 'bg-orange-100 text-orange-800',
            'others': 'bg-gray-100 text-gray-800'
        };
        return colors[category] || colors['others'];
    }
    
    timeAgo(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const diffMs = now - date;
        const diffHours = Math.floor(diffMs / (1000 * 60 * 60));
        const diffDays = Math.floor(diffHours / 24);
        
        if (diffHours < 1) return 'Just now';
        if (diffHours < 24) return `${diffHours}h ago`;
        if (diffDays < 7) return `${diffDays}d ago`;
        return `${Math.floor(diffDays / 7)}w ago`;
    }
    
    getPageNumbers(current, total) {
        const pages = [];
        const showPages = 5;
        
        if (total <= showPages) {
            for (let i = 1; i <= total; i++) {
                pages.push(i);
            }
        } else {
            if (current <= 3) {
                for (let i = 1; i <= 4; i++) pages.push(i);
                pages.push('...');
                pages.push(total);
            } else if (current >= total - 2) {
                pages.push(1);
                pages.push('...');
                for (let i = total - 3; i <= total; i++) pages.push(i);
            } else {
                pages.push(1);
                pages.push('...');
                for (let i = current - 1; i <= current + 1; i++) pages.push(i);
                pages.push('...');
                pages.push(total);
            }
        }
        
        return pages;
    }
    
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
}

// Initialize search functionality
let marketPriceSearch;
document.addEventListener('DOMContentLoaded', function() {
    marketPriceSearch = new MarketPriceSearch();
    
    // Handle modal display properly
    const modal = document.getElementById('updateModal');
    
    // Show modal function
    window.showUpdateModal = function() {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    };
    
    // Hide modal function
    window.hideUpdateModal = function() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    };
    
    // Close modal when clicking outside
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            hideUpdateModal();
        }
    });
});

// Refresh single ingredient price
async function refreshSinglePrice(ingredientId) {
    try {
        const response = await fetch(`/api/ingredient-price/${ingredientId}/refresh`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        });
        
        const result = await response.json();
        
        if (result.success) {
            // Reload data and refresh table
            await marketPriceSearch.loadData();
            marketPriceSearch.applyFilters();
            
            // Show success message
            showNotification('Price updated successfully!', 'success');
        } else {
            showNotification(result.message || 'Failed to update price', 'error');
        }
    } catch (error) {
        console.error('Error refreshing price:', error);
        showNotification('Error updating price', 'error');
    }
}

// Show notification
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 px-4 py-3 rounded-lg shadow-lg text-sm font-medium transition-all transform translate-x-0 ${
        type === 'success' ? 'bg-green-100 text-green-800 border border-green-200' :
        type === 'error' ? 'bg-red-100 text-red-800 border border-red-200' :
        'bg-blue-100 text-blue-800 border border-blue-200'
    }`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    // Remove after 4 seconds
    setTimeout(() => {
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => notification.remove(), 300);
    }, 4000);
}
</script>
