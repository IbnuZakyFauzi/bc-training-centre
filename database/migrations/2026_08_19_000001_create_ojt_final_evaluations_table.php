<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ojt_final_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ojt_logbook_id')->constrained()->cascadeOnDelete();
            $table->foreignId('trainer_id')->constrained('users')->cascadeOnDelete();
            $table->string('nama_operator');
            $table->string('perusahaan');
            $table->string('lokasi_kerja');
            $table->string('jenis_unit_a2b');
            $table->enum('jenis_sertifikasi', ['Green', 'Skill-up', 'Experience']);
            $table->string('instruktur');
            $table->string('operator_pendamping');
            $table->date('tanggal_penilaian');
            $table->enum('tahap_penilaian', ['pendampingan', 'tanpa_pendampingan']);
            $table->string('sub_tahap')->nullable();
            $table->enum('p2h_status', ['K', 'BK']);
            $table->enum('teknik_pengoperasian_status', ['K', 'BK']);
            $table->enum('kepatuhan_status', ['K', 'BK']);
            $table->enum('kedisiplinan_status', ['K', 'BK']);
            $table->enum('kesimpulan', ['kompeten', 'belum_kompeten']);
            $table->text('catatan')->nullable();
            $table->string('instruktur_signature_path')->nullable();
            $table->string('pengawas_signature_path')->nullable();
            $table->string('operator_pendamping_signature_path')->nullable();
            $table->string('kabag_signature_path')->nullable();
            $table->string('penanggung_jawab_signature_path')->nullable();
            $table->string('hse_signature_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ojt_final_evaluations');
    }
};
