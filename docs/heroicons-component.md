# Heroicons Component Documentation

## Overview
The StudEats application uses a custom Blade component for consistent icon implementation across the interface using Heroicons.

## Component Location
`resources/views/components/icon.blade.php`

## Basic Usage

```blade
<!-- Basic icon with default styling -->
<x-icon name="plus" />

<!-- Icon with custom classes -->
<x-icon name="eye" class="w-6 h-6 text-blue-500" />

<!-- Solid variant icon -->
<x-icon name="check-circle" variant="solid" />

<!-- Icon with custom stroke width (outline only) -->
<x-icon name="pencil" variant="outline" stroke-width="2" />
```

## Available Properties

| Property | Type | Default | Description |
|----------|------|---------|-------------|
| `name` | string | required | The icon name (mapped to Heroicons) |
| `variant` | string | `outline` | Icon style: `outline` or `solid` |
| `class` | string | `w-5 h-5` | CSS classes for styling |
| `stroke-width` | string | `1.5` | Stroke width (outline variant only) |

## Icon Name Mapping

The component includes built-in aliases for common UI patterns:

### Dashboard & Stats
- `clock` - Time/schedule related
- `currency-dollar` - Money/budget displays  
- `bolt` - Energy/calories
- `clipboard-document-list` - Lists/meal plans

### Actions
- `plus` (alias: `add`) - Add/create actions
- `eye` (alias: `view`) - View/show actions
- `pencil` (alias: `edit`) - Edit/modify actions
- `magnifying-glass` (alias: `search`) - Search functionality

### Navigation
- `home` (alias: `dashboard`) - Dashboard/home
- `book-open` (alias: `recipes`) - Recipe browsing
- `calendar-days` (alias: `calendar`) - Calendar/scheduling
- `cog-6-tooth` (alias: `settings`) - Settings/preferences

### Status Indicators
- `check` - Completion/success
- `check-circle` - Verified/completed status
- `x-mark` - Close/cancel
- `exclamation-circle` - Warnings/errors

## Best Practices

### 1. Consistent Sizing
```blade
<!-- Small icons for inline text -->
<x-icon name="clock" class="w-3 h-3" />

<!-- Standard UI icons -->
<x-icon name="plus" class="w-5 h-5" />

<!-- Larger focal icons -->
<x-icon name="calendar-days" class="w-8 h-8" />
```

### 2. Color and State Management
```blade
<!-- Use Tailwind color utilities -->
<x-icon name="heart" class="w-5 h-5 text-red-500" />

<!-- Group hover states -->
<div class="group">
    <x-icon name="star" class="w-4 h-4 text-gray-400 group-hover:text-yellow-500" />
</div>
```

### 3. Accessibility
```blade
<!-- Always include aria-label for interactive icons -->
<button aria-label="Edit meal plan">
    <x-icon name="pencil" class="w-4 h-4" />
</button>

<!-- Use aria-hidden for decorative icons -->
<x-icon name="bolt" class="w-4 h-4" aria-hidden="true" />
```

### 4. Semantic Usage
```blade
<!-- Use appropriate variants -->
<x-icon name="check" variant="solid" class="text-green-600" /> <!-- Completed state -->
<x-icon name="clock" variant="outline" class="text-gray-500" /> <!-- Pending state -->
```

## Dashboard Implementation Examples

### Stats Cards
```blade
<div class="text-green-600 bg-green-100 p-2 rounded-lg">
    <x-icon name="clock" class="w-8 h-8" variant="outline" aria-label="Today's meals icon" />
</div>
```

### Interactive Buttons
```blade
<button class="inline-flex items-center px-2 py-1 text-xs font-medium">
    <x-icon name="eye" class="w-3 h-3 mr-1" variant="outline" />
    View Recipe
</button>
```

### Status Badges
```blade
<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100">
    <x-icon name="check" class="w-3 h-3 mr-1" variant="solid" />
    Completed
</span>
```

## Adding New Icons

To add new icons to the component:

1. Add the icon name to the `$iconMap` array
2. Add the corresponding SVG path in both outline and solid variants
3. Update this documentation with usage examples

## Maintenance Notes

- All icons are from Heroicons v2
- Default classes ensure consistent sizing
- The component automatically handles `aria-hidden="true"` 
- Fallback icon provided for unmapped names
- Component supports all standard HTML attributes via `$attributes->merge()`