#!/bin/bash

echo "=== StudEats Railway Startup ==="
echo "Port: ${PORT:-8000}"
echo "Environment: ${APP_ENV:-production}"

# Minimal .env setup for Railway
if [ ! -f .env ]; then
    echo "Creating minimal .env for Railway..."
    cat > .env << EOF
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:cVdpd4/yHnZtTDWQB+yi351zUDmvNHf/j5pPEgTM4a4=
LOG_CHANNEL=errorlog
LOG_LEVEL=error
SESSION_DRIVER=file
CACHE_STORE=file
EOF
fi

# Quick config clear without timeout
php artisan config:clear --no-interaction 2>/dev/null || true

# Start server immediately - Railway handles database via DATABASE_URL
echo "Starting server on 0.0.0.0:${PORT:-8000}..."
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8000} --no-interaction
