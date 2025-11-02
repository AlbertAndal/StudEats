#!/bin/bash

echo "=== StudEats Emergency Startup ==="

# Minimal setup - just try to start Laravel
export APP_ENV=production
export APP_DEBUG=false
export CACHE_STORE=file
export SESSION_DRIVER=file
export QUEUE_CONNECTION=sync

# Try to clear caches
php artisan config:clear --no-interaction 2>/dev/null || echo "Config clear failed"

# Create basic .env if missing
if [ ! -f .env ]; then
    echo "Creating minimal .env..."
    cat > .env << EOF
APP_ENV=production
APP_DEBUG=false
CACHE_STORE=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
LOG_CHANNEL=errorlog
EOF
fi

# Generate key if needed
if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force --no-interaction 2>/dev/null || echo "Key generation failed"
fi

# Try to start server
echo "Starting server on port ${PORT:-8000}..."
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8000}