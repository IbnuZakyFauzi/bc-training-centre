<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ojt_final_evaluations', function (Blueprint $table) {
            $table->string('phase')->nullable();
            $table->string('status')->default('submitted');

            $table->foreignId('tc_approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('tc_approved_at')->nullable();
            $table->text('tc_notes')->nullable();

            $table->foreignId('pjo_approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('pjo_approved_at')->nullable();
            $table->text('pjo_notes')->nullable();

            $table->foreignId('hse_approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('hse_approved_at')->nullable();
            $table->text('hse_notes')->nullable();

            $table->timestamp('completed_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('ojt_final_evaluations', function (Blueprint $table) {
            $table->dropForeign(['tc_approved_by']);
            $table->dropForeign(['pjo_approved_by']);
            $table->dropForeign(['hse_approved_by']);
            $table->dropColumn([
                'phase',
                'status',
                'tc_approved_by',
                'tc_approved_at',
                'tc_notes',
                'pjo_approved_by',
                'pjo_approved_at',
                'pjo_notes',
                'hse_approved_by',
                'hse_approved_at',
                'hse_notes',
                'completed_at',
            ]);
        });
    }
};
