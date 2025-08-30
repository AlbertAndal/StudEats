<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'StudEats') }} - @yield('title', 'Smart Meal Planning for Students')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- Geist Font - Comprehensive weights for consistent typography -->
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Preload critical font weights for faster rendering -->
    <link rel="preload" href="https://fonts.gstatic.com/s/geist/v1/gyB-hkdavoI.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="https://fonts.gstatic.com/s/geist/v1/gyB4hkdavoI.woff2" as="font" type="font/woff2" crossorigin>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-background text-foreground">
    <div class="min-h-screen">
        <!-- Navigation -->
        @auth
        <nav class="bg-white border-b border-gray-200 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <!-- Logo -->
                        <div class="flex-shrink-0 flex items-center">
                            <a href="{{ route('dashboard') }}" class="text-2xl font-bold text-green-600">
                                🍽️ StudEats
                            </a>
                        </div>

                        <!-- Navigation Links -->
                        <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                            <a href="{{ route('dashboard') }}" 
                               class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium {{ request()->routeIs('dashboard') ? 'border-green-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }}">
                                Dashboard
                            </a>
                            <a href="{{ route('meal-plans.index') }}" 
                               class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium {{ request()->routeIs('meal-plans.*') ? 'border-green-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }}">
                                Meal Plans
                            </a>
                            <a href="{{ route('recipes.index') }}" 
                               class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium {{ request()->routeIs('recipes.*') ? 'border-green-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }}">
                                Recipes
                            </a>
                        </div>
                    </div>

                    <!-- Profile dropdown -->
                    <div class="hidden sm:ml-6 sm:flex sm:items-center">
                        <div class="ml-3 relative">
                            <div class="flex items-center space-x-4">
                                <span class="text-sm text-gray-700">{{ Auth::user()->name }}</span>
                                <div class="relative">
                                    <button type="button" 
                                            class="bg-white rounded-full flex text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500" 
                                            id="user-menu-button" 
                                            aria-expanded="false" 
                                            aria-haspopup="true">
                                        <span class="sr-only">Open user menu</span>
                                        <div class="h-8 w-8 rounded-full bg-green-100 flex items-center justify-center">
                                            <span class="text-sm font-medium text-green-600">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                        </div>
                                    </button>
                                    
                                    <!-- Dropdown menu -->
                                    <div class="hidden origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none" 
                                         role="menu" 
                                         aria-orientation="vertical" 
                                         aria-labelledby="user-menu-button" 
                                         tabindex="-1" 
                                         id="user-menu-dropdown">
                                        <a href="{{ route('profile.show') }}" 
                                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" 
                                           role="menuitem" 
                                           tabindex="-1">Profile</a>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" 
                                                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" 
                                                    role="menuitem" 
                                                    tabindex="-1">Sign out</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile menu button -->
                    <div class="-mr-2 flex items-center sm:hidden">
                        <button type="button" 
                                class="bg-white inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-green-500" 
                                aria-controls="mobile-menu" 
                                aria-expanded="false" 
                                id="mobile-menu-button">
                            <span class="sr-only">Open main menu</span>
                            <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile menu -->
            <div class="sm:hidden hidden" id="mobile-menu">
                <div class="pt-2 pb-3 space-y-1">
                    <a href="{{ route('dashboard') }}" 
                       class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium {{ request()->routeIs('dashboard') ? 'bg-green-50 border-green-500 text-green-700' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('meal-plans.index') }}" 
                       class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium {{ request()->routeIs('meal-plans.*') ? 'bg-green-50 border-green-500 text-green-700' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800' }}">
                        Meal Plans
                    </a>
                    <a href="{{ route('recipes.index') }}" 
                       class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium {{ request()->routeIs('recipes.*') ? 'bg-green-50 border-green-500 text-green-700' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800' }}">
                        Recipes
                    </a>
                </div>
                <div class="pt-4 pb-3 border-t border-gray-200">
                    <div class="flex items-center px-4">
                        <div class="flex-shrink-0">
                            <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                                <span class="text-sm font-medium text-green-600">{{ substr(Auth::user()->name, 0, 1) }}</span>
                            </div>
                        </div>
                        <div class="ml-3">
                            <div class="text-base font-medium text-gray-800">{{ Auth::user()->name }}</div>
                            <div class="text-sm font-medium text-gray-500">{{ Auth::user()->email }}</div>
                        </div>
                    </div>
                    <div class="mt-3 space-y-1">
                        <a href="{{ route('profile.show') }}" 
                           class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">
                            Profile
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" 
                                    class="block w-full text-left px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">
                                Sign out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>
        @endauth

        <!-- Page Content -->
        <main>
            @if(session('success'))
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('hidden');
        });

        // Profile dropdown toggle
        document.getElementById('user-menu-button').addEventListener('click', function() {
            const dropdown = document.getElementById('user-menu-dropdown');
            dropdown.classList.toggle('hidden');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('user-menu-dropdown');
            const button = document.getElementById('user-menu-button');
            
            if (!button.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.classList.add('hidden');
            }
        });
    </script>
</body>
</html>

