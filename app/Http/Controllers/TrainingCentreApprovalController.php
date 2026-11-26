<?php

namespace App\Http\Controllers;

use App\Models\LogbookHistory;
use App\Models\OjtLogbook;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TrainingCentreApprovalController extends Controller
{
    private function reviewer(): User
    {
        $user = auth()->user();
        abort_unless($user && $user->isTrainingCentre(), 403);

        return $user;
    }

    private function pendingQuery()
    {
        $reviewer = $this->reviewer();
        return OjtLogbook::with(['trainee', 'trainer', 'equipment', 'evaluation', 'assignedTc'])
            ->where('status', 'verified')
            ->whereNull('training_centre_decided_at');
    }

    public function index(Request $request)
    {
        $reviewer = $this->reviewer();
        $activeStatus = $request->get('status', 'pending');

        $query = OjtLogbook::with(['trainee', 'trainer', 'equipment', 'evaluation', 'assignedTc']);

        if ($activeStatus === 'finalized') {
            $query->where('status', 'final_approved')->whereNotNull('training_centre_decided_at');
        } elseif ($activeStatus === 'revision') {
            $query->where('status', 'revision')->whereNotNull('training_centre_decided_at');
        } else {
            $activeStatus = 'pending';
            $query->where('status', 'verified')
                ->whereNull('training_centre_decided_at');
        }

        if ($request->filled('search')) {
            $term = mb_strtolower(trim($request->search));
            $query->where(fn ($q) => $q->whereRaw('LOWER(logbook_number) LIKE ?', ["%{$term}%"])
                ->orWhereHas('trainee', fn ($u) => $u->whereRaw('LOWER(name) LIKE ?', ["%{$term}%"])
                    ->orWhereRaw('LOWER(sid) LIKE ?', ["%{$term}%"])
                    ->orWhereRaw('LOWER(company) LIKE ?', ["%{$term}%"])
                    ->orWhereRaw('LOWER(department) LIKE ?', ["%{$term}%"])));
        }

        if ($request->filled('department')) {
            $query->whereHas('trainee', fn ($u) => $u->where('department', $request->department));
        }

        $logbooks = $query->latest('updated_at')->paginate(10)->withQueryString();

        // Trainee Profile Groups: Rekap Profil Trainee, Logbook Menunggu Approval, dan Riwayat Lengkap Per Nama
        $allTraineesQuery = User::where('role', 'trainee')->with(['equipmentCategory']);
        if ($request->filled('department')) {
            $allTraineesQuery->where('department', $request->department);
        }
        if ($request->filled('search')) {
            $term = mb_strtolower(trim($request->search));
            $allTraineesQuery->where(function ($sub) use ($term) {
                $sub->whereRaw('LOWER(name) LIKE ?', ["%{$term}%"])
                    ->orWhereRaw('LOWER(sid) LIKE ?', ["%{$term}%"])
                    ->orWhereRaw('LOWER(company) LIKE ?', ["%{$term}%"]);
            });
        }
        $allTrainees = $allTraineesQuery->get();
        $allTraineeIds = $allTrainees->pluck('id');

        $allTraineeLogbooks = OjtLogbook::whereIn('trainee_id', $allTraineeIds)
            ->with(['trainer', 'equipment', 'equipmentCategory', 'evidences'])
            ->latest('date')
            ->get()
            ->groupBy('trainee_id');

        $allTraineeEvaluations = \App\Models\FinalEvaluation::whereIn('nama_operator', $allTrainees->pluck('name'))
            ->with(['trainer'])
            ->latest('created_at')
            ->get()
            ->groupBy('nama_operator');

        $traineeApprovalGroups = $allTrainees->map(function ($t) use ($allTraineeLogbooks, $allTraineeEvaluations) {
            $tLogbooks = $allTraineeLogbooks->get($t->id, collect());
            $tEvaluations = $allTraineeEvaluations->get($t->name, collect());

            $pendingLogs = $tLogbooks->where('status', 'verified')->whereNull('training_centre_decided_at')->values();
            $revisionLogs = $tLogbooks->where('status', 'revision')->whereNotNull('training_centre_decided_at')->values();
            $finalizedLogs = $tLogbooks->where('status', 'final_approved')->whereNotNull('training_centre_decided_at')->values();
            $pendingEvals = $tEvaluations->where('status', 'submitted')->values();
            $finalizedEvals = $tEvaluations->whereIn('status', ['tc_approved', 'pjo_approved', 'hse_approved'])->values();

            $latestLogbookDate = $tLogbooks->max('updated_at');
            $latestEvalDate = $tEvaluations->max('updated_at');
            $latestDate = collect([$latestLogbookDate, $latestEvalDate])->filter()->max();

            $trainer = $pendingLogs->first()?->trainer 
                ?? $tLogbooks->first()?->trainer 
                ?? $pendingEvals->first()?->trainer 
                ?? $tEvaluations->first()?->trainer;

            return [
                'trainee' => $t,
                'trainer' => $trainer,
                'pending_logbooks' => $pendingLogs,
                'revision_logbooks' => $revisionLogs,
                'finalized_logbooks' => $finalizedLogs,
                'pending_evaluations' => $pendingEvals,
                'finalized_evaluations' => $finalizedEvals,
                'pending_count' => $pendingLogs->count(),
                'pending_eval_count' => $pendingEvals->count(),
                'total_pending' => $pendingLogs->count() + $pendingEvals->count(),
                'revision_count' => $revisionLogs->count(),
                'finalized_count' => $finalizedLogs->count(),
                'finalized_eval_count' => $finalizedEvals->count(),
                'total_hm' => (float) $finalizedLogs->sum('total_hm'),
                'total_all_forms' => $tLogbooks->count(),
                'phase_label' => $t->currentPhaseMeta()['label'] ?? $t->currentPhaseKey(),
                'latest_date' => $latestDate ? \Carbon\Carbon::parse($latestDate) : null,
                'has_new' => ($pendingLogs->count() + $pendingEvals->count()) > 0,
            ];
        });

        if ($activeStatus === 'pending') {
            $groupedByTrainee = $traineeApprovalGroups->filter(fn ($g) => $g['total_pending'] > 0 || $g['total_all_forms'] > 0)
                ->sortByDesc(fn ($g) => [$g['total_pending'] > 0 ? 1 : 0, $g['total_pending'], $g['latest_date'] ? $g['latest_date']->timestamp : 0])
                ->values();
        } elseif ($activeStatus === 'revision') {
            $groupedByTrainee = $traineeApprovalGroups->filter(fn ($g) => $g['revision_count'] > 0)
                ->sortByDesc(fn ($g) => $g['latest_date'] ? $g['latest_date']->timestamp : 0)
                ->values();
        } else {
            $groupedByTrainee = $traineeApprovalGroups->filter(fn ($g) => $g['finalized_count'] > 0 || $g['finalized_eval_count'] > 0)
                ->sortByDesc(fn ($g) => $g['latest_date'] ? $g['latest_date']->timestamp : 0)
                ->values();
        }

        $groupedFinalized = $groupedByTrainee;

        $finalizedCount = OjtLogbook::where('status', 'final_approved')
            ->whereNotNull('training_centre_decided_at')
            ->pluck('trainee_id')
            ->merge(
                User::where('role', 'trainee')
                    ->whereIn('name', \App\Models\FinalEvaluation::whereNotNull('tc_approved_at')->orWhereIn('status', ['tc_approved', 'pjo_approved', 'hse_approved'])->pluck('nama_operator'))
                    ->pluck('id')
            )
            ->filter()
            ->unique()
            ->count();

        $counts = [
            'pending' => $this->pendingQuery()->count(),
            'finalized' => $finalizedCount,
            'revision' => OjtLogbook::where('status', 'revision')->whereNotNull('training_centre_decided_at')->count(),
        ];

        $pendingEvaluations = \App\Models\FinalEvaluation::with(['trainer', 'trainee'])
            ->where('status', 'submitted')
            ->latest()
            ->get();

        $evalCounts = [
            'submitted' => \App\Models\FinalEvaluation::where('status', 'submitted')->count(),
            'tc_approved' => \App\Models\FinalEvaluation::where('status', 'tc_approved')->count(),
            'pjo_approved' => \App\Models\FinalEvaluation::where('status', 'pjo_approved')->count(),
            'completed' => \App\Models\FinalEvaluation::where('status', 'hse_approved')->count(),
        ];

        $phaseRecap = User::where('role', 'trainee')
            ->get()
            ->groupBy(fn ($u) => ($u->certification ?? '-') . ' · ' . ($u->currentPhaseMeta()['label'] ?? $u->current_phase))
            ->map(fn ($g) => $g->count())
            ->sortKeys();

        $departments = User::whereNotNull('department')->where('department', '!=', '')->distinct()->orderBy('department')->pluck('department');

        return view('training-centre.approvals.index', compact('reviewer', 'logbooks', 'counts', 'activeStatus', 'groupedFinalized', 'groupedByTrainee', 'pendingEvaluations', 'evalCounts', 'phaseRecap', 'departments'));
    }

    public function show($id)
    {
        $reviewer = $this->reviewer();
        $logbook = OjtLogbook::with(['trainee', 'trainer', 'equipmentCategory', 'equipment', 'histories.user', 'evaluation.trainer', 'trainingCentre', 'assignedTc'])->findOrFail($id);
        $isPending = $logbook->status === 'verified' && !$logbook->training_centre_decided_at;
        abort_unless($isPending || $logbook->training_centre_decided_at, 403);
        $assignedPengawas = collect($logbook->selected_pengawas_ids ?? [])->map(fn ($id) => User::find($id))->filter();
        $assignedOperators = collect($logbook->selected_operator_pendamping_ids ?? [])->map(fn ($id) => User::find($id))->filter();
        return view('ojt.logbooks.show', ['logbook' => $logbook, 'trainerReview' => false, 'trainingCentreApproval' => true, 'isPending' => $isPending, 'assignedPengawas' => $assignedPengawas, 'assignedOperators' => $assignedOperators]);
    }

    public function decide(Request $request, $id)
    {
        $reviewer = $this->reviewer();
        $data = $request->validate(['action' => ['required', 'in:approve,revision'], 'approval_notes' => ['required_if:action,revision', 'nullable', 'string', 'max:2000']]);
        $logbook = $this->pendingQuery()->findOrFail($id);
        $previousStatus = $logbook->status;
        $approved = $data['action'] === 'approve';
        if ($approved && !$reviewer->signature_path) {
            throw ValidationException::withMessages([
                'signature' => 'Simpan tanda tangan di My Profile dulu sebelum final approval.',
            ]);
        }
        DB::transaction(function () use ($logbook, $reviewer, $data, $approved, $previousStatus) {
            $logbook->update(['status' => $approved ? 'final_approved' : 'revision', 'training_centre_id' => $reviewer->id, 'training_centre_notes' => $data['approval_notes'] ?? null, 'training_centre_decided_at' => now(), 'approved_at' => $approved ? now() : null, 'revision_notes' => $approved ? null : $data['approval_notes'], 'training_centre_signature_path' => $approved ? $reviewer->signature_path : null]);
            LogbookHistory::create(['ojt_logbook_id' => $logbook->id, 'user_id' => $reviewer->id, 'action' => $approved ? 'Approved by Head of Training Centre' : 'Revision Requested by Head of Training Centre', 'from_status' => $previousStatus, 'to_status' => $approved ? 'final_approved' : 'revision', 'comment' => $data['approval_notes'] ?? null]);
        });

        if ($approved) {
            $logbook->trainee?->notify(new \App\Notifications\LogbookApprovedNotification([
                'title' => 'Form OJT Telah Disahkan',
                'message' => "Form OJT {$logbook->logbook_number} telah disahkan oleh Kabag Training Centre.",
                'url' => route('ojt.logbooks.show', $logbook->id),
            ]));
            $logbook->trainer?->notify(new \App\Notifications\LogbookApprovedNotification([
                'title' => 'Form OJT Telah Disahkan',
                'message' => "Form OJT {$logbook->logbook_number} dari {$logbook->trainee->name} telah disahkan oleh Kabag Training Centre.",
                'url' => route('trainer.reviews.show', $logbook->id),
            ]));
        } else {
            $logbook->trainer?->notify(new \App\Notifications\LogbookRevisionRequestedNotification([
                'title' => 'Form OJT Dikembalikan untuk Revisi',
                'message' => "Form OJT {$logbook->logbook_number} dari {$logbook->trainee->name} dikembalikan untuk revisi oleh Kabag Training Centre.",
                'url' => route('trainer.reviews.show', $logbook->id),
            ]));
        }
        return redirect()->route('training-centre.approvals.index')->with('success', $approved ? 'Logbook telah disahkan oleh Kabag Training Centre.' : 'Logbook dikembalikan untuk revisi.');
    }

    public function traineeDocuments($traineeId)
    {
        $reviewer = $this->reviewer();

        $trainee = User::where('role', 'trainee')->findOrFail($traineeId);

        $logbooks = OjtLogbook::where('trainee_id', $traineeId)
            ->where('status', 'final_approved')
            ->whereNotNull('training_centre_decided_at')
            ->with(['trainer', 'equipmentCategory', 'equipment', 'trainingCentre'])
            ->orderBy('training_centre_decided_at', 'asc')
            ->get();

        $evaluations = \App\Models\FinalEvaluation::where('nama_operator', $trainee->name)
            ->with(['trainer'])
            ->orderBy('created_at', 'asc')
            ->get();

        return view('training-centre.trainee-documents', compact('reviewer', 'trainee', 'logbooks', 'evaluations'));
    }
}
