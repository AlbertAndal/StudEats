{{-- 
    Minimalist Recipe Ingredients Showcase Component
    
    This component demonstrates the new minimalist design system for recipe ingredients.
    It showcases clean typography, proper spacing, visual hierarchy, and elegant presentation.
    
    Design Principles Applied:
    - Clean typography with proper font weights and sizes
    - Ample white space for better readability
    - Clear visual hierarchy with structured layout
    - Consistent spacing using Tailwind's space utilities
    - Subtle interactions with hover states
    - Color-coded elements for better UX
    - Accessibility-friendly design
--}}

<div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="mb-12">
        <h1 class="text-3xl font-bold text-gray-900 mb-4">Minimalist Ingredients Layout Showcase</h1>
        <p class="text-lg text-gray-600">A demonstration of clean, modern UI/UX design principles for recipe ingredients.</p>
    </div>

    <!-- Example 1: Recipe View Style -->
    <div class="mb-16">
        <h2 class="text-2xl font-semibold text-gray-900 mb-6">Recipe View Style</h2>
        <div class="bg-white shadow-sm rounded-2xl border border-gray-100 overflow-hidden">
            <!-- Header Section -->
            <div class="px-8 py-6 bg-gradient-to-r from-gray-50 to-white border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 tracking-tight">Ingredients</h3>
                        <p class="text-sm text-gray-500 mt-1 font-medium">Serves 4 people</p>
                    </div>
                    <div class="w-10 h-10 bg-green-50 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3-6h.008v.008H15.75V12zm0 3h.008v.008H15.75V15zm0 3h.008v.008H15.75V18zm-12-3h3.75m0 0h3.75m0 0v3.75M5.25 15V9.75M5.25 15a2.25 2.25 0 01-2.25-2.25V9.75A2.25 2.25 0 015.25 7.5h3.75"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Ingredients List -->
            <div class="px-8 py-6">
                <div class="space-y-4">
                    @php
                        $sampleIngredients = [
                            ['name' => 'Chicken breast, boneless and skinless', 'amount' => '500', 'unit' => 'g'],
                            ['name' => 'Fresh garlic cloves', 'amount' => '3', 'unit' => 'pieces'],
                            ['name' => 'Extra virgin olive oil', 'amount' => '2', 'unit' => 'tbsp'],
                            ['name' => 'Sea salt', 'amount' => '1', 'unit' => 'tsp'],
                            ['name' => 'Black pepper, freshly ground', 'amount' => '½', 'unit' => 'tsp'],
                            ['name' => 'Fresh rosemary sprigs', 'amount' => '2', 'unit' => 'pieces']
                        ];
                    @endphp
                    
                    @foreach($sampleIngredients as $index => $ingredient)
                        <div class="group flex items-center space-x-4 p-4 rounded-xl hover:bg-gray-50 transition-all duration-200">
                            <!-- Index Number -->
                            <div class="flex-shrink-0 w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center group-hover:bg-green-100 transition-colors duration-200">
                                <span class="text-sm font-semibold text-gray-600 group-hover:text-green-700">{{ $index + 1 }}</span>
                            </div>
                            
                            <!-- Ingredient Details -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-baseline space-x-3">
                                    <h4 class="text-base font-medium text-gray-900 leading-tight">
                                        {{ $ingredient['name'] }}
                                    </h4>
                                    <div class="flex items-center space-x-1">
                                        <span class="text-lg font-semibold text-green-600">{{ $ingredient['amount'] }}</span>
                                        <span class="text-sm text-gray-500 font-medium uppercase tracking-wide">{{ $ingredient['unit'] }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Check Icon -->
                            <div class="flex-shrink-0 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center">
                                    <svg class="w-3.5 h-3.5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Example 2: Admin Grid Style -->
    <div class="mb-16">
        <h2 class="text-2xl font-semibold text-gray-900 mb-6">Admin Grid Style</h2>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <!-- Header Section -->
            <div class="px-8 py-6 bg-gradient-to-r from-blue-50 to-white border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900 tracking-tight">Recipe Ingredients</h2>
                        <p class="text-sm text-gray-500 mt-1 font-medium">6 ingredients total</p>
                    </div>
                    <div class="w-10 h-10 bg-blue-50 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3-6h.008v.008H15.75V12zm0 3h.008v.008H15.75V15zm0 3h.008v.008H15.75V18zm-12-3h3.75m0 0h3.75m0 0v3.75M5.25 15V9.75M5.25 15a2.25 2.25 0 01-2.25-2.25V9.75A2.25 2.25 0 015.25 7.5h3.75"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Ingredients Grid -->
            <div class="px-8 py-6">
                <!-- Column Headers -->
                <div class="grid grid-cols-12 gap-4 mb-6 pb-4 border-b border-gray-100">
                    <div class="col-span-7">
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Ingredient Name</span>
                    </div>
                    <div class="col-span-2 text-center">
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Quantity</span>
                    </div>
                    <div class="col-span-3 text-center">
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Unit</span>
                    </div>
                </div>
                
                <!-- Ingredient Rows -->
                <div class="space-y-3">
                    @foreach($sampleIngredients as $index => $ingredient)
                        <div class="group grid grid-cols-12 gap-4 items-center py-4 px-4 rounded-xl hover:bg-gray-50 transition-all duration-200">
                            <!-- Ingredient Name -->
                            <div class="col-span-7 flex items-center space-x-3">
                                <div class="flex-shrink-0 w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center group-hover:bg-blue-100 transition-colors duration-200">
                                    <span class="text-sm font-semibold text-gray-600 group-hover:text-blue-700">{{ $index + 1 }}</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <span class="text-base font-medium text-gray-900 leading-tight">{{ $ingredient['name'] }}</span>
                                </div>
                            </div>
                            
                            <!-- Quantity -->
                            <div class="col-span-2 text-center">
                                <span class="inline-flex items-center justify-center px-3 py-1 rounded-full text-sm font-semibold bg-green-50 text-green-700">
                                    {{ $ingredient['amount'] }}
                                </span>
                            </div>
                            
                            <!-- Unit -->
                            <div class="col-span-3 text-center">
                                <span class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700 uppercase tracking-wide">
                                    {{ $ingredient['unit'] }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Design System Guidelines -->
    <div class="bg-gray-50 rounded-2xl p-8">
        <h2 class="text-2xl font-semibold text-gray-900 mb-6">Design System Guidelines</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Typography -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Typography</h3>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li><strong>Headers:</strong> font-semibold, tracking-tight</li>
                    <li><strong>Ingredient names:</strong> font-medium, text-base</li>
                    <li><strong>Quantities:</strong> font-semibold, larger text</li>
                    <li><strong>Units:</strong> font-medium, uppercase, tracking-wide</li>
                    <li><strong>Descriptions:</strong> text-sm, text-gray-500</li>
                </ul>
            </div>
            
            <!-- Spacing & Layout -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Spacing & Layout</h3>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li><strong>Container padding:</strong> px-8 py-6</li>
                    <li><strong>Item spacing:</strong> space-y-4 for ingredients</li>
                    <li><strong>Border radius:</strong> rounded-2xl for containers</li>
                    <li><strong>Border radius:</strong> rounded-xl for items</li>
                    <li><strong>Grid gaps:</strong> gap-4 for consistent spacing</li>
                </ul>
            </div>
            
            <!-- Colors -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Color Palette</h3>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li><strong>Primary text:</strong> text-gray-900</li>
                    <li><strong>Secondary text:</strong> text-gray-500</li>
                    <li><strong>Accents:</strong> text-green-600, text-blue-600</li>
                    <li><strong>Backgrounds:</strong> bg-gray-50, bg-white</li>
                    <li><strong>Borders:</strong> border-gray-100</li>
                </ul>
            </div>
            
            <!-- Interactions -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Interactions</h3>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li><strong>Hover states:</strong> hover:bg-gray-50</li>
                    <li><strong>Transitions:</strong> transition-all duration-200</li>
                    <li><strong>Color changes:</strong> group-hover patterns</li>
                    <li><strong>Opacity effects:</strong> opacity-0 to opacity-100</li>
                </ul>
            </div>
        </div>
    </div>
</div>