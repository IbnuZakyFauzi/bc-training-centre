<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::getDriverName();

        if ($driver !== 'mysql') {
            return;
        }

        DB::statement("ALTER TABLE ojt_logbooks MODIFY status ENUM('draft','submitted','revision','verified','approved','supervisor_approved','final_approved') NOT NULL DEFAULT 'draft'");

        DB::table('ojt_logbooks')
            ->where('status', 'approved')
            ->whereNull('training_centre_decided_at')
            ->update(['status' => 'supervisor_approved']);

        DB::table('ojt_logbooks')
            ->where('status', 'approved')
            ->whereNotNull('training_centre_decided_at')
            ->update(['status' => 'final_approved']);

        DB::statement("ALTER TABLE ojt_logbooks MODIFY status ENUM('draft','submitted','revision','verified','supervisor_approved','final_approved') NOT NULL DEFAULT 'draft'");
    }

    public function down(): void
    {
        $driver = DB::getDriverName();

        if ($driver !== 'mysql') {
            return;
        }

        DB::statement("ALTER TABLE ojt_logbooks MODIFY status ENUM('draft','submitted','revision','verified','approved','supervisor_approved','final_approved') NOT NULL DEFAULT 'draft'");

        DB::table('ojt_logbooks')
            ->whereIn('status', ['supervisor_approved', 'final_approved'])
            ->update(['status' => 'approved']);

        DB::statement("ALTER TABLE ojt_logbooks MODIFY status ENUM('draft','submitted','revision','verified','approved') NOT NULL DEFAULT 'draft'");
    }
};
