<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OjtLogbookController;
use App\Http\Controllers\SubmissionHistoryController;
use App\Http\Controllers\TrainerReviewController;
use App\Http\Controllers\DepartmentOperationController;
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

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::get('/dashboard', function () {
        $user = Auth::user();

        return match ($user?->role) {
            'trainee' => redirect()->route('ojt.dashboard'),
            'trainer' => redirect()->route('trainer.dashboard'),
            'department_ops' => redirect()->route('department-operation.dashboard'),
            'admin' => redirect()->route('training-centre.dashboard'),
            default => abort(403),
        };
    })->name('dashboard');
});

Route::middleware(['auth', 'role:admin'])->prefix('training-centre')->name('training-centre.')->group(function () {
    Route::get('/dashboard', [TrainingCentreApprovalController::class, 'index'])->name('dashboard');
    Route::get('/approvals', [TrainingCentreApprovalController::class, 'index'])->name('approvals.index');
    Route::get('/approvals/{id}', [TrainingCentreApprovalController::class, 'show'])->name('approvals.show');
    Route::post('/approvals/{id}/decision', [TrainingCentreApprovalController::class, 'decide'])->name('approvals.decide');
});

Route::middleware(['auth', 'role:trainer'])->prefix('trainer')->name('trainer.')->group(function () {
    Route::get('/dashboard', [TrainerReviewController::class, 'index'])->name('dashboard');
    Route::get('/reviews', [TrainerReviewController::class, 'index'])->name('reviews.index');
    Route::get('/reviews/{id}', [TrainerReviewController::class, 'show'])->name('reviews.show');
    Route::post('/reviews/{id}/evaluate', [TrainerReviewController::class, 'evaluate'])->name('reviews.evaluate');
});

// Supervisor approval workspace. Supervisors use the Department Operation role.
Route::middleware(['auth', 'role:department_ops'])->prefix('department-operation')->name('department-operation.')->group(function () {
    Route::get('/dashboard', [DepartmentOperationController::class, 'dashboard'])->name('dashboard');
    Route::get('/approvals/pending', [DepartmentOperationController::class, 'pending'])->name('approvals.pending');
    Route::get('/approvals/history', [DepartmentOperationController::class, 'history'])->name('approvals.history');
    Route::get('/approvals/{id}', [DepartmentOperationController::class, 'show'])->name('approvals.show');
    Route::post('/approvals/{id}/decision', [DepartmentOperationController::class, 'decide'])->name('approvals.decide');
});

// OJT Trainee Module Routes
Route::middleware(['auth', 'role:trainee'])->prefix('ojt')->name('ojt.')->group(function () {
    // Page 1: Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Page 2 - 5: My Logbook & CRUD
    Route::get('/logbooks', [OjtLogbookController::class, 'index'])->name('logbooks.index');
    Route::get('/logbooks/create', [OjtLogbookController::class, 'create'])->name('logbooks.create');
    Route::post('/logbooks', [OjtLogbookController::class, 'store'])->name('logbooks.store');
    Route::get('/logbooks/{id}', [OjtLogbookController::class, 'show'])->name('logbooks.show');
    Route::get('/logbooks/{id}/edit', [OjtLogbookController::class, 'edit'])->name('logbooks.edit');
    Route::put('/logbooks/{id}', [OjtLogbookController::class, 'update'])->name('logbooks.update');
    Route::post('/logbooks/{id}/duplicate', [OjtLogbookController::class, 'duplicate'])->name('logbooks.duplicate');
    Route::get('/logbooks/{id}/print', [OjtLogbookController::class, 'print'])->name('logbooks.print');

    // Page 6: Submission History
    Route::get('/history', [SubmissionHistoryController::class, 'index'])->name('history');
});
