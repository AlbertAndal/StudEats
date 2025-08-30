<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>StudEats - Smart Meal Planning for Students</title>
    
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
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
                            <span class="text-2xl font-bold text-green-600">🍽️ StudEats</span>
                        </div>
                    </div>
                    <div class="hidden md:flex items-center space-x-6">
                        <a href="#features" class="text-gray-700 hover:text-green-600 text-sm font-medium">Features</a>
                        <a href="#how-it-works" class="text-gray-700 hover:text-green-600 text-sm font-medium">How It Works</a>
                        <a href="#sample-meals" class="text-gray-700 hover:text-green-600 text-sm font-medium">Meals</a>
                        <a href="#faq" class="text-gray-700 hover:text-green-600 text-sm font-medium">FAQ</a>
                    </div>
                    <div class="flex items-center space-x-3">
                        <a href="<?php echo e(route('login')); ?>" class="text-gray-700 hover:text-green-600 px-3 py-2 text-sm font-medium">
                            Sign In
                        </a>
                        <a href="<?php echo e(route('register')); ?>" class="bg-green-600 hover:bg-green-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-green-600 text-white px-4 py-2 rounded-md text-sm font-medium">
                            Get Started
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <div class="relative overflow-hidden">
            <div class="max-w-7xl mx-auto">
                <div class="relative z-10 pb-8 sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32">
                    <main id="main" class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-28">
                        <div class="sm:text-center lg:text-left">
                            <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
                                <span class="block xl:inline">Meal Planning</span>
                                <span class="block text-green-600 xl:inline">for Filipino Students</span>
                            </h1>
                            <p class="mt-3 text-base text-gray-600 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                                Plan healthy, affordable meals that fit your student budget and busy schedule. From tapsilog to adobo, eat well without the guilt.
                            </p>
                            <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start">
                                <div class="rounded-md shadow">
                                    <a href="<?php echo e(route('register')); ?>" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-green-600 md:py-4 md:text-lg md:px-10">
                                        Start Planning for Free
                                    </a>
                                </div>
                                <div class="mt-3 sm:mt-0 sm:ml-3">
                                    <a href="#sample-meals" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-green-700 bg-green-100 hover:bg-green-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-green-600 md:py-4 md:text-lg md:px-10">
                                        Browse Sample Meals
                                    </a>
                                </div>
                            </div>
                        </div>
                    </main>
                </div>
            </div>
        </div>

        <!-- Problem Statement Section -->
        <div class="py-16 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                        Tired of Expensive, Unhealthy Food Choices?
                    </h2>
                    <p class="mt-4 text-lg text-gray-600">
                        You're not alone. Filipino students face the same daily struggles with food choices.
                    </p>
                </div>

                <div class="mt-12 grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="text-center">
                        <div class="flex items-center justify-center h-16 w-16 rounded-md bg-green-500 text-white mx-auto mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900">₱500+ daily on fastfood and delivery</h3>
                        <p class="mt-2 text-sm text-gray-500">That's ₱15,000+ per month just on food!</p>
                    </div>

                    <div class="text-center">
                        <div class="flex items-center justify-center h-16 w-16 rounded-md bg-orange-500 text-white mx-auto mb-4">
                            <span class="text-2xl">🍜</span>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900">Same instant meals every day</h3>
                        <p class="mt-2 text-sm text-gray-500">Pancit canton for breakfast, lunch, and dinner again?</p>
                    </div>

                    <div class="text-center">
                        <div class="flex items-center justify-center h-16 w-16 rounded-md bg-yellow-500 text-white mx-auto mb-4">
                            <span class="text-2xl">⏰</span>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900">No time to plan healthy options</h3>
                        <p class="mt-2 text-sm text-gray-500">Between classes and deadlines, who has time to meal prep?</p>
                    </div>

                    <div class="text-center">
                        <div class="flex items-center justify-center h-16 w-16 rounded-md bg-blue-500 text-white mx-auto mb-4">
                            <span class="text-2xl">👩‍🍳</span>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900">Don't know how to cook Filipino dishes</h3>
                        <p class="mt-2 text-sm text-gray-500">Miss nanay's cooking but don't know where to start?</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <div id="features" class="py-12 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="lg:text-center">
                    <h2 class="text-base text-green-600 font-semibold tracking-wide uppercase">Solution</h2>
                    <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                        Meet StudEats - Your Personal Filipino Meal Planner
                    </p>
                    <p class="mt-4 max-w-2xl text-xl text-gray-500 lg:mx-auto">
                        Everything you need to eat well as a Filipino student, without the stress or overspending.
                    </p>
                </div>

                <div class="mt-10">
                    <div class="space-y-10 md:space-y-0 md:grid md:grid-cols-2 md:gap-x-8 md:gap-y-10">
                        <div class="relative">
                            <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-green-500 text-white">
                                <span class="text-2xl">💰</span>
                            </div>
                            <p class="ml-16 text-lg leading-6 font-medium text-gray-900">Budget-Friendly Planning</p>
                            <p class="mt-2 ml-16 text-base text-gray-500">
                                Keep meals under ₱350 per day with our cost calculator per meal. No more budget surprises.
                            </p>
                        </div>

                        <div class="relative">
                            <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-orange-500 text-white">
                                <span class="text-2xl">🇵🇭</span>
                            </div>
                            <p class="ml-16 text-lg leading-6 font-medium text-gray-900">Filipino-Focused Recipes</p>
                            <p class="mt-2 ml-16 text-base text-gray-500">
                                Adobo, Sinigang, Giniling variations using local ingredients you can find anywhere.
                            </p>
                        </div>

                        <div class="relative">
                            <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-blue-500 text-white">
                                <span class="text-2xl">📚</span>
                            </div>
                            <p class="ml-16 text-lg leading-6 font-medium text-gray-900">Student-Simple Instructions</p>
                            <p class="mt-2 ml-16 text-base text-gray-500">
                                Step-by-step cooking guides with 15-30 minute meal prep times. Perfect for busy students.
                            </p>
                        </div>

                        <div class="relative">
                            <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-green-500 text-white">
                                <span class="text-2xl">📅</span>
                            </div>
                            <p class="ml-16 text-lg leading-6 font-medium text-gray-900">Meal Calendar</p>
                            <p class="mt-2 ml-16 text-base text-gray-500">
                                Weekly planning made easy. Never repeat meals unintentionally or run out of ideas.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- How It Works Section -->
        <div id="how-it-works" class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                        How StudEats Works
                    </h2>
                    <p class="mt-4 text-lg text-gray-600">
                        Get started in just 3 simple steps
                    </p>
                </div>

                <div class="mt-16 grid grid-cols-1 gap-8 md:grid-cols-3">
                    <div class="text-center">
                        <div class="flex items-center justify-center h-16 w-16 rounded-full bg-green-500 text-white mx-auto mb-6">
                            <span class="text-2xl font-bold">1</span>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Set Your Profile</h3>
                        <p class="text-gray-600">
                            Tell us your budget, dietary preferences, and cooking skill level. We'll customize everything for you.
                        </p>
                    </div>

                    <div class="text-center">
                        <div class="flex items-center justify-center h-16 w-16 rounded-full bg-orange-500 text-white mx-auto mb-6">
                            <span class="text-2xl font-bold">2</span>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Get Meal Suggestions</h3>
                        <p class="text-gray-600">
                            Receive personalized Filipino meal recommendations that fit your budget and schedule.
                        </p>
                    </div>

                    <div class="text-center">
                        <div class="flex items-center justify-center h-16 w-16 rounded-full bg-green-500 text-white mx-auto mb-6">
                            <span class="text-2xl font-bold">3</span>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Follow & Cook</h3>
                        <p class="text-gray-600">
                            Follow our simple step-by-step instructions using ingredients available at your local market.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Testimonials Carousel Section -->
        <section class="bg-white dark:bg-gray-900">
            <div class="max-w-screen-xl px-4 py-8 mx-auto text-center lg:py-16 lg:px-6">
                <div class="mx-auto max-w-screen-sm text-center lg:mb-16 mb-8">
                    <h2 class="mb-4 text-3xl lg:text-4xl tracking-tight font-extrabold text-gray-900 dark:text-white">What Students Say</h2>
                    <p class="font-light text-gray-500 sm:text-xl dark:text-gray-400">Discover how StudEats has transformed the eating habits of Filipino students across the Philippines</p>
                </div>

                <div id="testimonial-carousel" class="relative" data-carousel="slide">
                    <!-- Carousel wrapper -->
                    <div class="relative overflow-hidden rounded-lg h-96">
                        <!-- Item 1 -->
                        <div class="hidden duration-700 ease-in-out" data-carousel-item="active">
                            <figure class="max-w-screen-md mx-auto">
                                <svg class="h-12 mx-auto mb-3 text-gray-400 dark:text-gray-600" viewBox="0 0 24 27" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M14.017 18L14.017 10.609C14.017 4.905 17.748 1.039 23 0L23.995 2.151C21.563 3.068 20 5.789 20 8H24V18H14.017ZM0 18V10.609C0 4.905 3.748 1.038 9 0L9.996 2.151C7.563 3.068 6 5.789 6 8H9.983L9.983 18L0 18Z" fill="currentColor"/>
                                </svg> 
                                <blockquote>
                                    <p class="text-2xl font-medium text-gray-900 dark:text-white">"StudEats completely transformed how I approach meal planning as a student. No more expensive food delivery or unhealthy instant meals - I'm finally eating proper Filipino food that fits my budget and schedule!"</p>
                                </blockquote>
                                <figcaption class="flex items-center justify-center mt-6 space-x-3">
                                    <img class="w-10 h-10 rounded-full" src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/avatars/karen-nelson.png" alt="profile picture">
                                    <div class="flex items-center divide-x-2 divide-gray-500 dark:divide-gray-700">
                                        <div class="pr-3 font-medium text-gray-900 dark:text-white">Maria Santos</div>
                                        <div class="pl-3 text-sm font-light text-gray-500 dark:text-gray-400">BS Psychology Student at UP Diliman</div>
                                    </div>
                                </figcaption>
                            </figure>
                        </div>
                        <!-- Item 2 -->
                        <div class="hidden duration-700 ease-in-out" data-carousel-item>
                            <figure class="max-w-screen-md mx-auto">
                                <svg class="h-12 mx-auto mb-3 text-gray-400 dark:text-gray-600" viewBox="0 0 24 27" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M14.017 18L14.017 10.609C14.017 4.905 17.748 1.039 23 0L23.995 2.151C21.563 3.068 20 5.789 20 8H24V18H14.017ZM0 18V10.609C0 4.905 3.748 1.038 9 0L9.996 2.151C7.563 3.068 6 5.789 6 8H9.983L9.983 18L0 18Z" fill="currentColor"/>
                                </svg> 
                                <blockquote>
                                    <p class="text-2xl font-medium text-gray-900 dark:text-white">"Finally learned to cook adobo without calling my mom every 5 minutes! The step-by-step guides are so easy to follow, and I've saved over ₱2,000 this month compared to food delivery."</p>
                                </blockquote>
                                <figcaption class="flex items-center justify-center mt-6 space-x-3">
                                    <img class="w-10 h-10 rounded-full" src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/avatars/roberta-casas.png" alt="profile picture">
                                    <div class="flex items-center divide-x-2 divide-gray-500 dark:divide-gray-700">
                                        <div class="pr-3 font-medium text-gray-900 dark:text-white">Josh Rivera</div>
                                        <div class="pl-3 text-sm font-light text-gray-500 dark:text-gray-400">BS Management Student at Ateneo</div>
                                    </div>
                                </figcaption>
                            </figure>
                        </div>
                        <!-- Item 3 -->
                        <div class="hidden duration-700 ease-in-out" data-carousel-item>
                            <figure class="max-w-screen-md mx-auto">
                                <svg class="h-12 mx-auto mb-3 text-gray-400 dark:text-gray-600" viewBox="0 0 24 27" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M14.017 18L14.017 10.609C14.017 4.905 17.748 1.039 23 0L23.995 2.151C21.563 3.068 20 5.789 20 8H24V18H14.017ZM0 18V10.609C0 4.905 3.748 1.038 9 0L9.996 2.151C7.563 3.068 6 5.789 6 8H9.983L9.983 18L0 18Z" fill="currentColor"/>
                                </svg> 
                                <blockquote>
                                    <p class="text-2xl font-medium text-gray-900 dark:text-white">"My grades improved dramatically when I stopped skipping meals! Having a proper meal plan made all the difference. StudEats helped me balance nutrition, budget, and my hectic schedule."</p>
                                </blockquote>
                                <figcaption class="flex items-center justify-center mt-6 space-x-3">
                                    <img class="w-10 h-10 rounded-full" src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/avatars/jese-leos.png" alt="profile picture">
                                    <div class="flex items-center divide-x-2 divide-gray-500 dark:divide-gray-700">
                                        <div class="pr-3 font-medium text-gray-900 dark:text-white">Alex Cruz</div>
                                        <div class="pl-3 text-sm font-light text-gray-500 dark:text-gray-400">BS Engineering Student at UST</div>
                                    </div>
                                </figcaption>
                            </figure>
                        </div>
                        <!-- Item 4 -->
                        <div class="hidden duration-700 ease-in-out" data-carousel-item>
                            <figure class="max-w-screen-md mx-auto">
                                <svg class="h-12 mx-auto mb-3 text-gray-400 dark:text-gray-600" viewBox="0 0 24 27" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M14.017 18L14.017 10.609C14.017 4.905 17.748 1.039 23 0L23.995 2.151C21.563 3.068 20 5.789 20 8H24V18H14.017ZM0 18V10.609C0 4.905 3.748 1.038 9 0L9.996 2.151C7.563 3.068 6 5.789 6 8H9.983L9.983 18L0 18Z" fill="currentColor"/>
                                </svg> 
                                <blockquote>
                                    <p class="text-2xl font-medium text-gray-900 dark:text-white">"As a working student, StudEats has been a lifesaver! I can prep healthy Filipino meals in advance and stay within my ₱250 daily food budget. My parents are so proud of my independence."</p>
                                </blockquote>
                                <figcaption class="flex items-center justify-center mt-6 space-x-3">
                                    <img class="w-10 h-10 rounded-full" src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/avatars/sofia-mcguire.png" alt="profile picture">
                                    <div class="flex items-center divide-x-2 divide-gray-500 dark:divide-gray-700">
                                        <div class="pr-3 font-medium text-gray-900 dark:text-white">Sophia Dela Cruz</div>
                                        <div class="pl-3 text-sm font-light text-gray-500 dark:text-gray-400">BS Nursing Student at FEU</div>
                                    </div>
                                </figcaption>
                            </figure>
                        </div>
                    </div>
                    
                    <!-- Slider indicators -->
                    <div class="absolute z-30 flex space-x-3 -translate-x-1/2 bottom-5 left-1/2">
                        <button type="button" class="w-3 h-3 rounded-full bg-white/50 hover:bg-white" aria-current="true" aria-label="Slide 1" data-carousel-slide-to="0"></button>
                        <button type="button" class="w-3 h-3 rounded-full bg-white/50 hover:bg-white" aria-current="false" aria-label="Slide 2" data-carousel-slide-to="1"></button>
                        <button type="button" class="w-3 h-3 rounded-full bg-white/50 hover:bg-white" aria-current="false" aria-label="Slide 3" data-carousel-slide-to="2"></button>
                        <button type="button" class="w-3 h-3 rounded-full bg-white/50 hover:bg-white" aria-current="false" aria-label="Slide 4" data-carousel-slide-to="3"></button>
                    </div>
                    
                    <!-- Slider controls -->
                    <button type="button" class="absolute top-0 left-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" data-carousel-prev>
                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                            <svg class="w-4 h-4 text-white dark:text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 1 1 5l4 4"/>
                            </svg>
                            <span class="sr-only">Previous</span>
                        </span>
                    </button>
                    <button type="button" class="absolute top-0 right-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" data-carousel-next>
                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                            <svg class="w-4 h-4 text-white dark:text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
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
                                    Absolutely not! StudEats is designed for complete beginners. Our step-by-step instructions include photos, cooking tips, and time estimates. Start with simple 15-minute meals and gradually build your skills as you become more confident in the kitchen.
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
                                    Students typically save ₱8,000-12,000 monthly compared to food delivery and dining out. Our meal plans average ₱200-350 per day versus ₱500+ for fast food. That's potentially ₱150,000+ in savings per year!
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
                                    Yes! Our recipes are based on traditional Filipino dishes but simplified for student cooking. From classic adobo to regional specialties, all recipes use authentic ingredients and techniques available at local markets.
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
                                    All ingredients are available at local markets, SM, Puregold, or neighborhood stores. We avoid hard-to-find items and provide substitution suggestions for regional availability and dietary restrictions.
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
                                    Most recipes take 15-30 minutes total. We include prep time, cooking time, and difficulty level for each recipe. Perfect for busy students between classes or during study breaks!
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
                                    Yes! Basic meal planning, recipes, and budget tracking are completely free. Premium features like advanced nutrition tracking and custom meal plans are available for ₱99/month for students who want extra features.
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
                    <a href="#sample-meals" class="inline-flex items-center justify-center px-6 py-3 border-2 border-white text-base font-medium rounded-md text-white hover:bg-green-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-white transition-colors">
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
                            <span class="text-3xl font-bold text-white">🍽️ StudEats</span>
                        </div>
                        <p class="text-gray-400 text-base mb-6 max-w-md">
                            Smart meal planning for Filipino students. Eat healthy, save money, and focus on your studies with our budget-friendly recipes and meal plans.
                        </p>
                        <div class="flex space-x-4">
                            <a href="#" class="text-gray-400 hover:text-white transition-colors">
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                                </svg>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-white transition-colors">
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z"/>
                                </svg>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-white transition-colors">
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.219-.359-1.219c0-1.142.662-1.997 1.482-1.997.699 0 1.037.219 1.037 1.142 0 .697-.442 1.738-.442 2.434 0 .179.219.359.442.359.938 0 1.663-.997 1.663-2.434 0-1.277-.442-2.155-1.482-2.155-1.739 0-3.078 1.277-3.078 3.078 0 .619.219 1.062.442 1.482.105.219.105.359.105.539 0 .359-.219.539-.442.539-.539 0-.997-.359-.997-1.142 0-1.482 1.037-2.873 2.953-2.873 1.663 0 2.953 1.219 2.953 2.873 0 1.739-1.037 3.078-2.434 3.078-.539 0-1.037-.219-1.277-.539 0 0-.359 1.277-.442 1.597-.179.539-.539 1.277-.759 1.738C9.717 23.686 10.754 24 12.017 24c6.624 0 11.99-5.367 11.99-11.987C24.007 5.367 18.641.001 12.017.001z"/>
                                </svg>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-white transition-colors">
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12.548 7.675c-.678 0-1.221.543-1.221 1.221s.543 1.221 1.221 1.221 1.221-.543 1.221-1.221-.543-1.221-1.221-1.221zm3.842 0c-.678 0-1.221.543-1.221 1.221s.543 1.221 1.221 1.221 1.221-.543 1.221-1.221-.543-1.221-1.221-1.221zm-7.684 0c-.678 0-1.221.543-1.221 1.221s.543 1.221 1.221 1.221 1.221-.543 1.221-1.221-.543-1.221-1.221-1.221zm7.684 4.885c-.678 0-1.221.543-1.221 1.221s.543 1.221 1.221 1.221 1.221-.543 1.221-1.221-.543-1.221-1.221-1.221zm-3.842 0c-.678 0-1.221.543-1.221 1.221s.543 1.221 1.221 1.221 1.221-.543 1.221-1.221-.543-1.221-1.221-1.221zm-3.842 0c-.678 0-1.221.543-1.221 1.221s.543 1.221 1.221 1.221 1.221-.543 1.221-1.221-.543-1.221-1.221-1.221z"/>
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div class="col-span-1">
                        <h3 class="text-sm font-semibold text-white tracking-wider uppercase mb-4">Quick Links</h3>
                        <ul class="space-y-3">
                            <li><a href="#features" class="text-gray-400 hover:text-white transition-colors">Features</a></li>
                            <li><a href="#how-it-works" class="text-gray-400 hover:text-white transition-colors">How It Works</a></li>
                            <li><a href="#faq" class="text-gray-400 hover:text-white transition-colors">FAQ</a></li>
                            <li><a href="<?php echo e(route('register')); ?>" class="text-gray-400 hover:text-white transition-colors">Get Started</a></li>
                        </ul>
                    </div>

                    <!-- Support -->
                    <div class="col-span-1">
                        <h3 class="text-sm font-semibold text-white tracking-wider uppercase mb-4">Support</h3>
                        <ul class="space-y-3">
                            <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Help Center</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Contact Us</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Privacy Policy</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Terms of Service</a></li>
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
                            <form class="flex max-w-md">
                                <input type="email" placeholder="Enter your email" class="flex-1 px-4 py-2 bg-gray-800 border border-gray-700 rounded-l-md text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
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
                        &copy; <?php echo e(now()->year); ?> StudEats. All rights reserved. Made with ❤️ for Filipino students.
                    </p>
                </div>
            </div>
        </footer>
    </div>
    <?php endif; ?>

    <!-- Accordion JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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