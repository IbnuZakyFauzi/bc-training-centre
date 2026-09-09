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
        Schema::table('ojt_logbooks', function (Blueprint $table) {
            $table->decimal('hm_day', 8, 1)->nullable();
            $table->decimal('hm_night', 8, 1)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ojt_logbooks', function (Blueprint $table) {
            $table->dropColumn(['hm_day', 'hm_night']);
        });
    }
};
