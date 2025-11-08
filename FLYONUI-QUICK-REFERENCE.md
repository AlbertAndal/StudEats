# 🚀 FlyonUI Loading Buttons - Quick Reference

## ✅ Installation Complete
- Package: `flyonui` ✓
- Tailwind Config: Updated ✓
- Component: `<x-loading-button>` ✓
- Demo: `/loading-buttons-demo` ✓

## 🎯 Quick Examples

### Basic
```blade
<x-loading-button variant="primary" :loading="true" loadingText="Loading..." />
```

### Square Icon
```blade
<x-loading-button variant="success" :loading="true" square aria-label="Loading" />
```

### With Different Animations
```blade
<x-loading-button loadingType="spinner" :loading="true" loadingText="Spinner" />
<x-loading-button loadingType="ring" :loading="true" loadingText="Ring" />
<x-loading-button loadingType="dots" :loading="true" loadingText="Dots" />
```

## 📋 Props Quick Reference

```blade
<x-loading-button 
    variant="primary|success|error|warning|info|secondary"
    size="xs|sm|md|lg"
    :loading="true|false"
    loadingText="Custom text"
    loadingType="spinner|ring|dots|ball|bars|infinity"
    :square="true|false"
    :disabled="true|false"
    iconPosition="left|right"
    type="button|submit|reset"
/>
```

## 🎨 Variants
- `primary` - Green (default)
- `success` - Green
- `error` - Red
- `warning` - Yellow
- `info` - Blue
- `secondary` - Gray

## 🔄 Animation Types
- `spinner` ⟳ - Default rotating spinner
- `ring` ○ - Circular ring
- `dots` ··· - Bouncing dots
- `ball` ● - Bouncing ball
- `bars` ║ - Vertical bars
- `infinity` ∞ - Infinity symbol

## 💻 Real Usage

### Form Submit
```blade
<x-loading-button 
    variant="success" 
    type="submit"
    id="submitBtn"
>
    Submit Form
</x-loading-button>
```

### Delete Action
```blade
<x-loading-button 
    variant="error" 
    :loading="false"
    loadingText="Deleting..."
>
    Delete
</x-loading-button>
```

### Icon Only
```blade
<x-loading-button 
    variant="primary" 
    :loading="true"
    square
    aria-label="Processing"
/>
```

## 📍 File Locations
- Component: `resources/views/components/loading-button.blade.php`
- Demo Page: `resources/views/components/loading-button-demo.blade.php`
- Route: `/loading-buttons-demo`
- Config: `tailwind.config.js`

## 🔗 View Demo
Visit: `http://localhost/loading-buttons-demo`
