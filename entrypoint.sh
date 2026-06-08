#!/bin/sh
set -e

echo ">>> Starting Laravel application..."

# Ensure temp directory exists
mkdir -p /tmp/php
chmod 1777 /tmp/php

# Fix storage permissions
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Configure PHP-FPM to use a Unix socket
echo ">>> Configuring PHP-FPM for Unix socket..."
docker-php-ext-enable opcache
cat > /usr/local/etc/php-fpm.d/zz-www.conf <<EOF
[www]
user = www-data
group = www-data
listen = /var/run/php-fpm.sock
listen.owner = www-data
listen.group = www-data
listen.mode = 0660
pm = dynamic
pm.max_children = 5
pm.start_servers = 2
pm.min_spare_servers = 1
pm.max_spare_servers = 3
EOF

# Start supervisor
echo ">>> Starting Supervisor..."
exec /usr/bin/supervisord -n -c /etc/supervisord.conf