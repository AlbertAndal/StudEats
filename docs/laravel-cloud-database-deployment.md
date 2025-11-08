# Database Deployment Guide for Laravel Cloud

## Problem
The production database on Laravel Cloud is missing the new `pdri_references` table and recent migrations, causing 500 errors.

## Solution

### Option 1: Via Laravel Cloud Dashboard
1. Go to Laravel Cloud dashboard: https://cloud.laravel.com
2. Navigate to your StudEats project
3. Go to the "Database" or "Commands" section
4. Run these commands in order:

```bash
php artisan migrate --force
php artisan db:seed --class=PdriReferenceSeeder --force
php artisan cache:clear
php artisan config:clear
```

### Option 2: Via SSH (if available)
1. SSH into your Laravel Cloud server
2. Navigate to your project directory
3. Run the deployment script:

```bash
chmod +x deploy-database.sh
./deploy-database.sh
```

Or run commands manually:
```bash
php artisan migrate --force
php artisan db:seed --class=PdriReferenceSeeder --force
php artisan cache:clear
php artisan config:clear
```

## What This Does

1. **Runs migrations**: Creates the `pdri_references` table and adds `meal_type` column to `meals` table
2. **Seeds PDRI data**: Populates the `pdri_references` table with 24 nutrition reference records
3. **Clears caches**: Ensures all new configurations are loaded

## New Database Tables/Columns

### 1. `pdri_references` table (created by migration `2025_11_08_000001_create_pdri_references_table.php`)
- `id` - Primary key
- `gender` - male/female
- `age_group` - 19-29, 30-59, 60+
- `activity_level` - sedentary, low_active, active, very_active
- `energy_kcal` - Daily calorie recommendation
- `protein_g` - Daily protein in grams
- `carbohydrates_g` - Daily carbs in grams
- `fat_g` - Daily fat in grams
- `fiber_g` - Daily fiber in grams
- `sodium_mg` - Daily sodium limit in mg
- `sugar_g` - Daily sugar limit in grams
- `timestamps`

### 2. `meals` table update (migration `2025_11_07_195554_add_meal_type_to_meals_table.php`)
- Added `meal_type` column (enum: breakfast, lunch, dinner, snack)

## Verification

After deployment, verify the changes:

```bash
# Check if pdri_references table exists
php artisan tinker
>>> DB::table('pdri_references')->count();
>>> exit

# Expected result: 24 records
```

## Troubleshooting

### If migrations fail:
```bash
# Check migration status
php artisan migrate:status

# Rollback if needed (careful in production!)
php artisan migrate:rollback --step=1

# Re-run migrations
php artisan migrate --force
```

### If seeder fails:
```bash
# Check if table exists
php artisan tinker
>>> Schema::hasTable('pdri_references')

# Manually seed
php artisan db:seed --class=PdriReferenceSeeder --force
```

## Important Notes

- The `--force` flag is required in production to bypass confirmation prompts
- These migrations are safe to run multiple times (idempotent)
- The PDRI seeder uses `insert()` which will fail if data already exists
- After deployment, all pages should work without 500 errors
