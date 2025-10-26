<!-- Admin Navigation -->
<nav class="bg-gray-900 border-b border-gray-800" role="navigation" aria-label="Admin navigation">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Brand/Logo Section -->
            <div class="flex items-center space-x-8">
                <div class="flex-shrink-0">
                    <span class="text-lg font-semibold text-white">StudEats</span>
                    <span class="ml-1 text-xs text-gray-400 font-medium">ADMIN</span>
                </div>
            </div>

            <!-- Main Navigation Links -->
            <div class="flex items-center space-x-1">
                <!-- Dashboard -->
                <a href="{{ route('admin.dashboard') }}"
                   class="group inline-flex items-center px-3 py-2 border-b-2 font-medium rounded-t
                          focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500/60
                          transition-colors duration-200
                          {{ request()->routeIs('admin.dashboard') 
                             ? 'border-blue-500 text-white bg-gray-800' 
                             : 'border-transparent text-gray-300 hover:text-white hover:border-gray-600' }}">
                    <svg class="w-4 h-4 mr-2 lucide lucide-layout-dashboard" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="7" height="9" x="3" y="3" rx="1"/>
                        <rect width="7" height="5" x="14" y="3" rx="1"/>
                        <rect width="7" height="9" x="14" y="12" rx="1"/>
                        <rect width="7" height="5" x="3" y="16" rx="1"/>
                    </svg>
                    Dashboard
                </a>

                <!-- Users -->
                <a href="{{ route('admin.users.index') }}"
                   class="group inline-flex items-center px-3 py-2 border-b-2 font-medium rounded-t
                          focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500/60
                          transition-colors duration-200
                          {{ request()->routeIs('admin.users.*') 
                             ? 'border-blue-500 text-white bg-gray-800' 
                             : 'border-transparent text-gray-300 hover:text-white hover:border-gray-600' }}">
                    <svg class="w-4 h-4 mr-2 lucide lucide-users" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="m22 21-2-2a4 4 0 0 0-4-4h-1"/>
                        <circle cx="16" cy="7" r="3"/>
                    </svg>
                    <span>Users</span>
                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-blue-500/15 text-blue-300 group-hover:bg-blue-500/25">
                        {{ $navUserCount ?? \App\Models\User::count() }}
                    </span>
                </a>

                <!-- Recipes -->
                <a href="{{ route('admin.recipes.index') }}"
                   class="group inline-flex items-center px-3 py-2 border-b-2 font-medium rounded-t
                          focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500/60
                          transition-colors duration-200
                          {{ request()->routeIs('admin.recipes.*') 
                             ? 'border-blue-500 text-white bg-gray-800' 
                             : 'border-transparent text-gray-300 hover:text-white hover:border-gray-600' }}">
                    <svg class="w-4 h-4 mr-2 lucide lucide-chef-hat" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 13.87A4 4 0 0 1 7.41 6a5.11 5.11 0 0 1 1.05-1.54 5 5 0 0 1 7.08 0A5.11 5.11 0 0 1 16.59 6 4 4 0 0 1 18 13.87V21H6Z"/>
                        <line x1="6" x2="18" y1="17" y2="17"/>
                    </svg>
                    <span>Recipes</span>
                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-500/15 text-emerald-300 group-hover:bg-emerald-500/25">
                        {{ $navRecipeCount ?? \App\Models\Meal::count() }}
                    </span>
                </a>

                <!-- Content (Coming Soon) -->
                <span role="button" aria-disabled="true" title="Coming Soon"
                      class="inline-flex items-center px-3 py-2 border-b-2 font-medium rounded-t border-dashed border-gray-700 text-gray-500 cursor-not-allowed select-none">
                    <svg class="w-4 h-4 mr-2 lucide lucide-file-text" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/>
                        <path d="M14 2v4a2 2 0 0 0 2 2h4"/>
                        <path d="M10 9H8"/>
                        <path d="M16 13H8"/>
                        <path d="M16 17H8"/>
                    </svg>
                    <span>Content</span>
                    <span class="ml-1 text-[10px] uppercase tracking-wide text-amber-400/70">Soon</span>
                </span>

                <!-- Analytics (Coming Soon) -->
                <span role="button" aria-disabled="true" title="Coming Soon"
                      class="inline-flex items-center px-3 py-2 border-b-2 font-medium rounded-t border-dashed border-gray-700 text-gray-500 cursor-not-allowed select-none">
                    <svg class="w-4 h-4 mr-2 lucide lucide-bar-chart-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 3v16a2 2 0 0 0 2 2h16"/>
                        <path d="m19 9-5 5-4-4-3 3"/>
                    </svg>
                    <span>Analytics</span>
                    <span class="ml-1 text-[10px] uppercase tracking-wide text-amber-400/70">Soon</span>
                </span>
            </div>

            <!-- Right Side: User Actions -->
            <div class="flex items-center space-x-2">
                <!-- User Profile -->
                <div class="flex items-center">
                    <div class="h-8 w-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white text-xs font-semibold mr-2">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>
                    <div class="text-xs">
                        <div class="font-medium text-white">{{ auth()->user()->name }}</div>
                        <div class="text-gray-400">{{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}</div>
                    </div>
                </div>

                <!-- Logout -->
                <form method="POST" action="{{ route('logout') }}" class="ml-2">
                    @csrf
                    <button type="submit"
                            class="inline-flex items-center px-3 py-2 text-sm font-medium text-red-400 hover:text-red-300 hover:bg-red-900/20 rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1 focus:ring-offset-gray-900"
                            title="Sign out">
                        <svg class="w-4 h-4 mr-1 lucide lucide-log-out" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                            <polyline points="16,17 21,12 16,7"/>
                            <line x1="21" x2="9" y1="12" y2="12"/>
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>

<script>
// Admin navigation functionality - System dropdown removed as requested
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') { 
        // System dropdown functions removed
    }
});
</script>