@props([
    'type' => 'button',
    'variant' => 'primary', // primary, success, error, warning, info, secondary
    'size' => 'md', // sm, md, lg
    'loading' => false,
    'loadingText' => null,
    'loadingType' => 'spinner', // spinner, ring, dots, ball, bars, infinity
    'square' => false,
    'disabled' => false,
    'iconPosition' => 'left', // left, right
    'href' => null,
])

@php
    $baseClasses = 'btn';
    
    // Variant classes
    $variantClasses = match($variant) {
        'primary' => 'btn-primary',
        'success' => 'btn-success',
        'error' => 'btn-error',
        'warning' => 'btn-warning',
        'info' => 'btn-info',
        'secondary' => 'btn-secondary',
        default => 'btn-primary',
    };
    
    // Size classes
    $sizeClasses = match($size) {
        'sm' => 'btn-sm',
        'lg' => 'btn-lg',
        'xs' => 'btn-xs',
        default => '',
    };
    
    // Loading spinner size based on button size
    $spinnerSize = match($size) {
        'xs' => 'loading-xs',
        'sm' => 'loading-sm',
        'lg' => 'loading-lg',
        default => 'loading-sm',
    };
    
    // Shape classes
    $shapeClasses = $square ? 'btn-square' : '';
    
    // Disabled state
    $disabledClasses = ($loading || $disabled) ? 'btn-disabled' : '';
    
    // Loading type
    $loadingClass = match($loadingType) {
        'spinner' => 'loading-spinner',
        'ring' => 'loading-ring',
        'dots' => 'loading-dots',
        'ball' => 'loading-ball',
        'bars' => 'loading-bars',
        'infinity' => 'loading-infinity',
        default => 'loading-spinner',
    };
    
    // Combine all classes
    $classes = trim("{$baseClasses} {$variantClasses} {$sizeClasses} {$shapeClasses} {$disabledClasses}");
    
    // Determine if this is a link or button
    $isLink = $href !== null || $attributes->has('href');
    $tag = $isLink ? 'a' : 'button';
@endphp

@if($isLink)
    <a 
        href="{{ $href ?? $attributes->get('href') }}"
        {{ $attributes->except('href')->merge(['class' => $classes]) }}
        @if($loading || $disabled) 
            onclick="event.preventDefault(); showLoadingState(this); setTimeout(() => window.location.href='{{ $href ?? $attributes->get('href') }}', 100);"
        @endif
    >
        @if($loading)
            @if($iconPosition === 'left')
                <span class="loading {{ $loadingClass }} {{ $spinnerSize }}"></span>
            @endif
            
            @if($loadingText && !$square)
                <span>{{ $loadingText }}</span>
            @elseif(!$square && $slot->isNotEmpty())
                {{ $slot }}
            @endif
            
            @if($iconPosition === 'right' && !$square)
                <span class="loading {{ $loadingClass }} {{ $spinnerSize }}"></span>
            @endif
        @else
            {{ $slot }}
        @endif
    </a>
@else
    <button 
        type="{{ $type }}"
        {{ $attributes->merge(['class' => $classes]) }}
        @if($loading || $disabled) disabled @endif
    >
        @if($loading)
            @if($iconPosition === 'left')
                <span class="loading {{ $loadingClass }} {{ $spinnerSize }}"></span>
            @endif
            
            @if($loadingText && !$square)
                <span>{{ $loadingText }}</span>
            @elseif(!$square && $slot->isNotEmpty())
                {{ $slot }}
            @endif
            
            @if($iconPosition === 'right' && !$square)
                <span class="loading {{ $loadingClass }} {{ $spinnerSize }}"></span>
            @endif
        @else
            {{ $slot }}
        @endif
    </button>
@endif

@once
@push('scripts')
<script>
function showLoadingState(element) {
    const spinner = document.createElement('span');
    spinner.className = 'loading loading-spinner loading-sm';
    element.innerHTML = '';
    element.appendChild(spinner);
    const text = document.createElement('span');
    text.textContent = 'Loading...';
    element.appendChild(text);
    element.classList.add('btn-disabled');
}

// Auto-loading state for links
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.btn[href]').forEach(function(link) {
        if (!link.hasAttribute('onclick') && !link.classList.contains('no-loading')) {
            link.addEventListener('click', function(e) {
                if (!this.classList.contains('btn-disabled')) {
                    const originalContent = this.innerHTML;
                    const spinner = document.createElement('span');
                    spinner.className = 'loading loading-spinner loading-sm';
                    this.innerHTML = '';
                    this.appendChild(spinner);
                    const text = document.createElement('span');
                    text.textContent = 'Loading...';
                    this.appendChild(text);
                    this.classList.add('btn-disabled');
                }
            });
        }
    });
});
</script>
@endpush
@endonce
