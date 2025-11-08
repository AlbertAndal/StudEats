<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CSRF Test - StudEats</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">CSRF Protection Test</h1>
        
        <div class="space-y-4">
            <!-- Test 1: Form with valid CSRF token -->
            <div class="border border-green-200 rounded-lg p-4 bg-green-50">
                <h3 class="font-semibold text-green-800 mb-2">✅ Valid CSRF Token Test</h3>
                <form action="{{ route('contact.submit') }}" method="POST" class="space-y-3">
                    @csrf
                    <input type="text" name="name" placeholder="Your name" required class="w-full px-3 py-2 border rounded-md">
                    <input type="email" name="email" placeholder="Your email" required class="w-full px-3 py-2 border rounded-md">
                    <textarea name="message" placeholder="Test message" required class="w-full px-3 py-2 border rounded-md h-20"></textarea>
                    <button type="submit" class="w-full bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700">
                        Submit with Valid Token
                    </button>
                </form>
            </div>

            <!-- Test 2: Form without CSRF token (should fail) -->
            <div class="border border-red-200 rounded-lg p-4 bg-red-50">
                <h3 class="font-semibold text-red-800 mb-2">❌ Missing CSRF Token Test</h3>
                <form action="{{ route('contact.submit') }}" method="POST" class="space-y-3">
                    <!-- No @csrf here intentionally -->
                    <input type="text" name="name" placeholder="Your name" required class="w-full px-3 py-2 border rounded-md">
                    <input type="email" name="email" placeholder="Your email" required class="w-full px-3 py-2 border rounded-md">
                    <textarea name="message" placeholder="Test message" required class="w-full px-3 py-2 border rounded-md h-20"></textarea>
                    <button type="submit" class="w-full bg-red-600 text-white py-2 px-4 rounded-md hover:bg-red-700">
                        Submit without Token (Should Fail)
                    </button>
                </form>
            </div>

            <!-- Test 3: AJAX request with CSRF token -->
            <div class="border border-blue-200 rounded-lg p-4 bg-blue-50">
                <h3 class="font-semibold text-blue-800 mb-2">📡 AJAX CSRF Test</h3>
                <button onclick="testAjaxCSRF()" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700">
                    Test AJAX with CSRF
                </button>
                <div id="ajax-result" class="mt-2 text-sm"></div>
            </div>

            <!-- Test 4: Token refresh -->
            <div class="border border-purple-200 rounded-lg p-4 bg-purple-50">
                <h3 class="font-semibold text-purple-800 mb-2">🔄 Token Refresh Test</h3>
                <button onclick="refreshToken()" class="w-full bg-purple-600 text-white py-2 px-4 rounded-md hover:bg-purple-700">
                    Refresh CSRF Token
                </button>
                <div class="mt-2 text-xs text-gray-600">
                    Current token: <span id="current-token" class="font-mono">{{ csrf_token() }}</span>
                </div>
            </div>
        </div>

        <div class="mt-6 text-center">
            <a href="{{ route('welcome') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                ← Back to StudEats
            </a>
        </div>
    </div>

    <script>
        function testAjaxCSRF() {
            const button = event.target;
            const result = document.getElementById('ajax-result');
            
            button.disabled = true;
            button.textContent = 'Testing...';
            result.textContent = '';
            
            fetch('/api/csrf-token', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            })
            .then(response => {
                console.log('Response status:', response.status);
                if (response.ok) {
                    return response.json();
                } else {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
            })
            .then(data => {
                console.log('Received data:', data);
                
                if (!data || !data.csrf_token) {
                    throw new Error('CSRF token not found in response');
                }
                
                result.innerHTML = `<span class="text-green-600">✅ Success! Token: ${data.csrf_token.substring(0, 10)}...</span>`;
                button.textContent = 'Test AJAX with CSRF';
                button.disabled = false;
            })
            .catch(error => {
                console.error('AJAX Error:', error);
                result.innerHTML = `<span class="text-red-600">❌ Error: ${error.message}</span>`;
                button.textContent = 'Test AJAX with CSRF';
                button.disabled = false;
            });
        }

        function refreshToken() {
            const button = event.target;
            const tokenDisplay = document.getElementById('current-token');
            
            button.disabled = true;
            button.textContent = 'Refreshing...';
            
            fetch('/api/csrf-token', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Token refresh data:', data);
                
                if (!data || !data.csrf_token) {
                    throw new Error('CSRF token not found');
                }
                
                // Update meta tag
                document.querySelector('meta[name="csrf-token"]').setAttribute('content', data.csrf_token);
                
                // Update all form tokens
                document.querySelectorAll('input[name="_token"]').forEach(input => {
                    input.value = data.csrf_token;
                });
                
                // Update display
                tokenDisplay.textContent = data.csrf_token;
                
                button.textContent = 'Token Refreshed!';
                button.className = button.className.replace('bg-purple-600 hover:bg-purple-700', 'bg-green-600 hover:bg-green-700');
                
                setTimeout(() => {
                    button.textContent = 'Refresh CSRF Token';
                    button.className = button.className.replace('bg-green-600 hover:bg-green-700', 'bg-purple-600 hover:bg-purple-700');
                    button.disabled = false;
                }, 2000);
            })
            .catch(error => {
                console.error('Failed to refresh token:', error);
                button.textContent = 'Failed - Try Again';
                button.className = button.className.replace('bg-purple-600 hover:bg-purple-700', 'bg-red-600 hover:bg-red-700');
                setTimeout(() => {
                    button.textContent = 'Refresh CSRF Token';
                    button.className = button.className.replace('bg-red-600 hover:bg-red-700', 'bg-purple-600 hover:bg-purple-700');
                    button.disabled = false;
                }, 2000);
            });
        }
    </script>
</body>
</html>