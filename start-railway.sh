#!/bin/bash
set -e

echo "🚀 Starting StudEats on Railway..."

# Clear Laravel caches to use environment variables
echo "📦 Clearing config cache..."
php artisan config:clear

# Wait briefly for database
echo "⏳ Waiting for database..."
sleep 2

# Try to run migrations
echo "🗄️  Running migrations..."
if php artisan migrate --force --isolated; then
    echo "✅ Migrations completed successfully"
else
    echo "⚠️  Migrations failed, but continuing..."
fi

# Start the Laravel server
echo "🌐 Starting Laravel server on 0.0.0.0:${PORT:-8000}..."
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
