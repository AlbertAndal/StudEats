# ⚡ Nutrition API Quick Reference

## 🎯 For Admins

### How to Use the Nutrition Calculator

#### Step-by-Step:
1. Navigate to **Admin → Recipes → Edit Recipe**
2. Add your ingredients with:
   - Name (e.g., "Chicken breast")
   - Quantity (e.g., "500")
   - Unit (e.g., "g")
3. Click **"Calculate Nutrition"** (green button)
4. Wait 2-5 seconds for calculation
5. Review results in summary panel
6. Click **"Apply to Form →"**
7. Save your recipe!

---

## ✅ Supported Units

| Type | Units |
|------|-------|
| Weight | kg, g, lb, oz |
| Volume | L, mL, cup |
| Spoons | tbsp, tsp |
| Count | pcs, pieces |

---

## 🔍 Best Practices

### ✅ DO:
- Use specific names: "chicken breast" not "chicken"
- Use standard units: kg, g, cup, tbsp
- Add all ingredients before calculating
- Verify servings count is correct
- Review calculated values before saving

### ❌ DON'T:
- Use vague names: "meat" or "vegetable"
- Use non-standard units: "handfuls" or "pinches"
- Calculate with empty ingredients
- Forget to set servings
- Save without reviewing results

---

## 🧪 Test Your Setup

**Quick Test URL:**
`http://127.0.0.1:8000/test-nutrition-api`

**What to Test:**
1. Search for "apple" - should find USDA data
2. Calculate 500g chicken breast - should show ~165 cal
3. Create a recipe with 2-3 ingredients - should calculate totals

---

## 🐛 Common Issues

| Problem | Solution |
|---------|----------|
| Button not appearing | Clear cache: `php artisan view:clear` |
| "No food found" | Try different ingredient name (English) |
| Calculation fails | Check internet connection |
| Wrong values | Verify quantity & unit are correct |

---

## 📊 What Gets Calculated

- Calories (kcal)
- Protein (g)
- Carbohydrates (g)
- Fats (g)
- Fiber (g)
- Sugar (g)
- Sodium (mg)

All values calculated **per serving** automatically!

---

## 💡 Pro Tips

1. **Save Time**: Use the calculator for new recipes, skip for simple updates
2. **Accuracy**: The API uses USDA data - very accurate for common foods
3. **Override**: You can manually edit calculated values before saving
4. **Cache**: Second calculation of same ingredient is instant (cached)
5. **Batch**: Add all ingredients first, then calculate once

---

## 🆘 Need Help?

1. Check **full documentation**: `docs/nutrition-api-integration-guide.md`
2. Test the API: `http://127.0.0.1:8000/test-nutrition-api`
3. Review Laravel logs: `storage/logs/laravel.log`
4. Check API status: USDA FoodData Central status page

---

## 🔐 Security Note

The nutrition calculator is **admin-only**. Regular users cannot access the API endpoints or calculator interface.

---

**Quick Setup Reminder:**

Add to `.env`:
```
NUTRITION_API_URL=https://api.nal.usda.gov/fdc/v1
NUTRITION_API_KEY=your_key_here
```

Get your free API key: https://fdc.nal.usda.gov/api-key-signup.html
