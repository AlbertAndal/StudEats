# Automatic Recipe Cost Calculation System

**Date:** October 11, 2025  
**Status:** ✅ Fully Operational  
**Data Source:** http://www.bantaypresyo.da.gov.ph/  

## Overview

The StudEats recipe management system automatically calculates the total cost of ingredients using live market prices from the Philippine government's **Bantay Presyo** price monitoring system. The total cost updates dynamically as ingredients are added, modified, or priced.

---

## How It Works

### 1. **Automatic Price Fetching from Bantay Presyo**

When an admin enters an ingredient name (e.g., "Carrots", "Chicken", "Pechay"):

1. **On ingredient name entry** → System triggers `fetchMarketPrice()` function
2. **API call** → POST request to `/api/ingredient-price` with ingredient name
3. **Database lookup** → System checks `ingredients` table for matching name
4. **Price retrieval** → Returns `current_price` and `price_source` from Bantay Presyo data
5. **Display** → Price appears in green with a live indicator ⚡

**Example:**
```
User enters: "Carrots"
System fetches: ₱240.00/kg (from Bantay Presyo)
Display shows: ₱240.00 with green background and live indicator
```

### 2. **Real-Time Total Cost Calculation**

The total cost is calculated automatically using the formula:

```javascript
Total Cost = Σ (Quantity × Market Price)
```

**Triggers:**
- ✅ When ingredient name is entered and price is fetched
- ✅ When quantity is changed
- ✅ When unit is changed  
- ✅ When "Refresh Prices" button is clicked
- ✅ On page load for existing ingredients

**Visual Feedback:**
- Total cost displays in large, bold text
- Color coding based on cost level:
  - **Gray** (₱0): No priced ingredients
  - **Green** (< ₱100): Very affordable
  - **Blue** (₱100-299): Affordable
  - **Orange** (₱300-499): Moderate
  - **Red** (≥ ₱500): Expensive
- Pulse animation when cost updates
- Recipe cost field auto-fills with total

### 3. **Cost Breakdown Display**

Shows ingredient pricing status:
```
X priced • Y pending
```

- **Priced (green):** Ingredients with valid Bantay Presyo prices
- **Pending (orange):** Ingredients without prices (manual price needed)

---

## User Interface Features

### Recipe Edit Page Elements

#### 1. **Ingredient Table**
```
┌────────────────────┬─────┬──────┬───────────────┬────┐
│ Ingredient Name    │ Qty │ Unit │ Price (₱)     │ ✕  │
│                    │     │      │ Bantay Presyo │    │
├────────────────────┼─────┼──────┼───────────────┼────┤
│ Carrots           │ 2   │ kg   │ ₱240.00 ⚡    │ ✕  │
│ Pechay            │ 1   │ kg   │ ₱80.00 ⚡     │ ✕  │
│ Bell Pepper       │ 0.5 │ kg   │ ₱300.00 ⚡    │ ✕  │
└────────────────────┴─────┴──────┴───────────────┴────┘
```

#### 2. **Action Buttons**
- **➕ Add Ingredient** - Adds new ingredient row
- **🔄 Refresh Prices** - Re-fetches all prices from Bantay Presyo
- **🧪 Test API** - Tests API connection with "Carrots" sample
- **ℹ️ Demo** - Auto-fills 4 sample ingredients with live prices

#### 3. **Total Cost Display**
```
┌─────────────────────────────────────┐
│ Estimated Total Cost    🔄 Auto     │
│ ₱620.00                             │
│ Based on live market prices × qty   │
│ 3 priced • 0 pending                │
└─────────────────────────────────────┘
```

---

## Technical Implementation

### Database Schema

**Table: `ingredients`**
```sql
- id (bigint)
- name (varchar) - "Carrots", "Pechay", etc.
- bantay_presyo_name (varchar) - Official Bantay Presyo name
- current_price (decimal) - Live price per unit
- price_source (varchar) - "bantay_presyo"
- price_updated_at (timestamp) - Last update time
- bantay_presyo_commodity_id (int) - API commodity ID
```

