<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pricelists', function (Blueprint $table) {
            $table->integer('duration')->default(15)->change();
        });
    }

    public function down(): void
    {
        Schema::table('pricelists', function (Blueprint $table) {
            $table->integer('duration')->default(30)->change();
        });
    }
};