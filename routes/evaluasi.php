<?php

use App\Http\Controllers\Evaluasi1\CertificateL1Controller;
use App\Http\Controllers\Evaluasi1\TraineeLmsController;
use App\Http\Controllers\Evaluasi1\TrainerEvaluasi1Controller;
use App\Http\Controllers\Evaluasi2\GerdasAssessmentController;
use App\Http\Controllers\EvaluasiMonitoring\EvaluasiMonitoringController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Routes Khusus Evaluasi 1 & Evaluasi 2 (Modular & Terisolasi)
|--------------------------------------------------------------------------
| File route ini dibuat terpisah untuk menjamin independensi fitur Evaluasi 1
| dan Evaluasi 2 tanpa risiko breaking changes pada Evaluasi 3 - 10.
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'must.change.password'])->group(function () {

    // =========================================================================
    // 1. MODUL EVALUASI 1 (In-Class LMS, Video Pembelajaran & Ujian CBT Proctoring)
    // =========================================================================
    Route::prefix('evaluasi-1')->name('evaluasi1.')->group(function () {

        // --- Fitur Trainee (Siswa) ---
        Route::prefix('lms')->name('trainee.')->group(function () {
            Route::get('/', [TraineeLmsController::class, 'index'])->name('lms');
            Route::get('/material/{id}', [TraineeLmsController::class, 'showMaterial'])->name('material.show');
            Route::post('/material/{id}/progress', [TraineeLmsController::class, 'updateProgress'])->name('material.progress');

            // Absensi Online In-Class LMS
            Route::post('/attendance/clock-in', [TraineeLmsController::class, 'clockIn'])->name('attendance.clock_in');
            Route::post('/attendance/clock-out', [TraineeLmsController::class, 'clockOut'])->name('attendance.clock_out');
            Route::post('/attendance/store', [TraineeLmsController::class, 'storeAttendance'])->name('attendance.store');

            // Ujian CBT & Camera Proctoring
            Route::get('/exam/{id}/prep', [TraineeLmsController::class, 'examPreparation'])->name('exam.prep');
            Route::get('/exam/{id}/start', [TraineeLmsController::class, 'startExam'])->name('exam.start');
            Route::post('/attempt/{attemptId}/proctoring-snapshot', [TraineeLmsController::class, 'proctoringSnapshot'])->name('exam.snapshot');
            Route::post('/attempt/{attemptId}/submit', [TraineeLmsController::class, 'submitExam'])->name('exam.submit');
            Route::get('/attempt/{attemptId}/result', [TraineeLmsController::class, 'result'])->name('exam.result');
        });

        // --- Fitur Trainer & Admin Training Centre (TC) ---
        Route::prefix('workspace')->name('trainer.')->group(function () {
            Route::get('/', [TrainerEvaluasi1Controller::class, 'index'])->name('dashboard');

            // Kelola Materi LMS
            Route::get('/materials', [TrainerEvaluasi1Controller::class, 'materials'])->name('materials.index');
            Route::get('/materials/create', [TrainerEvaluasi1Controller::class, 'createMaterial'])->name('materials.create');
            Route::post('/materials', [TrainerEvaluasi1Controller::class, 'storeMaterial'])->name('materials.store');
            Route::delete('/materials/{id}', [TrainerEvaluasi1Controller::class, 'destroyMaterial'])->name('materials.destroy');

            // Monitoring & Verifikasi Absensi Online In-Class
            Route::get('/attendances', [TrainerEvaluasi1Controller::class, 'attendances'])->name('attendances.index');
            Route::post('/attendances/{id}/verify', [TrainerEvaluasi1Controller::class, 'verifyAttendance'])->name('attendances.verify');

            // Kelola Ujian & Bank Soal CBT
            Route::get('/exams', [TrainerEvaluasi1Controller::class, 'exams'])->name('exams.index');
            Route::get('/exams/create', [TrainerEvaluasi1Controller::class, 'createExam'])->name('exams.create');
            Route::post('/exams', [TrainerEvaluasi1Controller::class, 'storeExam'])->name('exams.store');
            Route::get('/exams/{id}/questions', [TrainerEvaluasi1Controller::class, 'questions'])->name('exams.questions');
            Route::post('/exams/{id}/questions', [TrainerEvaluasi1Controller::class, 'storeQuestion'])->name('exams.questions.store');

            // Dashboard Pemantauan Live / Snapshot Camera Proctoring
            Route::get('/proctoring', [TrainerEvaluasi1Controller::class, 'proctoringMonitor'])->name('proctoring');
        });

        // Output: Auto-Generate Sertifikat Kompetensi SIMPER L1
        Route::prefix('certificate')->name('certificate.')->group(function () {
            Route::get('/{id}', [CertificateL1Controller::class, 'show'])->name('show');
            Route::get('/{id}/print', [CertificateL1Controller::class, 'print'])->name('print');
        });
    });

    // =========================================================================
    // 2. MODUL EVALUASI 2 (Penilaian Gerakan Dasar / Gerdas A2B)
    // =========================================================================
    Route::prefix('evaluasi-2')->name('evaluasi2.')->group(function () {
        Route::get('/assessments', [GerdasAssessmentController::class, 'index'])->name('assessments.index');
        Route::get('/assessments/create', [GerdasAssessmentController::class, 'create'])->name('assessments.create');
        Route::post('/assessments', [GerdasAssessmentController::class, 'store'])->name('assessments.store');
        Route::get('/assessments/{id}', [GerdasAssessmentController::class, 'show'])->name('assessments.show');

        // Output: Auto-Generate Sertifikat Kompetensi SIMPER L2
        Route::get('/certificate/{id}/print', [GerdasAssessmentController::class, 'printCertificateL2'])->name('certificate.print');

        // Output: Rekapitulasi Standar PT MTL "Evaluasi Nilai Uji Kompetensi Evaluasi 1 dan 2"
        Route::get('/rekap-mtl/{traineeId}/print', [GerdasAssessmentController::class, 'printRekapPTMTL'])->name('rekap-mtl.print');
    });

    // =========================================================================
    // 3. DASHBOARD MONITORING NILAI EVALUASI 1 & 2 (ADMIN TC & MANAJEMEN)
    // =========================================================================
    Route::get('/monitoring-evaluasi-1-2', [EvaluasiMonitoringController::class, 'index'])
        ->name('monitoring.evaluasi1-2');
});
