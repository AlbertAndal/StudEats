# Lucide Icon Component Documentation

## Overview
The StudEats application uses a custom Blade component for consistent icon implementation across the interface using Lucide icons.

## Component Location
`resources/views/components/icon.blade.php`

## Basic Usage

```blade
<!-- Basic icon with default styling -->
<x-icon name="plus" />

<!-- Icon with custom classes -->
<x-icon name="eye" class="w-6 h-6 text-blue-500" />

<!-- Icon with custom stroke width -->
<x-icon name="pencil" stroke-width="2" />
```

## Available Properties

| Property | Type | Required | Description |
|----------|------|----------|-------------|
| `name` | string | required | The icon name (mapped to Lucide) |
| `class` | string | optional | CSS classes (default: w-5 h-5) |
| `stroke-width` | string | optional | SVG stroke width (default: 1.5) |
| `variant` | string | optional | Not used in Lucide (kept for compatibility) |

## Icon Name Mapping

The component maps common icon names to Lucide equivalents:

### Dashboard Stats
- `clock` → Clock
- `currency-dollar` → DollarSign
- `bolt` → Zap
- `clipboard-document-list` → ClipboardList

### Actions
- `plus` → Plus
- `eye` → Eye
- `pencil` → Pencil
- `check` → Check
- `x-mark` → X
- `chevron-right` → ChevronRight
- `chevron-left` → ChevronLeft
- `magnifying-glass` → Search

### Navigation
- `home` → Home
- `book-open` → BookOpen
- `calendar-days` → Calendar
- `cog-6-tooth` → Settings
- `bars-3` → Menu

### Content & Status
- `photo` → Image
- `document-text` → FileText
- `heart` → Heart
- `star` → Star
- `check-circle` → CheckCircle
- `exclamation-circle` → AlertCircle

### Meal Type Icons
- `sun` → Sun (Breakfast)
- `fire` → Flame (Lunch)
- `moon` → Moon (Dinner)
- `cake` → Cake (Snack)
- `coffee` → Coffee
- `pizza-slice` → Pizza
- `cookie` → Cookie

## Best Practices

### 1. Consistent Sizing
```blade
<!-- Small icons for inline text -->
<x-icon name="clock" class="w-3 h-3" />

<!-- Standard UI icons -->
<x-icon name="plus" class="w-5 h-5" />

<!-- Larger focal icons -->
<x-icon name="calendar" class="w-8 h-8" />
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

### 4. Stroke Width
```blade
<!-- Lighter icons -->
<x-icon name="clock" stroke-width="1" class="text-gray-400" />

<!-- Standard weight -->
<x-icon name="check" stroke-width="1.5" class="text-green-600" />

<!-- Bolder icons -->
<x-icon name="x-mark" stroke-width="2" class="text-red-600" />
```

## Dashboard Implementation Examples

### Stats Cards
```blade
<div class="text-green-600 bg-green-100 p-2 rounded-lg">
    <x-icon name="clock" class="w-8 h-8" aria-label="Today's meals icon" />
</div>
```

### Interactive Buttons
```blade
<button class="inline-flex items-center px-2 py-1 text-xs font-medium">
    <x-icon name="eye" class="w-3 h-3 mr-1" />
    View Recipe
</button>
```

### Status Badges
```blade
<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100">
    <x-icon name="check" class="w-3 h-3 mr-1" />
    Completed
</span>
```

## Adding New Icons

To add new icons to the component:

1. Add the icon name mapping to the `$iconMap` array
2. Add the corresponding Lucide SVG paths to the switch statement
3. Update this documentation with usage examples

## Migration from Heroicons

This component was migrated from Heroicons to Lucide. Key changes:

- Removed `variant` functionality (all icons are outline-style)
- Updated all SVG paths to use Lucide icons
- Maintained backward compatibility with existing icon names
- All icons now use consistent stroke-based rendering

## Maintenance Notes

- All icons are from Lucide icon library
- Default classes ensure consistent sizing
- The component automatically handles `aria-hidden="true"` 
- Fallback icon provided for unmapped names
- Component supports all standard HTML attributes via `$attributes->merge()`
- All icons use stroke-based rendering for consistency