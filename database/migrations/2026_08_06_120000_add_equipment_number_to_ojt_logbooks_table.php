<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ojt_logbooks', function (Blueprint $table) {
            $table->string('equipment_number')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('ojt_logbooks', function (Blueprint $table) {
            $table->dropColumn('equipment_number');
        });
    }
};
