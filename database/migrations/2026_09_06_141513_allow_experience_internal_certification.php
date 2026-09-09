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

        DB::statement('ALTER TABLE "users" DROP CONSTRAINT IF EXISTS "users_certification_check"');
        DB::statement("ALTER TABLE \"users\" ADD CONSTRAINT \"users_certification_check\" CHECK (\"certification\" IN ('Green', 'Skill-up', 'Experience', 'Experience_internal'))");
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'pgsql') {
            return;
        }

        DB::statement('ALTER TABLE "users" DROP CONSTRAINT IF EXISTS "users_certification_check"');
        DB::statement("ALTER TABLE \"users\" ADD CONSTRAINT \"users_certification_check\" CHECK (\"certification\" IN ('Green', 'Skill-up', 'Experience'))");
    }
};
