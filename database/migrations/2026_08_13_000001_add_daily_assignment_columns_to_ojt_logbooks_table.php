<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ojt_logbooks', function (Blueprint $table) {
            $table->json('selected_pengawas_ids')->nullable()->after('trainer_id');
            $table->json('selected_operator_pendamping_ids')->nullable()->after('selected_pengawas_ids');
        });
    }

    public function down(): void
    {
        Schema::table('ojt_logbooks', function (Blueprint $table) {
            $table->dropColumn(['selected_pengawas_ids', 'selected_operator_pendamping_ids']);
        });
    }
};
