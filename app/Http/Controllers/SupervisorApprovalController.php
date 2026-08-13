<?php

namespace App\Http\Controllers;

use App\Models\LogbookAssignment;
use App\Models\OjtLogbook;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class SupervisorApprovalController extends Controller
{
    private function reviewer(): User
    {
        $user = Auth::user();
        abort_unless($user && $user->isTrainer(), 403);

        return $user;
    }

    public function index(Request $request)
    {
        $reviewer = $this->reviewer();
        $query = OjtLogbook::with(['trainee', 'trainer', 'equipment', 'evaluation', 'assignedTc'])
            ->whereHas('assignments', function ($q) use ($reviewer) {
                $q->where('user_id', $reviewer->id)
                  ->where('status', 'pending');
            })
            ->whereIn('status', ['verified', 'supervisor_approved']);

        if ($request->filled('search')) {
            $term = $request->search;
            $query->where(fn ($q) => $q->where('logbook_number', 'like', "%{$term}%")
                ->orWhereHas('trainee', fn ($u) => $u->where('name', 'like', "%{$term}%")->orWhere('sid', 'like', "%{$term}%")));
        }

        $logbooks = $query->latest('updated_at')->paginate(10)->withQueryString();

        $counts = [
            'pending' => $query->count(),
            'approved' => LogbookAssignment::where('user_id', $reviewer->id)
                ->where('status', 'approved')
                ->count(),
            'rejected' => LogbookAssignment::where('user_id', $reviewer->id)
                ->where('status', 'rejected')
                ->count(),
        ];

        return view('supervisor.approvals.index', compact('reviewer', 'logbooks', 'counts'));
    }

    public function show($id)
    {
        $reviewer = $this->reviewer();
        $logbook = OjtLogbook::with(['trainee', 'trainer', 'equipment', 'evaluation', 'histories.user', 'assignments.user'])
            ->findOrFail($id);

        $assignment = LogbookAssignment::where('ojt_logbook_id', $id)
            ->where('user_id', $reviewer->id)
            ->where('status', 'pending')
            ->first();

        abort_if(!$assignment, 403);

        return view('supervisor.approvals.show', compact('logbook', 'reviewer', 'assignment'));
    }

    public function decide(Request $request, $id)
    {
        $reviewer = $this->reviewer();
        $assignment = LogbookAssignment::where('ojt_logbook_id', $id)
            ->where('user_id', $reviewer->id)
            ->where('status', 'pending')
            ->firstOrFail();

        $logbook = OjtLogbook::findOrFail($id);

        $data = $request->validate([
            'action' => ['required', 'in:approve,reject'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $approved = $data['action'] === 'approve';

        $assignment->update([
            'status' => $approved ? 'approved' : 'rejected',
            'notes' => $data['notes'],
            'reviewed_at' => now(),
        ]);

        LogbookHistory::create([
            'ojt_logbook_id' => $logbook->id,
            'user_id' => $reviewer->id,
            'action' => $approved ? 'Approved by Pengawas' : 'Rejected by Pengawas',
            'from_status' => $logbook->status,
            'to_status' => $logbook->status,
            'comment' => $data['notes'],
        ]);

        if ($approved) {
            $pendingAssignments = LogbookAssignment::where('ojt_logbook_id', $id)
                ->where('status', 'pending')
                ->count();

            if ($pendingAssignments === 0) {
                $logbook->update(['status' => 'supervisor_approved']);
            }
        }

        return redirect()->route('supervisor.approvals.index')->with('success', $approved ? 'Logbook telah disetujui.' : 'Logbook telah ditolak.');
    }
}
