# FlyonUI Loading Buttons Integration Guide

## Overview
FlyonUI loading button components have been successfully integrated into the StudEats project. This guide explains how to use them effectively.

## Installation Complete ✅
- **Package**: `flyonui` installed via npm
- **Tailwind Config**: Updated with FlyonUI plugins
- **Component**: `<x-loading-button>` Blade component created
- **Demo Page**: `/loading-buttons-demo` route added

## Quick Start

### 1. Basic Usage

```blade
<x-loading-button variant="primary" :loading="true" loadingText="Loading..." />
```

### 2. Square Icon Button

```blade
<x-loading-button variant="primary" :loading="true" square aria-label="Loading" />
```

### 3. Different Animation Types

```blade
<!-- Spinner (default) -->
<x-loading-button variant="primary" :loading="true" loadingType="spinner" loadingText="Loading..." />

<!-- Ring -->
<x-loading-button variant="success" :loading="true" loadingType="ring" loadingText="Processing..." />

<!-- Dots -->
<x-loading-button variant="info" :loading="true" loadingType="dots" loadingText="Uploading..." />

<!-- Other types: ball, bars, infinity -->
```

## Component Props

| Prop | Type | Default | Options |
|------|------|---------|---------|
| `variant` | string | `'primary'` | `primary`, `success`, `error`, `warning`, `info`, `secondary` |
| `size` | string | `'md'` | `xs`, `sm`, `md`, `lg` |
| `loading` | boolean | `false` | `true`, `false` |
| `loadingText` | string | `null` | Any text |
| `loadingType` | string | `'spinner'` | `spinner`, `ring`, `dots`, `ball`, `bars`, `infinity` |
| `square` | boolean | `false` | `true`, `false` |
| `disabled` | boolean | `false` | `true`, `false` |
| `iconPosition` | string | `'left'` | `left`, `right` |
| `type` | string | `'button'` | `button`, `submit`, `reset` |

## Real-World Examples

### 1. Form Submission Button

```blade
<form method="POST" action="{{ route('meal-plans.store') }}">
    @csrf
    
    <!-- Other form fields -->
    
    <x-loading-button 
        variant="success" 
        :loading="false" 
        loadingText="Creating meal plan..."
        type="submit"
        id="submitBtn"
    >
        Create Meal Plan
    </x-loading-button>
</form>

<script>
document.querySelector('form').addEventListener('submit', function() {
    const btn = document.getElementById('submitBtn');
    // The button will show loading state automatically
});
</script>
```

### 2. Dynamic Loading State with Alpine.js

```blade
<div x-data="{ isLoading: false }">
    <x-loading-button 
        variant="primary" 
        :loading="false"
        loadingText="Processing..."
        x-bind:class="isLoading ? 'btn-disabled' : ''"
        @click="isLoading = true; setTimeout(() => isLoading = false, 2000)"
    >
        Click Me
    </x-loading-button>
</div>
```

### 3. Controller-Driven Loading State

In your controller:
```php
public function store(Request $request)
{
    // Processing logic
    return redirect()->route('dashboard')
        ->with('success', 'Meal plan created successfully!');
}
```

In your Blade view:
```blade
<x-loading-button 
    variant="success" 
    :loading="session('processing', false)"
    loadingText="Saving..."
    type="submit"
>
    Save Changes
</x-loading-button>
```

### 4. JavaScript Toggle Example

```blade
<button id="normalBtn" class="btn btn-primary" onclick="startProcess()">
    Start Process
</button>

<button id="loadingBtn" class="btn btn-primary btn-disabled hidden">
    <span class="loading loading-spinner loading-sm"></span>
    <span>Processing...</span>
</button>

<script>
function startProcess() {
    document.getElementById('normalBtn').classList.add('hidden');
    document.getElementById('loadingBtn').classList.remove('hidden');
    
    // Simulate API call
    setTimeout(() => {
        document.getElementById('loadingBtn').classList.add('hidden');
        document.getElementById('normalBtn').classList.remove('hidden');
    }, 3000);
}
</script>
```

## Styling Variants

### Primary (Green)
```blade
<x-loading-button variant="primary" :loading="true" loadingText="Primary" />
```

### Success (Green)
```blade
<x-loading-button variant="success" :loading="true" loadingText="Success" />
```

### Error (Red)
```blade
<x-loading-button variant="error" :loading="true" loadingText="Delete" />
```

