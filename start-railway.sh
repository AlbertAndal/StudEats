#!/bin/bash
set -e

echo "🚀 Starting StudEats on Railway..."
echo "📍 Environment: ${APP_ENV:-production}"
echo "🔌 Port: ${PORT:-8000}"

# Display database connection info (without password)
if [ -n "$DATABASE_URL" ]; then
    echo "✅ DATABASE_URL detected"
else
    echo "⚠️  No DATABASE_URL found, using DB_* variables"
    echo "   DB_HOST: ${DB_HOST:-not set}"
    echo "   DB_DATABASE: ${DB_DATABASE:-not set}"
fi

# Clear Laravel caches to use environment variables
echo "📦 Clearing config cache..."
php artisan config:clear || echo "Config clear failed"

# Test database connection
echo "🔍 Testing database connection..."
if php artisan db:show 2>&1 | head -5; then
    echo "✅ Database connection successful"
else
    echo "⚠️  Database connection test failed, continuing anyway..."
fi

# Try to run migrations with timeout
echo "🗄️  Running migrations..."
if timeout 60s php artisan migrate --force --no-interaction 2>&1; then
    echo "✅ Migrations completed successfully"
else
    echo "⚠️  Migrations failed or timed out, continuing..."
fi

# Start the Laravel server
echo "🌐 Starting Laravel server..."
echo "   Binding to 0.0.0.0:${PORT:-8000}"
echo "   Press Ctrl+C to stop"
echo ""

exec php artisan serve --host=0.0.0.0 --port=${PORT:-8000} --no-reload