**Sample Data:**
| Name | Current Price | Source | Updated |
|------|---------------|--------|---------|
| Carrots | ₱240.00 | bantay_presyo | 2025-10-10 21:42:47 |
| Pechay | ₱80.00 | bantay_presyo | 2025-10-10 21:42:47 |
| Bell Pepper | ₱300.00 | bantay_presyo | 2025-10-10 21:42:47 |
| Cabbage | ₱100.00 | bantay_presyo | 2025-10-10 17:52:21 |

### JavaScript Functions

#### `fetchMarketPrice(input)`
**Purpose:** Fetch price from Bantay Presyo API  
**Trigger:** `onchange` event on ingredient name input  
**Process:**
1. Extract ingredient name from input field
2. Show loading spinner
3. POST request to `/api/ingredient-price` with CSRF token
4. Parse JSON response
5. Update price display with green styling
6. Show live indicator (green dot)
7. Store price in `data-price` attribute
8. Trigger `calculateTotalCost()`

**API Request:**
```javascript
POST /api/ingredient-price
{
  "ingredient_name": "Carrots",
  "region": "NCR"
}
```

**API Response:**
```json
{
  "success": true,
  "price": 240.00,
  "source": "Bantay Presyo - Multiple Markets",
  "updated_at": "2025-10-10 21:42:47"
}
```

#### `calculateTotalCost()`
**Purpose:** Calculate and display total recipe cost  
**Trigger:** 
- After price fetch completes
- On quantity input change
- On page load
- Manual refresh

**Process:**
1. Loop through all `.ingredient-row` elements
2. Extract quantity and price for each row
3. Calculate: `sum += quantity × price`
4. Update `#total-cost` display
5. Apply color coding based on total
6. Add pulse animation
7. Auto-fill recipe cost field
8. Update breakdown counts

**Enhanced Features:**
- **Color Coding:** Visual feedback on cost level
- **Pulse Animation:** Draws attention to updates
- **Auto-Fill:** Recipe cost field syncs automatically
- **Breakdown:** Shows X priced / Y pending status
- **Error Handling:** Graceful handling of missing prices

#### `refreshAllPrices()`
**Purpose:** Re-fetch all ingredient prices  
**Trigger:** "Refresh Prices" button click  
**Process:**
1. Find all ingredient name inputs
2. Loop through each with value
3. Call `fetchMarketPrice()` for each
4. Show loading state on button
5. Display success notification with count

---

## Data Flow Diagram

```
┌─────────────────┐
│ Admin enters    │
│ ingredient name │
└────────┬────────┘
         │
         ▼
┌─────────────────────────┐
│ fetchMarketPrice()      │
│ triggered onchange      │
└────────┬────────────────┘
         │
         ▼
┌─────────────────────────┐
│ POST /api/ingredient-   │
│ price (with CSRF)       │
└────────┬────────────────┘
         │
         ▼
┌──────────────────────────────┐
│ IngredientPriceController    │
│ @getPrice()                  │
└────────┬─────────────────────┘
         │
         ▼
┌──────────────────────────────┐
│ Query ingredients table      │
│ WHERE name LIKE '%input%'    │
└────────┬─────────────────────┘
         │
         ▼
┌──────────────────────────────┐
│ Return JSON:                 │
│ {price, source, updated_at}  │
└────────┬─────────────────────┘
         │
         ▼
┌──────────────────────────────┐
│ Display price in UI          │
│ ₱240.00 with green style ⚡  │
└────────┬─────────────────────┘
         │
         ▼
┌──────────────────────────────┐
│ calculateTotalCost()         │
│ Updates total display        │
└──────────────────────────────┘
```

---

## Price Update Workflow

### Automatic Updates on Page Load

