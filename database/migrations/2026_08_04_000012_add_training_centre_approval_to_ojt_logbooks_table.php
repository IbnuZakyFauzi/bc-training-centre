<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ojt_logbooks', function (Blueprint $table) {
            $table->foreignId('training_centre_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('training_centre_notes')->nullable();
            $table->timestamp('training_centre_decided_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('ojt_logbooks', function (Blueprint $table) {
            $table->dropConstrainedForeignId('training_centre_id');
            $table->dropColumn(['training_centre_notes', 'training_centre_decided_at']);
        });
    }
};
