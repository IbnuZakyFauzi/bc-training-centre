<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OjtLogbookController;
use App\Http\Controllers\SubmissionHistoryController;
use App\Http\Controllers\TrainerReviewController;
use App\Http\Controllers\TrainingCentreApprovalController;

Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware(['auth', 'must.change.password'])->group(function () {
        Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

        Route::post('/notifications/read-all', [NotificationController::class, 'readAll'])->name('notifications.read-all');
        Route::get('/notifications/{id}/read', [NotificationController::class, 'read'])->name('notifications.read');

    Route::get('/dashboard', function () {
        $user = Auth::user();

        return match ($user?->role) {
            'trainee' => redirect()->route('ojt.dashboard'),
            'trainer' => redirect()->route('trainer.dashboard'),
            'admin' => redirect()->route('training-centre.dashboard'),
            'pjo' => redirect()->route('pjo.dashboard'),
            'hse_ct' => redirect()->route('hse-ct.dashboard'),
            default => abort(403),
        };
    })->name('dashboard');
});

Route::middleware(['auth', 'must.change.password', 'role:admin'])->prefix('training-centre')->name('training-centre.')->group(function () {
    Route::get('/dashboard', [TrainingCentreApprovalController::class, 'index'])->name('dashboard');
    Route::get('/approvals', [TrainingCentreApprovalController::class, 'index'])->name('approvals.index');
    Route::get('/approvals/{id}', [TrainingCentreApprovalController::class, 'show'])->name('approvals.show');
    Route::post('/approvals/{id}/decision', [TrainingCentreApprovalController::class, 'decide'])->name('approvals.decide');

    Route::get('/users', ['App\Http\Controllers\UserManagementController', 'index'])->name('users.index');
    Route::get('/users/create', ['App\Http\Controllers\UserManagementController', 'create'])->name('users.create');
    Route::post('/users', ['App\Http\Controllers\UserManagementController', 'store'])->name('users.store');
    Route::get('/users/{id}/edit', ['App\Http\Controllers\UserManagementController', 'edit'])->name('users.edit');
    Route::put('/users/{id}', ['App\Http\Controllers\UserManagementController', 'update'])->name('users.update');
    Route::delete('/users/{id}', ['App\Http\Controllers\UserManagementController', 'destroy'])->name('users.destroy');
    Route::post('/users/{id}/reset-password', ['App\Http\Controllers\UserManagementController', 'resetPassword'])->name('users.reset-password');
    Route::post('/users/{id}/assign-phase', ['App\Http\Controllers\UserManagementController', 'assignPhase'])->name('users.assign-phase');

    // Final Evaluation approval flow (Admin TC stage)
    Route::get('/final-evaluations', ['App\Http\Controllers\EvaluationFlowController', 'tcIndex'])->name('final-evaluations.index');
    Route::get('/final-evaluations/{id}', ['App\Http\Controllers\EvaluationFlowController', 'show'])->name('final-evaluations.show');
    Route::get('/final-evaluations/{id}/print', ['App\Http\Controllers\FinalEvaluationController', 'print'])->name('final-evaluations.print');
    Route::post('/final-evaluations/{id}/tc-approve', ['App\Http\Controllers\EvaluationFlowController', 'tcApprove'])->name('final-evaluations.tc-approve');
    Route::post('/final-evaluations/{id}/reject', ['App\Http\Controllers\EvaluationFlowController', 'reject'])->name('final-evaluations.reject');
    Route::get('/monitoring', ['App\Http\Controllers\EvaluationFlowController', 'monitoring'])->name('monitoring');
    Route::get('/trainees/{traineeId}/documents', ['App\Http\Controllers\TrainingCentreApprovalController', 'traineeDocuments'])->name('trainee-documents');
});

// Final Logbook Print Route (Restricted to Admin Training Centre only)
Route::middleware(['auth', 'must.change.password', 'role:admin'])->get('/ojt/logbooks/{id}/print', [OjtLogbookController::class, 'print'])->name('ojt.logbooks.print');
Route::middleware(['auth', 'must.change.password', 'role:admin'])->get('/ojt/logbooks/trainee/{traineeId}/print', [OjtLogbookController::class, 'printTrainee'])->name('ojt.logbooks.print-trainee');