```javascript
document.addEventListener('DOMContentLoaded', function() {
    // Auto-fetch prices for existing ingredients
    setTimeout(() => {
        const ingredientInputs = document.querySelectorAll('.ingredient-name');
        ingredientInputs.forEach(input => {
            if (input.value.trim()) {
                fetchMarketPrice(input);
            }
        });
    }, 500);
});
```

**Result:** When editing an existing recipe, all ingredient prices are automatically fetched from Bantay Presyo within 500ms of page load.

### Manual Refresh

Users can click **🔄 Refresh Prices** to:
- Re-fetch all prices from Bantay Presyo
- Update stale prices (older than 24 hours)
- Verify current market rates

---

## Cost Calculation Examples

### Example 1: Simple Recipe - Pechay Soup

| Ingredient | Qty | Unit | Price (₱/kg) | Subtotal |
|------------|-----|------|--------------|----------|
| Pechay | 1 | kg | 80.00 | ₱80.00 |
| Carrots | 0.5 | kg | 240.00 | ₱120.00 |

**Total Cost:** ₱200.00 (Blue - Affordable)  
**Breakdown:** 2 priced • 0 pending

### Example 2: Complex Recipe - Kare-Kare

| Ingredient | Qty | Unit | Price (₱/kg) | Subtotal |
|------------|-----|------|--------------|----------|
| Oxtail | 1 | kg | 450.00 | ₱450.00 |
| Peanut butter | 0.5 | kg | 200.00 | ₱100.00 |
| Eggplant | 0.3 | kg | 120.00 | ₱36.00 |
| Pechay | 1 | kg | 80.00 | ₱80.00 |

**Total Cost:** ₱666.00 (Red - Expensive)  
**Breakdown:** 4 priced • 0 pending

### Example 3: Partial Pricing

| Ingredient | Qty | Unit | Price (₱/kg) | Subtotal |
|------------|-----|------|--------------|----------|
| Chicken | 1 | kg | 220.00 | ₱220.00 |
| Special Spice | 0.1 | kg | -- | ₱0.00 |
| Carrots | 0.5 | kg | 240.00 | ₱120.00 |

**Total Cost:** ₱340.00 (Orange - Moderate)  
**Breakdown:** 2 priced • 1 pending  
**Note:** "Special Spice" has no Bantay Presyo price, so it's excluded from total

---

## Benefits & Features

### ✅ Benefits

1. **Real-Time Accuracy**
   - Prices from official Philippine government source
   - Updated daily/weekly from market surveys
   - Reflects actual market conditions

2. **Time Savings**
   - No manual price entry needed
   - Automatic calculation eliminates math errors
   - One-click refresh for all ingredients

3. **Budget Planning**
   - Students can see exact recipe costs
   - Filter recipes by budget (under ₱100, ₱200, ₱500)
   - Plan weekly meals within budget constraints

4. **Data Integrity**
   - Recipe cost field auto-syncs with ingredient total
   - Visual feedback prevents outdated prices
   - Breakdown shows which items lack prices

### 🎯 Key Features

- ✨ **Auto-fetch** - Prices load automatically on name entry
- 🔄 **Live Updates** - Total recalculates instantly on any change
- 🎨 **Color Coding** - Visual cost level indicators
- 📊 **Breakdown** - Shows priced vs pending ingredient counts
- 💚 **Green Indicators** - Live price badges for visual confirmation
- 🧪 **Test Mode** - Built-in API testing and demo functionality
- 🔐 **Secure** - CSRF token protection on all API calls

---

## Troubleshooting

### Issue: Price shows "N/A"

**Possible Causes:**
1. Ingredient not in Bantay Presyo database
2. Misspelled ingredient name
3. API connection issue

**Solutions:**
- ✅ Try alternate spelling: "Bell Pepper" vs "Green Bell Pepper"
- ✅ Click **Test API** to verify connection
- ✅ Check database for similar ingredient names
- ✅ Manually add price via admin panel if needed

### Issue: Total cost not updating

**Possible Causes:**
1. Price not fetched yet (still loading)
2. Quantity field empty
3. JavaScript error in console

