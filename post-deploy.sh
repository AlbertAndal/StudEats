#!/bin/bash
# Run migrations after deployment is healthy
echo "Running post-deployment migrations..."
php artisan migrate --force --no-interaction
echo "Migrations complete!"

# Create storage symlink
echo "Creating storage symlink..."
php artisan storage:link --force
echo "Storage symlink created!"
