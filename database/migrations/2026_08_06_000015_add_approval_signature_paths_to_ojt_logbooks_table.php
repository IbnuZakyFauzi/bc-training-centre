<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ojt_logbooks', function (Blueprint $table) {
            $table->string('trainer_signature_path')->nullable()->after('training_centre_decided_at');
            $table->string('pjo_signature_path')->nullable()->after('trainer_signature_path');
            $table->string('training_centre_signature_path')->nullable()->after('pjo_signature_path');
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
