#!/bin/bash
# Laravel Cloud Deployment Hook
# This script runs during Laravel Cloud deployment to ensure proper setup

set -e  # Exit on any error, but with proper error handling

echo "🚀 Laravel Cloud Post-Deploy Hook Starting..."
echo "Timestamp: $(date)"

# Function to run commands with error handling
run_command() {
    echo "Running: $1"
    if eval $1; then
        echo "✅ Success: $1"
    else
        echo "⚠️ Warning: $1 failed, continuing..."
    fi
}

# Clear caches individually with error handling
echo ""
echo "📦 Clearing application caches..."
run_command "php artisan config:clear"
run_command "php artisan cache:clear"
run_command "php artisan route:clear"

# Handle view:clear separately since it can fail on fresh deployments
echo ""
echo "🗂️ Clearing view cache..."
if [ -d "storage/framework/views" ]; then
    run_command "php artisan view:clear"
else
    echo "⚠️ Views directory doesn't exist, creating it..."
    mkdir -p storage/framework/views
    chmod 755 storage/framework/views
fi

# Ensure critical directories exist
echo ""
echo "📁 Ensuring critical directories exist..."
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs
chmod -R 755 storage/

# Run migrations
echo ""
echo "🗄️ Running migrations..."
run_command "php artisan migrate --force --no-interaction"

# Create storage link
echo ""
echo "🔗 Creating storage symlink..."
run_command "php artisan storage:link --force"

# Verify symlink points to expected target
echo "🧪 Verifying storage symlink integrity..."
if [ -L "public/storage" ]; then
    TARGET=$(readlink public/storage || echo "(unreadable)")
    EXPECTED="$(pwd)/storage/app/public"
    echo "   -> Symlink target: $TARGET"
    echo "   -> Expected:      $EXPECTED"
    if [ "$TARGET" != "$EXPECTED" ]; then
        echo "   ⚠️  Symlink target mismatch. Recreating..."
        rm public/storage || true
        run_command "php artisan storage:link --force"
    else
        echo "   ✅ Symlink target correct"
    fi
else
    echo "   ❌ Symlink missing after creation attempt. Retrying..."
    run_command "php artisan storage:link --force"
fi

# Quick meal image sample check (first record)
echo "🔍 Sampling a meal image path for existence check..."
php artisan tinker --execute="if(\App\Models\Meal::whereNotNull('image_path')->exists()){ $m=\App\Models\Meal::whereNotNull('image_path')->first(); echo 'Sample Meal ID: '.$m->id."\n"; echo 'Image Path: '.$m->image_path."\n"; echo 'Disk Exists: '.(Storage::disk('public')->exists($m->image_path)?'YES':'NO')."\n"; } else { echo 'No meals with images found.'; }" || echo "⚠️ Meal image sample check failed"


# Seed essential data
echo ""
echo "🌱 Seeding essential data..."
run_command "php artisan db:seed --class=AdminSeeder --force"
run_command "php artisan db:seed --class=PdriReferenceSeeder --force"

# Ensure admin account exists and is accessible
echo ""
echo "👤 Ensuring admin account is ready..."
run_command "php artisan admin:create --reset"

# Optimize for production
echo ""
echo "⚡ Optimizing for production..."
run_command "php artisan config:cache"
run_command "php artisan route:cache"

# Clear caches one more time to ensure session config is fresh
echo ""
echo "🔄 Final cache refresh for session configuration..."
run_command "php artisan config:clear"
run_command "php artisan config:cache"

# Only cache views if we can clear them successfully
if php artisan view:clear > /dev/null 2>&1; then
    run_command "php artisan view:cache"
else
    echo "⚠️ Skipping view cache due to clearing issues"
fi

# Final health check
echo ""
echo "🏥 Health check..."
if php artisan tinker --execute="echo 'Database connection: '; try { DB::connection()->getPdo(); echo 'OK'; } catch(Exception \$e) { echo 'FAILED'; }" 2>/dev/null; then
    echo "✅ Database connection healthy"
else
    echo "❌ Database connection issues detected"
fi

echo ""
echo "✅ Laravel Cloud deployment completed successfully!"
echo "🌐 Application ready at: https://studeats.laravel.cloud"
echo ""
echo "📧 Admin Login:"
echo "   URL: https://studeats.laravel.cloud/admin/login"
echo "   Email: admin@studeats.com"
echo "   Password: admin123"
echo ""
echo "⚠️ IMPORTANT: Change admin password after first login!"