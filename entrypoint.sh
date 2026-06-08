#!/usr/bin/env bash
set -e

# Set the correct user (usually www-data)
USER_ID=33
GROUP_ID=33

echo "Fixing permissions for /var/www/html/storage and /var/www/html/bootstrap/cache..."
chown -R $USER_ID:$GROUP_ID /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

echo "Starting Supervisor..."
exec /usr/bin/supervisord -n -c /etc/supervisord.conf