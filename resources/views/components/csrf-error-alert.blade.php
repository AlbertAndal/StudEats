@if(session('csrf_error') || session('error'))
<div class="mb-6 rounded-md border border-yellow-200 bg-yellow-50 p-4" id="csrf-error-alert">
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
        </div>
        <div class="ml-3">
            <h3 class="text-sm font-medium text-yellow-800">
                @if(session('csrf_error'))
                    Session Expired
                @else
                    Security Notice
                @endif
            </h3>
            <div class="mt-2 text-sm text-yellow-700">
                <p>{{ session('error', 'Your session has expired for security reasons. Please try submitting the form again.') }}</p>
                @if(session('csrf_error'))
                <div class="mt-3">
                    <button onclick="refreshCSRFToken()" 
                            class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded text-yellow-800 bg-yellow-100 hover:bg-yellow-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Refresh Security Token
                    </button>
                    <button onclick="dismissAlert()" 
                            class="ml-2 inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded text-yellow-600 hover:text-yellow-800 focus:outline-none transition-colors">
                        Dismiss
                    </button>
                </div>
                @endif
            </div>
        </div>
        <div class="ml-auto pl-3">
            <div class="-mx-1.5 -my-1.5">
                <button onclick="dismissAlert()" 
                        class="inline-flex rounded-md p-1.5 text-yellow-500 hover:bg-yellow-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-yellow-50 focus:ring-yellow-600 transition-colors">
                    <span class="sr-only">Dismiss</span>
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function refreshCSRFToken() {
    const button = event.target;
    const originalContent = button.innerHTML;
    
    // Show loading state
    button.innerHTML = `
        <svg class="animate-spin w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Refreshing...
    `;
    button.disabled = true;
    
    // Fetch new CSRF token
    fetch('/api/csrf-token', {
        method: 'GET',
        credentials: 'same-origin',
        headers: {
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data && data.csrf_token) {
            // Update CSRF token in meta tag
            const metaTag = document.querySelector('meta[name="csrf-token"]');
            if (metaTag) {
                metaTag.setAttribute('content', data.csrf_token);
            }
            
            // Update CSRF tokens in all forms
            document.querySelectorAll('input[name="_token"]').forEach(input => {
                input.value = data.csrf_token;
            });
            
            // Show success
            button.innerHTML = `
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Token Updated!
            `;
            button.className = button.className.replace('bg-yellow-100 hover:bg-yellow-200', 'bg-green-100 hover:bg-green-200');
            
            // Auto-dismiss after 3 seconds
            setTimeout(() => {
                dismissAlert();
            }, 3000);
        } else {
            throw new Error('Invalid response from server');
        }
    })
    .catch(error => {
        console.error('Failed to refresh CSRF token:', error);
        button.innerHTML = originalContent;
        button.disabled = false;
        
        // Show error message
        alert('Failed to refresh security token. Please refresh the page manually.');
    });
}

function dismissAlert() {
    const alert = document.getElementById('csrf-error-alert');
    if (alert) {
        alert.style.transition = 'opacity 0.3s ease-out';
        alert.style.opacity = '0';
        setTimeout(() => {
            alert.remove();
        }, 300);
    }
}
</script>
@endif