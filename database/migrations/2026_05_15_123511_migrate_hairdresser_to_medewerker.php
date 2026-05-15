<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Step 1: Drop the old foreign key
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropForeign(['hairdresser_id']);
        });

        // Step 2: Rename the column
        Schema::table('appointments', function (Blueprint $table) {
            $table->renameColumn('hairdresser_id', 'medewerker_id');
        });

        // Step 3: Drop the hairdressers table
        Schema::dropIfExists('hairdressers');
    }

    public function down(): void
    {
        // Recreate hairdressers table
        Schema::create('hairdressers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Rename column back
        Schema::table('appointments', function (Blueprint $table) {
            $table->renameColumn('medewerker_id', 'hairdresser_id');
        });

        // Re-add foreign key
        Schema::table('appointments', function (Blueprint $table) {
            $table->foreign('hairdresser_id')->references('id')->on('hairdressers')->onDelete('set null');
        });
    }
};