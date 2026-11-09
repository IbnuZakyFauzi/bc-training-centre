<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations for Evaluasi 1 Online Attendance (Presensi In-Class LMS).
     */
    public function up(): void
    {
        Schema::create('eval1_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->date('date');
            $table->time('clock_in')->nullable();
            $table->time('clock_out')->nullable();
            $table->decimal('total_hours', 5, 2)->default(8.00);
            $table->string('topic'); // Sesuai SOP: K3L, Teknis Alat, Defensive Driving & Emergency
            $table->string('status')->default('hadir'); // hadir, izin, sakit
            $table->string('selfie_path')->nullable(); // Foto bukti kehadiran webcam
            $table->string('location')->nullable()->default('Training Centre Berau Coal (In-Class LMS)');
            $table->text('notes')->nullable();
            
            // Verifikasi oleh Instruktur / Trainer
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->text('verification_notes')->nullable();

            $table->timestamps();

            $table->index(['user_id', 'date']);
            $table->index('date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eval1_attendances');
    }
};
