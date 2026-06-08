#!/usr/bin/env bash
set -e
cd /var/www/html

echo "Running migrations..."
php artisan migrate --force

echo "Checking if sessions table exists..."
if ! php artisan db:table sessions > /dev/null 2>&1; then
    echo "Creating sessions table..."
    php artisan session:table
    php artisan migrate --force
fi