@props([
    'size' => 'default', // options: small, default, large
    'variant' => 'primary', // options: primary, secondary, muted
    'showEmoji' => true,
    'class' => ''
])

@php
    // Size configurations
    $sizeClasses = [
        'small' => [
            'container' => 'p-2',
            'icon' => 'w-4 h-4',
            'emoji' => 'text-lg'
        ],
        'default' => [
            'container' => 'p-3',
            'icon' => 'w-6 h-6',
            'emoji' => 'text-2xl'
        ],
        'large' => [
            'container' => 'p-4',
            'icon' => 'w-8 h-8',
            'emoji' => 'text-3xl'
        ]
    ];

    // Color variant configurations
    $variantClasses = [
        'primary' => [
            'bg' => 'bg-gradient-to-br from-blue-50 to-indigo-100',
            'border' => 'border-blue-200',
            'iconColor' => 'text-blue-600',
            'emojiColor' => 'text-blue-600',
            'shadow' => 'shadow-sm hover:shadow-md'
        ],
        'secondary' => [
            'bg' => 'bg-gradient-to-br from-green-50 to-emerald-100',
            'border' => 'border-green-200',
            'iconColor' => 'text-green-600',
            'emojiColor' => 'text-green-600',
            'shadow' => 'shadow-sm hover:shadow-md'
        ],
        'muted' => [
            'bg' => 'bg-gradient-to-br from-gray-50 to-slate-100',
            'border' => 'border-gray-200',
            'iconColor' => 'text-gray-600',
            'emojiColor' => 'text-gray-600',
            'shadow' => 'shadow-sm hover:shadow-md'
        ]
    ];

    $currentSize = $sizeClasses[$size];
    $currentVariant = $variantClasses[$variant];
@endphp

<div class="calendar1-component inline-flex items-center justify-center space-x-2 rounded-xl border transition-all duration-200 {{ $currentVariant['bg'] }} {{ $currentVariant['border'] }} {{ $currentVariant['shadow'] }} {{ $currentSize['container'] }} {{ $class }}" {{ $attributes }}>
    <!-- Lucide Calendar Icon -->
    <div class="flex-shrink-0 flex items-center justify-center">
        <svg class="{{ $currentSize['icon'] }} {{ $currentVariant['iconColor'] }}" 
             fill="none" 
             stroke="currentColor" 
             viewBox="0 0 24 24" 
             xmlns="http://www.w3.org/2000/svg">
            <rect x="3" y="4" width="18" height="18" rx="2" ry="2" stroke-width="2"></rect>
            <line x1="16" y1="2" x2="16" y2="6" stroke-width="2"></line>
            <line x1="8" y1="2" x2="8" y2="6" stroke-width="2"></line>
            <line x1="3" y1="10" x2="21" y2="10" stroke-width="2"></line>
            <circle cx="8" cy="14" r="1" fill="currentColor"></circle>
            <circle cx="12" cy="14" r="1" fill="currentColor"></circle>
            <circle cx="16" cy="14" r="1" fill="currentColor"></circle>
            <circle cx="8" cy="18" r="1" fill="currentColor"></circle>
            <circle cx="12" cy="18" r="1" fill="currentColor"></circle>
        </svg>
    </div>

    @if($showEmoji)
    <!-- Calendar Emoji -->
    <div class="flex-shrink-0 flex items-center justify-center">
        <span class="{{ $currentSize['emoji'] }} {{ $currentVariant['emojiColor'] }} leading-none">📅</span>
    </div>
    @endif

    <!-- Optional Slot for Additional Content -->
    @if(!empty($slot))
    <div class="flex-1 min-w-0">
        {{ $slot }}
    </div>
    @endif
</div>
