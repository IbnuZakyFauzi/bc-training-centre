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
        return OjtLogbook::with(['trainee.department', 'trainer', 'equipment', 'evaluation', 'assignedTc'])
            ->where('status', 'verified')
            ->whereNull('training_centre_decided_at');
    }

    public function index(Request $request)
    {
        $reviewer = $this->reviewer();
        $activeStatus = $request->get('status', 'pending');

        $query = OjtLogbook::with(['trainee.department', 'trainer', 'equipment', 'evaluation', 'assignedTc']);

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
            $term = $request->search;
            $query->where(fn ($q) => $q->where('logbook_number', 'like', "%{$term}%")
                ->orWhereHas('trainee', fn ($u) => $u->where('name', 'like', "%{$term}%")->orWhere('sid', 'like', "%{$term}%")));
        }
        $logbooks = $query->latest('updated_at')->paginate(10)->withQueryString();

        $groupedFinalized = collect();
        if ($activeStatus === 'finalized') {
            $groupedFinalized = OjtLogbook::with(['trainee', 'trainer'])
                ->where('status', 'final_approved')
                ->whereNotNull('training_centre_decided_at')
                ->latest('updated_at')
                ->get()
                ->groupBy('trainee_id')
                ->map(fn ($items) => [
                    'trainee' => $items->first()->trainee,
                    'trainer' => $items->first()->trainer,
                    'count' => $items->count(),
                    'latest_date' => $items->max('updated_at'),
                ]);
        }

        $counts = [
            'pending' => $this->pendingQuery()->count(),
            'finalized' => OjtLogbook::where('status', 'final_approved')->whereNotNull('training_centre_decided_at')->distinct('trainee_id')->count('trainee_id'),
            'revision' => OjtLogbook::where('status', 'revision')->whereNotNull('training_centre_decided_at')->count(),
        ];
        return view('training-centre.approvals.index', compact('reviewer', 'logbooks', 'counts', 'activeStatus', 'groupedFinalized'));
    }

    public function show($id)
    {
        $reviewer = $this->reviewer();
        $logbook = OjtLogbook::with(['trainee', 'trainer', 'supervisor', 'department', 'equipmentCategory', 'equipment', 'histories.user', 'evaluation.trainer', 'trainingCentre', 'assignedTc'])->findOrFail($id);
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
        return redirect()->route('training-centre.approvals.index')->with('success', $approved ? 'Logbook telah disahkan oleh Kabag Training Centre.' : 'Logbook dikembalikan untuk revisi.');
    }
}
