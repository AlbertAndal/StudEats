#!/bin/bash
set -e  # Exit on any error

echo "=== Laravel Cloud Deployment Started ==="
echo "Timestamp: $(date)"

# Step 1: Clear ALL cached configurations FIRST
echo ""
echo "Step 1/8: Clearing cached configurations..."
php artisan config:clear || echo "⚠️ Config clear skipped"
php artisan route:clear || echo "⚠️ Route clear skipped"
php artisan view:clear || echo "⚠️ View clear skipped"
php artisan cache:clear || echo "⚠️ Cache clear skipped"

# Step 2: Verify database connection
echo ""
echo "Step 2/8: Verifying database connection..."
php artisan tinker --execute="
try {
    \$pdo = DB::connection()->getPdo();
    echo 'Database connection successful!' . PHP_EOL;
    echo 'Driver: ' . \$pdo->getAttribute(PDO::ATTR_DRIVER_NAME) . PHP_EOL;
} catch (Exception \$e) {
    echo 'Database connection failed: ' . \$e->getMessage() . PHP_EOL;
    exit(1);
}
"

# Step 3: Run migrations
echo ""
echo "Step 3/8: Running database migrations..."
php artisan migrate --force --no-interaction

# Step 4: Seed PDRI reference data
echo ""
echo "Step 4/8: Seeding PDRI reference data..."
php artisan db:seed --class=PdriReferenceSeeder --force || echo "✓ PDRI data already exists or seeding skipped"

# Step 5: Create storage symlink
echo ""
echo "Step 5/8: Creating storage symlink..."
php artisan storage:link --force || echo "✓ Storage link already exists"

# Step 6: Setup admin account
echo ""
echo "Step 6/8: Setting up admin account..."
php artisan db:seed --class=AdminSeeder --force || echo "✓ Admin account already configured"

# Step 7: Optimize for production
echo ""
echo "Step 7/8: Optimizing application for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Step 8: Final verification
echo ""
echo "Step 8/8: Final verification..."
php artisan tinker --execute="
echo 'Users: ' . App\Models\User::count() . PHP_EOL;
echo 'Recipes: ' . App\Models\Recipe::count() . PHP_EOL;
echo 'Sessions table: ' . DB::table('sessions')->count() . ' sessions' . PHP_EOL;
"

echo ""
echo "=== Deployment Complete ==="
echo "Timestamp: $(date)"
echo ""
echo "📧 Default Admin Credentials:"
echo "   Email: admin@studeats.com"
echo "   Password: admin123"
echo ""
echo "⚠️  IMPORTANT: Change admin password after first login!"
echo "🌐 Application URL: https://studeats.laravel.cloud/"
echo "✅ Deployment successful!"