### Warning (Yellow)
```blade
<x-loading-button variant="warning" :loading="true" loadingText="Warning" />
```

### Info (Blue)
```blade
<x-loading-button variant="info" :loading="true" loadingText="Information" />
```

### Secondary (Gray)
```blade
<x-loading-button variant="secondary" :loading="true" loadingText="Cancel" />
```

## Size Variations

```blade
<!-- Extra Small -->
<x-loading-button size="xs" :loading="true" loadingText="Tiny" />

<!-- Small -->
<x-loading-button size="sm" :loading="true" loadingText="Small" />

<!-- Medium (default) -->
<x-loading-button :loading="true" loadingText="Medium" />

<!-- Large -->
<x-loading-button size="lg" :loading="true" loadingText="Large" />
```

## Icon Position

```blade
<!-- Icon on left (default) -->
<x-loading-button :loading="true" loadingText="Loading..." />

<!-- Icon on right -->
<x-loading-button :loading="true" loadingText="Loading..." iconPosition="right" />
```

## Use Cases in StudEats

### 1. Meal Plan Creation
```blade
<!-- In meal-plans/create.blade.php -->
<x-loading-button 
    variant="success" 
    type="submit"
    class="w-full"
    id="createMealBtn"
>
    Create Meal Plan
</x-loading-button>
```

### 2. Recipe Deletion
```blade
<!-- In admin/recipes/edit.blade.php -->
<x-loading-button 
    variant="error" 
    :loading="false"
    loadingText="Deleting..."
    loadingType="ring"
>
    <x-icon name="trash" class="w-4 h-4 mr-2" />
    Delete Recipe
</x-loading-button>
```

### 3. Profile Photo Upload
```blade
<!-- In profile/edit.blade.php -->
<x-loading-button 
    variant="primary" 
    :loading="false"
    loadingText="Uploading..."
    loadingType="dots"
    size="sm"
>
    <x-icon name="upload" class="w-4 h-4 mr-2" />
    Upload Photo
</x-loading-button>
```

## Native FlyonUI HTML

If you prefer using native FlyonUI HTML instead of the component:

```html
<!-- Spinner Button -->
<button class="btn btn-primary btn-disabled">
    <span class="loading loading-spinner loading-sm"></span>
    <span>Loading...</span>
</button>

<!-- Square Icon Button -->
<button class="btn btn-success btn-square btn-disabled" aria-label="Loading">
    <span class="loading loading-ring loading-sm"></span>
</button>

<!-- Icon on Right -->
<button class="btn btn-primary btn-disabled">
    <span>Processing</span>
    <span class="loading loading-spinner loading-sm"></span>
</button>
```

## Demo Page

Visit `/loading-buttons-demo` in your browser to see all examples in action:
```
http://localhost/loading-buttons-demo
```

## Animation Types Reference

1. **spinner** - Classic rotating spinner (default)
2. **ring** - Circular ring animation
3. **dots** - Three bouncing dots
4. **ball** - Bouncing ball effect
5. **bars** - Vertical bars animation
6. **infinity** - Infinity symbol animation

## Best Practices

1. **Use descriptive loading text**: Instead of "Loading...", use specific text like "Creating meal plan..." or "Uploading photo..."

2. **Match button variant to action**: 
   - Use `success` for create/submit actions
   - Use `error` for delete/remove actions
   - Use `primary` for general actions

3. **Consider icon position**: Place loading icon on the left for primary actions, right for secondary

4. **Accessibility**: Always include `aria-label` for square icon-only buttons

5. **Disable during loading**: The component automatically disables the button when `loading` is true

## Troubleshooting

### Buttons not showing loading styles
- Ensure you've run `npm run build` after installing FlyonUI
- Check that FlyonUI is in your `tailwind.config.js` plugins

### Custom styling not working
- FlyonUI uses specific class names. You can extend classes with the `class` attribute:
```blade
<x-loading-button class="w-full mt-4" variant="primary" :loading="true" />
```

## Next Steps

1. Replace existing loading states in forms with the new component
2. Update admin panel buttons to use FlyonUI loading buttons
3. Implement consistent loading UX across all user interactions
4. Consider adding loading states to AJAX requests

## Resources

- **FlyonUI Documentation**: https://flyonui.com
- **Demo Page**: `/loading-buttons-demo`
- **Component Location**: `resources/views/components/loading-button.blade.php`
