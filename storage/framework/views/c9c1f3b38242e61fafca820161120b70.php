<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
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
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
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
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
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
?>

<?php if($isLink): ?>
    <a 
        href="<?php echo e($href ?? $attributes->get('href')); ?>"
        <?php echo e($attributes->except('href')->merge(['class' => $classes])); ?>

        <?php if($loading || $disabled): ?> 
            onclick="event.preventDefault(); showLoadingState(this); setTimeout(() => window.location.href='<?php echo e($href ?? $attributes->get('href')); ?>', 100);"
        <?php endif; ?>
    >
        <?php if($loading): ?>
            <?php if($iconPosition === 'left'): ?>
                <span class="loading <?php echo e($loadingClass); ?> <?php echo e($spinnerSize); ?>"></span>
            <?php endif; ?>
            
            <?php if($loadingText && !$square): ?>
                <span><?php echo e($loadingText); ?></span>
            <?php elseif(!$square && $slot->isNotEmpty()): ?>
                <?php echo e($slot); ?>

            <?php endif; ?>
            
            <?php if($iconPosition === 'right' && !$square): ?>
                <span class="loading <?php echo e($loadingClass); ?> <?php echo e($spinnerSize); ?>"></span>
            <?php endif; ?>
        <?php else: ?>
            <?php echo e($slot); ?>

        <?php endif; ?>
    </a>
<?php else: ?>
    <button 
        type="<?php echo e($type); ?>"
        <?php echo e($attributes->merge(['class' => $classes])); ?>

        <?php if($loading || $disabled): ?> disabled <?php endif; ?>
    >
        <?php if($loading): ?>
            <?php if($iconPosition === 'left'): ?>
                <span class="loading <?php echo e($loadingClass); ?> <?php echo e($spinnerSize); ?>"></span>
            <?php endif; ?>
            
            <?php if($loadingText && !$square): ?>
                <span><?php echo e($loadingText); ?></span>
            <?php elseif(!$square && $slot->isNotEmpty()): ?>
                <?php echo e($slot); ?>

            <?php endif; ?>
            
            <?php if($iconPosition === 'right' && !$square): ?>
                <span class="loading <?php echo e($loadingClass); ?> <?php echo e($spinnerSize); ?>"></span>
            <?php endif; ?>
        <?php else: ?>
            <?php echo e($slot); ?>

        <?php endif; ?>
    </button>
<?php endif; ?>

<?php if (! $__env->hasRenderedOnce('da0c5106-b6ba-4ca5-9477-23d7eb457dab')): $__env->markAsRenderedOnce('da0c5106-b6ba-4ca5-9477-23d7eb457dab'); ?>
<?php $__env->startPush('scripts'); ?>
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
<?php $__env->stopPush(); ?>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\StudEats\resources\views/components/loading-button.blade.php ENDPATH**/ ?>