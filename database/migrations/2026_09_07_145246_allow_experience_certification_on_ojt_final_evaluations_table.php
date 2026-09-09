<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'pgsql') {
            return;
        }

        DB::statement('ALTER TABLE "ojt_final_evaluations" DROP CONSTRAINT IF EXISTS "ojt_final_evaluations_jenis_sertifikasi_check"');
        DB::statement("ALTER TABLE \"ojt_final_evaluations\" ADD CONSTRAINT \"ojt_final_evaluations_jenis_sertifikasi_check\" CHECK (\"jenis_sertifikasi\" IN ('Green', 'Skill-up', 'Experience', 'Experience_internal', 'Experience_external'))");
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'pgsql') {
            return;
        }

        DB::statement('ALTER TABLE "ojt_final_evaluations" DROP CONSTRAINT IF EXISTS "ojt_final_evaluations_jenis_sertifikasi_check"');
        DB::statement("ALTER TABLE \"ojt_final_evaluations\" ADD CONSTRAINT \"ojt_final_evaluations_jenis_sertifikasi_check\" CHECK (\"jenis_sertifikasi\" IN ('Green', 'Skill-up', 'Experience'))");
    }
};
