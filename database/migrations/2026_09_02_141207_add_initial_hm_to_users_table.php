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
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('initial_hm_day', 10, 1)->default(0)->after('equipment_number');
            $table->decimal('initial_hm_night', 10, 1)->default(0)->after('initial_hm_day');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['initial_hm_day', 'initial_hm_night']);
        });
    }
};
