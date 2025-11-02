#!/bin/bash

echo "Starting Laravel on port ${PORT:-8000}..."

# Don't need .env file - Railway provides environment variables directly
# Clear config cache to use environment variables
php artisan config:clear 2>&1 || true

# Start server NOW
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
