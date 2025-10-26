# Bantay Presyo Integration - Quick Start Guide

## 🚀 Quick Setup (5 Minutes)

### Step 1: Seed Initial Ingredients
```bash
php artisan db:seed --class=IngredientSeeder
```
✅ This creates 25+ common Filipino ingredients with Bantay Presyo mappings

### Step 2: Seed Sample Recipes
```bash
php artisan db:seed --class=RecipeIngredientSeeder
```
✅ Creates 5 Filipino recipes with ingredient relationships for real-time pricing

### Step 3: Fetch Latest Prices
```bash
php artisan prices:update
```
✅ Fetches current prices from Bantay Presyo for NCR region

### Step 4: Access Admin Panel
Navigate to: `/admin/market-prices`

✅ View statistics, recent updates, and manage prices

---

## 💡 Common Usage Examples

### Update Prices via Admin Panel
1. Go to `/admin/market-prices`
2. Click **"Update Market Prices"** button
3. Select region (default: NCR)
4. Click **"Update Prices"**

### Update Specific Commodities
```bash
# Rice and Corn only
php artisan prices:update --commodities=1 --commodities=2

# All vegetables (Highland + Lowland)
php artisan prices:update --commodities=6 --commodities=7

# Meat, Fish, and Fruits
php artisan prices:update --commodities=4 --commodities=5 --commodities=8

# Essential commodities (Rice, Vegetables, Meat)
php artisan prices:update --commodities=1 --commodities=6 --commodities=7 --commodities=8
```

### Update Specific Region
```bash
# Central Luzon
php artisan prices:update --region=REGION_III

# Davao Region
php artisan prices:update --region=REGION_XI
```

### Calculate Recipe Cost
```php
$recipe = Recipe::with('ingredientRelations')->find(1);

// Total cost
$totalCost = $recipe->calculateTotalCost('NCR');
echo "Total: ₱" . number_format($totalCost, 2);

// Per serving
$perServing = $recipe->calculateCostPerServing('NCR');
echo "Per Serving: ₱" . number_format($perServing, 2);
```

---

## 🔄 Scheduling Automatic Updates

Add to `app/Console/Kernel.php`:

```php
protected function schedule(Schedule $schedule): void
{
    // Daily at 8 AM Manila time
    $schedule->command('prices:update')
        ->dailyAt('08:00')
        ->timezone('Asia/Manila');
}
```

Then make sure your cron job is running:
```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

---

## 📊 Available Regions

| Code | Region Name |
|------|-------------|
| `NCR` | National Capital Region |
| `CAR` | Cordillera Administrative Region |
| `REGION_I` | Ilocos Region |
| `REGION_III` | Central Luzon |
| `REGION_IV_A` | CALABARZON |
| `REGION_VII` | Central Visayas |
| `REGION_XI` | Davao Region |
| *(and 10 more regions)* |

---

## 🥘 Available Commodity Categories

| ID | Category | Examples |
|----|----------|----------|
| `1` | Rice | Regular milled, Well milled, NFA |
| `2` | Corn | Yellow corn, White corn |
| `4` | Fish | Tilapia, Bangus, Galunggong |
| `5` | Fruits | Banana, Mango, Pineapple |
| `6` | Highland Vegetables | Cabbage, Carrots, Potato |
| `7` | Lowland Vegetables | Tomato, Onion, Garlic |
| `8` | Meat and Poultry | Chicken, Pork, Beef |
| `9` | Spices | Chili, Ginger |
| `10` | Other Commodities | Eggs, Cooking Oil, Sugar |

---

## 🔍 Checking Price Data

### Via Database
```sql
-- Latest prices
SELECT i.name, i.current_price, i.price_updated_at 
FROM ingredients i 
WHERE price_source = 'bantay_presyo' 
ORDER BY price_updated_at DESC;

-- Price history
SELECT i.name, h.price, h.region_code, h.recorded_at 
FROM ingredient_price_history h 
JOIN ingredients i ON h.ingredient_id = i.id 
ORDER BY h.recorded_at DESC 
LIMIT 20;
```

### Via Tinker
```php
php artisan tinker

// Get all ingredients with prices
Ingredient::whereNotNull('current_price')->get();

// Check stale prices
Ingredient::withStalePrices(7)->count();

// Get ingredient price for specific region
$ingredient = Ingredient::find(1);
$price = $ingredient->getPriceForRegion('NCR');
```

---

## ⚠️ Troubleshooting

### "No prices fetched"
```bash
# Check logs
tail -f storage/logs/laravel.log

# Test with verbose mode
php artisan prices:update -v
```

### "Recipe cost is 0"
```php
// Check if recipe has ingredients
$recipe->ingredientRelations->count();

// Check if ingredients have prices
foreach ($recipe->ingredientRelations as $ing) {
    echo "{$ing->name}: {$ing->current_price}\n";
}
```

### "Admin panel not accessible"
- Ensure you're logged in as admin
- Check middleware: `Route::middleware(['auth', 'admin'])`
- Verify user role: `auth()->user()->isAdmin()`

---

## 📞 Quick Help

**Service Class:** `App\Services\BantayPresyoService`  
**Artisan Command:** `php artisan prices:update --help`  
**Admin Route:** `/admin/market-prices`  
**Documentation:** `docs/bantay-presyo-integration-complete.md`  
**Seeder:** `database/seeders/IngredientSeeder.php`

---

## 📈 Current Database Status

| Category | Total | With Prices | Missing Prices | Coverage |
|----------|-------|-------------|----------------|----------|
| **Vegetables** | 28 | 22 | 6 | 78.6% |
| **Meat** | 17 | 10 | 7 | 58.8% |
| **Others** | 16 | 11 | 5 | 68.8% |
| **Rice** | 10 | 8 | 2 | 80.0% |
| **Fruits** | 9 | 7 | 2 | 77.8% |
| **Fish** | 8 | 7 | 1 | 87.5% |
| **TOTAL** | **88** | **65** | **23** | **73.9%** |

---

## ✅ Verification Checklist

- [x] Ingredients seeded (`IngredientSeeder`) - 88 ingredients across 6 categories
- [x] Recipe ingredients seeded (`RecipeIngredientSeeder`) - 5 meals with 14 relationships
- [x] Prices fetched successfully - 65 ingredients with current pricing (73.9% coverage)
- [x] Admin panel accessible at `/admin/market-prices`
- [x] Recipe costs calculating correctly with live pricing
- [x] Advanced search and filtering system implemented
- [x] Real-time price refresh functionality working
- [ ] Scheduled task configured (optional)
- [x] Logs showing no errors

**🎉 You're all set!** The Bantay Presyo integration is ready to automate your menu cost computation.
