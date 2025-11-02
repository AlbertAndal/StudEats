#!/bin/bash

echo "Starting Laravel on port ${PORT:-8000}..."

# Clear config cache
php artisan config:clear 2>&1 || true

# Start server NOW - don't wait for anything
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
