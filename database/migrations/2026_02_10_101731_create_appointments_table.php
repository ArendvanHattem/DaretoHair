<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('klant_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('medewerker_id')->constrained('users')->onDelete('cascade');
            $table->string('service');
            $table->dateTime('appointment_date');
            $table->text('notes')->nullable();
            $table->enum('status', ['in afwachting', 'bevestigd', 'geannuleerd'])->default('in afwachting');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
