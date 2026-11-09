<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations for Evaluasi 1 (In-Class LMS, CBT Exam, Camera Proctoring, SIMPER L1).
     */
    public function up(): void
    {
        // 1. Materi Pembelajaran In-Class LMS
        Schema::create('eval1_materials', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('type')->default('video'); // video, document, pdf, slide
            $table->text('content_url')->nullable(); // URL youtube/drive atau link video
            $table->string('file_path')->nullable(); // Uploaded document
            $table->integer('order')->default(0);
            $table->integer('min_duration_seconds')->default(0); // Waktu tonton minimal
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // 2. Tracking Progress Materi Trainee
        Schema::create('eval1_material_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('material_id')->constrained('eval1_materials')->cascadeOnDelete();
            $table->integer('watch_time_seconds')->default(0);
            $table->boolean('is_completed')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'material_id']);
        });

        // 3. Ujian CBT (Pre-test / Post-test / Evaluasi 1 Teori)
        Schema::create('eval1_exams', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('type')->default('evaluasi_1'); // pre_test, post_test, evaluasi_1
            $table->text('description')->nullable();
            $table->integer('duration_minutes')->default(60);
            $table->decimal('passing_score', 5, 2)->default(75.00);
            $table->boolean('proctoring_enabled')->default(true);
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // 4. Bank Soal Ujian
        Schema::create('eval1_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained('eval1_exams')->cascadeOnDelete();
            $table->text('question_text');
            $table->string('question_image')->nullable();
            $table->json('options'); // [{key: 'A', text: '...'}, ...]
            $table->string('correct_answer', 10); // A, B, C, D
            $table->decimal('score_weight', 5, 2)->default(10.00);
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // 5. Pengerjaan Ujian Trainee (Attempts)
        Schema::create('eval1_exam_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained('eval1_exams')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamp('started_at');
            $table->timestamp('submitted_at')->nullable();
            $table->json('answers')->nullable();
            $table->decimal('total_score', 5, 2)->default(0.00);
            $table->boolean('is_passed')->default(false);
            $table->string('status')->default('in_progress'); // in_progress, completed, violation
            $table->integer('suspicious_flags_count')->default(0);
            $table->timestamps();
        });

        // 6. Camera Proctoring Logs & Snapshots
        Schema::create('eval1_proctoring_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attempt_id')->constrained('eval1_exam_attempts')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('snapshot_path')->nullable();
            $table->string('event_type')->default('periodic_capture'); // periodic_capture, tab_switch, multiple_faces, no_face
            $table->text('event_details')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        // 7. Auto-generated Sertifikat SIMPER L1
        Schema::create('eval1_certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('exam_attempt_id')->constrained('eval1_exam_attempts')->cascadeOnDelete();
            $table->string('certificate_number')->unique();
            $table->string('license_type')->default('SIMPER L1 (In-Class & Teori CBT)');
            $table->date('issue_date');
            $table->date('valid_until')->nullable();
            $table->decimal('score', 5, 2);
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
        Schema::dropIfExists('eval1_certificates');
        Schema::dropIfExists('eval1_proctoring_logs');
        Schema::dropIfExists('eval1_exam_attempts');
        Schema::dropIfExists('eval1_questions');
        Schema::dropIfExists('eval1_exams');
        Schema::dropIfExists('eval1_material_progress');
        Schema::dropIfExists('eval1_materials');
    }
};
