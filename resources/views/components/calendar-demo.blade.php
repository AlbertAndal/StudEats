@extends('layouts.app')

@section('title', 'Calendar Component Demo')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-foreground mb-2">Calendar1 Component Demo</h1>
        <p class="text-muted-foreground">Demonstration of the Calendar1 component with various configurations</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Basic Usage -->
        <div class="bg-white p-6 rounded-lg border border-gray-200">
            <h2 class="text-xl font-semibold mb-4">Basic Usage</h2>
            <div class="space-y-4">
                <div>
                    <h3 class="text-sm font-medium text-gray-700 mb-2">Default (Primary)</h3>
                    <x-calendar1 />
                </div>
                
                <div>
                    <h3 class="text-sm font-medium text-gray-700 mb-2">With Content Slot</h3>
                    <x-calendar1 variant="secondary">
                        <span class="text-sm font-medium text-green-700">Schedule Meeting</span>
                    </x-calendar1>
                </div>
            </div>
        </div>

        <!-- Size Variations -->
        <div class="bg-white p-6 rounded-lg border border-gray-200">
            <h2 class="text-xl font-semibold mb-4">Size Variations</h2>
            <div class="space-y-4">
                <div>
                    <h3 class="text-sm font-medium text-gray-700 mb-2">Small</h3>
                    <x-calendar1 size="small" variant="primary" />
                </div>
                
                <div>
                    <h3 class="text-sm font-medium text-gray-700 mb-2">Default</h3>
                    <x-calendar1 size="default" variant="primary" />
                </div>
                
                <div>
                    <h3 class="text-sm font-medium text-gray-700 mb-2">Large</h3>
                    <x-calendar1 size="large" variant="primary" />
                </div>
            </div>
        </div>

        <!-- Color Variants -->
        <div class="bg-white p-6 rounded-lg border border-gray-200">
            <h2 class="text-xl font-semibold mb-4">Color Variants</h2>
            <div class="space-y-4">
                <div>
                    <h3 class="text-sm font-medium text-gray-700 mb-2">Primary (Blue)</h3>
                    <x-calendar1 variant="primary" />
                </div>
                
                <div>
                    <h3 class="text-sm font-medium text-gray-700 mb-2">Secondary (Green)</h3>
                    <x-calendar1 variant="secondary" />
                </div>
                
                <div>
                    <h3 class="text-sm font-medium text-gray-700 mb-2">Muted (Gray)</h3>
                    <x-calendar1 variant="muted" />
                </div>
            </div>
        </div>

        <!-- Icon Only Variations -->
        <div class="bg-white p-6 rounded-lg border border-gray-200">
            <h2 class="text-xl font-semibold mb-4">Icon Only (No Emoji)</h2>
            <div class="space-y-4">
                <div>
                    <h3 class="text-sm font-medium text-gray-700 mb-2">Primary - Icon Only</h3>
                    <x-calendar1 variant="primary" :show-emoji="false" />
                </div>
                
                <div>
                    <h3 class="text-sm font-medium text-gray-700 mb-2">Secondary - Icon Only</h3>
                    <x-calendar1 variant="secondary" :show-emoji="false" />
                </div>
                
                <div>
                    <h3 class="text-sm font-medium text-gray-700 mb-2">Large Muted - Icon Only</h3>
                    <x-calendar1 variant="muted" size="large" :show-emoji="false" />
                </div>
            </div>
        </div>

        <!-- Usage Examples -->
        <div class="bg-white p-6 rounded-lg border border-gray-200 lg:col-span-2">
            <h2 class="text-xl font-semibold mb-4">Real Usage Examples</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="text-center">
                    <x-calendar1 variant="secondary" size="large" class="mx-auto mb-3">
                        <div class="text-center">
                            <div class="font-semibold text-green-700">Today's Meals</div>
                            <div class="text-sm text-green-600">3 planned</div>
                        </div>
                    </x-calendar1>
                    <p class="text-sm text-gray-600">Meal Planning Card</p>
                </div>

                <div class="text-center">
                    <x-calendar1 variant="primary" :show-emoji="false" class="mx-auto mb-3">
                        <span class="font-medium text-blue-700">Schedule</span>
                    </x-calendar1>
                    <p class="text-sm text-gray-600">Clean Icon with Text</p>
                </div>

                <div class="text-center">
                    <x-calendar1 variant="muted" size="small" class="mx-auto mb-3" />
                    <p class="text-sm text-gray-600">Compact Version</p>
                </div>
            </div>
        </div>

        <!-- Code Examples -->
        <div class="bg-gray-50 p-6 rounded-lg border border-gray-200 lg:col-span-2">
            <h2 class="text-xl font-semibold mb-4">Usage Code Examples</h2>
            <div class="space-y-4 text-sm">
                <div>
                    <h3 class="font-medium text-gray-700 mb-2">Basic Calendar:</h3>
                    <code class="block bg-white p-3 rounded border text-gray-800">&lt;x-calendar1 /&gt;</code>
                </div>
                
                <div>
                    <h3 class="font-medium text-gray-700 mb-2">Large Green Calendar with Content:</h3>
                    <code class="block bg-white p-3 rounded border text-gray-800">&lt;x-calendar1 variant="secondary" size="large"&gt;<br>&nbsp;&nbsp;Meal Planning<br>&lt;/x-calendar1&gt;</code>
                </div>
                
                <div>
                    <h3 class="font-medium text-gray-700 mb-2">Icon Only (No Emoji):</h3>
                    <code class="block bg-white p-3 rounded border text-gray-800">&lt;x-calendar1 :show-emoji="false" variant="muted" /&gt;</code>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection