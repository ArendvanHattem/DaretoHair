#!/usr/bin/env bash
set -e

echo ">>> Running entrypoint.sh..."

# Create PHP temp directory
echo ">>> Creating PHP temp directory..."
mkdir -p /tmp/php
chmod 777 /tmp/php

# Fix permissions
echo ">>> Fixing storage permissions..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 777 /var/www/html/storage/logs

# Set PHP temp directory in environment
echo ">>> Setting TMPDIR environment variable..."
export TMPDIR=/tmp/php

# Ensure the index.php file exists
if [ -f /var/www/html/public/index.php ]; then
    echo ">>> index.php found at /var/www/html/public/index.php"
else
    echo ">>> ERROR: index.php not found!"
    ls -la /var/www/html/public/
fi

echo ">>> Starting Supervisor..."
exec /usr/bin/supervisord -n -c /etc/supervisord.conf