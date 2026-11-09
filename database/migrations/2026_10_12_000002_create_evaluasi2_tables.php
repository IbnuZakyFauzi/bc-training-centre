<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations for Evaluasi 2 (Penilaian Gerakan Dasar / Gerdas, SIMPER L2, & Rekap PT MTL).
     */
    public function up(): void
    {
        // 1. Penilaian Praktek Gerakan Dasar (Gerdas) A2B
        Schema::create('eval2_gerdas_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trainee_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('trainer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('equipment_category_id')->nullable()->constrained('equipment_categories')->nullOnDelete();
            $table->string('unit_model')->nullable(); // misal DT 500, HD 785, PC 1250, dll.
            $table->string('unit_number')->nullable(); // CN / Unit Number
            $table->string('lokasi_kerja')->default('Training Centre Area');
            $table->date('tanggal_penilaian');
            $table->string('shift')->default('day');
            
            // Aspek Penilaian Gerakan Dasar (Detail Rubrik disimpan dalam format JSON untuk fleksibilitas & kekayaan data)
            $table->json('criteria_scores')->nullable();
            
            // Sub-nilai rata-rata aspek
            $table->decimal('score_p2h', 5, 2)->default(0.00); // Pemeriksaan & Perawatan Harian
            $table->decimal('score_basic_control', 5, 2)->default(0.00); // Kontrol Dasar, Pedal & Transmisi
            $table->decimal('score_maneuver', 5, 2)->default(0.00); // Maju, Mundur, Belok Sempit, Parkir
            $table->decimal('score_safety_k3', 5, 2)->default(0.00); // K3LH & Prosedur Keamanan Operasi
            $table->decimal('total_score', 5, 2)->default(0.00); // Skor Akhir (0 - 100)
            
            $table->string('kesimpulan')->default('kompeten'); // kompeten, belum_kompeten
            $table->text('catatan_instruktur')->nullable();
            $table->string('instruktur_signature_path')->nullable();
            $table->string('status')->default('completed'); // draft, completed
            $table->timestamps();
        });

        // 2. Auto-generated Sertifikat SIMPER L2
        Schema::create('eval2_certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trainee_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('assessment_id')->constrained('eval2_gerdas_assessments')->cascadeOnDelete();
            $table->string('certificate_number')->unique();
            $table->string('license_type')->default('SIMPER L2 (Gerakan Dasar / Gerdas)');
            $table->date('issue_date');
            $table->date('valid_until')->nullable();
            $table->decimal('total_score', 5, 2);
            $table->string('status')->default('valid');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eval2_certificates');
        Schema::dropIfExists('eval2_gerdas_assessments');
    }
};
