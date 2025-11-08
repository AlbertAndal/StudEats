{{--
Reusable Confirmation Modal Component
Usage: <x-confirmation-modal id="myModal" title="Confirm Action" />
--}}

@props([
    'id' => 'confirmationModal',
    'title' => 'Confirm Action',
    'icon' => 'exclamation-triangle',
    'iconColor' => 'red',
    'size' => 'md'
])

<div id="{{ $id }}" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="hideModal('{{ $id }}')"></div>

        <!-- Center the modal contents -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <!-- Modal panel -->
        <div class="relative inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle 
            @if($size === 'sm') sm:max-w-sm 
            @elseif($size === 'lg') sm:max-w-2xl 
            @elseif($size === 'xl') sm:max-w-4xl 
            @else sm:max-w-lg @endif 
            sm:w-full sm:p-6">
            
            <div class="sm:flex sm:items-start">
                <!-- Icon -->
                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full 
                    @if($iconColor === 'red') bg-red-100 
                    @elseif($iconColor === 'yellow') bg-yellow-100 
                    @elseif($iconColor === 'green') bg-green-100 
                    @elseif($iconColor === 'blue') bg-blue-100 
                    @else bg-gray-100 @endif 
                    sm:mx-0 sm:h-10 sm:w-10">
                    
                    @if($icon === 'exclamation-triangle')
                        <svg class="h-6 w-6 
                            @if($iconColor === 'red') text-red-600 
                            @elseif($iconColor === 'yellow') text-yellow-600 
                            @elseif($iconColor === 'green') text-green-600 
                            @elseif($iconColor === 'blue') text-blue-600 
                            @else text-gray-600 @endif" 
                            fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                    @elseif($icon === 'question')
                        <svg class="h-6 w-6 
                            @if($iconColor === 'red') text-red-600 
                            @elseif($iconColor === 'yellow') text-yellow-600 
                            @elseif($iconColor === 'green') text-green-600 
                            @elseif($iconColor === 'blue') text-blue-600 
                            @else text-gray-600 @endif" 
                            fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    @elseif($icon === 'trash')
                        <svg class="h-6 w-6 
                            @if($iconColor === 'red') text-red-600 
                            @elseif($iconColor === 'yellow') text-yellow-600 
                            @elseif($iconColor === 'green') text-green-600 
                            @elseif($iconColor === 'blue') text-blue-600 
                            @else text-gray-600 @endif" 
                            fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1-1H8a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    @elseif($icon === 'check')
                        <svg class="h-6 w-6 
                            @if($iconColor === 'red') text-red-600 
                            @elseif($iconColor === 'yellow') text-yellow-600 
                            @elseif($iconColor === 'green') text-green-600 
                            @elseif($iconColor === 'blue') text-blue-600 
                            @else text-gray-600 @endif" 
                            fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    @else
                        <x-icon name="{{ $icon }}" class="h-6 w-6 
                            @if($iconColor === 'red') text-red-600 
                            @elseif($iconColor === 'yellow') text-yellow-600 
                            @elseif($iconColor === 'green') text-green-600 
                            @elseif($iconColor === 'blue') text-blue-600 
                            @else text-gray-600 @endif" 
                            variant="outline" />
                    @endif
                </div>
                
                <!-- Modal Content -->
                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left flex-1">
                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                        {{ $title }}
                    </h3>
                    <div class="mt-3">
                        {{ $slot }}
                    </div>
                </div>
            </div>
            
            <!-- Modal Actions -->
            <div class="mt-6 sm:mt-4 sm:flex sm:flex-row-reverse">
                {{ $actions ?? '' }}
            </div>
        </div>
    </div>
</div>

@pushOnce('scripts')
<script>
// Global modal functions
window.showModal = function(modalId, config = {}) {
    const modal = document.getElementById(modalId);
    if (!modal) return;
    
    // Update modal content if config provided
    if (config.title) {
        const titleElement = modal.querySelector('#modal-title');
        if (titleElement) titleElement.textContent = config.title;
    }
    
    modal.classList.remove('hidden');
    
    // Add entrance animation
    requestAnimationFrame(() => {
        const overlay = modal.querySelector('.bg-gray-500');
        const panel = modal.querySelector('.bg-white');
        
        overlay.style.opacity = '0';
        panel.style.transform = 'scale(0.95)';
        panel.style.opacity = '0';
        
        overlay.style.transition = 'opacity 300ms ease-out';
        panel.style.transition = 'all 300ms ease-out';
        
        requestAnimationFrame(() => {
            overlay.style.opacity = '0.75';
            panel.style.transform = 'scale(1)';
            panel.style.opacity = '1';
        });
    });
    
    // Focus management for accessibility
    setTimeout(() => {
        const firstButton = modal.querySelector('button');
        if (firstButton) firstButton.focus();
    }, 100);
};

window.hideModal = function(modalId) {
    const modal = document.getElementById(modalId);
    if (!modal) return;
    
    const overlay = modal.querySelector('.bg-gray-500');
    const panel = modal.querySelector('.bg-white');
    
    // Add exit animation
    overlay.style.transition = 'opacity 200ms ease-in';
    panel.style.transition = 'all 200ms ease-in';
    
    overlay.style.opacity = '0';
    panel.style.transform = 'scale(0.95)';
    panel.style.opacity = '0';
    
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 200);
};

// Global ESC key handler
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const visibleModals = document.querySelectorAll('[id$="Modal"]:not(.hidden)');
        visibleModals.forEach(modal => {
            hideModal(modal.id);
        });
    }
});
</script>
@endPushOnce