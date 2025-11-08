<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CSRF Protection Test Suite - StudEats</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .test-section { transition: all 0.3s ease; }
        .test-section:hover { transform: translateY(-2px); box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1); }
        .console-log { font-family: 'Courier New', monospace; font-size: 11px; max-height: 200px; overflow-y: auto; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen p-6">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">🔒 CSRF Protection Test Suite</h1>
            <p class="text-gray-600">Comprehensive testing of CSRF token generation, validation, and refresh mechanisms</p>
            <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                <div class="bg-blue-50 p-3 rounded">
                    <span class="font-semibold text-blue-800">Session ID:</span>
                    <code class="block text-blue-600 text-xs mt-1" id="session-id">{{ session()->getId() }}</code>
                </div>
                <div class="bg-green-50 p-3 rounded">
                    <span class="font-semibold text-green-800">CSRF Token:</span>
                    <code class="block text-green-600 text-xs mt-1 break-all" id="current-token">{{ csrf_token() }}</code>
                </div>
                <div class="bg-purple-50 p-3 rounded">
                    <span class="font-semibold text-purple-800">Lifetime:</span>
                    <code class="block text-purple-600 mt-1">{{ config('session.lifetime') }} minutes</code>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Test 1: Valid CSRF Token -->
            <div class="test-section bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
                <div class="flex items-center mb-4">
                    <div class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                        <span class="text-xl">✅</span>
                    </div>
                    <div class="ml-3">
                        <h3 class="font-bold text-gray-900">Test 1: Valid CSRF Token</h3>
                        <p class="text-sm text-gray-600">Form with valid CSRF token should succeed</p>
                    </div>
                </div>
                <form action="{{ route('csrf.test.submit') }}" method="POST" class="space-y-3">
                    @csrf
                    <input type="text" name="name" placeholder="Your name" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <input type="email" name="email" placeholder="Your email" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <textarea name="message" placeholder="Test message" required 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md h-20 focus:ring-2 focus:ring-green-500 focus:border-transparent"></textarea>
                    <button type="submit" 
                            class="w-full bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 transition-colors font-medium">
                        Submit with Valid Token
                    </button>
                </form>
            </div>

            <!-- Test 2: Missing CSRF Token -->
            <div class="test-section bg-white rounded-lg shadow p-6 border-l-4 border-red-500">
                <div class="flex items-center mb-4">
                    <div class="flex-shrink-0 w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                        <span class="text-xl">❌</span>
                    </div>
                    <div class="ml-3">
                        <h3 class="font-bold text-gray-900">Test 2: Missing CSRF Token</h3>
                        <p class="text-sm text-gray-600">Should trigger 419 error page</p>
                    </div>
                </div>
                <form action="{{ route('csrf.test.submit') }}" method="POST" class="space-y-3">
                    <!-- Intentionally NO @csrf -->
                    <input type="text" name="name" placeholder="Your name" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    <input type="email" name="email" placeholder="Your email" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    <textarea name="message" placeholder="Test message" required 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md h-20 focus:ring-2 focus:ring-red-500 focus:border-transparent"></textarea>
                    <button type="submit" 
                            class="w-full bg-red-600 text-white py-2 px-4 rounded-md hover:bg-red-700 transition-colors font-medium">
                        Submit without Token (Expected: 419 Error)
                    </button>
                </form>
            </div>

            <!-- Test 3: AJAX Token Request -->
            <div class="test-section bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
                <div class="flex items-center mb-4">
                    <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                        <span class="text-xl">📡</span>
                    </div>
                    <div class="ml-3">
                        <h3 class="font-bold text-gray-900">Test 3: AJAX Token Request</h3>
                        <p class="text-sm text-gray-600">Fetch fresh CSRF token via API</p>
                    </div>
                </div>
                <button onclick="testAjaxCSRF()" 
                        class="w-full bg-blue-600 text-white py-3 px-4 rounded-md hover:bg-blue-700 transition-colors font-medium mb-3">
                    Test AJAX CSRF Token Request
                </button>
                <div id="ajax-result" class="mt-2 p-3 bg-gray-50 rounded text-sm min-h-[60px]"></div>
            </div>

            <!-- Test 4: Token Refresh -->
            <div class="test-section bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
                <div class="flex items-center mb-4">
                    <div class="flex-shrink-0 w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                        <span class="text-xl">🔄</span>
                    </div>
                    <div class="ml-3">
                        <h3 class="font-bold text-gray-900">Test 4: Token Refresh</h3>
                        <p class="text-sm text-gray-600">Refresh CSRF token and update forms</p>
                    </div>
                </div>
                <button onclick="refreshToken()" 
                        class="w-full bg-purple-600 text-white py-3 px-4 rounded-md hover:bg-purple-700 transition-colors font-medium mb-3">
                        Refresh CSRF Token
                </button>
                <div class="text-xs text-gray-600 bg-gray-50 p-3 rounded">
                    <strong>Current Token:</strong>
                    <code class="block mt-1 text-purple-600 break-all font-mono" id="refresh-token-display">{{ csrf_token() }}</code>
                </div>
            </div>

            <!-- Test 5: Session Health Check -->
            <div class="test-section bg-white rounded-lg shadow p-6 border-l-4 border-yellow-500">
                <div class="flex items-center mb-4">
                    <div class="flex-shrink-0 w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                        <span class="text-xl">🔍</span>
                    </div>
                    <div class="ml-3">
                        <h3 class="font-bold text-gray-900">Test 5: Session Health Check</h3>
                        <p class="text-sm text-gray-600">Verify session and token status</p>
                    </div>
                </div>
                <button onclick="checkSessionHealth()" 
                        class="w-full bg-yellow-600 text-white py-3 px-4 rounded-md hover:bg-yellow-700 transition-colors font-medium mb-3">
                        Check Session Health
                </button>
                <div id="health-result" class="mt-2 p-3 bg-gray-50 rounded text-sm min-h-[100px]"></div>
            </div>

            <!-- Test 6: Token Validation -->
            <div class="test-section bg-white rounded-lg shadow p-6 border-l-4 border-indigo-500">
                <div class="flex items-center mb-4">
                    <div class="flex-shrink-0 w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center">
                        <span class="text-xl">🛡️</span>
                    </div>
                    <div class="ml-3">
                        <h3 class="font-bold text-gray-900">Test 6: Token Validation</h3>
                        <p class="text-sm text-gray-600">Validate current CSRF token</p>
                    </div>
                </div>
                <button onclick="validateCurrentToken()" 
                        class="w-full bg-indigo-600 text-white py-3 px-4 rounded-md hover:bg-indigo-700 transition-colors font-medium mb-3">
                        Validate Current Token
                </button>
                <div id="validation-result" class="mt-2 p-3 bg-gray-50 rounded text-sm min-h-[60px]"></div>
            </div>
        </div>

        <!-- Console Logs -->
        <div class="mt-6 bg-gray-900 rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-white">📋 Test Console Logs</h3>
                <button onclick="clearConsoleLogs()" class="text-xs bg-gray-700 hover:bg-gray-600 text-white px-3 py-1 rounded">
                    Clear Logs
                </button>
            </div>
            <div id="console-logs" class="console-log bg-black text-green-400 p-4 rounded"></div>
        </div>

        <!-- Back Link -->
        <div class="mt-6 text-center">
            <a href="{{ route('welcome') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                ← Back to StudEats Homepage
            </a>
        </div>
    </div>

    <script>
        // Console logging helper
        function logToConsole(message, type = 'info') {
            const console = document.getElementById('console-logs');
            const timestamp = new Date().toISOString();
            const colors = {
                info: 'text-green-400',
                success: 'text-blue-400',
                error: 'text-red-400',
                warning: 'text-yellow-400'
            };
            const entry = document.createElement('div');
            entry.className = `${colors[type]} mb-1`;
            entry.textContent = `[${timestamp}] ${message}`;
            console.appendChild(entry);
            console.scrollTop = console.scrollHeight;
            console.log(`[CSRF Test] ${message}`);
        }

        function clearConsoleLogs() {
            document.getElementById('console-logs').innerHTML = '';
            logToConsole('Console cleared', 'info');
        }

        // Test 3: AJAX Token Request
        async function testAjaxCSRF() {
            const button = event.target;
            const result = document.getElementById('ajax-result');
            
            button.disabled = true;
            button.textContent = 'Testing...';
            result.innerHTML = '<div class="animate-pulse text-blue-600">⏳ Requesting CSRF token...</div>';
            
            logToConsole('Starting AJAX CSRF token request test', 'info');
            
            try {
                const response = await fetch('/api/csrf-token', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                });
                
                logToConsole(`Response status: ${response.status} ${response.statusText}`, 'info');
                
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                
                const data = await response.json();
                logToConsole(`Received data: ${JSON.stringify(data)}`, 'success');
                
                // Validate response structure
                if (!data || typeof data !== 'object') {
                    throw new Error('Invalid response format');
                }
                
                if (!data.csrf_token) {
                    throw new Error('CSRF token not found in response');
                }
                
                if (!data.success) {
                    throw new Error('Response indicates failure');
                }
                
                result.innerHTML = `
                    <div class="text-green-700">
                        <div class="flex items-center font-semibold mb-2">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            ✅ AJAX Request Successful
                        </div>
                        <div class="text-sm space-y-1">
                            <div><strong>Token:</strong> <code class="text-xs">${data.csrf_token.substring(0, 20)}...</code></div>
                            <div><strong>Session:</strong> <code class="text-xs">${data.session_id || 'N/A'}</code></div>
                            <div><strong>Expires:</strong> ${data.expires_at || 'N/A'}</div>
                        </div>
                    </div>
                `;
                
                logToConsole('AJAX CSRF test PASSED ✅', 'success');
                button.textContent = 'Test AJAX CSRF Token Request';
                button.disabled = false;
                
            } catch (error) {
                logToConsole(`AJAX CSRF test FAILED: ${error.message}`, 'error');
                result.innerHTML = `
                    <div class="text-red-700">
                        <div class="flex items-center font-semibold mb-2">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            ❌ AJAX Request Failed
                        </div>
                        <div class="text-sm">${error.message}</div>
                    </div>
                `;
                button.textContent = 'Test AJAX CSRF Token Request';
                button.disabled = false;
            }
        }

        // Test 4: Token Refresh
        async function refreshToken() {
            const button = event.target;
            const tokenDisplay = document.getElementById('refresh-token-display');
            const currentTokenDisplay = document.getElementById('current-token');
            
            button.disabled = true;
            button.textContent = 'Refreshing...';
            
            logToConsole('Starting token refresh test', 'info');
            
            try {
                const response = await fetch('/api/csrf-token', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                });
                
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}`);
                }
                
                const data = await response.json();
                logToConsole(`Token refresh data: ${JSON.stringify(data)}`, 'info');
                
                if (!data || !data.csrf_token) {
                    throw new Error('CSRF token not found in response');
                }
                
                // Update meta tag
                const metaTag = document.querySelector('meta[name="csrf-token"]');
                if (metaTag) {
                    metaTag.setAttribute('content', data.csrf_token);
                    logToConsole('Updated meta tag with new token', 'success');
                }
                
                // Update all form tokens
                const formTokens = document.querySelectorAll('input[name="_token"]');
                formTokens.forEach(input => {
                    input.value = data.csrf_token;
                });
                logToConsole(`Updated ${formTokens.length} form tokens`, 'success');
                
                // Update displays
                tokenDisplay.textContent = data.csrf_token;
                currentTokenDisplay.textContent = data.csrf_token;
                
                button.textContent = '✅ Token Refreshed!';
                button.className = button.className.replace('bg-purple-600 hover:bg-purple-700', 'bg-green-600 hover:bg-green-700');
                
                logToConsole('Token refresh test PASSED ✅', 'success');
                
                setTimeout(() => {
                    button.textContent = 'Refresh CSRF Token';
                    button.className = button.className.replace('bg-green-600 hover:bg-green-700', 'bg-purple-600 hover:bg-purple-700');
                    button.disabled = false;
                }, 2000);
                
            } catch (error) {
                logToConsole(`Token refresh test FAILED: ${error.message}`, 'error');
                button.textContent = '❌ Refresh Failed';
                button.className = button.className.replace('bg-purple-600 hover:bg-purple-700', 'bg-red-600 hover:bg-red-700');
                
                setTimeout(() => {
                    button.textContent = 'Refresh CSRF Token';
                    button.className = button.className.replace('bg-red-600 hover:bg-red-700', 'bg-purple-600 hover:bg-purple-700');
                    button.disabled = false;
                }, 2000);
            }
        }

        // Test 5: Session Health Check
        async function checkSessionHealth() {
            const button = event.target;
            const result = document.getElementById('health-result');
            
            button.disabled = true;
            button.textContent = 'Checking...';
            result.innerHTML = '<div class="animate-pulse text-yellow-600">⏳ Checking session health...</div>';
            
            logToConsole('Starting session health check', 'info');
            
            try {
                const response = await fetch('/api/session-check', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                });
                
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}`);
                }
                
                const data = await response.json();
                logToConsole(`Session health data: ${JSON.stringify(data)}`, 'success');
                
                result.innerHTML = `
                    <div class="text-sm space-y-2">
                        <div class="flex items-center ${data.status === 'active' ? 'text-green-700' : 'text-red-700'}">
                            <strong>Status:</strong>
                            <span class="ml-2">${data.status === 'active' ? '✅ Active' : '❌ Inactive'}</span>
                        </div>
                        <div class="text-gray-700">
                            <strong>Session ID:</strong>
                            <code class="text-xs block mt-1">${data.session_id}</code>
                        </div>
                        <div class="text-gray-700">
                            <strong>CSRF Token Present:</strong>
                            <span class="ml-2">${data.csrf_token_present ? '✅ Yes' : '❌ No'}</span>
                        </div>
                        <div class="text-gray-700">
                            <strong>Lifetime:</strong> ${data.session_lifetime} minutes
                        </div>
                        <div class="text-gray-700">
                            <strong>Expires:</strong> ${data.expires_at}
                        </div>
                    </div>
                `;
                
                logToConsole('Session health check PASSED ✅', 'success');
                button.textContent = 'Check Session Health';
                button.disabled = false;
                
            } catch (error) {
                logToConsole(`Session health check FAILED: ${error.message}`, 'error');
                result.innerHTML = `<div class="text-red-700">❌ Error: ${error.message}</div>`;
                button.textContent = 'Check Session Health';
                button.disabled = false;
            }
        }

        // Test 6: Token Validation
        async function validateCurrentToken() {
            const button = event.target;
            const result = document.getElementById('validation-result');
            
            button.disabled = true;
            button.textContent = 'Validating...';
            result.innerHTML = '<div class="animate-pulse text-indigo-600">⏳ Validating token...</div>';
            
            const currentToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            logToConsole(`Validating token: ${currentToken.substring(0, 20)}...`, 'info');
            
            try {
                const response = await fetch('/api/csrf-validate', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': currentToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({ token: currentToken })
                });
                
                const data = await response.json();
                logToConsole(`Validation response: ${JSON.stringify(data)}`, data.valid ? 'success' : 'error');
                
                result.innerHTML = `
                    <div class="${data.valid ? 'text-green-700' : 'text-red-700'}">
                        <div class="flex items-center font-semibold mb-2">
                            ${data.valid ? '✅ Token is Valid' : '❌ Token is Invalid'}
                        </div>
                        <div class="text-sm">${data.message}</div>
                    </div>
                `;
                
                logToConsole(`Token validation test ${data.valid ? 'PASSED ✅' : 'FAILED ❌'}`, data.valid ? 'success' : 'error');
                button.textContent = 'Validate Current Token';
                button.disabled = false;
                
            } catch (error) {
                logToConsole(`Token validation test FAILED: ${error.message}`, 'error');
                result.innerHTML = `<div class="text-red-700">❌ Error: ${error.message}</div>`;
                button.textContent = 'Validate Current Token';
                button.disabled = false;
            }
        }

        // Initialize console
        window.addEventListener('DOMContentLoaded', () => {
            logToConsole('CSRF Test Suite initialized', 'success');
            logToConsole(`Session ID: {{ session()->getId() }}`, 'info');
            logToConsole(`Initial CSRF Token: {{ csrf_token() }}`, 'info');
        });
    </script>
</body>
</html>