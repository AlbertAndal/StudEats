# Modal System Documentation

## Overview
The StudEats Modal System provides a comprehensive solution for displaying confirmation dialogs, alerts, and interactive modals throughout the application.

## Features

### ✅ **Confirmation Modal on Dashboard**
- **Location**: `http://127.0.0.1:8000/dashboard` (Today's Meals section)
- **Functionality**: Remove meals from today's plan with confirmation
- **Trigger**: Hover over any meal and click the "Remove" button
- **Modal Features**:
  - Shows meal name, type, and icon
  - Animated entrance/exit
  - Proper focus management
  - ESC key support
  - Loading states during deletion

### 🛠 **Reusable Modal Component**
Located at: `resources/views/components/confirmation-modal.blade.php`

#### Usage in Blade Templates:
```php
<x-confirmation-modal 
    id="deleteUserModal" 
    title="Delete User Account" 
    icon="trash" 
    iconColor="red" 
    size="lg">
    
    <div class="bg-gray-50 rounded-lg p-4 mb-4">
        <p class="text-sm text-gray-700">
            This will permanently delete the user account and all associated data.
        </p>
    </div>
    
    <x-slot name="actions">
        <form id="deleteUserForm" method="POST" action="/users/123">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">
                Delete Account
            </button>
        </form>
        <button onclick="hideModal('deleteUserModal')" class="btn btn-secondary">
            Cancel
        </button>
    </x-slot>
</x-confirmation-modal>
```

### 🎯 **JavaScript Modal Utilities**
Located at: `resources/js/modal-utils.js`

#### Basic Usage:
```javascript
// Show existing modal
StudEatsModal.show('myModal', {
    title: 'Custom Title',
    message: 'Custom message content'
});

// Hide modal
StudEatsModal.hide('myModal');

// Programmatic confirmation dialog
const confirmed = await StudEatsModal.confirm({
    title: 'Are you sure?',
    message: 'This action cannot be undone.',
    confirmText: 'Yes, Delete',
    cancelText: 'Cancel',
    icon: 'trash',
    iconColor: 'red'
});

if (confirmed) {
    // User confirmed action
    console.log('User confirmed!');
} else {
    // User cancelled
    console.log('User cancelled');
}
```

#### Advanced Configuration:
```javascript
StudEatsModal.show('advancedModal', {
    animation: true,
    closeOnEscape: true,
    closeOnOverlay: true,
    focusManagement: true,
    animationDuration: 300,
    title: 'Advanced Modal',
    html: '<div class="custom-content">Rich HTML content</div>'
});
```

## Implementation Details

### 1. **Dashboard Integration**
The dashboard now includes meal removal functionality with:
- Hover-to-reveal action buttons
- Contextual meal information in modal
- Proper form handling with CSRF protection
- Loading states during submission
- Success feedback after removal

### 2. **Modal Component Features**
- **Accessibility**: ARIA labels, focus management, keyboard navigation
- **Responsive Design**: Works on mobile and desktop
- **Customizable**: Icons, colors, sizes, and content
- **Animated**: Smooth entrance/exit animations
- **Flexible**: Slot-based content and actions

### 3. **JavaScript Utilities**
- **Promise-based**: Async/await support for confirmations
- **State Management**: Tracks active modals
- **Focus Restoration**: Returns focus to previous element
- **Event Handling**: ESC key, overlay clicks, form submissions
- **Loading Integration**: Works with StudEats loading system

## Usage Examples

### Quick Confirmation:
```javascript
// Simple confirmation
if (await StudEatsModal.confirm({
    title: 'Remove Item?',
    message: 'Are you sure you want to remove this item?'
})) {
    // Handle removal
}
```

### Custom Meal Removal (as implemented in dashboard):
```javascript
function showRemoveMealModal(mealPlanId, mealName, mealType) {
    StudEatsModal.show('removeMealModal', {
        title: 'Remove Meal from Plan?'
    });
    
    // Update content
    document.getElementById('modalMealName').textContent = mealName;
    document.getElementById('modalMealType').textContent = mealType;
    document.getElementById('removeMealForm').action = `/meal-plans/${mealPlanId}`;
}
```

### Form Integration with Loading:
```javascript
document.getElementById('myForm').addEventListener('submit', function(e) {
    const submitBtn = this.querySelector('button[type="submit"]');
    
    // Show loading state using StudEats LoadingUtils
    if (window.LoadingUtils) {
        LoadingUtils.showOnButton(submitBtn, {
            text: 'Removing...',
            size: 'small'
        });
    }
});
```

## Browser Support
- ✅ Modern browsers (Chrome, Firefox, Safari, Edge)
- ✅ Mobile responsive
- ✅ Keyboard navigation
- ✅ Screen reader compatible
- ✅ Animations with reduced motion support

## Testing the Modal

### Dashboard Modal
1. Navigate to `http://127.0.0.1:8000/dashboard`
2. Ensure you're logged in as a user with meal plans
3. Hover over any meal in "Today's Meals" section
4. Click the "Remove" button that appears
5. Observe the confirmation modal with meal details
6. Test both "Yes, Remove Meal" and "Cancel" options

### Meal Plans Index Modal
1. Navigate to `http://127.0.0.1:8000/meal-plans`
2. Ensure you're logged in as a user with meal plans
3. Find any planned meal in the meal type cards
4. Click the "Remove" button in the meal card
5. Observe the confirmation modal asking "Are you sure you want to remove this meal?"
6. Modal shows:
   - Meal name and type
   - Scheduled date
   - Appropriate meal type icon (🌅 breakfast, ☀️ lunch, 🌙 dinner, 🍎 snack)
   - Warning that action cannot be undone
7. Test both "Yes, Remove Meal" and "Cancel" options
8. Loading states during submission
9. ESC key to close modal

The modal system is fully integrated and production-ready across multiple pages! 🚀