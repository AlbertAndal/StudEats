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
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/>
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
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
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
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/>
                    </svg>
                    <span>Recipes</span>
                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-500/15 text-emerald-300 group-hover:bg-emerald-500/25">
                        {{ $navRecipeCount ?? \App\Models\Meal::count() }}
                    </span>
                </a>

                <!-- Content (Coming Soon) -->
                <span role="button" aria-disabled="true" title="Coming Soon"
                      class="inline-flex items-center px-3 py-2 border-b-2 font-medium rounded-t border-dashed border-gray-700 text-gray-500 cursor-not-allowed select-none">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                    </svg>
                    <span>Content</span>
                    <span class="ml-1 text-[10px] uppercase tracking-wide text-amber-400/70">Soon</span>
                </span>

                <!-- Analytics (Coming Soon) -->
                <span role="button" aria-disabled="true" title="Coming Soon"
                      class="inline-flex items-center px-3 py-2 border-b-2 font-medium rounded-t border-dashed border-gray-700 text-gray-500 cursor-not-allowed select-none">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/>
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
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"/>
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