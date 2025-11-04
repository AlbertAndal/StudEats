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
    
    <!-- Fallback CSS for production if Vite assets fail to load -->
    @if(app()->environment('production'))
    <script>
        window.addEventListener('DOMContentLoaded', function() {
            const links = document.querySelectorAll('link[href*="app"]');
            let cssLoaded = false;
            links.forEach(link => {
                if (link.sheet && link.sheet.cssRules.length > 0) {
                    cssLoaded = true;
                }
            });
            
            if (!cssLoaded) {
                console.warn('Vite CSS failed to load, using Tailwind CDN fallback');
                const fallback = document.createElement('script');
                fallback.src = 'https://cdn.tailwindcss.com';
                document.head.appendChild(fallback);
            }
        });
    </script>
    @endif
</head>
<body class="font-sans antialiased bg-background text-foreground">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-white border-b border-gray-200 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <!-- Logo -->
                        <div class="flex-shrink-0 flex items-center">
                            <a href="@auth{{ route('dashboard') }}@else{{ route('welcome') }}@endauth" class="text-2xl font-bold text-green-600">
                                StudEats
                            </a>
                        </div>

                        <!-- Navigation Links -->
                        @auth
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
                        @else
                        <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                            <a href="{{ route('recipes.index') }}" 
                               class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium {{ request()->routeIs('recipes.*') ? 'border-green-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }}">
                                Recipes
                            </a>
                        </div>
                        @endauth
                    </div>

                    @auth
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
                                        @if(Auth::user()->hasProfilePhoto())
                                            <img src="{{ Auth::user()->getAvatarUrl() }}" 
                                                 alt="{{ Auth::user()->name }}" 
                                                 class="h-8 w-8 rounded-full object-cover"
                                                 data-avatar-image>
                                        @else
                                            <div class="h-8 w-8 rounded-full bg-green-100 flex items-center justify-center">
                                                <span class="text-sm font-medium text-green-600">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                            </div>
                                        @endif
                                    </button>
                                    
                                    <!-- Dropdown menu -->
                                    <div class="hidden origin-top-right absolute right-0 mt-2 w-80 rounded-lg shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none" 
                                         role="menu" 
                                         aria-orientation="vertical" 
                                         aria-labelledby="user-menu-button" 
                                         tabindex="-1" 
                                         id="user-menu-dropdown">
                                        
                                        <!-- User Info Header -->
                                        <div class="px-4 py-3 border-b border-gray-100">
                                            <div class="flex items-center space-x-3">
                                                @if(Auth::user()->hasProfilePhoto())
                                                    <img src="{{ Auth::user()->getAvatarUrl() }}" 
                                                         alt="{{ Auth::user()->name }}" 
                                                         class="h-10 w-10 rounded-full object-cover">
                                                @else
                                                    <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                                                        <span class="text-sm font-medium text-green-600">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                                    </div>
                                                @endif
                                                <div class="flex-1 min-w-0">
                                                    <div class="text-sm font-medium text-gray-900 truncate">{{ Auth::user()->name }}</div>
                                                    <div class="text-sm text-gray-500 truncate">{{ Auth::user()->email }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        @if(Auth::user()->dietary_preferences && trim(Auth::user()->dietary_preferences) !== '')
                                        <!-- Dietary Preferences Quick View -->
                                        <div class="px-4 py-3 border-b border-gray-100">
                                            <div class="flex items-center justify-between mb-2">
                                                <h3 class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Dietary Preferences</h3>
                                                <span class="text-xs text-gray-500">Configured</span>
                                            </div>
                                            <div class="bg-green-50 rounded-lg p-2 border border-green-200">
                                                <div class="flex items-start gap-2">
                                                    <span class="text-sm flex-shrink-0">🍽️</span>
                                                    <p class="text-xs text-green-700 leading-relaxed">
                                                        {{ Str::limit(Auth::user()->dietary_preferences, 80) }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="mt-2">
                                                <a href="{{ route('profile.edit') }}" 
                                                   class="text-xs text-green-600 hover:text-green-700 font-medium">
                                                    Update preferences →
                                                </a>
                                            </div>
                                        </div>
                                        @else
                                        <!-- No Dietary Preferences -->
                                        <div class="px-4 py-3 border-b border-gray-100">
                                            <div class="flex items-center justify-between mb-2">
                                                <h3 class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Dietary Preferences</h3>
                                            </div>
                                            <div class="text-center py-2">
                                                <div class="text-gray-400 mb-1">🍽️</div>
                                                <p class="text-xs text-gray-500 mb-2">No preferences set</p>
                                                <a href="{{ route('profile.edit') }}" 
                                                   class="inline-flex items-center px-2 py-1 text-xs font-medium text-green-600 hover:text-green-700 bg-green-50 hover:bg-green-100 rounded transition-colors">
                                                    + Add preferences
                                                </a>
                                            </div>
                                        </div>
                                        @endif
                                        
                                        <!-- Quick Stats -->
                                        @if(Auth::user()->daily_budget)
                                        <div class="px-4 py-2 border-b border-gray-100">
                                            <div class="flex items-center justify-between text-xs">
                                                <span class="text-gray-500">Daily Budget</span>
                                                <span class="font-medium text-green-600">₱{{ number_format(Auth::user()->daily_budget, 0) }}</span>
                                            </div>
                                        </div>
                                        @endif
                                        
                                        <!-- Menu Items -->
                                        <div class="py-1">
                                            <a href="{{ route('profile.show') }}" 
                                               class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors" 
                                               role="menuitem" 
                                               tabindex="-1">
                                                <svg class="h-4 w-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                                Profile
                                            </a>
                                            <a href="{{ route('profile.edit') }}" 
                                               class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors" 
                                               role="menuitem" 
                                               tabindex="-1">
                                                <svg class="h-4 w-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                                Edit Profile
                                            </a>
                                            <div class="border-t border-gray-100 my-1"></div>
                                            <form method="POST" action="{{ route('logout') }}">
                                                @csrf
                                                <button type="submit" 
                                                        class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors" 
                                                        role="menuitem" 
                                                        tabindex="-1">
                                                    <svg class="h-4 w-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                                    </svg>
                                                    Sign out
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                    <!-- Guest Actions -->
                    <div class="hidden sm:flex sm:items-center sm:space-x-3">
                        <a href="{{ route('login') }}" 
                           class="text-gray-700 hover:text-green-600 px-3 py-2 text-sm font-medium transition-colors">
                            Sign In
                        </a>
                        <a href="{{ route('register') }}" 
                           class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors shadow-sm">
                            Get Started
                        </a>
                    </div>
                    @endauth

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
            @auth
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
                            @if(Auth::user()->hasProfilePhoto())
                                <img src="{{ Auth::user()->getAvatarUrl() }}" 
                                     alt="{{ Auth::user()->name }}" 
                                     class="h-10 w-10 rounded-full object-cover"
                                     data-avatar-image>
                            @else
                                <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                                    <span class="text-sm font-medium text-green-600">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                </div>
                            @endif
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
            @else
            <!-- Guest Mobile menu -->
            <div class="sm:hidden hidden" id="mobile-menu">
                <div class="pt-2 pb-3 space-y-1">
                    <a href="{{ route('recipes.index') }}" 
                       class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium {{ request()->routeIs('recipes.*') ? 'bg-green-50 border-green-500 text-green-700' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800' }}">
                        Recipes
                    </a>
                </div>
                <div class="pt-4 pb-3 border-t border-gray-200">
                    <div class="space-y-1 px-2">
                        <a href="{{ route('login') }}" 
                           class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">
                            Sign In
                        </a>
                        <a href="{{ route('register') }}" 
                           class="block px-3 py-2 rounded-md text-base font-medium text-white bg-green-600 hover:bg-green-700">
                            Get Started
                        </a>
                    </div>
                </div>
            </div>
            @endauth
        </nav>

        <!-- Page Content -->
        <main>

            @if(session('error'))
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-gray-50 border-t border-gray-200 mt-auto">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <!-- Main Footer Content -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <!-- Brand Section -->
                    <div class="lg:col-span-2">
                        <div class="flex items-center mb-4">
                            <span class="text-2xl font-bold text-green-600">StudEats</span>
                        </div>
                        <p class="text-gray-600 text-sm leading-relaxed max-w-md">
                            Smart meal planning for Filipino students. Eat healthy, save money, and focus on your studies with our budget-friendly recipes and meal plans.
                        </p>
                    </div>

                    <!-- Quick Links -->
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 tracking-wider uppercase mb-4">Quick Links</h3>
                        <ul class="space-y-3">
                            @auth
                            <li>
                                <a href="{{ route('dashboard') }}" class="text-sm text-gray-600 hover:text-green-600 transition-colors duration-200">
                                    Dashboard
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('meal-plans.index') }}" class="text-sm text-gray-600 hover:text-green-600 transition-colors duration-200">
                                    Meal Plans
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('recipes.index') }}" class="text-sm text-gray-600 hover:text-green-600 transition-colors duration-200">
                                    Recipes
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('profile.show') }}" class="text-sm text-gray-600 hover:text-green-600 transition-colors duration-200">
                                    Profile
                                </a>
                            </li>
                            @else
                            <li>
                                <a href="/" class="text-sm text-gray-600 hover:text-green-600 transition-colors duration-200">
                                    Home
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-green-600 transition-colors duration-200">
                                    Sign In
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('register') }}" class="text-sm text-gray-600 hover:text-green-600 transition-colors duration-200">
                                    Get Started
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('password.request') }}" class="text-sm text-gray-600 hover:text-green-600 transition-colors duration-200">
                                    Forgot Password
                                </a>
                            </li>
                            @endauth
                        </ul>
                    </div>

                    <!-- Support -->
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 tracking-wider uppercase mb-4">Support</h3>
                        <ul class="space-y-3">
                            <li>
                                <a href="mailto:support@studeats.com" class="text-sm text-gray-600 hover:text-green-600 transition-colors duration-200">
                                    Email Support
                                </a>
                            </li>
                            <li class="text-sm text-gray-600">
                                <span class="block font-medium">Questions?</span>
                                <span class="block">support@studeats.com</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Legal Section -->
                <div class="mt-8 pt-8 border-t border-gray-200">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                        <div class="mb-4 md:mb-0">
                            <h4 class="text-sm font-semibold text-gray-900 tracking-wider uppercase mb-3">Legal</h4>
                            <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-6">
                                <a href="{{ route('privacy-policy') }}" class="text-sm text-gray-600 hover:text-green-600 transition-colors duration-200">
                                    Privacy Policy
                                </a>
                                <a href="{{ route('terms-of-service') }}" class="text-sm text-gray-600 hover:text-green-600 transition-colors duration-200">
                                    Terms of Service
                                </a>
                            </div>
                        </div>
                        
                        <!-- Copyright -->
                        <div class="text-sm text-gray-500">
                            <p>&copy; {{ date('Y') }} StudEats. All rights reserved.</p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <script>
        // Mobile menu toggle
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        if (mobileMenuButton) {
            mobileMenuButton.addEventListener('click', function() {
                const mobileMenu = document.getElementById('mobile-menu');
                if (mobileMenu) {
                    mobileMenu.classList.toggle('hidden');
                }
            });
        }

        // Profile dropdown toggle
        const userMenuButton = document.getElementById('user-menu-button');
        if (userMenuButton) {
            userMenuButton.addEventListener('click', function() {
                const dropdown = document.getElementById('user-menu-dropdown');
                if (dropdown) {
                    dropdown.classList.toggle('hidden');
                }
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(event) {
                const dropdown = document.getElementById('user-menu-dropdown');
                const button = document.getElementById('user-menu-button');
                
                if (dropdown && button && !button.contains(event.target) && !dropdown.contains(event.target)) {
                    dropdown.classList.add('hidden');
                }
            });
        }
    </script>
</body>
</html>