**Solutions:**
- ✅ Wait for green live indicator (⚡) to appear
- ✅ Click **Refresh Prices** to re-fetch
- ✅ Check browser console for errors (F12)
- ✅ Verify quantity field has numeric value

### Issue: Cost field not auto-filling

**Possible Causes:**
1. Total cost is ₱0.00
2. No ingredients with prices

**Solutions:**
- ✅ Ensure at least one ingredient has price and quantity
- ✅ Check if prices show green (not gray/red)
- ✅ Manually refresh prices if needed

---

## API Endpoint Details

### POST /api/ingredient-price

**Purpose:** Fetch current market price for an ingredient

**Authentication:** None (public endpoint, was previously auth-protected)

**Headers:**
```
Content-Type: application/json
X-CSRF-TOKEN: {token}
Accept: application/json
```

**Request Body:**
```json
{
  "ingredient_name": "Carrots",
  "region": "NCR"
}
```

**Success Response (200):**
```json
{
  "success": true,
  "ingredient_name": "Carrots",
  "price": 240.00,
  "unit": "kg",
  "source": "Bantay Presyo - Multiple Markets",
  "updated_at": "2025-10-10 21:42:47",
  "region": "NCR"
}
```

**Not Found Response (200):**
```json
{
  "success": false,
  "message": "Ingredient not found in price database",
  "ingredient_name": "Rare Spice"
}
```

**Error Response (500):**
```json
{
  "success": false,
  "message": "Server error message"
}
```

---

## Related Files

### Views
- `resources/views/admin/recipes/edit.blade.php` - Main recipe edit interface
- `resources/views/admin/recipes/create.blade.php` - Recipe creation (similar pricing)
- `resources/views/admin/market-prices/index.blade.php` - Price management

### Controllers
- `app/Http/Controllers/Api/IngredientPriceController.php` - Price API endpoint
- `app/Http/Controllers/Admin/RecipeController.php` - Recipe management

### Models
- `app/Models/Ingredient.php` - Ingredient with price tracking
- `app/Models/Recipe.php` - Recipe with cost calculation

### Routes
- `routes/web.php` - API route: `POST /api/ingredient-price`

### Documentation
- `docs/market-pricing-status.md` - Bantay Presyo integration guide
- `docs/ingredient-validation-implementation.md` - Form validation guide

---

## Future Enhancements

### Planned Features

1. **Price History Tracking**
   - Show price trends over time
   - Alert when prices spike significantly
   - Historical cost comparisons

2. **Regional Pricing**
   - Support multiple regions (NCR, Visayas, Mindanao)
   - Regional cost variations
   - Cheapest region indicators

3. **Substitute Suggestions**
   - Recommend cheaper alternatives
   - "Use X instead of Y to save ₱Z"
   - Maintain similar nutrition profile

4. **Bulk Recipe Costing**
   - Calculate costs for multiple recipes
   - Weekly meal plan budgets
   - Monthly food cost projections

5. **Export & Reporting**
   - Export cost breakdown to PDF/Excel
   - Shopping list with estimated costs
   - Budget vs actual tracking

---

## Summary

The automatic cost calculation system provides:

✅ **Real-time pricing** from Philippine government Bantay Presyo database  
✅ **Automatic calculation** of total recipe costs (quantity × price)  
✅ **Dynamic updates** when ingredients or quantities change  
✅ **Visual feedback** with color coding and live indicators  
✅ **Cost breakdown** showing priced vs unpriced ingredients  
✅ **Auto-sync** with recipe cost field  
✅ **Test & demo** tools for verification  

**Result:** Admins can create recipes with accurate, up-to-date costs without manual calculations, and students can see exactly how much each meal will cost based on current market prices.

---

**Last Updated:** October 11, 2025  
**System Status:** ✅ Operational  
**Data Source:** http://www.bantaypresyo.da.gov.ph/  
**Average Price Update Frequency:** Daily/Weekly from Bantay Presyo
