# Save & Calculate Nutrition Feature

## 🎯 Overview

The recipe edit interface now includes a **"Save & Calculate Nutrition"** button that:
1. Validates the form data
2. Saves the recipe via AJAX
3. Automatically triggers nutrition calculation
4. Provides real-time feedback throughout the process

This ensures ingredients are persisted to the database before calculating nutrition values.

---

## 🆕 What Changed

### Visual Updates

**Before:**
```
[🧮 Calculate Nutrition] (Single button)
```

**After:**
```
[💾 Save & Calculate Nutrition] [🧮 Calculate Only]
Two buttons with clear purposes
```

### Button Layout

The Ingredients & Nutrition section now has two action buttons:

1. **Save & Calculate Nutrition** (Blue button - Primary action)
   - Icon: Save/download arrow
   - Action: Saves recipe → Calculates nutrition
   - Recommended workflow

2. **Calculate Only** (Green button - Secondary action)
   - Icon: Calculator
   - Action: Calculates with current values (no save)
   - Quick preview without committing changes

**Helper text:** "Save first for best results"

---

## 🔄 User Workflow

### Recommended Flow (Save & Calculate):

```
1. User fills in recipe details:
   ├─ Recipe name ✓
   ├─ Description ✓
   ├─ Servings: 4 ✓
   └─ Ingredients:
      ├─ Chicken breast - 200 - g - ₱150
      ├─ Rice - 1 - cup - ₱20
      └─ Broccoli - 150 - g - ₱30

2. User clicks "Save & Calculate Nutrition"
   ↓
3. Frontend validates:
   ├─ Recipe name exists? ✓
   ├─ Servings > 0? ✓
   └─ At least 1 ingredient? ✓

4. Notification: "💾 Saving recipe and calculating nutrition..."
   ↓
5. AJAX request to save recipe
   ↓
6. Backend processes:
   ├─ Validates data
   ├─ Updates meal record
   ├─ Saves ingredient array
   ├─ Logs admin action
   └─ Returns JSON: {success: true}

7. Frontend receives success
   ↓
8. Notification: "✅ Recipe saved successfully! Calculating nutrition..."
   ↓
9. After 500ms delay, calls calculateNutrition()
   ↓
10. API calculates nutrition from saved ingredients
    ↓
11. Fields auto-fill with green highlight
    ↓
12. Final notification: "✅ Nutrition calculated successfully!"
```

### Quick Flow (Calculate Only):

```
1. User has unsaved changes
   ↓
2. Clicks "Calculate Only"
   ↓
3. Calculates with current form values
   ↓
4. Fields auto-fill (no save to database)
```

---

## 💻 Technical Implementation

### Frontend (Blade Template)

**File:** `resources/views/admin/recipes/edit.blade.php`

#### Button HTML (Lines 196-214):

```html
<!-- Action Buttons -->
<div class="flex items-center gap-3 pt-4 border-t border-gray-200">
    <!-- Primary: Save & Calculate -->
    <button type="button" 
            onclick="saveAndCalculateNutrition()"
            class="inline-flex items-center px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
        </svg>
        Save & Calculate Nutrition
    </button>
    
    <!-- Secondary: Calculate Only -->
    <button type="button" 
            onclick="calculateNutrition()"
            class="inline-flex items-center px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
        </svg>
        Calculate Only
    </button>
    
    <span class="text-xs text-gray-500">Save first for best results</span>
</div>
```

#### JavaScript Function (Lines 603-672):

