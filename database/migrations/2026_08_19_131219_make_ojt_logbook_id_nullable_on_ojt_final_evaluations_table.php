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
            $table->dropForeign(['ojt_logbook_id']);
            $table->unsignedBigInteger('ojt_logbook_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('ojt_final_evaluations', function (Blueprint $table) {
            $table->unsignedBigInteger('ojt_logbook_id')->nullable(false)->change();
            $table->foreign('ojt_logbook_id')->references('id')->on('ojt_logbooks')->cascadeOnDelete();
        });
    }
};
