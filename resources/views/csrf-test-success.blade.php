<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSRF Test Success - StudEats</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8">
        <div class="text-center mb-6">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
                <svg class="h-10 w-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            
            <h1 class="text-2xl font-bold text-gray-900 mb-2">
                ✅ CSRF Test Successful!
            </h1>
            <p class="text-gray-600">Your form was validated with a valid CSRF token.</p>
        </div>
        
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
            <h3 class="font-semibold text-green-800 mb-3">Submitted Data:</h3>
            <div class="space-y-2 text-sm">
                <div>
                    <span class="text-green-700 font-medium">Name:</span>
                    <span class="text-green-900">{{ $name }}</span>
                </div>
                <div>
                    <span class="text-green-700 font-medium">Email:</span>
                    <span class="text-green-900">{{ $email }}</span>
                </div>
                @if(!empty($message))
                <div>
                    <span class="text-green-700 font-medium">Message:</span>
                    <span class="text-green-900">{{ $message }}</span>
                </div>
                @endif
                <div class="pt-2 border-t border-green-200">
                    <span class="text-green-700 font-medium">CSRF Token:</span>
                    <code class="block mt-1 text-xs font-mono text-green-900 break-all bg-green-100 p-2 rounded">{{ $csrf_token }}</code>
                </div>
            </div>
        </div>
        
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <div class="flex items-start">
                <svg class="h-5 w-5 text-blue-600 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <p class="text-sm font-medium text-blue-800 mb-1">CSRF Protection Working!</p>
                    <p class="text-xs text-blue-700">
                        Your form submission was successfully validated. The CSRF token matched the session token, proving that the request came from your authenticated browser session.
                    </p>
                </div>
            </div>
        </div>
        
        <div class="space-y-3">
            <a href="{{ route('csrf.test') }}" 
               class="block w-full text-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                ← Back to CSRF Test Page
            </a>
            
            <a href="{{ route('welcome') }}" 
               class="block w-full text-center py-3 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                Go to StudEats Home
            </a>
        </div>
        
        <div class="mt-6 pt-6 border-t border-gray-200">
            <p class="text-xs text-gray-500 text-center">
                This test confirms your CSRF protection is properly configured and working.
            </p>
        </div>
    </div>
</body>
</html>
