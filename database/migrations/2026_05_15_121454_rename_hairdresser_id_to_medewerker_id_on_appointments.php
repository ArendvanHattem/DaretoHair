<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
        public function up()
        {
            Schema::table('appointments', function (Blueprint $table) {
                $table->renameColumn('hairdresser_id', 'medewerker_id');
            });
        }

        public function down()
        {
            Schema::table('appointments', function (Blueprint $table) {
                $table->renameColumn('medewerker_id', 'hairdresser_id');
            });
        }
};
