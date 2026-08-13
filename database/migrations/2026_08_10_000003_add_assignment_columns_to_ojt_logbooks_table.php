<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ojt_logbooks', function (Blueprint $table) {
            $table->foreignId('assigned_pjo_id')->nullable()->constrained('users')->nullOnDelete()->after('supervisor_id');
            $table->foreignId('assigned_tc_id')->nullable()->constrained('users')->nullOnDelete()->after('assigned_pjo_id');
        });
    }

    public function down(): void
    {
        Schema::table('ojt_logbooks', function (Blueprint $table) {
            $table->dropForeign(['assigned_tc_id']);
            $table->dropForeign(['assigned_pjo_id']);
            $table->dropColumn(['assigned_tc_id', 'assigned_pjo_id']);
        });
    }
};
