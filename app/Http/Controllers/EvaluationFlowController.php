<?php

namespace App\Http\Controllers;

use App\Models\FinalEvaluation;
use App\Models\User;
use App\Services\PhaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EvaluationFlowController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'must.change.password']);
    }

    private function resolveTrainee(FinalEvaluation $evaluation): ?User
    {
        return User::where('name', $evaluation->nama_operator)
            ->where('role', 'trainee')
            ->first();
    }

    public function show($id)
    {
        $user = Auth::user();
        abort_unless(
            $user && ($user->isTrainer() || $user->isTrainingCentre() || $user->isPjo() || $user->isHseCt() || $user->isSuperAdmin()),
            403
        );

        $evaluation = FinalEvaluation::with(['trainer', 'tcApprover', 'pjoApprover', 'hseApprover', 'histories.approver', 'logbook'])->findOrFail($id);

        return view('evaluation-flow.show', compact('evaluation'));
    }

    // ===================== ADMIN TC =====================
    public function tcIndex()
    {
        $user = Auth::user();
        abort_unless($user && $user->isTrainingCentre(), 403);

        $evaluations = FinalEvaluation::with(['trainer', 'trainee'])
            ->where('status', 'submitted')
            ->latest()
            ->paginate(15);

        $counts = [
            'pending' => FinalEvaluation::where('status', 'submitted')->count(),
            'tc_approved' => FinalEvaluation::where('status', 'tc_approved')->count(),
            'pjo_approved' => FinalEvaluation::where('status', 'pjo_approved')->count(),
            'completed' => FinalEvaluation::where('status', 'hse_approved')->count(),
            'rejected' => FinalEvaluation::where('status', 'rejected')->count(),
        ];

        return view('evaluation-flow.tc-index', compact('evaluations', 'counts'));
    }

    public function tcApprove(Request $request, $id)
    {
        $user = Auth::user();
        abort_unless($user && $user->isTrainingCentre(), 403);

        $evaluation = FinalEvaluation::where('status', 'submitted')->findOrFail($id);

        $request->validate([
            'tc_notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $evaluation->update([
            'status' => 'tc_approved',
            'tc_approved_by' => $user->id,
            'tc_approved_at' => now(),
            'tc_notes' => $request->input('tc_notes'),
            'kabag_signature_path' => $user->signature_path,
        ]);

        \App\Models\User::where('role', 'pjo')->get()->each(fn ($pjo) => $pjo->notify(new \App\Notifications\LogbookApprovedNotification([
            'title' => 'Evaluasi Menunggu Persetujuan PJO',
            'message' => "Evaluasi final untuk {$evaluation->nama_operator} telah disetujui Admin TC dan menunggu persetujuan Anda.",
            'url' => route('pjo.final-evaluations.show', $evaluation->id),
        ])));

        return redirect()->back()->with('success', 'Evaluasi disetujui oleh Admin TC.');
    }

    // ===================== PJO =====================
    public function pjoIndex()
    {
        return $this->pjoDashboard();
    }

    public function pjoDashboard()
    {
        $user = Auth::user();
        abort_unless($user && $user->isPjo(), 403);

        $evaluations = FinalEvaluation::with(['trainer', 'trainee'])
            ->where('status', 'tc_approved')
            ->latest()
            ->paginate(15);

        $counts = [
            'pending' => FinalEvaluation::where('status', 'tc_approved')->count(),
            'approved' => FinalEvaluation::where('status', 'pjo_approved')->count(),
            'completed' => FinalEvaluation::where('status', 'hse_approved')->count(),
        ];

        return view('evaluation-flow.pjo-dashboard', compact('evaluations', 'counts'));
    }

    public function pjoApprove(Request $request, $id)
    {
        $user = Auth::user();
        abort_unless($user && $user->isPjo(), 403);

        $evaluation = FinalEvaluation::where('status', 'tc_approved')->findOrFail($id);

        $request->validate([
            'pjo_notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $evaluation->update([
            'status' => 'pjo_approved',
            'pjo_approved_by' => $user->id,
            'pjo_approved_at' => now(),
            'pjo_notes' => $request->input('pjo_notes'),
            'penanggung_jawab_signature_path' => $user->signature_path,
        ]);

        \App\Models\User::where('role', 'hse_ct')->get()->each(fn ($hse) => $hse->notify(new \App\Notifications\LogbookApprovedNotification([
            'title' => 'Evaluasi Menunggu Persetujuan HSE CT',
            'message' => "Evaluasi final untuk {$evaluation->nama_operator} telah disetujui PJO dan menunggu persetujuan Anda.",
            'url' => route('hse-ct.final-evaluations.show', $evaluation->id),
        ])));

        return redirect()->back()->with('success', 'Evaluasi disetujui oleh PJO.');
    }

    // ===================== HSE CT =====================
    public function hseIndex()
    {
        return $this->hseCtDashboard();
    }

    public function hseCtDashboard()
    {
        $user = Auth::user();
        abort_unless($user && $user->isHseCt(), 403);

        $evaluations = FinalEvaluation::with(['trainer', 'trainee'])
            ->where('status', 'pjo_approved')
            ->latest()
            ->paginate(15);

        $counts = [
            'pending' => FinalEvaluation::where('status', 'pjo_approved')->count(),
            'completed' => FinalEvaluation::where('status', 'hse_approved')->count(),
        ];

        return view('evaluation-flow.hse-dashboard', compact('evaluations', 'counts'));
    }

    public function hseApprove(Request $request, $id)
    {
        $user = Auth::user();
        abort_unless($user && $user->isHseCt(), 403);

        $evaluation = FinalEvaluation::where('status', 'pjo_approved')->findOrFail($id);

        $request->validate([
            'hse_notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $trainee = $this->resolveTrainee($evaluation);
        $fromPhase = $trainee?->currentPhaseKey();
        $nextPhase = $trainee ? PhaseService::nextPhase($trainee->certification ?? 'Green', $fromPhase) : null;

        $evaluation->update([
            'status' => 'hse_approved',
            'hse_approved_by' => $user->id,
            'hse_approved_at' => now(),
            'hse_notes' => $request->input('hse_notes'),
            'completed_at' => now(),
            'hse_signature_path' => $user->signature_path,
        ]);

        if ($trainee && $nextPhase) {
            $trainee->update([
                'current_phase' => $nextPhase,
                'initial_hm_day' => 0,
                'initial_hm_night' => 0,
            ]);
            \App\Models\TraineePhaseHistory::create([
                'user_id' => $trainee->id,
                'from_phase' => $fromPhase,
                'to_phase' => $nextPhase,
                'evaluation_id' => $evaluation->id,
                'approved_by' => $user->id,
                'notes' => 'Auto phase shift setelah persetujuan HSE CT.',
            ]);
        }

        if ($trainee) {
            $traineeUrl = $evaluation->logbook ? route('ojt.logbooks.show', $evaluation->logbook->id) : route('ojt.dashboard');

            $trainee->notify(new \App\Notifications\LogbookApprovedNotification([
                'title' => 'Evaluasi Telah Disetujui Final',
                'message' => "Evaluasi final fase {$fromPhase} telah disetujui final oleh HSE CT.",
                'url' => $traineeUrl,
            ]));

            $trainee->assignedTrainers()->get()->each(fn ($trainer) => $trainer->notify(new \App\Notifications\LogbookApprovedNotification([
                'title' => 'Evaluasi Trainee Telah Final',
                'message' => "Evaluasi final untuk {$trainee->name} telah disetujui final oleh HSE CT.",
                'url' => route('trainer.final-evaluations.show', $evaluation->id),
            ])));
        }

        return redirect()->back()->with('success', 'Evaluasi disetujui final oleh HSE CT. Trainee dipindahkan ke fase berikutnya.');
    }

    // ===================== REJECT / REVISI =====================
    public function reject(Request $request, $id)
    {
        $user = Auth::user();
        abort_unless($user && ($user->isTrainingCentre() || $user->isPjo() || $user->isHseCt()), 403);

        $evaluation = FinalEvaluation::findOrFail($id);
        abort_unless(in_array($evaluation->status, ['submitted', 'tc_approved', 'pjo_approved'], true), 404);

        $request->validate([
            'tc_notes' => ['nullable', 'string', 'max:2000'],
            'pjo_notes' => ['nullable', 'string', 'max:2000'],
            'hse_notes' => ['nullable', 'string', 'max:2000'],
        ]);

        if ($user->isTrainingCentre() && $evaluation->status === 'submitted') {
            $evaluation->update([
                'status' => 'rejected',
                'tc_notes' => $request->input('tc_notes'),
                'tc_approved_at' => null,
                'tc_approved_by' => null,
            ]);
        } elseif ($user->isPjo() && $evaluation->status === 'tc_approved') {
            $evaluation->update([
                'status' => 'rejected',
                'pjo_notes' => $request->input('pjo_notes'),
                'pjo_approved_at' => null,
                'pjo_approved_by' => null,
                'tc_approved_at' => null,
                'tc_approved_by' => null,
            ]);
        } elseif ($user->isHseCt() && $evaluation->status === 'pjo_approved') {
            $evaluation->update([
                'status' => 'rejected',
                'hse_notes' => $request->input('hse_notes'),
                'hse_approved_at' => null,
                'hse_approved_by' => null,
                'pjo_approved_at' => null,
                'pjo_approved_by' => null,
                'tc_approved_at' => null,
                'tc_approved_by' => null,
            ]);
        } else {
            abort(403);
        }

        $trainee = $this->resolveTrainee($evaluation);
        if ($trainee) {
            $trainee->assignedTrainers()->get()->each(fn ($trainer) => $trainer->notify(new \App\Notifications\LogbookRevisionRequestedNotification([
                'title' => 'Evaluasi Dikembalikan untuk Revisi',
                'message' => "Evaluasi final untuk {$trainee->name} telah dikembalikan untuk revisi. Silakan perbaiki sesuai catatan.",
                'url' => route('trainer.final-evaluations.edit', $evaluation->id),
            ])));
        }

        return redirect()->back()->with('success', 'Form telah dikembalikan untuk direvisi ke Trainer.');
    }

    // ===================== MONITORING (Trainer & Admin TC) =====================
    public function monitoring(Request $request)
    {
        $user = Auth::user();
        abort_unless($user && ($user->isTrainer() || $user->isTrainingCentre()), 403);

        $query = User::where('role', 'trainee');
        if ($user->isTrainer()) {
            $query->whereHas('assignedTrainers', fn ($q) => $q->where('trainer_id', $user->id));
        }
        if ($request->filled('search')) {
            $term = $request->search;
            $query->where(fn ($q) => $q->where('name', 'like', "%{$term}%")->orWhere('sid', 'like', "%{$term}%"));
        }
        if ($request->filled('certification')) {
            $query->where('certification', $request->certification);
        }

        $trainees = $query->with('equipmentCategory')->get()->map(function ($t) {
            $cert = $t->certification ?? 'Green';
            $seq = PhaseService::sequence($cert);
            $currentKey = $t->currentPhaseKey();
            $evals = FinalEvaluation::where('nama_operator', $t->name)->get();

            $phaseRows = collect($seq)->map(function ($p) use ($evals, $currentKey) {
                $pe = $evals->where('phase', $p['key']);
                return [
                    'key' => $p['key'],
                    'label' => $p['label'],
                    'type' => $p['type'],
                    'is_current' => $p['key'] === $currentKey,
                    'completed' => $pe->where('status', 'hse_approved')->count(),
                    'pending' => $pe->whereIn('status', ['submitted', 'tc_approved', 'pjo_approved'])->count(),
                    'rejected' => $pe->where('status', 'rejected')->count(),
                    'total' => $pe->count(),
                ];
            });

            return [
                'id' => $t->id,
                'name' => $t->name,
                'sid' => $t->sid,
                'certification' => $cert,
                'current_phase_label' => $t->currentPhaseMeta()['label'] ?? $currentKey,
                'eligible' => $t->isPhaseEligible(),
                'hm' => $t->hmProgress(),
                'progress' => $t->phaseProgressPercent(),
                'phases' => $phaseRows,
                'evaluations_count' => $evals->count(),
                'completed_count' => $evals->where('status', 'hse_approved')->count(),
            ];
        });

        $certifications = PhaseService::CERTIFICATIONS;

        return view('evaluation-flow.monitoring', compact('trainees', 'certifications', 'user'));
    }
}
