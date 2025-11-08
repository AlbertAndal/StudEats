<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FlyonUI Loading Spinners - StudEats</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-gray-900 mb-4">FlyonUI Loading Spinners</h1>
                <p class="text-lg text-gray-600">Comprehensive spinner component integration for StudEats</p>
                <div class="mt-4 flex items-center justify-center gap-4">
                    <a href="{{ url('/') }}" class="text-blue-600 hover:text-blue-700 font-medium">← Back to Home</a>
                    <a href="{{ url('/loading-buttons-demo') }}" class="text-green-600 hover:text-green-700 font-medium">View Loading Buttons →</a>
                </div>
            </div>

            <!-- Basic Sizes -->
            <div class="bg-white rounded-lg shadow-sm p-8 mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Basic Spinner Sizes</h2>
                <div class="grid grid-cols-1 md:grid-cols-5 gap-8">
                    <div class="text-center">
                        <div class="mb-4 p-8 bg-gray-50 rounded-lg flex items-center justify-center">
                            <span class="loading loading-spinner loading-xs"></span>
                        </div>
                        <p class="text-sm font-medium text-gray-700">Extra Small</p>
                        <code class="text-xs text-gray-500 mt-1 block">loading-xs</code>
                    </div>
                    <div class="text-center">
                        <div class="mb-4 p-8 bg-gray-50 rounded-lg flex items-center justify-center">
                            <span class="loading loading-spinner loading-sm"></span>
                        </div>
                        <p class="text-sm font-medium text-gray-700">Small</p>
                        <code class="text-xs text-gray-500 mt-1 block">loading-sm</code>
                    </div>
                    <div class="text-center">
                        <div class="mb-4 p-8 bg-gray-50 rounded-lg flex items-center justify-center">
                            <span class="loading loading-spinner"></span>
                        </div>
                        <p class="text-sm font-medium text-gray-700">Medium (Default)</p>
                        <code class="text-xs text-gray-500 mt-1 block">loading</code>
                    </div>
                    <div class="text-center">
                        <div class="mb-4 p-8 bg-gray-50 rounded-lg flex items-center justify-center">
                            <span class="loading loading-spinner loading-lg"></span>
                        </div>
                        <p class="text-sm font-medium text-gray-700">Large</p>
                        <code class="text-xs text-gray-500 mt-1 block">loading-lg</code>
                    </div>
                    <div class="text-center">
                        <div class="mb-4 p-8 bg-gray-50 rounded-lg flex items-center justify-center">
                            <span class="loading loading-spinner loading-xl"></span>
                        </div>
                        <p class="text-sm font-medium text-gray-700">Extra Large</p>
                        <code class="text-xs text-gray-500 mt-1 block">loading-xl</code>
                    </div>
                </div>
            </div>

            <!-- Colored Spinners -->
            <div class="bg-white rounded-lg shadow-sm p-8 mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Colored Spinners</h2>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
                    <div class="text-center">
                        <div class="mb-4 p-8 bg-gray-50 rounded-lg flex items-center justify-center">
                            <span class="loading loading-spinner text-blue-600"></span>
                        </div>
                        <p class="text-sm font-medium text-gray-700">Primary</p>
                        <code class="text-xs text-gray-500">text-blue-600</code>
                    </div>
                    <div class="text-center">
                        <div class="mb-4 p-8 bg-gray-50 rounded-lg flex items-center justify-center">
                            <span class="loading loading-spinner text-green-600"></span>
                        </div>
                        <p class="text-sm font-medium text-gray-700">Success</p>
                        <code class="text-xs text-gray-500">text-green-600</code>
                    </div>
                    <div class="text-center">
                        <div class="mb-4 p-8 bg-gray-50 rounded-lg flex items-center justify-center">
                            <span class="loading loading-spinner text-red-600"></span>
                        </div>
                        <p class="text-sm font-medium text-gray-700">Error</p>
                        <code class="text-xs text-gray-500">text-red-600</code>
                    </div>
                    <div class="text-center">
                        <div class="mb-4 p-8 bg-gray-50 rounded-lg flex items-center justify-center">
                            <span class="loading loading-spinner text-orange-600"></span>
                        </div>
                        <p class="text-sm font-medium text-gray-700">Warning</p>
                        <code class="text-xs text-gray-500">text-orange-600</code>
                    </div>
                    <div class="text-center">
                        <div class="mb-4 p-8 bg-gray-50 rounded-lg flex items-center justify-center">
                            <span class="loading loading-spinner text-cyan-600"></span>
                        </div>
                        <p class="text-sm font-medium text-gray-700">Info</p>
                        <code class="text-xs text-gray-500">text-cyan-600</code>
                    </div>
                    <div class="text-center">
                        <div class="mb-4 p-8 bg-gray-50 rounded-lg flex items-center justify-center">
                            <span class="loading loading-spinner text-gray-600"></span>
                        </div>
                        <p class="text-sm font-medium text-gray-700">Secondary</p>
                        <code class="text-xs text-gray-500">text-gray-600</code>
                    </div>
                </div>
            </div>

            <!-- Blade Component Usage -->
            <div class="bg-white rounded-lg shadow-sm p-8 mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Blade Component Usage</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Basic Spinner</h3>
                        <div class="p-6 bg-gray-50 rounded-lg mb-3">
                            <x-loading-spinner />
                        </div>
                        <pre class="bg-gray-900 text-gray-100 p-4 rounded-lg text-sm overflow-x-auto"><code>&lt;x-loading-spinner /&gt;</code></pre>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">With Label</h3>
                        <div class="p-6 bg-gray-50 rounded-lg mb-3">
                            <x-loading-spinner label="Loading data..." />
                        </div>
                        <pre class="bg-gray-900 text-gray-100 p-4 rounded-lg text-sm overflow-x-auto"><code>&lt;x-loading-spinner label="Loading data..." /&gt;</code></pre>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Different Sizes</h3>
                        <div class="p-6 bg-gray-50 rounded-lg mb-3 flex items-center gap-6">
                            <x-loading-spinner size="xs" inline />
                            <x-loading-spinner size="sm" inline />
                            <x-loading-spinner size="lg" inline />
                            <x-loading-spinner size="xl" inline />
                        </div>
                        <pre class="bg-gray-900 text-gray-100 p-4 rounded-lg text-sm overflow-x-auto"><code>&lt;x-loading-spinner size="xs" /&gt;
