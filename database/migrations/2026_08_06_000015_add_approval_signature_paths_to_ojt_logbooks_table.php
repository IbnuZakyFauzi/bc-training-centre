<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ojt_logbooks', function (Blueprint $table) {
            $table->string('trainer_signature_path')->nullable();
            $table->string('pjo_signature_path')->nullable();
            $table->string('training_centre_signature_path')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('ojt_logbooks', function (Blueprint $table) {
            $table->dropColumn([
                'trainer_signature_path',
                'pjo_signature_path',
                'training_centre_signature_path',
            ]);
        });
    }
};
