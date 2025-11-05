@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<!-- Admin Header -->
<div class="bg-white shadow-sm border-b border-gray-200 mb-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-6">
            <div class="flex items-center space-x-4">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Admin Dashboard</h1>
                    <p class="text-sm text-gray-600">Welcome back! Here's your StudEats system overview</p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <button onclick="location.reload()" 
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Refresh
                </button>
            </div>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-sm p-6 text-white">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                            <!-- Lucide Users Icon -->
                            <svg class="w-7 h-7 text-blue-200 lucide lucide-users" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                                <circle cx="9" cy="7" r="4"/>
                                <path d="m22 21-2-2a4 4 0 0 0-4-4h-1"/>
                                <circle cx="16" cy="7" r="3"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-blue-100">Total Users</h3>
                        <p class="text-3xl font-bold">{{ number_format($stats['total_users']) }}</p>
                        <p class="text-xs text-blue-100 mt-1">
                            <span class="inline-flex items-center">
                                <svg class="w-3 h-3 mr-1 lucide lucide-trending-up" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="22,7 13.5,15.5 8.5,10.5 2,17"/>
                                    <polyline points="16,7 22,7 22,13"/>
                                </svg>
                                +{{ $stats['recent_registrations'] }} this week
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl shadow-sm p-6 text-white">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                            <!-- Lucide Activity Icon -->
                            <svg class="w-7 h-7 text-green-200 lucide lucide-activity" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m22 12-4-4-6 6-4-4-4 4"/>
                                <path d="M2 12h20"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-green-100">Active Users</h3>
                        <p class="text-3xl font-bold">{{ number_format($stats['active_users']) }}</p>
                        <p class="text-xs text-green-100 mt-1">
                            @if($stats['total_users'] > 0)
                                {{ number_format(($stats['active_users'] / $stats['total_users']) * 100, 1) }}% of total
                            @else
                                0% of total
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl shadow-sm p-6 text-white">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                            <!-- Lucide Book Open Icon -->
                            <svg class="w-7 h-7 text-yellow-200 lucide lucide-book-open" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                                <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-yellow-100">Total Recipes</h3>
                        <p class="text-3xl font-bold">{{ number_format($stats['total_meals']) }}</p>
                        <p class="text-xs text-yellow-100 mt-1">{{ $stats['featured_meals'] ?? 0 }} featured</p>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl shadow-sm p-6 text-white">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                            <!-- Lucide Server Icon -->
                            <svg class="w-7 h-7 text-purple-200 lucide lucide-server" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect width="20" height="8" x="2" y="2" rx="2" ry="2"/>
                                <rect width="20" height="8" x="2" y="14" rx="2" ry="2"/>
                                <line x1="6" x2="6.01" y1="6" y2="6"/>
                                <line x1="6" x2="6.01" y1="18" y2="18"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-purple-100">System Status</h3>
                        <p class="text-lg font-bold">Operational</p>
                        <p class="text-xs text-purple-100 mt-1">All systems running</p>
                    </div>
                </div>
            </div>
        </div>

    <!-- Management Cards -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Recent Activities Card -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Recent Admin Activities</h3>
                        <p class="mt-1 text-sm text-gray-600">Latest administrative actions</p>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Last 24 hours</span>
                </div>
            </div>
            <div class="px-6 py-4">
                    @if($recentActivities->count() > 0)
                        <div class="flow-root">
                            <ul class="-mb-8">
                                @foreach($recentActivities as $activity)
                                    <li>
                                        <div class="relative pb-4">
                                            @if(!$loop->last)
                                                <span class="absolute top-3 left-3 -ml-px h-full w-0.5 bg-gray-200"></span>
                                            @endif
                                            <div class="relative flex items-start gap-2">
                                                <div class="relative">
                                                    <div class="h-6 w-6 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white">
                                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414L9 13.414l4.707-4.707z" clip-rule="evenodd" />
                                                        </svg>
                                                    </div>
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <div>
                                                        <p class="text-sm text-gray-900 font-medium">{{ $activity->description ?? 'Activity' }}</p>
                                                        <div class="mt-1 flex items-center gap-2 text-sm text-gray-500">
                                                            <span>{{ $activity->adminUser->name ?? 'Unknown Admin' }}</span>
                                                            <span>•</span>
                                                            <span>{{ $activity->created_at ? $activity->created_at->diffForHumans() : 'Recently' }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 mx-auto text-gray-300 mb-4 lucide lucide-folder-open" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m6 14 1.5-2.9A2 2 0 0 1 9.24 10H20a2 2 0 0 1 1.94 2.5l-1.54 6a2 2 0 0 1-1.95 1.5H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h3.9a2 2 0 0 1 1.69.9l.81 1.2a2 2 0 0 0 1.67.9H18a2 2 0 0 1 2 2v2"/>
                            </svg>
                            <p class="text-gray-500 font-medium">No recent activities</p>
                            <p class="text-sm text-gray-400 mt-1">Admin activities will appear here</p>
                        </div>
                    @endif
                </div>
            </div>

        <!-- System Health Card -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">System Health</h3>
                        <p class="mt-1 text-sm text-gray-600">Real-time system status monitoring</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="flex items-center">
                            <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse mr-2"></div>
                            <span class="text-sm text-green-600 font-medium">Live</span>
                        </div>
                        <button onclick="refreshSystemHealth()" 
                                class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Refresh
                        </button>
                    </div>
                </div>
            </div>
            <div class="px-6 py-4">
                    <div id="system-health">
                        <div class="flex items-center">
                            <div class="w-1.5 h-1.5 bg-gray-300 rounded-full animate-pulse mr-2"></div>
                            <span class="text-xs text-gray-600">Loading system health...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <!-- Popular Recipes Card -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-medium text-gray-900">Popular Recipes</h3>
                    <p class="mt-1 text-sm text-gray-600">Most added recipes to meal plans</p>
                </div>
                <a href="{{ route('admin.recipes.index') }}" 
                   class="text-sm font-medium text-blue-600 hover:text-blue-700">View all</a>
            </div>
        </div>
        <div class="px-6 py-4">
                    @if($topMeals->count() > 0)
                        <div class="space-y-2">
                            @foreach($topMeals as $meal)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center min-w-0">
                                        @php
                                            $imageUrl = null;
                                            if (isset($meal->image_path) && $meal->image_path) {
                                                try {
                                                    if (\Illuminate\Support\Facades\Storage::disk('public')->exists($meal->image_path)) {
                                                        $imageUrl = asset('storage/' . $meal->image_path);
                                                    }
                                                } catch (\Exception $e) {
                                                    // Silent fail - use placeholder
                                                }
                                            }
                                        @endphp
                                        
                                        @if($imageUrl)
                                            <img src="{{ $imageUrl }}" 
                                                 alt="{{ $meal->name ?? 'Meal' }}" 
                                                 class="w-10 h-10 rounded-lg object-cover mr-3 flex-shrink-0">
                                        @else
                                            <div class="w-10 h-10 bg-gradient-to-br from-orange-400 to-pink-500 rounded-lg flex items-center justify-center text-white font-bold mr-3 flex-shrink-0 text-sm">
                                                {{ isset($meal->name) ? strtoupper(substr($meal->name, 0, 2)) : 'M' }}
                                            </div>
                                        @endif
                                        <div class="min-w-0">
                                            <h4 class="font-medium text-gray-900 text-sm truncate">{{ $meal->name ?? 'Unnamed Meal' }}</h4>
                                            <p class="text-xs text-gray-500 truncate">
                                                {{ $meal->cuisine_type ?? 'Unknown' }} • {{ isset($meal->difficulty) ? ucfirst($meal->difficulty) : 'N/A' }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3 flex-shrink-0">
                                        <div class="text-center">
                                            <div class="text-sm font-bold text-gray-900">{{ $meal->meal_plans_count ?? 0 }}</div>
                                            <div class="text-xs text-gray-500">Plans</div>
                                        </div>
                                        @if(isset($meal->id))
                                            <a href="{{ route('admin.recipes.show', $meal->id) }}" 
                                               class="text-blue-600 hover:text-blue-700">
                                                <svg class="w-4 h-4 lucide lucide-eye" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                                                    <circle cx="12" cy="12" r="3"/>
                                                </svg>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <svg class="w-8 h-8 mx-auto text-gray-300 mb-2 lucide lucide-utensils" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"/>
                                <path d="M7 2v20"/>
                                <path d="M21 15V2a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7"/>
                            </svg>
                            <p class="text-gray-500 text-sm font-medium">No recipes found</p>
                        </div>
                    @endif
        </div>
    </div>
</div>

<script>
function refreshSystemHealth() {
    fetch('{{ route("admin.system-health") }}')
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('system-health');
            container.innerHTML = `
                <div class="grid grid-cols-2 gap-3 sm:gap-4">
                    <!-- Database Status -->
                    <div class="flex items-center justify-between p-2.5 bg-gray-50 rounded-lg">
                        <div class="flex items-center min-w-0">
                            <div class="w-2 h-2 ${data.database.status === 'healthy' ? 'bg-green-500' : 'bg-red-500'} rounded-full mr-2 flex-shrink-0"></div>
                            <span class="text-xs font-medium text-gray-900 truncate">Database</span>
                        </div>
                        <span class="px-1.5 py-0.5 text-xs font-medium rounded ${data.database.status === 'healthy' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'} ml-2 flex-shrink-0">
                            ${data.database.status === 'healthy' ? '✓' : '✗'}
                        </span>
                    </div>
                    
                    <!-- Storage Status -->
                    <div class="flex items-center justify-between p-2.5 bg-gray-50 rounded-lg">
                        <div class="flex items-center min-w-0">
                            <div class="w-2 h-2 ${data.storage.status === 'healthy' ? 'bg-green-500' : 'bg-yellow-500'} rounded-full mr-2 flex-shrink-0"></div>
                            <span class="text-xs font-medium text-gray-900 truncate">Storage</span>
                        </div>
                        <span class="text-xs text-gray-600 font-medium ml-2 flex-shrink-0">${data.storage.used_percentage}%</span>
                    </div>
                    
                    <!-- Memory Status -->
                    <div class="flex items-center justify-between p-2.5 bg-gray-50 rounded-lg">
                        <div class="flex items-center min-w-0">
                            <div class="w-2 h-2 ${data.memory_usage.percentage < 80 ? 'bg-green-500' : 'bg-yellow-500'} rounded-full mr-2 flex-shrink-0"></div>
                            <span class="text-xs font-medium text-gray-900 truncate">Memory</span>
                        </div>
                        <span class="text-xs text-gray-600 font-medium ml-2 flex-shrink-0">${data.memory_usage.percentage}%</span>
                    </div>
                    
                    <!-- Disk Space Status -->
                    <div class="flex items-center justify-between p-2.5 bg-gray-50 rounded-lg">
                        <div class="flex items-center min-w-0">
                            <div class="w-2 h-2 bg-green-500 rounded-full mr-2 flex-shrink-0"></div>
                            <span class="text-xs font-medium text-gray-900 truncate">Disk</span>
                        </div>
                        <span class="text-xs text-gray-600 font-medium ml-2 flex-shrink-0">${data.disk_usage.used_percentage}%</span>
                    </div>
                </div>
                
                <!-- Overall Health Summary -->
                <div class="mt-3 pt-3 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-medium text-gray-600">Overall Status</span>
                        <div class="flex items-center">
                            <div class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5"></div>
                            <span class="text-xs font-semibold text-green-700">Operational</span>
                        </div>
                    </div>
                </div>
            `;
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('system-health').innerHTML = `
                <div class="p-2.5 bg-red-50 rounded-lg text-center">
                    <p class="text-red-600 text-xs font-medium">Error loading system health</p>
                    <button onclick="refreshSystemHealth()" class="text-red-700 hover:text-red-800 text-xs underline mt-1">
                        Retry
                    </button>
                </div>
            `;
        });
}

function checkSystemHealth() {
    refreshSystemHealth();
}

// Load system health on page load
document.addEventListener('DOMContentLoaded', refreshSystemHealth);
</script>
@endsection