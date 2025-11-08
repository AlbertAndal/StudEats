#!/bin/bash
# Run migrations after deployment is healthy
echo "Running post-deployment migrations..."
php artisan migrate --force --no-interaction
echo "Migrations complete!"

# Seed PDRI reference data
echo "Seeding PDRI reference data..."
php artisan db:seed --class=PdriReferenceSeeder --force --no-interaction
echo "PDRI data seeded!"

# Create storage symlink
echo "Creating storage symlink..."
php artisan storage:link --force
echo "Storage symlink created!"

# Ensure admin account exists and reset password
echo "Setting up default admin account..."
php artisan admin:reset-password admin@studeats.com --no-interaction
echo "Admin account setup complete!"
