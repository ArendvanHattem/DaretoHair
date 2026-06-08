#!/usr/bin/env bash
set -e
cd /var/www/html

echo "Running migrations..."
php artisan migrate --force

echo "Checking if sessions table exists..."
php artisan tinker --execute="echo 'Table exists: ' . (Illuminate\Support\Facades\Schema::hasTable('sessions') ? 'YES' : 'NO');"

echo "Attempting to create sessions table directly..."
php artisan tinker --execute="
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

echo 'Creating sessions table...\n';
Schema::create('sessions', function (Blueprint \$table) {
    \$table->string('id')->primary();
    \$table->foreignId('user_id')->nullable()->index();
    \$table->string('ip_address', 45)->nullable();
    \$table->text('user_agent')->nullable();
    \$table->longText('payload');
    \$table->integer('last_activity')->index();
});
echo 'Sessions table created.\n';
"

echo "Re-checking if sessions table exists..."
php artisan tinker --execute="echo 'Table exists: ' . (Illuminate\Support\Facades\Schema::hasTable('sessions') ? 'YES' : 'NO');"