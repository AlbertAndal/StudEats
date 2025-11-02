<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>StudEats - Smart Meal Planning for Students</title>
    
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    
    <style>
        @keyframes fadeUp {
            0% {
                opacity: 0;
                transform: translateY(30px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .hero-title {
            animation: fadeUp 1s ease-in-out;
        }
    </style>
</head>
<body class="antialiased font-sans">
    <?php if(auth()->guard()->check()): ?>
        <script>window.location.href = "<?php echo e(route('dashboard')); ?>";</script>
    <?php else: ?>
    <div class="min-h-screen bg-gradient-to-br from-green-50 to-blue-50">
        <a href="#main" class="sr-only focus:not-sr-only focus:absolute focus:top-2 focus:left-2 focus:z-50 bg-white text-gray-900 px-3 py-2 rounded shadow">Skip to content</a>
        <!-- Navigation -->
        <nav class="bg-white/90 backdrop-blur supports-[backdrop-filter]:bg-white/70 sticky top-0 z-40 shadow-sm" aria-label="Primary">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 flex items-center">
                            <span class="text-2xl font-bold text-green-600">StudEats</span>
                        </div>
                    </div>
                    <div class="hidden md:flex items-center space-x-6">
                        <a href="#features" class="text-gray-700 hover:text-green-600 text-sm font-medium">Features</a>
                        <a href="<?php echo e(route('recipes.index')); ?>" class="text-gray-700 hover:text-green-600 text-sm font-medium">Meals</a>
                        <a href="#faq" class="text-gray-700 hover:text-green-600 text-sm font-medium">FAQ</a>
                    </div>
                    <div class="flex items-center space-x-3">
                        <?php if(auth()->guard()->check()): ?>
                            <!-- Profile dropdown -->
                            <div class="relative">
                                <img id="avatarButton" type="button" data-dropdown-toggle="userDropdown" data-dropdown-placement="bottom-start" class="w-10 h-10 rounded-full cursor-pointer" src="/docs/images/people/profile-picture-5.jpg" alt="User dropdown">

                                <!-- Dropdown menu -->
                                <div id="userDropdown" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44 dark:bg-gray-700 dark:divide-gray-600 absolute right-0 mt-2">
                                    <div class="px-4 py-3 text-sm text-gray-900 dark:text-white">
                                        <div><?php echo e(Auth::user()->name ?? 'User'); ?></div>
                                        <div class="font-medium truncate"><?php echo e(Auth::user()->email); ?></div>
                                    </div>
                                    <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="avatarButton">
                                        <li>
                                            <a href="<?php echo e(route('dashboard')); ?>" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Dashboard</a>
                                        </li>
                                        <li>
                                            <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Settings</a>
                                        </li>
                                        <li>
                                            <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Earnings</a>
                                        </li>
                                    </ul>
                                    <div class="py-1">
                                        <form method="POST" action="<?php echo e(route('logout')); ?>">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">
                                                Sign out
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php else: ?>
                            <a href="<?php echo e(route('login')); ?>" class="text-gray-700 hover:text-green-600 px-3 py-2 text-sm font-medium">
                                Sign In
                            </a>
                            <a href="<?php echo e(route('register')); ?>" class="bg-green-600 hover:bg-green-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-green-600 text-white px-4 py-2 rounded-md text-sm font-medium">
                                Sign Up
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <div class="relative overflow-hidden min-h-[700px] sm:min-h-[750px] lg:min-h-[800px] flex items-center justify-center py-16 sm:py-20 md:py-24 lg:py-28 xl:py-32">
            <div class="max-w-7xl mx-auto w-full px-6 sm:px-8 lg:px-10">
                <div class="relative z-10">
                    <main id="main" class="flex flex-col items-center justify-center text-center">
                        <div class="max-w-4xl mx-auto px-4 sm:px-6">
                            <h1 class="hero-title text-4xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-6xl lg:text-7xl leading-tight sm:leading-tight md:leading-tight lg:leading-tight">
                                <span class="block mb-2 sm:mb-3 md:mb-4">Meal Planning</span>
                                <span class="block text-green-600">for Filipino Students</span>
                            </h1>
                            <p class="mt-8 sm:mt-10 md:mt-12 text-base text-gray-600 sm:text-lg md:text-xl lg:text-2xl max-w-3xl mx-auto leading-relaxed sm:leading-relaxed md:leading-relaxed px-4">
                                Helping students eat smarter and spend wiser with healthy, budget-friendly meal plans made for college students
                            </p>
                            <div class="mt-10 sm:mt-12 md:mt-14 lg:mt-16 flex flex-col sm:flex-row gap-4 justify-center items-center px-4">
                                <a href="<?php echo e(route('register')); ?>" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-green-600 transition-colors shadow-lg hover:shadow-xl">
                                    Sign Up Now
                                </a>
                                <a href="<?php echo e(route('recipes.index')); ?>" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-green-700 bg-green-100 hover:bg-green-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-green-600 transition-colors">
                                    Browse Sample Meals
                                </a>
                            </div>
                        </div>
                    </main>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <div id="features" class="py-16 sm:py-20 lg:py-24 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="text-center mb-12 sm:mb-16 lg:mb-20">
                    <p class="text-sm font-semibold text-green-600 tracking-wider uppercase mb-3">Features</p>
                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 mb-4 sm:mb-6 leading-tight px-4">
                        Smart meal planning made simple
                    </h2>
                    <p class="text-base sm:text-lg text-gray-600 max-w-2xl mx-auto leading-relaxed px-4">
                        Comprehensive tools that help Filipino students plan nutritious meals, track spending, and manage their food budget efficiently.
                    </p>
                </div>

                <!-- Features Content -->
                <div class="max-w-6xl mx-auto">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                        <!-- Image Slideshow -->
                        <div class="relative lg:order-1 order-2">
                            <div class="slideshow-container rounded-2xl overflow-hidden shadow-2xl bg-gray-100 aspect-square">
                                <!-- Slide 1 -->
                                <div class="slide fade active">
                                    <img src="https://images.unsplash.com/photo-1565299624946-b28f40a0ca4b?auto=format&fit=crop&w=800&q=80" 
                                         alt="Budget-friendly Filipino meal" 
                                         class="w-full h-full object-cover">
                                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/60 to-transparent p-6">
                                        <p class="text-white text-sm font-medium">Budget-Friendly Planning</p>
                                    </div>
                                </div>
                                <!-- Slide 2 -->
                                <div class="slide fade">
                                    <img src="https://images.unsplash.com/photo-1555939594-58d7cb561ad1?auto=format&fit=crop&w=800&q=80" 
                                         alt="Filipino adobo dish" 
                                         class="w-full h-full object-cover">
                                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/60 to-transparent p-6">
                                        <p class="text-white text-sm font-medium">Filipino Recipes</p>
                                    </div>
                                </div>
                                <!-- Slide 3 -->
                                <div class="slide fade">
                                    <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=800&q=80" 
                                         alt="Quick and simple meal preparation" 
                                         class="w-full h-full object-cover">
                                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/60 to-transparent p-6">
                                        <p class="text-white text-sm font-medium">Quick & Simple</p>
                                    </div>
                                </div>
                                <!-- Slide 4 -->
                                <div class="slide fade">
                                    <img src="https://images.unsplash.com/photo-1490645935967-10de6ba17061?auto=format&fit=crop&w=800&q=80" 
                                         alt="Weekly meal planning calendar" 
                                         class="w-full h-full object-cover">
                                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/60 to-transparent p-6">
                                        <p class="text-white text-sm font-medium">Weekly Meal Calendar</p>
                                    </div>
                                </div>
                                
                                <!-- Navigation dots -->
                                <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2">
                                    <span class="dot active" onclick="currentSlide(1)"></span>
                                    <span class="dot" onclick="currentSlide(2)"></span>
                                    <span class="dot" onclick="currentSlide(3)"></span>
                                    <span class="dot" onclick="currentSlide(4)"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Features Text -->
                        <div class="lg:order-2 order-1 space-y-8">
                            <!-- Feature 1 -->
                            <div class="group">
                                <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-3 leading-tight">Budget-Friendly Planning</h3>
                                <p class="text-base sm:text-lg text-gray-600 leading-relaxed">
                                    Track your daily food budget with precision. Stay under preferred budget with diverse meal suggestions.
                                </p>
                            </div>

                            <!-- Feature 2 -->
                            <div class="group">
                                <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-3 leading-tight">Filipino Recipes</h3>
                                <p class="text-base sm:text-lg text-gray-600 leading-relaxed">
                                    Authentic Filipino dishes simplified for students. From adobo to sinigang, cook what you love.
                                </p>
                            </div>

                            <!-- Feature 3 -->
                            <div class="group">
                                <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-3 leading-tight">Quick & Simple</h3>
                                <p class="text-base sm:text-lg text-gray-600 leading-relaxed">
                                    Most meals ready in 15-30 minutes. Perfect for busy schedules between classes and study sessions.
                                </p>
                            </div>

                            <!-- Feature 4 -->
                            <div class="group">
                                <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-3 leading-tight">Weekly Meal Calendar</h3>
                                <p class="text-base sm:text-lg text-gray-600 leading-relaxed">
                                    Plan your entire week in advance. Never worry about what to eat or repeat meals by accident.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Slideshow CSS and JavaScript -->
        <style>
        .slideshow-container {
            position: relative;
            height: 500px;
        }

        .slide {
            display: none;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .slide.active {
            display: block;
        }

        .slide.fade {
            animation-name: fade;
            animation-duration: 1.5s;
        }

        @keyframes fade {
            from {opacity: 0.4} 
            to {opacity: 1}
        }

        .dot {
            cursor: pointer;
            height: 10px;
            width: 10px;
            background-color: rgba(255, 255, 255, 0.4);
            border-radius: 50%;
            display: inline-block;
            transition: background-color 0.3s ease;
        }

        .dot.active, .dot:hover {
            background-color: rgba(255, 255, 255, 0.9);
        }

        @media (max-width: 1023px) {
            .slideshow-container {
                height: 400px;
            }
        }

        @media (max-width: 640px) {
            .slideshow-container {
                height: 300px;
            }
        }
        </style>

        <script>
        let slideIndex = 1;
        let slideTimer;

        function showSlides(n) {
            let slides = document.getElementsByClassName("slide");
            let dots = document.getElementsByClassName("dot");
            
            if (n > slides.length) {slideIndex = 1}
            if (n < 1) {slideIndex = slides.length}
            
            for (let i = 0; i < slides.length; i++) {
                slides[i].classList.remove("active");
            }
            
            for (let i = 0; i < dots.length; i++) {
                dots[i].classList.remove("active");
            }
            
            if (slides[slideIndex-1]) {
                slides[slideIndex-1].classList.add("active");
            }
            if (dots[slideIndex-1]) {
                dots[slideIndex-1].classList.add("active");
            }
        }

        function currentSlide(n) {
            clearTimeout(slideTimer);
            showSlides(slideIndex = n);
            autoSlide();
        }

        function nextSlide() {
            showSlides(slideIndex += 1);
        }

        function autoSlide() {
            slideTimer = setTimeout(function() {
                nextSlide();
                autoSlide();
            }, 4000);
        }

        // Initialize slideshow when page loads
        document.addEventListener('DOMContentLoaded', function() {
            showSlides(slideIndex);
            autoSlide();
        });
        </script>

        <!-- Testimonials Carousel Section -->
        <section class="bg-gray-50">
            <div class="max-w-screen-xl px-4 py-16 mx-auto text-center lg:py-20 lg:px-6">
                <div class="mx-auto max-w-screen-sm text-center lg:mb-16 mb-12">
                    <h2 class="mb-4 text-3xl lg:text-4xl tracking-tight font-bold text-gray-800">What Students Say</h2>
                    <p class="text-gray-600 text-lg leading-relaxed">Real experiences from Filipino students</p>
                </div>

                <div id="testimonial-carousel" class="relative" data-carousel="slide">
                    <!-- Carousel wrapper -->
                    <div class="relative overflow-hidden rounded-lg h-96">
                        <!-- Item 1 -->
                        <div class="hidden duration-700 ease-in-out" data-carousel-item="active">
                            <figure class="max-w-screen-md mx-auto px-4">
                                <svg class="h-10 mx-auto mb-4 text-gray-300" viewBox="0 0 24 27" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M14.017 18L14.017 10.609C14.017 4.905 17.748 1.039 23 0L23.995 2.151C21.563 3.068 20 5.789 20 8H24V18H14.017ZM0 18V10.609C0 4.905 3.748 1.038 9 0L9.996 2.151C7.563 3.068 6 5.789 6 8H9.983L9.983 18L0 18Z" fill="currentColor"/>
                                </svg> 
                                <blockquote>
                                    <p class="text-xl sm:text-2xl text-gray-700 leading-relaxed">"StudEats completely transformed how I approach meal planning as a student. No more expensive food delivery or unhealthy instant meals - I'm finally eating proper Filipino food that fits my budget and schedule!"</p>
                                </blockquote>
                                <figcaption class="flex items-center justify-center mt-8 space-x-3">
                                    <img class="w-10 h-10 rounded-full" src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/avatars/karen-nelson.png" alt="profile picture">
                                    <div class="flex items-center divide-x divide-gray-300">
                                        <div class="pr-3 font-semibold text-gray-800">Maria Santos</div>
                                        <div class="pl-3 text-sm text-gray-600">BS Psychology Student at UP Diliman</div>
                                    </div>
                                </figcaption>
                            </figure>
                        </div>
                        <!-- Item 2 -->
                        <div class="hidden duration-700 ease-in-out" data-carousel-item>
                            <figure class="max-w-screen-md mx-auto px-4">
                                <svg class="h-10 mx-auto mb-4 text-gray-300" viewBox="0 0 24 27" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M14.017 18L14.017 10.609C14.017 4.905 17.748 1.039 23 0L23.995 2.151C21.563 3.068 20 5.789 20 8H24V18H14.017ZM0 18V10.609C0 4.905 3.748 1.038 9 0L9.996 2.151C7.563 3.068 6 5.789 6 8H9.983L9.983 18L0 18Z" fill="currentColor"/>
                                </svg> 
                                <blockquote>
                                    <p class="text-xl sm:text-2xl text-gray-700 leading-relaxed">"Finally learned to cook adobo without calling my mom every 5 minutes! The step-by-step guides are so easy to follow, and I've saved over ₱2,000 this month compared to food delivery."</p>
                                </blockquote>
                                <figcaption class="flex items-center justify-center mt-8 space-x-3">
                                    <img class="w-10 h-10 rounded-full" src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/avatars/roberta-casas.png" alt="profile picture">
                                    <div class="flex items-center divide-x divide-gray-300">
                                        <div class="pr-3 font-semibold text-gray-800">Josh Rivera</div>
                                        <div class="pl-3 text-sm text-gray-600">BS Management Student at Ateneo</div>
                                    </div>
                                </figcaption>
                            </figure>
                        </div>
                        <!-- Item 3 -->
                        <div class="hidden duration-700 ease-in-out" data-carousel-item>
                            <figure class="max-w-screen-md mx-auto px-4">
                                <svg class="h-10 mx-auto mb-4 text-gray-300" viewBox="0 0 24 27" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M14.017 18L14.017 10.609C14.017 4.905 17.748 1.039 23 0L23.995 2.151C21.563 3.068 20 5.789 20 8H24V18H14.017ZM0 18V10.609C0 4.905 3.748 1.038 9 0L9.996 2.151C7.563 3.068 6 5.789 6 8H9.983L9.983 18L0 18Z" fill="currentColor"/>
                                </svg> 
                                <blockquote>
                                    <p class="text-xl sm:text-2xl text-gray-700 leading-relaxed">"My grades improved dramatically when I stopped skipping meals! Having a proper meal plan made all the difference. StudEats helped me balance nutrition, budget, and my hectic schedule."</p>
                                </blockquote>
                                <figcaption class="flex items-center justify-center mt-8 space-x-3">
                                    <img class="w-10 h-10 rounded-full" src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/avatars/jese-leos.png" alt="profile picture">
                                    <div class="flex items-center divide-x divide-gray-300">
                                        <div class="pr-3 font-semibold text-gray-800">Alex Cruz</div>
                                        <div class="pl-3 text-sm text-gray-600">BS Engineering Student at UST</div>
                                    </div>
                                </figcaption>
                            </figure>
                        </div>
                        <!-- Item 4 -->
                        <div class="hidden duration-700 ease-in-out" data-carousel-item>
                            <figure class="max-w-screen-md mx-auto px-4">
                                <svg class="h-10 mx-auto mb-4 text-gray-300" viewBox="0 0 24 27" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M14.017 18L14.017 10.609C14.017 4.905 17.748 1.039 23 0L23.995 2.151C21.563 3.068 20 5.789 20 8H24V18H14.017ZM0 18V10.609C0 4.905 3.748 1.038 9 0L9.996 2.151C7.563 3.068 6 5.789 6 8H9.983L9.983 18L0 18Z" fill="currentColor"/>
                                </svg> 
                                <blockquote>
                                    <p class="text-xl sm:text-2xl text-gray-700 leading-relaxed">"As a working student, StudEats has been a lifesaver! I can prep healthy Filipino meals in advance and stay within my ₱250 daily food budget. My parents are so proud of my independence."</p>
                                </blockquote>
                                <figcaption class="flex items-center justify-center mt-8 space-x-3">
                                    <img class="w-10 h-10 rounded-full" src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/avatars/sofia-mcguire.png" alt="profile picture">
                                    <div class="flex items-center divide-x divide-gray-300">
                                        <div class="pr-3 font-semibold text-gray-800">Sophia Dela Cruz</div>
                                        <div class="pl-3 text-sm text-gray-600">BS Nursing Student at FEU</div>
                                    </div>
                                </figcaption>
                            </figure>
                        </div>
                    </div>
                    
                    <!-- Slider indicators -->
                    <div class="absolute z-30 flex space-x-3 -translate-x-1/2 bottom-5 left-1/2">
                        <button type="button" class="w-2.5 h-2.5 rounded-full bg-gray-300 hover:bg-gray-400 transition-colors" aria-current="true" aria-label="Slide 1" data-carousel-slide-to="0"></button>
                        <button type="button" class="w-2.5 h-2.5 rounded-full bg-gray-300 hover:bg-gray-400 transition-colors" aria-current="false" aria-label="Slide 2" data-carousel-slide-to="1"></button>
                        <button type="button" class="w-2.5 h-2.5 rounded-full bg-gray-300 hover:bg-gray-400 transition-colors" aria-current="false" aria-label="Slide 3" data-carousel-slide-to="2"></button>
                        <button type="button" class="w-2.5 h-2.5 rounded-full bg-gray-300 hover:bg-gray-400 transition-colors" aria-current="false" aria-label="Slide 4" data-carousel-slide-to="3"></button>
                    </div>
                    
                    <!-- Slider controls -->
                    <button type="button" class="absolute top-0 left-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" data-carousel-prev>
                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/60 hover:bg-white/80 transition-all shadow-sm">
                            <svg class="w-4 h-4 text-gray-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 1 1 5l4 4"/>
                            </svg>
                            <span class="sr-only">Previous</span>
                        </span>
                    </button>
                    <button type="button" class="absolute top-0 right-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" data-carousel-next>
                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/60 hover:bg-white/80 transition-all shadow-sm">
                            <svg class="w-4 h-4 text-gray-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                            </svg>
                            <span class="sr-only">Next</span>
                        </span>
                    </button>
                </div>
            </div>
        </section>

        <!-- FAQ Section -->
        <section id="faq" class="py-24">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="mb-16">
                    <h6 class="text-lg text-green-600 font-medium text-center mb-2">
                        FAQs
                    </h6>
                    <h2 class="text-4xl font-manrope text-center font-bold text-gray-900 leading-[3.25rem]">
                        Frequently asked questions
                    </h2>
                </div>

                <div class="accordion-group" data-accordion="default-accordion">
                    <div class="accordion py-8 px-6 border-b border-solid border-gray-200 transition-all duration-500 rounded-2xl hover:bg-green-50 accordion-active:bg-green-50 active" id="basic-heading-one-with-arrow">
                        <button class="accordion-toggle group inline-flex items-center justify-between leading-8 text-gray-900 w-full transition duration-500 text-left hover:text-green-600 accordion-active:font-medium accordion-active:text-green-600" aria-controls="basic-collapse-one-with-arrow">
                            <h5>Do I need cooking experience to use StudEats?</h5>
                            <svg class="text-gray-500 transition duration-500 group-hover:text-green-600 accordion-active:text-green-600 accordion-active:rotate-180" width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M16.5 8.25L12.4142 12.3358C11.7475 13.0025 11.4142 13.3358 11 13.3358C10.5858 13.3358 10.2525 13.0025 9.58579 12.3358L5.5 8.25" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </button>
                        <div id="basic-collapse-one-with-arrow" class="accordion-content w-full px-0 overflow-hidden transition-all duration-500" aria-labelledby="basic-heading-one-with-arrow" style="max-height: 0;">
                            <div class="pt-4">
                                <p class="text-base text-gray-900 leading-6">
                                    No cooking experience needed! StudEats features simple recipes perfect for students living in dorms or shared apartments. Our recipes use basic cooking methods like boiling, frying, and sautéing with detailed instructions. Many meals can be prepared with just a rice cooker, electric stove, or basic kitchen setup.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion py-8 px-6 border-b border-solid border-gray-200 transition-all duration-500 rounded-2xl hover:bg-green-50 accordion-active:bg-green-50" id="basic-heading-two-with-arrow">
                        <button class="accordion-toggle group inline-flex items-center justify-between leading-8 text-gray-900 w-full transition duration-500 text-left hover:text-green-600 accordion-active:text-green-600" aria-controls="basic-collapse-two-with-arrow">
                            <h5>How much money can I save with meal planning?</h5>
                            <svg class="text-gray-500 transition duration-500 group-hover:text-green-600 accordion-active:text-green-600 accordion-active:rotate-180" width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M16.5 8.25L12.4142 12.3358C11.7475 13.0025 11.4142 13.3358 11 13.3358C10.5858 13.3358 10.2525 13.0025 9.58579 12.3358L5.5 8.25" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </button>
                        <div id="basic-collapse-two-with-arrow" class="accordion-content w-full px-0 overflow-hidden transition-all duration-500" aria-labelledby="basic-heading-two-with-arrow" style="max-height: 0;">
                            <div class="pt-4">
                                <p class="text-base text-gray-900 leading-6">
                                    Home cooking can save you ₱3,000-6,000 monthly compared to regular food delivery and canteen meals. While a typical fastfood meal costs ₱150-300, our planned meals average ₱80-200 per serving. Actual savings depend on your current eating habits and local ingredient prices.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion py-8 px-6 border-b border-solid border-gray-200 transition-all duration-500 rounded-2xl hover:bg-green-50 accordion-active:bg-green-50" id="basic-heading-three-with-arrow">
                        <button class="accordion-toggle group inline-flex items-center justify-between leading-8 text-gray-900 w-full transition duration-500 text-left hover:text-green-600 accordion-active:text-green-600" aria-controls="basic-collapse-three-with-arrow">
                            <h5>Are these really authentic Filipino recipes?</h5>
                            <svg class="text-gray-500 transition duration-500 group-hover:text-green-600 accordion-active:text-green-600 accordion-active:rotate-180" width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M16.5 8.25L12.4142 12.3358C11.7475 13.0025 11.4142 13.3358 11 13.3358C10.5858 13.3358 10.2525 13.0025 9.58579 12.3358L5.5 8.25" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </button>
                        <div id="basic-collapse-three-with-arrow" class="accordion-content w-full px-0 overflow-hidden transition-all duration-500" aria-labelledby="basic-heading-three-with-arrow" style="max-height: 0;">
                            <div class="pt-4">
                                <p class="text-base text-gray-900 leading-6">
                                    Yes! Our recipes feature authentic Filipino comfort food adapted for student budgets and cooking skills. We focus on popular dishes like adobo, sinigang, tinola, and fried rice using traditional ingredients you'll find in any Filipino household or local market.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion py-8 px-6 border-b border-solid border-gray-200 transition-all duration-500 rounded-2xl hover:bg-green-50 accordion-active:bg-green-50" id="basic-heading-four-with-arrow">
                        <button class="accordion-toggle group inline-flex items-center justify-between leading-8 text-gray-900 w-full transition duration-500 text-left hover:text-green-600 accordion-active:text-green-600" aria-controls="basic-collapse-four-with-arrow">
                            <h5>Where can I find the ingredients for these recipes?</h5>
                            <svg class="text-gray-500 transition duration-500 group-hover:text-green-600 accordion-active:text-green-600 accordion-active:rotate-180" width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M16.5 8.25L12.4142 12.3358C11.7475 13.0025 11.4142 13.3358 11 13.3358C10.5858 13.3358 10.2525 13.0025 9.58579 12.3358L5.5 8.25" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </button>
                        <div id="basic-collapse-four-with-arrow" class="accordion-content w-full px-0 overflow-hidden transition-all duration-500" aria-labelledby="basic-heading-four-with-arrow" style="max-height: 0;">
                            <div class="pt-4">
                                <p class="text-base text-gray-900 leading-6">
                                    All ingredients are commonly available at wet markets, grocery stores like SM, Puregold, Robinson's, or local sari-sari stores. We prioritize affordable, Filipino staples and provide alternative ingredients when certain items might be expensive or unavailable in your area.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion py-8 px-6 border-b border-solid border-gray-200 transition-all duration-500 rounded-2xl hover:bg-green-50 accordion-active:bg-green-50" id="basic-heading-five-with-arrow">
                        <button class="accordion-toggle group inline-flex items-center justify-between leading-8 text-gray-900 w-full transition duration-500 text-left hover:text-green-600 accordion-active:text-green-600" aria-controls="basic-collapse-five-with-arrow">
                            <h5>How long do meals take to prepare?</h5>
                            <svg class="text-gray-500 transition duration-500 group-hover:text-green-600 accordion-active:text-green-600 accordion-active:rotate-180" width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M16.5 8.25L12.4142 12.3358C11.7475 13.0025 11.4142 13.3358 11 13.3358C10.5858 13.3358 10.2525 13.0025 9.58579 12.3358L5.5 8.25" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </button>
                        <div id="basic-collapse-five-with-arrow" class="accordion-content w-full px-0 overflow-hidden transition-all duration-500" aria-labelledby="basic-heading-five-with-arrow" style="max-height: 0;">
                            <div class="pt-4">
                                <p class="text-base text-gray-900 leading-6">
                                    Preparation times vary from 10-45 minutes depending on the recipe complexity. Quick meals like scrambled eggs or instant noodle upgrades take 10-15 minutes, while dishes like adobo or sinigang may need 30-45 minutes. Each recipe clearly shows prep and cooking times to help you plan around your schedule.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion py-8 px-6 transition-all duration-500 rounded-2xl hover:bg-green-50 accordion-active:bg-green-50" id="basic-heading-six-with-arrow">
                        <button class="accordion-toggle group inline-flex items-center justify-between leading-8 text-gray-900 w-full transition duration-500 text-left hover:text-green-600 accordion-active:text-green-600" aria-controls="basic-collapse-six-with-arrow">
                            <h5>Is StudEats really free to use?</h5>
                            <svg class="text-gray-500 transition duration-500 group-hover:text-green-600 accordion-active:text-green-600 accordion-active:rotate-180" width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M16.5 8.25L12.4142 12.3358C11.7475 13.0025 11.4142 13.3358 11 13.3358C10.5858 13.3358 10.2525 13.0025 9.58579 12.3358L5.5 8.25" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </button>
                        <div id="basic-collapse-six-with-arrow" class="accordion-content w-full px-0 overflow-hidden transition-all duration-500" aria-labelledby="basic-heading-six-with-arrow" style="max-height: 0;">
                            <div class="pt-4">
                                <p class="text-base text-gray-900 leading-6">
                                    Yes, StudEats is free to use for basic meal planning and recipes. You can create meal plans, browse recipes, and track your food budget without any subscription fees. We believe healthy eating should be accessible to all Filipino students regardless of their financial situation.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <div class="bg-green-600">
            <div class="max-w-2xl mx-auto text-center py-16 px-4 sm:py-20 sm:px-6 lg:px-8">
                <h2 class="text-3xl font-extrabold text-white sm:text-4xl">
                    <span class="block">Start Eating Better Today</span>
                </h2>
                <p class="mt-4 text-lg leading-6 text-green-100">
                    Join fellow Filipino students who've transformed their eating habits with StudEats.
                </p>
                <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="<?php echo e(route('register')); ?>" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-green-700 bg-white hover:bg-green-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-white transition-colors">
                        Create Free Account
                    </a>
                    <a href="<?php echo e(route('recipes.index')); ?>" class="inline-flex items-center justify-center px-6 py-3 border-2 border-white text-base font-medium rounded-md text-white hover:bg-green-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-white transition-colors">
                        Browse Sample Meals
                    </a>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-gray-900">
            <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:py-16 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <!-- Brand Section -->
                    <div class="col-span-1 md:col-span-2">
                        <div class="flex items-center mb-4">
                            <span class="text-3xl font-bold text-white">StudEats</span>
                        </div>
                        <p class="text-gray-400 text-base mb-6 max-w-md">
                            Smart meal planning for Filipino students. Eat healthy, save money, and focus on your studies with our budget-friendly recipes and meal plans.
                        </p>
                        <div class="flex space-x-4">
                            <a href="#" class="text-gray-400 hover:text-white transition-colors" aria-label="Twitter">
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"/>
                                </svg>
                            </a>
                            <a href="https://www.facebook.com/profile.php?id=61576740215108" target="_blank" rel="noopener noreferrer" class="text-gray-400 hover:text-white transition-colors" aria-label="Facebook">
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd"/>
                                </svg>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-white transition-colors" aria-label="Instagram">
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd"/>
                                </svg>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-white transition-colors" aria-label="YouTube">
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd" d="M19.812 5.418c.861.23 1.538.907 1.768 1.768C21.998 8.746 22 12 22 12s0 3.255-.418 4.814a2.504 2.504 0 0 1-1.768 1.768c-1.56.419-7.814.419-7.814.419s-6.255 0-7.814-.419a2.505 2.505 0 0 1-1.768-1.768C2 15.255 2 12 2 12s0-3.255.417-4.814a2.507 2.507 0 0 1 1.768-1.768C5.744 5 11.998 5 11.998 5s6.255 0 7.814.418ZM15.194 12 10 15V9l5.194 3Z" clip-rule="evenodd"/>
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div class="col-span-1">
                        <h3 class="text-sm font-semibold text-white tracking-wider uppercase mb-4">Quick Links</h3>
                        <ul class="space-y-3">
                            <li><a href="#features" class="text-gray-400 hover:text-white transition-colors">Features</a></li>
                            <li><a href="#faq" class="text-gray-400 hover:text-white transition-colors">FAQ</a></li>
                            <li><a href="<?php echo e(route('register')); ?>" class="text-gray-400 hover:text-white transition-colors">Get Started</a></li>
                        </ul>
                    </div>

                    <!-- Support -->
                    <div class="col-span-1">
                        <h3 class="text-sm font-semibold text-white tracking-wider uppercase mb-4">Support</h3>
                        <ul class="space-y-3">
                            <li><a href="mailto:studeats23@gmail.com?subject=Help%20Request" class="text-gray-400 hover:text-white transition-colors">Help Center</a></li>
                            <li><a href="<?php echo e(route('contact.show')); ?>" class="text-gray-400 hover:text-white transition-colors">Contact Us</a></li>
                            <li><a href="<?php echo e(route('privacy-policy')); ?>" class="text-gray-400 hover:text-white transition-colors">Privacy Policy</a></li>
                            <li><a href="<?php echo e(route('terms-of-service')); ?>" class="text-gray-400 hover:text-white transition-colors">Terms of Service</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Newsletter Signup -->
                <div class="mt-12 pt-8 border-t border-gray-800">
                    <div class="md:flex md:items-center md:justify-between">
                        <div class="md:flex-1">
                            <h3 class="text-lg font-semibold text-white mb-2">Stay updated with new recipes</h3>
                            <p class="text-gray-400 text-sm">Get weekly Filipino meal ideas and budget tips delivered to your inbox.</p>
                        </div>
                        <div class="mt-4 md:mt-0 md:ml-8">
                            <form class="flex max-w-md" action="mailto:studeats23@gmail.com?subject=Newsletter%20Subscription" method="get">
                                <input type="email" name="body" placeholder="Enter your email" class="flex-1 px-4 py-2 bg-gray-800 border border-gray-700 rounded-l-md text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent" required>
                                <button type="submit" class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-r-md transition-colors focus:outline-none focus:ring-2 focus:ring-green-500">
                                    Subscribe
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Bottom -->
                <div class="mt-8 pt-8 border-t border-gray-800">
                    <p class="text-sm text-gray-400 text-center">
                        © 2025 StudEats. A Capstone Project by Jhet Reuel De Ramos, Allen Antonio, and John Albert Andal. All rights reserved.
                    </p>
                    <p class="text-xs text-gray-500 text-center mt-2">
                        Support: <a href="mailto:studeats23@gmail.com" class="text-green-400 hover:text-green-300 transition-colors">studeats23@gmail.com</a>
                    </p>
                </div>
            </div>
        </footer>
    </div>
    <?php endif; ?>

    <!-- Accordion JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Profile Dropdown JavaScript
            const avatarButton = document.getElementById('avatarButton');
            const userDropdown = document.getElementById('userDropdown');
            
            if (avatarButton && userDropdown) {
                avatarButton.addEventListener('click', function() {
                    userDropdown.classList.toggle('hidden');
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', function(event) {
                    if (!avatarButton.contains(event.target) && !userDropdown.contains(event.target)) {
                        userDropdown.classList.add('hidden');
                    }
                });

                // Close dropdown when pressing Escape key
                document.addEventListener('keydown', function(event) {
                    if (event.key === 'Escape') {
                        userDropdown.classList.add('hidden');
                    }
                });
            }

            // Get all accordion toggles
            const accordionToggles = document.querySelectorAll('.accordion-toggle');
            
            accordionToggles.forEach(function(toggle) {
                toggle.addEventListener('click', function() {
                    const targetId = this.getAttribute('aria-controls');
                    const targetContent = document.getElementById(targetId);
                    const accordion = this.closest('.accordion');
                    const isActive = accordion.classList.contains('accordion-active');
                    
                    // Close all other accordions
                    accordionToggles.forEach(function(otherToggle) {
                        const otherTargetId = otherToggle.getAttribute('aria-controls');
                        const otherTargetContent = document.getElementById(otherTargetId);
                        const otherAccordion = otherToggle.closest('.accordion');
                        
                        if (otherToggle !== toggle) {
                            otherAccordion.classList.remove('accordion-active');
                            otherTargetContent.style.maxHeight = '0';
                        }
                    });
                    
                    // Toggle current accordion
                    if (isActive) {
                        accordion.classList.remove('accordion-active');
                        targetContent.style.maxHeight = '0';
                    } else {
                        accordion.classList.add('accordion-active');
                        targetContent.style.maxHeight = targetContent.scrollHeight + 'px';
                    }
                });
            });

            // Testimonial Carousel JavaScript
            const carousel = document.getElementById('testimonial-carousel');
            const items = carousel.querySelectorAll('[data-carousel-item]');
            const indicators = carousel.querySelectorAll('[data-carousel-slide-to]');
            const prevButton = carousel.querySelector('[data-carousel-prev]');
            const nextButton = carousel.querySelector('[data-carousel-next]');
            
            let currentIndex = 0;
            let autoSlideInterval;

            function showSlide(index) {
                // Hide all items
                items.forEach((item, i) => {
                    item.classList.add('hidden');
                    item.removeAttribute('data-carousel-item');
                    if (i === index) {
                        item.classList.remove('hidden');
                        item.setAttribute('data-carousel-item', 'active');
                    } else {
                        item.setAttribute('data-carousel-item', '');
                    }
                });

                // Update indicators
                indicators.forEach((indicator, i) => {
                    if (i === index) {
                        indicator.classList.remove('bg-white/50');
                        indicator.classList.add('bg-white');
                        indicator.setAttribute('aria-current', 'true');
                    } else {
                        indicator.classList.remove('bg-white');
                        indicator.classList.add('bg-white/50');
                        indicator.setAttribute('aria-current', 'false');
                    }
                });

                currentIndex = index;
            }

            function nextSlide() {
                const nextIndex = (currentIndex + 1) % items.length;
                showSlide(nextIndex);
            }

            function prevSlide() {
                const prevIndex = (currentIndex - 1 + items.length) % items.length;
                showSlide(prevIndex);
            }

            function startAutoSlide() {
                autoSlideInterval = setInterval(nextSlide, 5000); // Auto slide every 5 seconds
            }

            function stopAutoSlide() {
                clearInterval(autoSlideInterval);
            }

            // Event listeners
            nextButton.addEventListener('click', () => {
                stopAutoSlide();
                nextSlide();
                startAutoSlide();
            });

            prevButton.addEventListener('click', () => {
                stopAutoSlide();
                prevSlide();
                startAutoSlide();
            });

            indicators.forEach((indicator, index) => {
                indicator.addEventListener('click', () => {
                    stopAutoSlide();
                    showSlide(index);
                    startAutoSlide();
                });
            });

            // Pause auto-slide on hover
            carousel.addEventListener('mouseenter', stopAutoSlide);
            carousel.addEventListener('mouseleave', startAutoSlide);

            // Initialize carousel
            showSlide(0);
            startAutoSlide();
        });
    </script>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\StudEats\resources\views/welcome.blade.php ENDPATH**/ ?>