```javascript
function saveAndCalculateNutrition() {
    // 1. Validate required fields
    const recipeName = document.querySelector('input[name="name"]');
    const servings = document.querySelector('input[name="servings"]');
    
    if (!recipeName || !recipeName.value.trim()) {
        showNotification('⚠️ Please enter a recipe name before saving', 'warning');
        recipeName?.focus();
        return;
    }
    
    if (!servings || !servings.value || servings.value < 1) {
        showNotification('⚠️ Please enter number of servings before calculating', 'warning');
        servings?.focus();
        return;
    }
    
    // 2. Check for ingredients
    const names = document.querySelectorAll('input[name="ingredient_names[]"]');
    let hasIngredients = false;
    names.forEach(input => {
        if (input.value.trim()) {
            hasIngredients = true;
        }
    });
    
    if (!hasIngredients) {
        showNotification('⚠️ Please add at least one ingredient', 'warning');
        return;
    }
    
    // 3. Show loading notification
    showNotification('💾 Saving recipe and calculating nutrition...', 'info');
    
    // 4. Get form and submit via AJAX
    const form = document.querySelector('form');
    const formData = new FormData(form);
    
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // 5. Save succeeded
            showNotification('✅ Recipe saved successfully! Calculating nutrition...', 'success');
            
            // 6. Wait 500ms, then calculate
            setTimeout(() => {
                calculateNutrition();
            }, 500);
        } else {
            showNotification('❌ Failed to save recipe: ' + (data.message || 'Unknown error'), 'error');
        }
    })
    .catch(error => {
        console.error('Save error:', error);
        
        // 7. Error fallback - offer to calculate anyway
        if (confirm('Could not save recipe. Calculate nutrition with current values anyway?')) {
            calculateNutrition();
        } else {
            showNotification('❌ Save failed. Please check your inputs and try again.', 'error');
        }
    });
}
```

---

### Backend (Controller)

**File:** `app/Http/Controllers/Admin/AdminRecipeController.php`

#### Update Method Enhancement (Lines 338-352):

```php
AdminLog::createLog(
    Auth::id(),
    'recipe_updated',
    "Updated recipe: {$recipe->name}",
    $recipe
);

// NEW: Return JSON for AJAX requests
if ($request->wantsJson() || $request->ajax()) {
    return response()->json([
        'success' => true,
        'message' => 'Recipe updated successfully!',
        'recipe' => [
            'id' => $recipe->id,
            'name' => $recipe->name,
        ]
    ]);
}

// Traditional redirect for non-AJAX
return redirect()->route('admin.recipes.index')
    ->with('success', 'Recipe updated successfully!');
```

**Key Features:**
- Detects AJAX requests via `$request->wantsJson()` or `$request->ajax()`
- Returns JSON response with success status and recipe data
- Falls back to traditional redirect for normal form submissions
- Maintains backward compatibility

---

## 🎨 Visual Design

### Button Styling

**Primary Button (Save & Calculate):**
- Background: `bg-blue-600` → `hover:bg-blue-700`
- Icon: Save/download arrow (indicates persistence)
- Padding: `px-5 py-2.5` (larger for prominence)
- Shadow: `shadow-sm` → `hover:shadow-md`

**Secondary Button (Calculate Only):**
- Background: `bg-green-600` → `hover:bg-green-700`
- Icon: Calculator (indicates computation)
- Padding: `px-4 py-2.5` (slightly smaller)
- Shadow: `shadow-sm` → `hover:shadow-md`

**Layout:**
- Flexbox with `gap-3` spacing
- Helper text in muted gray
- Border top separator from ingredients table

---

## 🧪 Testing Guide

### Test Case 1: Successful Save & Calculate

**Steps:**
1. Navigate to: http://127.0.0.1:8000/admin/recipes/14/edit
2. Fill in required fields:
   - Name: "Filipino Chicken Adobo"
   - Description: "Classic Filipino dish"
   - Servings: 4
3. Add ingredients:
   - Chicken thigh - 500 - g - ₱200
   - Soy sauce - 3 - tbsp - ₱10
   - Rice - 2 - cup - ₱40
4. Click **"Save & Calculate Nutrition"**

