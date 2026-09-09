<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE "users" DROP CONSTRAINT IF EXISTS "users_certification_check"');
            DB::statement("ALTER TABLE \"users\" ADD CONSTRAINT \"users_certification_check\" CHECK (\"certification\" IN ('Green', 'Skill-up', 'Experience', 'Experience_internal'))");
        }

        // Normalisasi kategori Experience tunggal -> Experience_internal (default split)
        DB::table('users')
            ->where('role', 'trainee')
            ->where('certification', 'Experience')
            ->update(['certification' => 'Experience_internal']);

        // Inisialisasi fase awal untuk trainee yang belum memiliki current_phase
        DB::table('users')
            ->where('role', 'trainee')
            ->whereNull('current_phase')
            ->update(['current_phase' => 'evaluasi_3']);
    }

    public function down(): void
    {
        DB::table('users')
            ->where('role', 'trainee')
            ->where('certification', 'Experience_internal')
            ->update(['certification' => 'Experience']);

        DB::table('users')
            ->where('role', 'trainee')
            ->update(['current_phase' => null]);

        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE "users" DROP CONSTRAINT IF EXISTS "users_certification_check"');
            DB::statement("ALTER TABLE \"users\" ADD CONSTRAINT \"users_certification_check\" CHECK (\"certification\" IN ('Green', 'Skill-up', 'Experience'))");
        }
    }
};
