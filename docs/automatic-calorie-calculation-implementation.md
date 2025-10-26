# Automatic Calorie Calculation Implementation

## Overview
Implemented automatic calorie calculation that computes calories from nutritional macronutrients (protein, carbs, fats) using standard nutritional formulas.

## Implementation Details

### 1. Backend Implementation

#### NutritionalInfo Model (`app/Models/NutritionalInfo.php`)
- **New Method**: `calculateCaloriesFromMacros()`
  - Formula: `(Protein × 4) + (Carbs × 4) + (Fats × 9)`
  - Returns rounded calorie value

- **Model Boot Hook**: `booted()` static method
  - Automatically calculates and sets calories when saving nutritional info
  - Triggers when any macronutrient values are provided

#### AdminRecipeController (`app/Http/Controllers/Admin/AdminRecipeController.php`)
- **Updated `store()` method**:
  - Auto-calculates calories from macronutrients before creating nutritional info
  - Updates both NutritionalInfo and Meal calories fields
  - Falls back to manual input if no macronutrients provided

- **Updated `update()` method**:
  - Auto-calculates calories from macronutrients when updating
  - Synchronizes calories between Meal and NutritionalInfo models
  - Maintains backward compatibility with manual calorie input

### 2. Frontend Implementation

#### Recipe Edit Page (`resources/views/admin/recipes/edit.blade.php`)
- **New Function**: `calculateCaloriesFromMacros()`
  - Real-time calorie calculation in JavaScript
  - Uses same formula as backend: `(Protein × 4) + (Carbs × 4) + (Fats × 9)`
  - Provides visual feedback with green highlighting
  - Shows success notification when calories are calculated

- **Event Listeners**:
  - `input` event: Calculates calories after 500ms delay (debounced)
  - `blur` event: Immediate calculation when field loses focus
  - Applied to protein, carbs, and fats input fields

- **Integration with Nutrition API**:
  - Auto-calculates calories after API nutrition calculation
  - Maintains consistency between API results and manual input

### 3. Calorie Calculation Formula

**Standard Nutritional Formula:**
- **Protein**: 4 calories per gram
- **Carbohydrates**: 4 calories per gram  
- **Fats**: 9 calories per gram
- **Formula**: `Total Calories = (Protein × 4) + (Carbs × 4) + (Fats × 9)`

### 4. User Experience Features

#### Real-time Calculation
- Calories update automatically as users type nutritional values
- 500ms debounce prevents excessive calculations during typing
- Immediate calculation on field blur for instant feedback

#### Visual Feedback
- Green highlighting when calories are auto-calculated
- Success notifications confirm calculation completion
- Seamless integration with existing form validation

#### Fallback Behavior
- Manual calorie input still supported for edge cases
- Auto-calculation only occurs when macronutrients are provided
- Backward compatibility with existing recipes

### 5. Data Synchronization

#### Model Relationships
- NutritionalInfo model automatically updates calories on save
- AdminRecipeController synchronizes calories between Meal and NutritionalInfo
- Consistent calorie values across all related models

#### API Integration
- Nutrition calculation API results trigger auto-calorie calculation
- Frontend immediately calculates calories after API response
- Maintains accuracy between external nutrition data and calorie values

## Benefits

1. **Accuracy**: Uses standard nutritional formulas for precise calorie calculation
2. **Automation**: Eliminates manual calorie calculation errors
3. **Real-time**: Instant feedback as users enter nutritional data
4. **Consistency**: Synchronized calorie values across all models
5. **User-friendly**: Seamless integration with existing workflow
6. **Reliable**: Fallback to manual input when needed

## Usage

### For Administrators
1. Navigate to recipe edit page (`/admin/recipes/{id}/edit`)
2. Enter protein, carbs, and fats values in nutritional information section
3. Calories are automatically calculated and displayed
4. Save recipe to persist calculated calories

### For API Integration
- Use nutrition calculation API (`/api/nutrition/calculate`)
- Frontend automatically calculates calories from API response
- Consistent results between manual input and API calculation

## Technical Notes

- Calorie calculation occurs both on frontend (immediate feedback) and backend (data persistence)
- Formula follows USDA nutritional guidelines
- Rounded to nearest whole number for practical use
- Backward compatible with existing recipes and manual calorie input