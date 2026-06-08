#!/usr/bin/env bash
set -e
cd /var/www/html

echo "Running migrations..."
php artisan migrate --force

echo "Creating sessions table if it doesn't exist..."
php scripts/create_sessions_table.php