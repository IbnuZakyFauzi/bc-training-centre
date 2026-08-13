<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('logbook_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ojt_logbook_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('role_type', ['pengawas', 'operator_pendamping', 'instruktur']);
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            $table->unique(['ojt_logbook_id', 'user_id', 'role_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('logbook_assignments');
    }
};
