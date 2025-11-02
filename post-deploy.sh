#!/bin/bash
# Run migrations after deployment is healthy
echo "Running post-deployment migrations..."
php artisan migrate --force --no-interaction
echo "Migrations complete!"
