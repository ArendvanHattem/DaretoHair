#!/usr/bin/env bash
set -e

echo ">>> Running entrypoint.sh..."

# Fix permissions - try multiple approaches
echo ">>> Fixing storage permissions..."

# Try with www-data user first, then fallback to 33, then 1000
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || \
chown -R 33:33 /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || \
chown -R 1000:1000 /var/www/html/storage /var/www/html/bootstrap/cache

chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Create and set permissions for laravel.log specifically
touch /var/www/html/storage/logs/laravel.log
chmod 666 /var/www/html/storage/logs/laravel.log

# Also set permissions on the entire storage directory
chmod -R 777 /var/www/html/storage

echo ">>> Starting Supervisor..."
exec /usr/bin/supervisord -n -c /etc/supervisord.conf