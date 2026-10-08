<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'department')) {
                $table->string('department')->nullable()->after('company');
            }
        });

        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE "users" DROP CONSTRAINT IF EXISTS "users_certification_check"');
            DB::statement("ALTER TABLE \"users\" ADD CONSTRAINT \"users_certification_check\" CHECK (\"certification\" IN ('Green', 'Skill-up', 'Experience', 'Experience_internal', 'Experience_external'))");
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'department')) {
                $table->dropColumn('department');
            }
        });

        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE "users" DROP CONSTRAINT IF EXISTS "users_certification_check"');
            DB::statement("ALTER TABLE \"users\" ADD CONSTRAINT \"users_certification_check\" CHECK (\"certification\" IN ('Green', 'Skill-up', 'Experience', 'Experience_internal'))");
        }
    }
};