Route::middleware(['auth', 'must.change.password', 'role:trainer'])->prefix('trainer')->name('trainer.')->group(function () {
    Route::get('/dashboard', [TrainerReviewController::class, 'index'])->name('dashboard');
    Route::get('/reviews', [TrainerReviewController::class, 'index'])->name('reviews.index');
    Route::get('/reviews/{id}', [TrainerReviewController::class, 'show'])->name('reviews.show');
    Route::get('/reviews/{id}/edit', [TrainerReviewController::class, 'edit'])->name('reviews.edit');
    Route::put('/reviews/{id}', [TrainerReviewController::class, 'updateLogbook'])->name('reviews.update');
    Route::put('/reviews/{id}/checklist', [TrainerReviewController::class, 'updateChecklist'])->name('reviews.checklist.update');
    Route::post('/reviews/{id}/evaluate', [TrainerReviewController::class, 'evaluate'])->name('reviews.evaluate');

    Route::get('/final-evaluation/create', 'App\Http\Controllers\FinalEvaluationController@createStandalone')->name('final-evaluations.create');
    Route::get('/final-evaluations', 'App\Http\Controllers\FinalEvaluationController@index')->name('final-evaluations.index');
    Route::post('/final-evaluation', 'App\Http\Controllers\FinalEvaluationController@storeStandalone')->name('final-evaluations.store');
    Route::get('/final-evaluations/{id}', 'App\Http\Controllers\FinalEvaluationController@show')->name('final-evaluations.show');
    Route::get('/final-evaluations/{id}/edit', 'App\Http\Controllers\FinalEvaluationController@edit')->name('final-evaluations.edit');
    Route::get('/final-evaluations/{id}/print', 'App\Http\Controllers\FinalEvaluationController@print')->name('final-evaluations.print');
    Route::put('/final-evaluations/{id}', 'App\Http\Controllers\FinalEvaluationController@update')->name('final-evaluations.update');
        Route::get('/monitoring', ['App\Http\Controllers\EvaluationFlowController', 'monitoring'])->name('monitoring');
    });

// OJT Trainee Module Routes
Route::middleware(['auth', 'must.change.password', 'role:trainee'])->prefix('ojt')->name('ojt.')->group(function () {
    // Page 1: Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Page 2 - 5: My Logbook & CRUD
    Route::get('/logbooks', [OjtLogbookController::class, 'index'])->name('logbooks.index');
    Route::get('/logbooks/create', [OjtLogbookController::class, 'create'])->name('logbooks.create');
    Route::post('/logbooks', [OjtLogbookController::class, 'store'])->name('logbooks.store');
    Route::get('/logbooks/{id}', [OjtLogbookController::class, 'show'])->name('logbooks.show');
    Route::get('/logbooks/{id}/edit', [OjtLogbookController::class, 'edit'])->name('logbooks.edit');
    Route::put('/logbooks/{id}', [OjtLogbookController::class, 'update'])->name('logbooks.update');
    Route::put('/logbooks/{id}/checklist', [OjtLogbookController::class, 'updateChecklist'])->name('logbooks.checklist.update');
    Route::post('/logbooks/{id}/duplicate', [OjtLogbookController::class, 'duplicate'])->name('logbooks.duplicate');

    // Page 6: Submission History
    Route::get('/history', [SubmissionHistoryController::class, 'index'])->name('history');
});

// PJO (Penanggung Jawab Operasional) approval flow
Route::middleware(['auth', 'must.change.password', 'role:pjo'])->prefix('pjo')->name('pjo.')->group(function () {
    Route::get('/dashboard', ['App\Http\Controllers\EvaluationFlowController', 'pjoDashboard'])->name('dashboard');
    Route::get('/final-evaluations', ['App\Http\Controllers\EvaluationFlowController', 'pjoIndex'])->name('final-evaluations.index');
    Route::get('/final-evaluations/{id}', ['App\Http\Controllers\EvaluationFlowController', 'show'])->name('final-evaluations.show');
    Route::post('/final-evaluations/{id}/approve', ['App\Http\Controllers\EvaluationFlowController', 'pjoApprove'])->name('final-evaluations.approve');
    Route::post('/final-evaluations/{id}/reject', ['App\Http\Controllers\EvaluationFlowController', 'reject'])->name('final-evaluations.reject');
});

// HSE CT (HSE Training Section) approval flow
Route::middleware(['auth', 'must.change.password', 'role:hse_ct'])->prefix('hse-ct')->name('hse-ct.')->group(function () {
    Route::get('/dashboard', ['App\Http\Controllers\EvaluationFlowController', 'hseCtDashboard'])->name('dashboard');
    Route::get('/final-evaluations', ['App\Http\Controllers\EvaluationFlowController', 'hseIndex'])->name('final-evaluations.index');
    Route::get('/final-evaluations/{id}', ['App\Http\Controllers\EvaluationFlowController', 'show'])->name('final-evaluations.show');
    Route::post('/final-evaluations/{id}/approve', ['App\Http\Controllers\EvaluationFlowController', 'hseApprove'])->name('final-evaluations.approve');
    Route::post('/final-evaluations/{id}/reject', ['App\Http\Controllers\EvaluationFlowController', 'reject'])->name('final-evaluations.reject');
});
