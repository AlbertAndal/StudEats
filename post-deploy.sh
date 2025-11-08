#!/bin/bash
# Run migrations after deployment is healthy
echo "Running post-deployment migrations..."
php artisan migrate --force --no-interaction
echo "Migrations complete!"

# Seed PDRI reference data (ignore if already exists)
echo "Seeding PDRI reference data..."
php artisan db:seed --class=PdriReferenceSeeder --force --no-interaction || echo "PDRI data already exists or seeding skipped"
echo "PDRI data check complete!"

# Create storage symlink
echo "Creating storage symlink..."
php artisan storage:link --force || echo "Storage link already exists"
echo "Storage symlink check complete!"

# Ensure admin account exists and reset password
echo "Setting up default admin account..."
php artisan admin:reset-password admin@studeats.com --no-interaction || echo "Admin setup skipped"
echo "Admin account setup complete!"
