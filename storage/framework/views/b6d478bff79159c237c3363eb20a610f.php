<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'StudEats')); ?> Admin - <?php echo $__env->yieldContent('title', 'Admin Panel'); ?></title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- Geist Font - Comprehensive weights for consistent typography -->
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Preload critical font weights for faster rendering -->
    <link rel="preload" href="https://fonts.gstatic.com/s/geist/v1/gyB-hkdavoI.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="https://fonts.gstatic.com/s/geist/v1/gyB4hkdavoI.woff2" as="font" type="font/woff2" crossorigin>

    <!-- Admin Lucide Icons Styling -->
    <link rel="stylesheet" href="<?php echo e(asset('css/admin-lucide.css')); ?>">
    
    <!-- Scripts -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    
    <!-- CSRF Manager for session handling and 419 error prevention -->
    <script src="<?php echo e(asset('js/csrf-manager.js')); ?>"></script>
    
    <!-- Fallback CSS for production if Vite assets fail to load -->
    <?php if(app()->environment('production')): ?>
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
    <?php endif; ?>
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen">
        <?php echo $__env->make('admin.partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <!-- Page Content -->
        <main>


            <?php if(session('error')): ?>
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline"><?php echo e(session('error')); ?></span>
                    </div>
                </div>
            <?php endif; ?>

            <?php echo $__env->yieldContent('content'); ?>
        </main>

        <?php echo $__env->make('admin.partials.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>
</body>
</html><?php /**PATH C:\xampp\htdocs\StudEats\resources\views/layouts/admin.blade.php ENDPATH**/ ?>