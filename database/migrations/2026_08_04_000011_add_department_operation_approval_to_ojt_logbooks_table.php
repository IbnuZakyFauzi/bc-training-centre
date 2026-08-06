<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ojt_logbooks', function (Blueprint $table) {
            $table->foreignId('pjo_id')->nullable()->after('supervisor_id')->constrained('users')->nullOnDelete();
            $table->text('pjo_notes')->nullable()->after('revision_notes');
            $table->timestamp('pjo_decided_at')->nullable()->after('approved_at');
        });
    }

    public function down(): void
    {
        Schema::table('ojt_logbooks', function (Blueprint $table) {
            $table->dropConstrainedForeignId('pjo_id');
            $table->dropColumn(['pjo_notes', 'pjo_decided_at']);
        });
    }
};