&lt;x-loading-spinner size="sm" /&gt;
&lt;x-loading-spinner size="lg" /&gt;
&lt;x-loading-spinner size="xl" /&gt;</code></pre>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Colored Spinners</h3>
                        <div class="p-6 bg-gray-50 rounded-lg mb-3 flex items-center gap-6">
                            <x-loading-spinner color="primary" inline />
                            <x-loading-spinner color="success" inline />
                            <x-loading-spinner color="error" inline />
                        </div>
                        <pre class="bg-gray-900 text-gray-100 p-4 rounded-lg text-sm overflow-x-auto"><code>&lt;x-loading-spinner color="primary" /&gt;
&lt;x-loading-spinner color="success" /&gt;
&lt;x-loading-spinner color="error" /&gt;</code></pre>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Inline with Text</h3>
                        <div class="p-6 bg-gray-50 rounded-lg mb-3">
                            <p class="text-gray-700">
                                <x-loading-spinner size="xs" color="success" inline label="Processing your request..." />
                            </p>
                        </div>
                        <pre class="bg-gray-900 text-gray-100 p-4 rounded-lg text-sm overflow-x-auto"><code>&lt;x-loading-spinner 
    size="xs" 
    color="success" 
    inline 
    label="Processing..." /&gt;</code></pre>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Full Page Loading</h3>
                        <div class="p-6 bg-gray-50 rounded-lg mb-3 h-48">
                            <x-loading-spinner 
                                size="xl" 
                                color="primary" 
                                label="Loading application..." 
                                class="h-full" />
                        </div>
                        <pre class="bg-gray-900 text-gray-100 p-4 rounded-lg text-sm overflow-x-auto"><code>&lt;x-loading-spinner 
    size="xl" 
    color="primary" 
    label="Loading..." 
    class="h-full" /&gt;</code></pre>
                    </div>
                </div>
            </div>

            <!-- Practical Examples -->
            <div class="bg-white rounded-lg shadow-sm p-8 mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Practical Use Cases</h2>
                <div class="space-y-6">
                    <!-- Card Loading State -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">Card Loading State</h3>
                        <div class="border border-gray-200 rounded-lg p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="text-md font-medium text-gray-700">User Profile</h4>
                                <button class="text-blue-600 hover:text-blue-700 text-sm font-medium" onclick="toggleCardLoading()">
                                    Toggle Loading
                                </button>
                            </div>
                            <div id="cardContent">
                                <x-loading-spinner label="Loading profile data..." />
                            </div>
                            <div id="cardData" class="hidden">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                        <span class="text-blue-600 font-bold">JD</span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">John Doe</p>
                                        <p class="text-sm text-gray-500">john.doe@example.com</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Table Loading State -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">Table Loading State</h3>
                        <div class="border border-gray-200 rounded-lg overflow-hidden">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr>
                                        <td colspan="3" class="px-6 py-12">
                                            <x-loading-spinner label="Loading table data..." />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Inline Button Loading -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">Inline Button Loading</h3>
                        <div class="flex items-center gap-4">
                            <button class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                <span class="loading loading-spinner loading-xs"></span>
                                Processing...
                            </button>
                            <button class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                                <span class="loading loading-spinner loading-xs"></span>
                                Saving...
                            </button>
                            <button class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                                <span class="loading loading-spinner loading-xs"></span>
                                Deleting...
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Accessibility Features -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
                <h2 class="text-xl font-bold text-blue-900 mb-4">♿ Accessibility Features</h2>
                <ul class="space-y-2 text-blue-800">
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span><strong>aria-busy="true":</strong> Indicates loading state to screen readers</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span><strong>aria-live="polite":</strong> Announces loading state changes</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span><strong>role="status":</strong> Semantic role for status updates</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span><strong>sr-only text:</strong> Hidden "Loading..." text for screen readers</span>
                    </li>
                </ul>
            </div>

            <!-- HTML Code Examples -->
            <div class="bg-white rounded-lg shadow-sm p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">HTML Code Examples</h2>
                <div class="space-y-4">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-700 mb-2">Extra Small Spinner</h3>
                        <pre class="bg-gray-900 text-gray-100 p-4 rounded-lg text-sm overflow-x-auto"><code>&lt;span class="loading loading-spinner loading-xs"&gt;&lt;/span&gt;</code></pre>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-700 mb-2">Small Spinner</h3>
                        <pre class="bg-gray-900 text-gray-100 p-4 rounded-lg text-sm overflow-x-auto"><code>&lt;span class="loading loading-spinner loading-sm"&gt;&lt;/span&gt;</code></pre>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-700 mb-2">Medium (Default) Spinner</h3>
                        <pre class="bg-gray-900 text-gray-100 p-4 rounded-lg text-sm overflow-x-auto"><code>&lt;span class="loading loading-spinner"&gt;&lt;/span&gt;</code></pre>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-700 mb-2">Large Spinner</h3>
                        <pre class="bg-gray-900 text-gray-100 p-4 rounded-lg text-sm overflow-x-auto"><code>&lt;span class="loading loading-spinner loading-lg"&gt;&lt;/span&gt;</code></pre>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-700 mb-2">Extra Large Spinner</h3>
                        <pre class="bg-gray-900 text-gray-100 p-4 rounded-lg text-sm overflow-x-auto"><code>&lt;span class="loading loading-spinner loading-xl"&gt;&lt;/span&gt;</code></pre>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-700 mb-2">Colored Spinner</h3>
                        <pre class="bg-gray-900 text-gray-100 p-4 rounded-lg text-sm overflow-x-auto"><code>&lt;span class="loading loading-spinner text-blue-600"&gt;&lt;/span&gt;</code></pre>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleCardLoading() {
            const content = document.getElementById('cardContent');
            const data = document.getElementById('cardData');
            
            if (content.classList.contains('hidden')) {
                content.classList.remove('hidden');
                data.classList.add('hidden');
            } else {
                content.classList.add('hidden');
                data.classList.remove('hidden');
            }
        }
    </script>
</body>
</html>
