<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ojt_logbooks', function (Blueprint $table) {
            $table->date('date')->nullable()->change();
            $table->enum('shift', ['day', 'night'])->nullable()->default('day')->change();
            $table->string('location')->nullable()->change();
            $table->longText('daily_activity')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('ojt_logbooks', function (Blueprint $table) {
            $table->date('date')->nullable(false)->change();
            $table->enum('shift', ['day', 'night'])->nullable(false)->default('day')->change();
            $table->string('location')->nullable(false)->change();
            $table->longText('daily_activity')->nullable(false)->change();
        });
    }
};
