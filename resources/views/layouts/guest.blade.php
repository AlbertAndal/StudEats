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
    
    <!-- Fallback CSS if Vite assets fail to load -->
    <script>
        // Check if Vite CSS loaded, if not load Tailwind from CDN
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
                
                // Add basic styling for immediate visual feedback
                const style = document.createElement('style');
                style.textContent = `
                    body { font-family: 'Geist', ui-sans-serif, system-ui, sans-serif; }
                    .min-h-screen { min-height: 100vh; }
                    .grid { display: grid; }
                    .md\\:grid-cols-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
                    .flex { display: flex; }
                    .items-center { align-items: center; }
                    .justify-center { justify-content: center; }
                    .bg-green-600 { background-color: #059669; }
                    .text-white { color: #ffffff; }
                    .rounded-md { border-radius: 0.375rem; }
                    .px-4 { padding-left: 1rem; padding-right: 1rem; }
                    .py-2 { padding-top: 0.5rem; padding-bottom: 0.5rem; }
                    .w-full { width: 100%; }
                    .max-w-md { max-width: 28rem; }
                    .mx-auto { margin-left: auto; margin-right: auto; }
                `;
                document.head.appendChild(style);
            }
        });
    </script>
</head>
<body class="font-sans antialiased bg-background text-foreground">
    <div class="min-h-screen flex flex-col">
        <main class="flex-grow">
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
</body>
</html>
