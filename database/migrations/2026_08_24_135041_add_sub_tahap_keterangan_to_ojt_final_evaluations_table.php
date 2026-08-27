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
        Schema::table('ojt_final_evaluations', function (Blueprint $table) {
            $table->string('sub_tahap_keterangan')->nullable()->after('sub_tahap');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ojt_final_evaluations', function (Blueprint $table) {
            $table->dropColumn('sub_tahap_keterangan');
        });
    }
};
