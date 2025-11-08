@extends('layouts.app')

@section('title', 'Loading Button Examples - FlyonUI')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">FlyonUI Loading Buttons</h1>
        <p class="mt-2 text-gray-600">Interactive loading button components with various styles and animations</p>
    </div>

    <!-- Original FlyonUI Examples -->
    <div class="bg-white shadow rounded-lg p-6 mb-8">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Original FlyonUI Examples</h2>
        <div class="flex flex-wrap gap-4 items-center">
            <button class="btn btn-primary btn-square btn-disabled" aria-label="Loading Button">
                <span class="loading loading-spinner loading-sm"></span>
            </button>
            <button class="btn btn-primary btn-disabled">
                <span class="loading loading-spinner loading-sm"></span>
                <span>Loading...</span>
            </button>
            <button class="btn btn-success btn-square btn-disabled" aria-label="Loading Button">
                <span class="loading loading-ring loading-sm"></span>
            </button>
            <button class="btn btn-success btn-disabled">
                <span>Ping</span>
                <span class="loading loading-ring loading-sm"></span>
            </button>
        </div>
    </div>

    <!-- Component Examples - Primary Buttons -->
    <div class="bg-white shadow rounded-lg p-6 mb-8">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Primary Loading Buttons</h2>
        
        <div class="space-y-6">
            <!-- Spinner Examples -->
            <div>
                <h3 class="text-sm font-medium text-gray-700 mb-3">Spinner Animation</h3>
                <div class="flex flex-wrap gap-4 items-center">
                    <x-loading-button variant="primary" :loading="true" square aria-label="Loading" />
                    <x-loading-button variant="primary" :loading="true" loadingText="Loading..." />
                    <x-loading-button variant="primary" :loading="true" size="sm" loadingText="Small" />
                    <x-loading-button variant="primary" :loading="true" size="lg" loadingText="Large" />
                </div>
            </div>

            <!-- Ring Examples -->
            <div>
                <h3 class="text-sm font-medium text-gray-700 mb-3">Ring Animation</h3>
                <div class="flex flex-wrap gap-4 items-center">
                    <x-loading-button variant="primary" :loading="true" loadingType="ring" square aria-label="Loading" />
                    <x-loading-button variant="primary" :loading="true" loadingType="ring" loadingText="Processing..." />
                    <x-loading-button variant="primary" :loading="true" loadingType="ring" loadingText="Please wait" iconPosition="right" />
                </div>
            </div>

            <!-- Dots Examples -->
            <div>
                <h3 class="text-sm font-medium text-gray-700 mb-3">Dots Animation</h3>
                <div class="flex flex-wrap gap-4 items-center">
                    <x-loading-button variant="primary" :loading="true" loadingType="dots" square aria-label="Loading" />
                    <x-loading-button variant="primary" :loading="true" loadingType="dots" loadingText="Saving..." />
                </div>
            </div>
        </div>
    </div>

    <!-- Component Examples - Success Buttons -->
    <div class="bg-white shadow rounded-lg p-6 mb-8">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Success Loading Buttons</h2>
        <div class="flex flex-wrap gap-4 items-center">
            <x-loading-button variant="success" :loading="true" square aria-label="Loading" />
            <x-loading-button variant="success" :loading="true" loadingText="Submitting..." />
            <x-loading-button variant="success" :loading="true" loadingType="ring" loadingText="Creating..." />
            <x-loading-button variant="success" :loading="true" loadingType="dots" loadingText="Uploading..." iconPosition="right" />
        </div>
    </div>

    <!-- Component Examples - Other Variants -->
    <div class="bg-white shadow rounded-lg p-6 mb-8">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Other Variants</h2>
        <div class="flex flex-wrap gap-4 items-center">
            <x-loading-button variant="error" :loading="true" loadingText="Deleting..." />
            <x-loading-button variant="warning" :loading="true" loadingText="Warning..." />
            <x-loading-button variant="info" :loading="true" loadingText="Info..." />
            <x-loading-button variant="secondary" :loading="true" loadingText="Secondary..." />
        </div>
    </div>

    <!-- Interactive Toggle Example -->
    <div class="bg-white shadow rounded-lg p-6 mb-8">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Interactive Example</h2>
        <div class="flex flex-wrap gap-4 items-center">
            <button 
                id="toggleBtn" 
                class="btn btn-primary"
                onclick="simulateLoading()"
            >
                Click to Simulate Loading
            </button>
            
            <button 
                id="loadingBtn" 
                class="btn btn-success hidden"
                disabled
            >
                <span class="loading loading-spinner loading-sm"></span>
                <span>Processing...</span>
            </button>
        </div>
        <p class="mt-4 text-sm text-gray-600">Click the button to see the loading state in action (simulates 3 seconds)</p>
    </div>

    <!-- All Animation Types -->
    <div class="bg-white shadow rounded-lg p-6 mb-8">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">All Animation Types</h2>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            <x-loading-button variant="primary" :loading="true" loadingType="spinner" loadingText="Spinner" />
            <x-loading-button variant="primary" :loading="true" loadingType="ring" loadingText="Ring" />
            <x-loading-button variant="primary" :loading="true" loadingType="dots" loadingText="Dots" />
            <x-loading-button variant="primary" :loading="true" loadingType="ball" loadingText="Ball" />
            <x-loading-button variant="primary" :loading="true" loadingType="bars" loadingText="Bars" />
            <x-loading-button variant="primary" :loading="true" loadingType="infinity" loadingText="Infinity" />
        </div>
    </div>

    <!-- Code Examples -->
    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Usage Examples</h2>
        <div class="space-y-4">
            <div>
                <h3 class="text-sm font-medium text-gray-700 mb-2">Basic Loading Button</h3>
                <pre class="bg-gray-900 text-gray-100 p-4 rounded-lg overflow-x-auto"><code>&lt;x-loading-button variant="primary" :loading="true" loadingText="Loading..." /&gt;</code></pre>
            </div>
            
            <div>
                <h3 class="text-sm font-medium text-gray-700 mb-2">Square Button with Ring Animation</h3>
                <pre class="bg-gray-900 text-gray-100 p-4 rounded-lg overflow-x-auto"><code>&lt;x-loading-button variant="success" :loading="true" loadingType="ring" square aria-label="Loading" /&gt;</code></pre>
            </div>
            
            <div>
                <h3 class="text-sm font-medium text-gray-700 mb-2">Dynamic Loading State</h3>
                <pre class="bg-gray-900 text-gray-100 p-4 rounded-lg overflow-x-auto"><code>&lt;x-loading-button 
    variant="primary" 
    :loading="$isSubmitting" 
    loadingText="Submitting..."
    type="submit"
&gt;
    Submit Form
&lt;/x-loading-button&gt;</code></pre>
            </div>

            <div>
                <h3 class="text-sm font-medium text-gray-700 mb-2">All Available Props</h3>
                <pre class="bg-gray-900 text-gray-100 p-4 rounded-lg overflow-x-auto text-xs"><code>variant      : primary, success, error, warning, info, secondary
size         : xs, sm, md, lg
loading      : true, false
loadingText  : "Custom text..."
loadingType  : spinner, ring, dots, ball, bars, infinity
square       : true, false
disabled     : true, false
iconPosition : left, right
type         : button, submit, reset</code></pre>
            </div>
        </div>
    </div>
</div>

<script>
function simulateLoading() {
    const toggleBtn = document.getElementById('toggleBtn');
    const loadingBtn = document.getElementById('loadingBtn');
    
    toggleBtn.classList.add('hidden');
    loadingBtn.classList.remove('hidden');
    
    setTimeout(() => {
        loadingBtn.classList.add('hidden');
        toggleBtn.classList.remove('hidden');
    }, 3000);
}
</script>
@endsection
