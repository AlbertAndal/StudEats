@props([
    'size' => 'md', // xs, sm, md, lg, xl
    'color' => 'current', // current, primary, success, error, warning, info
    'label' => null,
    'inline' => false,
])

@php
    $sizeClasses = match($size) {
        'xs' => 'loading-xs',
        'sm' => 'loading-sm',
        'lg' => 'loading-lg',
        'xl' => 'loading-xl',
        default => '', // md is default
    };

    $colorClasses = match($color) {
        'primary' => 'text-blue-600',
        'success' => 'text-green-600',
        'error' => 'text-red-600',
        'warning' => 'text-orange-600',
        'info' => 'text-cyan-600',
        default => 'text-current',
    };

    $containerClasses = $inline ? 'inline-flex items-center gap-2' : 'flex items-center justify-center gap-2';
@endphp

<div {{ $attributes->merge(['class' => $containerClasses]) }} role="status" aria-busy="true" aria-live="polite">
    <span class="loading loading-spinner {{ $sizeClasses }} {{ $colorClasses }}"></span>
    @if($label)
        <span class="text-sm {{ $colorClasses }}">{{ $label }}</span>
    @endif
    <span class="sr-only">Loading...</span>
</div>
