#!/bin/sh
set -e

echo ">>> Starting Laravel application..."

# Ensure temp directory exists
mkdir -p /tmp/php
chmod 1777 /tmp/php

# Fix storage permissions
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Start supervisor
# Wait for PHP-FPM socket
echo ">>> Waiting for PHP-FPM socket..."
while [ ! -S /var/run/php-fpm.sock ]; do
    sleep 1
done
echo ">>> PHP-FPM socket found."
exec /usr/bin/supervisord -n -c /etc/supervisord.conf