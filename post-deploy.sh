#!/bin/bash

echo "=== StudEats Railway Post-Deploy Setup ==="

# Wait for database connection with timeout
echo "Waiting for database connection..."
TIMEOUT=20
COUNTER=0
until php artisan migrate:status &> /dev/null || [ $COUNTER -ge $TIMEOUT ]; do
    echo "Database not ready, waiting... ($COUNTER/$TIMEOUT)"
    sleep 1
    COUNTER=$((COUNTER + 1))
done

if [ $COUNTER -ge $TIMEOUT ]; then
    echo "Database connection timeout - continuing without migrations"
    exit 0
fi

echo "Database connected - running migrations..."
timeout 30 php artisan migrate --force --no-interaction || echo "Migration completed or timed out"

echo "Setting up essential tables..."
php artisan session:table --no-interaction 2>/dev/null || true
php artisan cache:table --no-interaction 2>/dev/null || true
timeout 15 php artisan migrate --force --no-interaction || true

echo "Post-deploy setup completed"