**Expected Results:**
- ✅ Blue notification: "💾 Saving recipe and calculating nutrition..."
- ✅ Green notification: "✅ Recipe saved successfully! Calculating nutrition..."
- ✅ Button shows loading state with spinner
- ✅ Nutrition fields auto-fill with values
- ✅ Green highlight animation on nutrition fields
- ✅ Page scrolls to nutrition section
- ✅ Final notification: "✅ Nutrition calculated successfully!"

**Database Check:**
```sql
SELECT * FROM meals WHERE id = 14;
-- Should show updated name, servings

SELECT * FROM recipes WHERE meal_id = 14;
-- Should show ingredient array with 3 items

SELECT * FROM nutritional_info WHERE meal_id = 14;
-- Should show calculated nutrition values
```

---

### Test Case 2: Validation Errors

**Scenario A: Missing Recipe Name**
1. Clear recipe name field
2. Click "Save & Calculate Nutrition"

**Expected:**
- ⚠️ Yellow notification: "Please enter a recipe name before saving"
- Recipe name field gets focus
- No save attempt

**Scenario B: Missing Servings**
1. Set servings to 0 or empty
2. Click "Save & Calculate Nutrition"

**Expected:**
- ⚠️ Yellow notification: "Please enter number of servings before calculating"
- Servings field gets focus
- No save attempt

**Scenario C: No Ingredients**
1. Remove all ingredient rows or leave names empty
2. Click "Save & Calculate Nutrition"

**Expected:**
- ⚠️ Yellow notification: "Please add at least one ingredient"
- No save attempt

---

### Test Case 3: Calculate Only (No Save)

