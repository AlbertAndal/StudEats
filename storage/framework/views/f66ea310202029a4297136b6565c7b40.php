<?php
    // Map common icon names to Lucide icons
    $iconMap = [
        // Dashboard Stats
        'clock' => 'Clock',
        'currency-dollar' => 'PesoSign',
        'bolt' => 'Zap',
        'clipboard-document-list' => 'ClipboardList',
        
        // Actions
        'plus' => 'Plus',
        'eye' => 'Eye',
        'pencil' => 'Pencil',
        'pencil-square' => 'Edit',
        'check' => 'Check',
        'x-mark' => 'X',
        'chevron-right' => 'ChevronRight',
        'chevron-left' => 'ChevronLeft',
        'chevron-down' => 'ChevronDown',
        'chevron-up' => 'ChevronUp',
        'chevron-up-down' => 'ChevronsUpDown',
        'magnifying-glass' => 'Search',
        'trash' => 'Trash2',
        'utensils' => 'Utensils',
        'squares-2x2' => 'Grid2X2',
        'banknotes' => 'PesoSign',
        'clock' => 'Clock',
        'users' => 'Users',
        'bell' => 'Bell',
        'chat-bubble-left-ellipsis' => 'MessageCircle',
        
        // Navigation
        'home' => 'Home',
        'book-open' => 'BookOpen',
        'calendar-days' => 'Calendar',
        'cog-6-tooth' => 'Settings',
        'bars-3' => 'Menu',
        
        // Status
        'check-circle' => 'CheckCircle',
        'exclamation-circle' => 'AlertCircle',
        'exclamation-triangle' => 'AlertTriangle',
        'information-circle' => 'Info',
        
        // Content
        'photo' => 'Image',
        'document-text' => 'FileText',
        'heart' => 'Heart',
        'star' => 'Star',
        'rectangle-stack' => 'Layers',
        'tag' => 'Tag',
        
        // Meal type icons
        'sun' => 'Sun',
        'fire' => 'Flame',
        'moon' => 'Moon',
        'cake' => 'Cake',
        'coffee' => 'Coffee',
        'pizza-slice' => 'Pizza',
        'cookie' => 'Cookie',
        
        // Common aliases for backward compatibility
        'add' => 'Plus',
        'edit' => 'Pencil',
        'view' => 'Eye',
        'search' => 'Search',
        'time' => 'Clock',
        'money' => 'DollarSign',
        'energy' => 'Zap',
        'list' => 'ClipboardList',
        'settings' => 'Settings',
        'calendar' => 'Calendar',
        'recipes' => 'BookOpen',
        'dashboard' => 'Home',
        
        // Meal type aliases
        'breakfast' => 'Sun',
        'lunch' => 'Flame', 
        'dinner' => 'Moon',
        'snack' => 'Cake',
    ];
    
    // Get the actual icon name
    $lucideIconName = $iconMap[$name] ?? 'HelpCircle';
    
    // Set default classes
    $defaultClasses = 'w-5 h-5';
    $classes = isset($class) ? $class : $defaultClasses;
    
    // Determine if we should use outline or solid variant
    $variant = $variant ?? 'outline';
    $strokeWidth = $strokeWidth ?? '1.5';
    
    // Handle aria attributes - don't set aria-hidden if aria-label is provided
    $hasAriaLabel = $attributes->has('aria-label');
    $defaultAriaAttributes = $hasAriaLabel ? [] : ['aria-hidden' => 'true'];
    
    // Filter out component-specific attributes that shouldn't be passed to the SVG
    $svgAttributes = $attributes->except(['name', 'variant', 'stroke-width'])->merge(['class' => $classes] + $defaultAriaAttributes);
?>

