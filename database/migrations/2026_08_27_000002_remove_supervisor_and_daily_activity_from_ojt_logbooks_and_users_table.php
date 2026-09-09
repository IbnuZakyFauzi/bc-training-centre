<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ojt_logbooks', function (Blueprint $table) {
            $table->dropForeign(['supervisor_id']);
            $table->dropColumn('supervisor_id');
        });

        Schema::table('ojt_logbooks', function (Blueprint $table) {
            $table->dropColumn('daily_activity');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->enum('role_new', ['trainee', 'trainer', 'admin', 'pjo', 'hse_ct'])->default('trainee');
        });

        DB::table('users')->update(['role_new' => DB::raw('role')]);

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('role_new', 'role');
        });

        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE "ojt_logbooks" DROP CONSTRAINT IF EXISTS "ojt_logbooks_status_check"');
            DB::statement("ALTER TABLE \"ojt_logbooks\" ADD CONSTRAINT \"ojt_logbooks_status_check\" CHECK (\"status\" IN ('draft', 'submitted', 'revision', 'verified', 'final_approved'))");
            DB::statement("ALTER TABLE \"ojt_logbooks\" ALTER COLUMN \"status\" SET DEFAULT 'draft'");
        } else {
            Schema::table('ojt_logbooks', function (Blueprint $table) {
                $table->enum('status', ['draft', 'submitted', 'revision', 'verified', 'final_approved'])->default('draft')->change();
            });
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role_new', ['trainee', 'trainer', 'supervisor', 'admin', 'pjo', 'hse_ct'])->default('trainee');
        });

        DB::table('users')->update(['role_new' => DB::raw('role')]);

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('role_new', 'role');
        });

        Schema::table('ojt_logbooks', function (Blueprint $table) {
            $table->foreignId('supervisor_id')->nullable()->constrained('users')->nullOnDelete();
        });

        Schema::table('ojt_logbooks', function (Blueprint $table) {
            $table->longText('daily_activity');
        });

        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE "ojt_logbooks" DROP CONSTRAINT IF EXISTS "ojt_logbooks_status_check"');
            DB::statement("ALTER TABLE \"ojt_logbooks\" ADD CONSTRAINT \"ojt_logbooks_status_check\" CHECK (\"status\" IN ('draft', 'submitted', 'revision', 'verified', 'supervisor_approved', 'final_approved'))");
            DB::statement("ALTER TABLE \"ojt_logbooks\" ALTER COLUMN \"status\" SET DEFAULT 'draft'");
        } else {
            Schema::table('ojt_logbooks', function (Blueprint $table) {
                $table->enum('status', ['draft', 'submitted', 'revision', 'verified', 'supervisor_approved', 'final_approved'])->default('draft')->change();
            });
        }
    }
};
