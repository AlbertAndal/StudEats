<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'StudEats') }} Admin - @yield('title', 'Admin Panel')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- Geist Font - Comprehensive weights for consistent typography -->
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Preload critical font weights for faster rendering -->
    <link rel="preload" href="https://fonts.gstatic.com/s/geist/v1/gyB-hkdavoI.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="https://fonts.gstatic.com/s/geist/v1/gyB4hkdavoI.woff2" as="font" type="font/woff2" crossorigin>

    <!-- Admin Lucide Icons Styling -->
    <link rel="stylesheet" href="{{ asset('css/admin-lucide.css') }}">
    
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
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen">
        @include('admin.partials.header')

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

        @include('admin.partials.footer')
    </div>
</body>
</html>