<svg <?php echo e($svgAttributes); ?> fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="<?php echo e($strokeWidth); ?>">
    <?php switch($lucideIconName):
        case ('Clock'): ?>
            <circle cx="12" cy="12" r="10"/>
            <polyline points="12,6 12,12 16,14"/>
            <?php break; ?>
        <?php case ('DollarSign'): ?>
            <line x1="12" y1="2" x2="12" y2="22" stroke-width="2"/>
            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" stroke-width="2"/>
            <?php break; ?>
        <?php case ('PesoSign'): ?>
            <line x1="12" y1="2" x2="12" y2="22" stroke-width="2"/>
            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" stroke-width="2"/>
            <?php break; ?>
        <?php case ('Zap'): ?>
            <polygon points="13,2 3,14 12,14 11,22 21,10 12,10 13,2"/>
            <?php break; ?>
        <?php case ('ClipboardList'): ?>
            <rect x="8" y="2" width="8" height="4" rx="1" ry="1"/>
            <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/>
            <path d="M12 11h4"/>
            <path d="M12 16h4"/>
            <path d="M8 11h.01"/>
            <path d="M8 16h.01"/>
            <?php break; ?>
        <?php case ('Plus'): ?>
            <circle cx="12" cy="12" r="10"/>
            <line x1="12" y1="8" x2="12" y2="16"/>
            <line x1="8" y1="12" x2="16" y2="12"/>
            <?php break; ?>
        <?php case ('Eye'): ?>
            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
            <circle cx="12" cy="12" r="3"/>
            <?php break; ?>
        <?php case ('Pencil'): ?>
            <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/>
            <?php break; ?>
        <?php case ('Check'): ?>
            <polyline points="20,6 9,17 4,12"/>
            <?php break; ?>
        <?php case ('X'): ?>
            <line x1="18" y1="6" x2="6" y2="18"/>
            <line x1="6" y1="6" x2="18" y2="18"/>
            <?php break; ?>
        <?php case ('ChevronRight'): ?>
            <polyline points="9,18 15,12 9,6"/>
            <?php break; ?>
        <?php case ('ChevronLeft'): ?>
            <polyline points="15,18 9,12 15,6"/>
            <?php break; ?>
        <?php case ('Search'): ?>
            <circle cx="11" cy="11" r="8"/>
            <path d="M21 21l-4.35-4.35"/>
            <?php break; ?>
        <?php case ('Home'): ?>
            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
            <polyline points="9,22 9,12 15,12 15,22"/>
            <?php break; ?>
        <?php case ('BookOpen'): ?>
            <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
            <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
            <?php break; ?>
        <?php case ('Calendar'): ?>
            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
            <line x1="16" y1="2" x2="16" y2="6"/>
            <line x1="8" y1="2" x2="8" y2="6"/>
            <line x1="3" y1="10" x2="21" y2="10"/>
            <?php break; ?>
        <?php case ('Settings'): ?>
            <circle cx="12" cy="12" r="3"/>
            <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1 1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/>
            <?php break; ?>
        <?php case ('Menu'): ?>
            <line x1="3" y1="6" x2="21" y2="6"/>
            <line x1="3" y1="12" x2="21" y2="12"/>
            <line x1="3" y1="18" x2="21" y2="18"/>
            <?php break; ?>
        <?php case ('CheckCircle'): ?>
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
            <polyline points="22,4 12,14.01 9,11.01"/>
            <?php break; ?>
        <?php case ('AlertCircle'): ?>
            <circle cx="12" cy="12" r="10"/>
            <line x1="12" y1="8" x2="12" y2="12"/>
            <line x1="12" y1="16" x2="12.01" y2="16"/>
            <?php break; ?>
        <?php case ('Image'): ?>
            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
            <circle cx="8.5" cy="8.5" r="1.5"/>
            <polyline points="21,15 16,10 5,21"/>
            <?php break; ?>
        <?php case ('FileText'): ?>
            <path d="M14,2H6a2,2 0 0,0 -2,2V20a2,2 0 0,0 2,2H18a2,2 0 0,0 2,-2V8Z"/>
            <polyline points="14,2 14,8 20,8"/>
            <line x1="16" y1="13" x2="8" y2="13"/>
            <line x1="16" y1="17" x2="8" y2="17"/>
            <polyline points="10,9 9,9 8,9"/>
            <?php break; ?>
        <?php case ('Heart'): ?>
            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
            <?php break; ?>
        <?php case ('Star'): ?>
            <polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26 12,2"/>
            <?php break; ?>
        <?php case ('Layers'): ?>
            <polygon points="12,2 2,7 12,12 22,7 12,2"/>
            <polyline points="2,17 12,22 22,17"/>
            <polyline points="2,12 12,17 22,12"/>
            <?php break; ?>
        <?php case ('Tag'): ?>
            <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/>
            <line x1="7" y1="7" x2="7.01" y2="7"/>
            <?php break; ?>
        <?php case ('Sun'): ?>
            <circle cx="12" cy="12" r="5"/>
            <line x1="12" y1="1" x2="12" y2="3"/>
            <line x1="12" y1="21" x2="12" y2="23"/>
            <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/>
            <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/>
            <line x1="1" y1="12" x2="3" y2="12"/>
            <line x1="21" y1="12" x2="23" y2="12"/>
            <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/>
            <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>
            <?php break; ?>
        <?php case ('Flame'): ?>
            <path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 2.5z"/>
            <?php break; ?>
        <?php case ('Moon'): ?>
            <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
            <?php break; ?>
        <?php case ('Cake'): ?>
            <path d="M20 21v-8a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v8"/>
            <path d="M4 16s.5-1 2-1 2.5 2 4 2 2.5-2 4-2 2.5 2 4 2 2-1 2-1"/>
            <path d="M2 21h20"/>
            <path d="M7 8v3"/>
            <path d="M12 8v3"/>
            <path d="M17 8v3"/>
            <?php break; ?>
        <?php case ('Coffee'): ?>
            <path d="M18 8h1a4 4 0 0 1 0 8h-1"/>
            <path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/>
            <line x1="6" y1="1" x2="6" y2="4"/>
            <line x1="10" y1="1" x2="10" y2="4"/>
            <line x1="14" y1="1" x2="14" y2="4"/>
            <?php break; ?>
        <?php case ('Pizza'): ?>
            <path d="M15 11h.01"/>
            <path d="M11 15h.01"/>
            <path d="M16 16h.01"/>
            <path d="M2 16 20 20 20 4 2 8Z"/>
            <?php break; ?>
        <?php case ('Cookie'): ?>
            <path d="M12 2a10 10 0 1 0 10 10 4 4 0 0 1-5-5 4 4 0 0 1-5-5"/>
            <path d="M8.5 8.5v.01"/>
            <path d="M16 15.5v.01"/>
            <path d="M12 12v.01"/>
            <path d="M11 17v.01"/>
            <path d="M7 14v.01"/>
            <?php break; ?>
        <?php default: ?>
            <!-- Fallback help circle icon -->
            <circle cx="12" cy="12" r="10"/>
            <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/>
            <line x1="12" y1="17" x2="12.01" y2="17"/>
    <?php endswitch; ?>
</svg><?php /**PATH C:\xampp\htdocs\StudEats\resources\views/components/icon.blade.php ENDPATH**/ ?>