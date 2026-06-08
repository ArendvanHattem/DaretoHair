#!/usr/bin/env bash
set -e  # Stop on error
export COMPOSER_MEMORY_LIMIT=-1

echo "Running composer install (if not already done)..."
cd /var/www/html

echo "Generating application key..."
php artisan key:generate --force

echo "Caching config..."
php artisan config:cache

echo "Caching routes..."
php artisan route:cache

echo "Running migrations..."
php artisan migrate --force