**Steps:**
1. Make changes to ingredients (don't save)
2. Click **"Calculate Only"** (green button)

**Expected Results:**
- ⏳ Loading state on button
- Calculation uses current form values
- Fields auto-fill with nutrition
- **No database update** (recipe not saved)

**Verification:**
- Check database - changes not persisted
- Refresh page - changes lost
- Nutrition calculation still worked

---

### Test Case 4: Network Error Handling

**Simulate Network Failure:**
1. Open DevTools → Network tab
2. Set throttling to "Offline"
3. Click "Save & Calculate Nutrition"

**Expected:**
- ❌ Confirmation dialog: "Could not save recipe. Calculate nutrition with current values anyway?"
- If user clicks OK → Calculates with current values
- If user clicks Cancel → Red notification "Save failed. Please check your inputs and try again."

---

### Test Case 5: Backend Validation Error

**Trigger Validation Failure:**
1. Edit recipe name to exceed 255 characters
2. Click "Save & Calculate Nutrition"

**Expected:**
- ❌ Red notification: "Failed to save recipe: [validation error message]"
- Form not reset
- User can fix and retry

---

## 📊 User Benefits

### 1. **Data Persistence**
- Ingredients saved before calculation
- No data loss if calculation fails
- Can close browser and return later

### 2. **Streamlined Workflow**
- Single click does both actions
- No need to save manually first
- Reduces steps from 3 to 1

### 3. **Clear Feedback**
- Real-time notifications
- Progress indicators
- Error messages guide fixes

### 4. **Flexibility**
- Can still calculate without saving ("Calculate Only")
- Quick preview mode for testing
- Choose workflow that fits task

### 5. **Error Recovery**
- Graceful handling of failures
- Option to continue even if save fails
- Clear error messages

---

## 🔮 Future Enhancements

### Phase 1: Auto-Save Draft
- [ ] Save draft every 30 seconds
- [ ] Store in browser localStorage
- [ ] Recover after browser crash

### Phase 2: Smart Validation
- [ ] Real-time validation as user types
- [ ] Inline error messages
- [ ] Field-level highlighting

### Phase 3: Batch Operations
- [ ] Save and calculate multiple recipes
- [ ] Bulk nutrition updates
- [ ] Recipe duplication with calculation

### Phase 4: Version History
- [ ] Track recipe versions
- [ ] Compare nutrition between versions
- [ ] Rollback to previous state

### Phase 5: Advanced Feedback
- [ ] Progress bar for multi-step operation
- [ ] Step-by-step status indicators
- [ ] Estimated time remaining

---

## 🐛 Troubleshooting

### Issue: "Failed to save recipe"

**Possible Causes:**
1. Validation errors (check console)
2. CSRF token expired (refresh page)
3. Session timeout (log in again)
4. Server error (check Laravel logs)

**Solutions:**
- Refresh page to get new CSRF token
- Check `storage/logs/laravel.log`
- Verify all required fields filled
- Try "Calculate Only" as workaround

---

### Issue: Save succeeds but calculation fails

**Possible Causes:**
1. API endpoint not responding
2. Ingredients not in database
3. JavaScript error

**Solutions:**
- Check browser console for errors
- Verify API routes: `php artisan route:list --path=api/nutrition`
- Try manual calculation after page refresh
- Check ingredient names match database

---

### Issue: Button stays in loading state

**Possible Causes:**
1. JavaScript error in calculation function
2. Network timeout
3. API not returning response

**Solutions:**
- Refresh page
- Check browser console
- Verify network tab for failed requests
- Check Laravel logs for backend errors

---

## 📝 Code Locations

### Frontend Files:
- **Blade Template:** `resources/views/admin/recipes/edit.blade.php`
  - Button HTML: Lines 196-214
  - JavaScript: Lines 603-672

### Backend Files:
- **Controller:** `app/Http/Controllers/Admin/AdminRecipeController.php`
  - Update method: Lines 205-352
  - AJAX response: Lines 338-352

### Documentation:
- **This file:** `docs/save-and-calculate-feature.md`
- **API docs:** `docs/nutrition-api-backend-implementation.md`
- **Frontend docs:** `docs/nutrition-calculation-integration.md`

---

## ✅ Success Metrics

**Before Implementation:**
- Users had to manually save
- Then manually calculate
- Risk of data loss
- 3 separate steps

**After Implementation:**
- Single click workflow
- Automatic save + calculate
- Data always persisted
- 1 streamlined step

**Improvement:** 66% reduction in user actions! 🎉

---

## 🎓 Developer Notes

### AJAX Detection in Laravel

The controller uses two methods to detect AJAX:

```php
if ($request->wantsJson() || $request->ajax()) {
    // Return JSON
}
```

- `wantsJson()`: Checks `Accept` header for `application/json`
- `ajax()`: Checks for `X-Requested-With: XMLHttpRequest` header

The frontend sets the header explicitly:

```javascript
headers: {
    'X-Requested-With': 'XMLHttpRequest',
}
```

### FormData Handling

FormData automatically includes:
- All form inputs (including file uploads)
- CSRF token (from hidden input)
- Array inputs (ingredient_names[], etc.)

Benefits:
- No manual serialization needed
- File uploads work automatically
- Multipart/form-data encoding

### Error Recovery Pattern

The implementation follows graceful degradation:

```
Try: Save recipe
Success: Calculate nutrition
Fail: Offer to calculate anyway
Cancel: Show error, allow retry
```

This ensures users can always proceed, even if save fails.

---

## 📞 Support

**Questions or issues?**

1. Check browser console for JavaScript errors
2. Check Laravel logs: `storage/logs/laravel.log`
3. Verify routes: `php artisan route:list`
4. Test API directly: See `docs/nutrition-api-backend-implementation.md`

**Common commands:**
```bash
# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# Rebuild assets
npm run build

# View logs
tail -f storage/logs/laravel.log
```

---

## 🎉 Summary

The **Save & Calculate Nutrition** feature provides:

✅ **One-click workflow** - Save and calculate in single action
✅ **Data persistence** - Ingredients saved before calculation
✅ **Real-time feedback** - Notifications guide user through process
✅ **Graceful errors** - Clear messages and recovery options
✅ **Flexible options** - Can still calculate without saving
✅ **AJAX integration** - Smooth experience without page reload

**Result:** Faster workflow, better UX, safer data handling! 🚀
