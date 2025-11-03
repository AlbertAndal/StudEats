#!/bin/bash

echo "=== StudEats Queue Worker Starting ==="
echo "Environment: ${APP_ENV:-production}"
echo "Queue Connection: ${QUEUE_CONNECTION:-database}"
echo "Mail Host: ${MAIL_HOST:-not-set}"

# Wait for database to be ready
echo "Waiting for database connection..."
timeout=30
while ! php artisan migrate:status > /dev/null 2>&1; do
    sleep 2
    timeout=$((timeout - 2))
    if [ $timeout -le 0 ]; then
        echo "⚠️ Database connection timeout - starting worker anyway"
        break
    fi
done

if [ $timeout -gt 0 ]; then
    echo "✅ Database connected"
fi

# Show mail configuration
echo ""
echo "Mail Configuration:"
echo "- Mailer: ${MAIL_MAILER:-not-set}"
echo "- Host: ${MAIL_HOST:-not-set}"
echo "- Port: ${MAIL_PORT:-not-set}"  
echo "- Username: ${MAIL_USERNAME:-not-set}"
echo "- From: ${MAIL_FROM_ADDRESS:-not-set}"

# Start the queue worker with verbose output
echo ""
echo "Starting Laravel queue worker..."
echo "Command: php artisan queue:work --verbose --tries=3 --timeout=90 --sleep=3"
echo ""

# Run queue worker with restart on failure
exec php artisan queue:work --verbose --tries=3 --timeout=90 --sleep=3 --max-jobs=1000 --max-time=3600
