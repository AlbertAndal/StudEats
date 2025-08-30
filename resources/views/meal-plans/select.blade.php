@extends('layouts.app')

@section('title', 'Select Meal Type')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" data-selected-date="{{ $selectedDate->format('Y-m-d') }}">
    <!-- Header -->
    <div class="text-center mb-12">
        <div class="flex items-center justify-center mb-4">
            <div class="flex items-center justify-center w-16 h-16 bg-green-100 rounded-full">
                <x-icon name="calendar-days" class="w-8 h-8 text-green-600" />
            </div>
        </div>
        <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">Choose Your Meal Type</h1>
        <p class="text-lg text-gray-600 mb-2">Select which type of meal you'd like to plan for</p>
        <div class="inline-flex items-center px-4 py-2 bg-blue-50 text-blue-700 rounded-full text-sm font-medium">
            <x-icon name="calendar-days" class="w-4 h-4 mr-2" />
            {{ $selectedDate->format('l, F j, Y') }}
        </div>
        <!-- Primary Action Buttons -->
        <div class="mt-10 flex flex-col sm:flex-row justify-center gap-4">
            <a href="{{ route('meal-plans.weekly') }}" class="inline-flex items-center justify-center px-5 py-2.5 border border-gray-300 text-sm font-medium rounded-md bg-white text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                Weekly View
            </a>
            <a href="{{ route('meal-plans.select', ['date' => $selectedDate->format('Y-m-d')]) }}" class="inline-flex items-center justify-center px-5 py-2.5 border border-green-300 text-sm font-medium rounded-md bg-green-50 text-green-700 hover:bg-green-100 transition-colors duration-200">
                Select Meal Type
            </a>
            <a href="{{ route('meal-plans.create', ['date' => $selectedDate->format('Y-m-d')]) }}" class="inline-flex items-center justify-center px-5 py-2.5 border border-transparent text-sm font-medium rounded-md bg-green-600 text-white hover:bg-green-700 transition-colors duration-200">
                Add Meal
            </a>
        </div>
    </div>

    <!-- Meal Type Selection Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
        @php
            $mealTypes = [
                'breakfast' => [
                    'icon' => 'sun',
                    'color' => 'yellow',
                    'title' => 'Breakfast',
                    'description' => 'Start your day with energy',
                    'time' => '6:00 AM - 10:00 AM',
                    'suggestions' => ['Oatmeal', 'Eggs', 'Toast', 'Fruit', 'Coffee'],
                ],
                'lunch' => [
                    'icon' => 'fire',
                    'color' => 'orange',
                    'title' => 'Lunch', 
                    'description' => 'Fuel your afternoon activities',
                    'time' => '11:00 AM - 2:00 PM',
                    'suggestions' => ['Salads', 'Sandwiches', 'Rice Meals', 'Soup', 'Pasta'],
                ],
                'dinner' => [
                    'icon' => 'moon',
                    'color' => 'blue',
                    'title' => 'Dinner',
                    'description' => 'End your day with satisfaction',
                    'time' => '5:00 PM - 9:00 PM',
                    'suggestions' => ['Protein dishes', 'Vegetables', 'Rice', 'Fish', 'Meat'],
                ],
                'snack' => [
                    'icon' => 'cake',
                    'color' => 'purple',
                    'title' => 'Snack',
                    'description' => 'Quick bites anytime you need',
                    'time' => 'Anytime',
                    'suggestions' => ['Fruits', 'Nuts', 'Yogurt', 'Crackers', 'Smoothies'],
                ]
            ];
        @endphp

        @foreach($mealTypes as $mealType => $config)
            @php
                $isPlanned = in_array($mealType, $existingMealPlans);
                $colorClasses = [
                    'yellow' => [
                        'bg' => 'bg-yellow-50',
                        'border' => 'border-yellow-200',
                        'icon' => 'text-yellow-600',
                        'hover' => 'hover:border-yellow-300 hover:shadow-yellow-100',
                        'button' => 'bg-yellow-600 hover:bg-yellow-700',
                        'planned' => 'bg-yellow-100 border-yellow-300',
                        'badge' => 'bg-yellow-100 text-yellow-800'
                    ],
                    'orange' => [
                        'bg' => 'bg-orange-50',
                        'border' => 'border-orange-200',
                        'icon' => 'text-orange-600',
                        'hover' => 'hover:border-orange-300 hover:shadow-orange-100',
                        'button' => 'bg-orange-600 hover:bg-orange-700',
                        'planned' => 'bg-orange-100 border-orange-300',
                        'badge' => 'bg-orange-100 text-orange-800'
                    ],
                    'blue' => [
                        'bg' => 'bg-blue-50',
                        'border' => 'border-blue-200',
                        'icon' => 'text-blue-600',
                        'hover' => 'hover:border-blue-300 hover:shadow-blue-100',
                        'button' => 'bg-blue-600 hover:bg-blue-700',
                        'planned' => 'bg-blue-100 border-blue-300',
                        'badge' => 'bg-blue-100 text-blue-800'
                    ],
                    'purple' => [
                        'bg' => 'bg-purple-50',
                        'border' => 'border-purple-200',
                        'icon' => 'text-purple-600',
                        'hover' => 'hover:border-purple-300 hover:shadow-purple-100',
                        'button' => 'bg-purple-600 hover:bg-purple-700',
                        'planned' => 'bg-purple-100 border-purple-300',
                        'badge' => 'bg-purple-100 text-purple-800'
                    ]
                ];
                $colors = $colorClasses[$config['color']];
            @endphp
            
            <div class="relative group">
                <!-- Already Planned Badge -->
                @if($isPlanned)
                    <div class="absolute -top-2 -right-2 z-10 flex items-center space-x-1 {{ $colors['badge'] }} px-2 py-1 rounded-full text-xs font-medium">
                        <x-icon name="check-circle" class="w-3 h-3" variant="solid" />
                        <span>Planned</span>
                    </div>
                @endif
                
                <!-- Meal Box -->
                <div class="bg-white border-2 {{ $isPlanned ? $colors['planned'] : $colors['border'] }} {{ $colors['bg'] }} rounded-xl p-6 transition-all duration-300 transform group-hover:-translate-y-2 {{ $colors['hover'] }} shadow-lg group-hover:shadow-xl">
                    <!-- Icon -->
                    <div class="flex items-center justify-center w-16 h-16 bg-white rounded-full shadow-sm mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                        <x-icon name="{{ $config['icon'] }}" class="w-8 h-8 {{ $colors['icon'] }}" />
                    </div>
                    
                    <!-- Content -->
                    <div class="text-center">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $config['title'] }}</h3>
                        <p class="text-gray-600 text-sm mb-4">{{ $config['description'] }}</p>
                        
                        <!-- Time -->
                        <div class="flex items-center justify-center text-gray-500 text-xs mb-4">
                            <x-icon name="clock" class="w-3 h-3 mr-1" />
                            {{ $config['time'] }}
                        </div>
                        
                        <!-- Available Count -->
                        <div class="flex items-center justify-center text-gray-500 text-xs mb-6">
                            <x-icon name="rectangle-stack" class="w-3 h-3 mr-1" />
                            {{ $mealCounts[$mealType] }} meals available
                        </div>
                        
                        <!-- Suggestions -->
                        <div class="mb-6">
                            <p class="text-xs text-gray-500 mb-2">Popular choices:</p>
                            <div class="flex flex-wrap justify-center gap-1">
                                @foreach(array_slice($config['suggestions'], 0, 3) as $suggestion)
                                    <span class="inline-block px-2 py-1 bg-white/50 text-gray-600 text-xs rounded-full">{{ $suggestion }}</span>
                                @endforeach
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="space-y-2">
                            @if($isPlanned)
                                <a href="{{ route('meal-plans.index', ['date' => $selectedDate->format('Y-m-d')]) }}" 
                                   class="w-full inline-flex items-center justify-center px-4 py-3 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
                                    <x-icon name="eye" class="w-4 h-4 mr-2" />
                                    View Planned Meal
                                </a>
                                <a href="{{ route('meal-plans.create', ['date' => $selectedDate->format('Y-m-d'), 'meal_type' => $mealType]) }}" 
                                   class="w-full inline-flex items-center justify-center px-4 py-3 text-sm font-medium text-gray-600 hover:text-gray-800 transition-colors duration-200">
                                    <x-icon name="pencil" class="w-4 h-4 mr-2" />
                                    Change Meal
                                </a>
                            @else
                                <a href="{{ route('meal-plans.create', ['date' => $selectedDate->format('Y-m-d'), 'meal_type' => $mealType]) }}" 
                                   class="w-full inline-flex items-center justify-center px-4 py-3 border border-transparent text-sm font-medium rounded-lg text-white {{ $colors['button'] }} focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-{{ $config['color'] }}-500 transform transition-all duration-200 hover:scale-105">
                                    <x-icon name="plus" class="w-4 h-4 mr-2" />
                                    Plan {{ $config['title'] }}
                                </a>
                                <button data-meal-type="{{ $mealType }}" data-meal-title="{{ $config['title'] }}" data-meal-description="{{ $config['description'] }}" onclick="showMealPreview(this)" 
                                        class="w-full text-sm font-medium text-gray-600 hover:text-gray-800 transition-colors duration-200">
                                    <x-icon name="magnifying-glass" class="w-4 h-4 mr-2 inline" />
                                    Preview Options
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Quick Navigation -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div class="mb-4 sm:mb-0">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <x-icon name="calendar-days" class="w-5 h-5 mr-2 text-gray-500" />
                    Quick Actions
                </h3>
                <p class="text-sm text-gray-600 mt-1">Manage your meal planning efficiently</p>
            </div>
            <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-4">
                <a href="{{ route('meal-plans.index') }}" 
                   class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
                    <x-icon name="calendar-days" class="w-4 h-4 mr-2" />
                    View All Plans
                </a>
                <a href="{{ route('meal-plans.weekly') }}" 
                   class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
                    <x-icon name="calendar-days" class="w-4 h-4 mr-2" />
                    Weekly View
                </a>
                <a href="{{ route('recipes.index') }}" 
                   class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 transition-colors duration-200">
                    <x-icon name="book-open" class="w-4 h-4 mr-2" />
                    Browse Recipes
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Meal Preview Modal -->
<div id="mealPreviewModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" onclick="closeMealPreview()"></div>
        
        <!-- Modal panel -->
        <div class="inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900" id="previewTitle"></h3>
                <button onclick="closeMealPreview()" class="text-gray-400 hover:text-gray-600">
                    <x-icon name="x-mark" class="w-6 h-6" />
                </button>
            </div>
            
            <div id="previewContent" class="text-sm text-gray-600 mb-6"></div>
            
            <div class="flex space-x-3">
                <button onclick="closeMealPreview()" 
                        class="flex-1 px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50">
                    Close
                </button>
                <a id="previewPlanButton" href="#" 
                   class="flex-1 px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 text-center">
                    Plan This Meal
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function showMealPreview(button) {
    const mealType = button.getAttribute('data-meal-type');
    const title = button.getAttribute('data-meal-title');
    const description = button.getAttribute('data-meal-description');
    
    const modal = document.getElementById('mealPreviewModal');
    const previewTitle = document.getElementById('previewTitle');
    const previewContent = document.getElementById('previewContent');
    const previewPlanButton = document.getElementById('previewPlanButton');
    
    previewTitle.textContent = title + ' Options';
    previewContent.innerHTML = `
        <p class="mb-4">${description}</p>
        <div class="bg-gray-50 rounded-lg p-4">
            <h4 class="font-medium text-gray-900 mb-2">What you can expect:</h4>
            <ul class="text-sm text-gray-600 space-y-1">
                <li>• Nutritionally balanced meals</li>
                <li>• Within your budget preferences</li>
                <li>• Variety of cuisines and options</li>
                <li>• Detailed recipes and instructions</li>
            </ul>
        </div>
    `;
    
    const currentDate = document.querySelector('[data-selected-date]').getAttribute('data-selected-date');
    previewPlanButton.href = `/meal-plans/create?date=${currentDate}&meal_type=${mealType}`;
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeMealPreview() {
    const modal = document.getElementById('mealPreviewModal');
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeMealPreview();
    }
});

// Add entrance animation
document.addEventListener('DOMContentLoaded', function() {
    const boxes = document.querySelectorAll('.group');
    boxes.forEach((box, index) => {
        box.style.opacity = '0';
        box.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            box.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            box.style.opacity = '1';
            box.style.transform = 'translateY(0)';
        }, index * 100);
    });
});
</script>

@endsection