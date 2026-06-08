#!/usr/bin/env bash
set -e

echo ">>> Starting Laravel application..."

# Ensure temp directory exists
mkdir -p /tmp/php
chmod 1777 /tmp/php

# Fix storage permissions
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Start supervisor
exec /usr/bin/supervisord -c /etc/supervisord.conf