#!/usr/bin/env bash
set -e
cd /var/www/html

echo "Generating application key..."
php artisan key:generate --force

echo "Caching config..."
php artisan config:cache

echo "Caching routes..."
php artisan route:cache

echo "Running migrations..."
php artisan migrate --force