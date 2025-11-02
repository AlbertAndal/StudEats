#!/bin/bash

echo "🚀 Starting StudEats on Railway..."
echo "📍 Port: ${PORT:-8000}"

# Clear config to use environment variables
php artisan config:clear 2>&1 || true

# Quick database check
echo "🔍 Checking database..."
php artisan migrate:status 2>&1 || echo "⚠️  Database not ready"

# Run migrations (don't fail if it times out)
echo "🗄️  Running migrations..."
php artisan migrate --force --no-interaction 2>&1 || echo "⚠️  Migration warning"

# Start server immediately
echo "🌐 Starting server on 0.0.0.0:${PORT:-8000}"
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8000} --no-reload
