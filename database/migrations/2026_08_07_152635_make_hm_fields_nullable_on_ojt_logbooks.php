<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ojt_logbooks', function (Blueprint $table) {
            $table->decimal('hm_start', 8, 1)->nullable()->default(0)->change();
            $table->decimal('hm_end', 8, 1)->nullable()->default(0)->change();
        });
    }

    public function down(): void
    {
        Schema::table('ojt_logbooks', function (Blueprint $table) {
            $table->decimal('hm_start', 8, 1)->nullable(false)->default(0)->change();
            $table->decimal('hm_end', 8, 1)->nullable(false)->default(0)->change();
        });
    }
};
