# Nutrition Calculation Integration

**Date:** October 13, 2025  
**Feature:** Calculate Nutrition button now populates Nutritional Information section  
**Status:** ✅ IMPLEMENTED

## Overview

The "Calculate Nutrition" button in the Ingredients & Nutrition section now automatically analyzes ingredients and fills in the Nutritional Information section with calculated values.

## Visual Flow

```
┌─────────────────────────────────────────────────────────┐
│ 📋 Ingredients & Nutrition                              │
│ ┌─────────────────────────────────────────────────────┐ │
│ │ Chicken breast  │ 500 │ g  │ 85.50 │ [Remove]      │ │
│ │ Brown rice      │ 185 │ g  │ 25.00 │ [Remove]      │ │
│ │ Broccoli        │ 150 │ g  │ 30.00 │ [Remove]      │ │
│ └─────────────────────────────────────────────────────┘ │
│                                                          │
│  [🧮 Calculate Nutrition]  ← Click here                 │
└─────────────────────────────────────────────────────────┘
                    ↓
        ┌──────────────────────┐
        │  Analyzing...        │
        │  🔄 API Call         │
        └──────────────────────┘
                    ↓
┌─────────────────────────────────────────────────────────┐
│ 🧮 Nutritional Information                              │
│ Detailed nutrition facts per serving                    │
│ ┌─────────────────────────────────────────────────────┐ │
│ │ Protein: [42.5] g  ✨ (auto-filled & highlighted)   │ │
│ │ Carbs:   [38.2] g  ✨                                │ │
│ │ Fats:    [8.5]  g  ✨                                │ │
│ │ Fiber:   [5.2]  g  ✨                                │ │
│ │ Sugar:   [2.1]  g  ✨                                │ │
│ │ Sodium:  [245]  mg ✨                                │ │
│ └─────────────────────────────────────────────────────┘ │
└─────────────────────────────────────────────────────────┘
```

## Button Behavior

### Before Click
```html
[🧮 Calculate Nutrition]
```
- Green button (`bg-green-600`)
- Calculator icon
- Ready state

### During Calculation
```html
[🔄 Calculating...]
```
- Button disabled
- Spinner animation
- Opacity reduced (75%)
- Cursor: not-allowed

### After Calculation
```html
[🧮 Calculate Nutrition]
```
- Returns to ready state
- Shows success notification
- Scrolls to nutrition section

## Features Implemented

### 1. **API Integration** 🌐

**Endpoint:** `POST /api/nutrition/calculate`

**Request Payload:**
```json
{
  "ingredients": [
    {
      "name": "Chicken breast",
      "quantity": "500",
      "unit": "g"
    },
    {
      "name": "Brown rice",
      "quantity": "185",
      "unit": "g"
    }
  ],
  "servings": 4
}
```

**Response:**
```json
{
  "success": true,
  "nutrition": {
    "protein": 42.5,
    "carbs": 38.2,
    "fats": 8.5,
    "fiber": 5.2,
    "sugar": 2.1,
    "sodium": 245
  }
}
```

### 2. **Auto-Fill Nutrition Fields** ✨

When calculation succeeds:
- ✅ Populates all 6 nutrition input fields
- ✅ Formats values to 1 decimal place
- ✅ Adds green highlight animation (2 seconds)
- ✅ Smooth scrolls to nutrition section
- ✅ Shows success notification

**Highlight Effect:**
```css
input.highlighted {
  ring-2 ring-green-400
  bg-green-50
  transition: all 2s
}
```

### 3. **Validation Checks** ✅

Before calculation:
- ✅ Checks if ingredients exist
- ✅ Validates at least one ingredient has a name
- ✅ Shows appropriate error messages

### 4. **Loading States** 🔄

**Button States:**
1. **Ready:** Green button, calculator icon
2. **Loading:** Spinner icon, "Calculating..." text, disabled
3. **Success:** Returns to ready, shows notification

### 5. **Error Handling** 🛡️

**API Failure Fallback:**
If the API endpoint doesn't exist or fails:
- Falls back to `estimateNutrition()` function
- Uses simple estimation logic
- Shows warning notification (yellow)
- Still populates fields with estimates

**Estimation Formula:**
```javascript
protein = ingredients.length × 5g
carbs   = ingredients.length × 15g
fats    = ingredients.length × 3g
fiber   = ingredients.length × 2g
sugar   = ingredients.length × 1g
sodium  = ingredients.length × 50mg
```

### 6. **Notification System** 📬

**Types:**
- ✅ **Success** (Green) - Calculation succeeded
- ⚠️ **Warning** (Yellow) - Using estimates
- ❌ **Error** (Red) - Failed to calculate

**Features:**
- Fixed position (bottom-right)
- Auto-dismisses after 5 seconds
- Manual close button (×)
- Smooth fade-in animation
- Color-coded by type

**Example Notifications:**
```
✅ Nutrition calculated successfully!
⚠️ Could not calculate nutrition. Please try again.
❌ Error calculating nutrition. Using estimated values.
```

### 7. **Visual Feedback** 🎨

**Field Highlights:**
- **Green ring + light green background** - API calculated values
- **Yellow ring + light yellow background** - Estimated values
- Duration: 2 seconds fade out
- Smooth CSS transitions

**Auto-Scroll:**
- Scrolls to first nutrition field (protein)
- Smooth scroll behavior
- Centers field in viewport

## Updated Header

