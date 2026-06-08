#!/usr/bin/env bash
set -e
cd /var/www/html

echo "Running migrations..."
php artisan migrate --force

echo "Ensuring sessions table exists (bypassing migration system)..."
php artisan tinker --execute="
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

if (!Schema::hasTable('sessions')) {
    Schema::create('sessions', function (Blueprint \$table) {
        \$table->string('id')->primary();
        \$table->foreignId('user_id')->nullable()->index();
        \$table->string('ip_address', 45)->nullable();
        \$table->text('user_agent')->nullable();
        \$table->longText('payload');
        \$table->integer('last_activity')->index();
    });
    echo 'Sessions table created successfully.\n';
} else {
    echo 'Sessions table already exists.\n';
}
"