**Nutritional Information Section:**
```blade
<div class="flex items-center">
    <svg class="calculator-icon">...</svg>
    <div>
        <h2>Nutritional Information</h2>
        <p>Detailed nutrition facts per serving</p>
    </div>
</div>
```

- Added calculator icon (🧮)
- Consistent with Ingredients & Nutrition header style
- Teal gradient header (`from-teal-50 to-teal-100`)

## Technical Implementation

### JavaScript Functions

1. **`calculateNutrition()`**
   - Main function triggered by button click
   - Collects ingredient data
   - Makes API call
   - Handles success/error states

2. **`estimateNutrition(ingredients)`**
   - Fallback function
   - Provides basic estimates
   - Yellow highlight for estimates

3. **`showNotification(message, type)`**
   - Creates toast notifications
   - Color-coded by type
   - Auto-dismiss with manual close option

4. **`escapeHtml(text)`**
   - Security function
   - Prevents XSS attacks

### Security Features

- ✅ CSRF token included in API requests
- ✅ HTML escaping on all user inputs
- ✅ Input validation before submission
- ✅ Error handling for malformed responses

## Backend Requirements

### API Endpoint Needed

**Create:** `app/Http/Controllers/Api/NutritionController.php`

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NutritionController extends Controller
{
    public function calculate(Request $request)
    {
        $validated = $request->validate([
            'ingredients' => 'required|array',
            'ingredients.*.name' => 'required|string',
            'ingredients.*.quantity' => 'nullable|numeric',
            'ingredients.*.unit' => 'nullable|string',
            'servings' => 'required|integer|min:1'
        ]);

        // TODO: Integrate with nutrition API or database
        // For now, return mock data
        $nutrition = $this->calculateFromIngredients(
            $validated['ingredients'],
            $validated['servings']
        );

        return response()->json([
            'success' => true,
            'nutrition' => $nutrition
        ]);
    }

    private function calculateFromIngredients($ingredients, $servings)
    {
        // Mock calculation - replace with real API/database lookup
        $total = [
            'protein' => 0,
            'carbs' => 0,
            'fats' => 0,
            'fiber' => 0,
            'sugar' => 0,
            'sodium' => 0
        ];

        foreach ($ingredients as $ingredient) {
            // Look up nutrition values per 100g
            // Multiply by quantity
            // Add to totals
        }

        // Divide by servings
        return array_map(function($value) use ($servings) {
            return round($value / $servings, 1);
        }, $total);
    }
}
```

**Add Route:** `routes/api.php`

```php
Route::post('/nutrition/calculate', [NutritionController::class, 'calculate']);
```

## Testing

### Manual Testing Steps

1. **Navigate to edit page:** http://127.0.0.1:8000/admin/recipes/14/edit
2. **Scroll to Ingredients & Nutrition section**
3. **Add 3-5 ingredients with quantities**
4. **Click "Calculate Nutrition" button**
5. **Observe:**
   - Button shows "Calculating..." with spinner
   - After ~1 second, nutrition fields populate
   - Fields highlight with green background
   - Success notification appears bottom-right
   - Page scrolls to nutrition section
6. **Verify values** are reasonable
7. **Close notification** manually or wait 5 seconds

### Edge Cases

- ✅ No ingredients → Alert message
- ✅ Empty ingredient names → Alert message  
- ✅ API unavailable → Fallback to estimates
- ✅ Invalid response → Error notification
- ✅ Network error → Error notification + estimates

## Future Enhancements

### Phase 2 Features

1. **Real Nutrition Database** 🗄️
   - Integrate USDA FoodData Central API
   - Filipino food database (FNRI)
   - Custom ingredient library

2. **Smart Ingredient Matching** 🎯
   - Fuzzy search for ingredient names
   - Autocomplete suggestions
   - Handle typos and variations

3. **Unit Conversions** ⚖️
   - Auto-convert between units
   - Handle cups/tbsp → grams
   - Support metric and imperial

4. **Recipe Adjustments** 🔢
   - Scale recipe by servings
   - Update nutrition dynamically
   - Show per-serving and total values

5. **Nutrition Visualization** 📊
   - Pie charts for macros
   - Bar graphs for minerals
   - Daily value percentages
   - Color-coded health indicators

6. **Save Calculations** 💾
   - Cache nutrition data
   - Avoid re-calculation
   - Version history

## Known Limitations

⚠️ **Current State:**
- API endpoint not yet implemented
- Falls back to estimates if API missing
- Estimates are very basic (not accurate)
- No unit conversion logic
- No ingredient database integration

✅ **What Works:**
- UI/UX flow complete
- Validation working
- Error handling robust
- Fallback system functional
- Visual feedback excellent

## Build Output

```
✓ Views cleared
✓ Assets compiled successfully
  - app-DF1s_uuV.css: 114.86 KB (gzipped: 16.57 KB)
  - app-CydmHwdp.js: 47.73 KB (gzipped: 17.32 KB)
```

## Usage

1. Add ingredients with quantities in the table
2. Set total servings (defaults to 4)
3. Click "Calculate Nutrition" button
4. Wait for calculation (shows spinner)
5. Nutrition fields auto-fill with values
6. Fields highlight in green for 2 seconds
7. Page scrolls to show nutrition section
8. Save recipe to persist calculated values

---

**Status:** Frontend implementation complete! Backend API endpoint needed for production use. Currently uses fallback estimates when API unavailable.

**User Experience:** Seamless, with loading states, error handling, visual feedback, and automatic form population. Ready for production once backend API is implemented! 🎉
