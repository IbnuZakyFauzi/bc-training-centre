<?php

namespace App\Http\Controllers;

use App\Models\LogbookHistory;
use App\Models\OjtLogbook;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DepartmentOperationController extends Controller
{
    private function departmentOperator(): User
    {
        $user = Auth::user();
        abort_unless($user && $user->isDepartmentOperation(), 403);

        return $user;
    }

    private function pendingQuery()
    {
        return OjtLogbook::with(['trainee.department', 'trainer', 'department', 'equipment', 'evaluation'])
            ->where('status', 'verified')
            ->whereHas('evaluation', fn ($query) => $query->whereNotNull('sent_to_pjo_at'));
    }

    public function dashboard()
    {
        $operator = $this->departmentOperator();
        $kpi = [
            'pending' => $this->pendingQuery()->count(),
            'approved_this_month' => OjtLogbook::whereIn('status', ['approved', 'supervisor_approved'])->whereMonth('pjo_decided_at', now()->month)->whereYear('pjo_decided_at', now()->year)->count(),
            'returned_this_month' => OjtLogbook::whereNotNull('pjo_decided_at')->where('status', 'revision')->whereMonth('pjo_decided_at', now()->month)->whereYear('pjo_decided_at', now()->year)->count(),
        ];
        $recentApprovals = OjtLogbook::with(['trainee', 'equipment'])
            ->whereNotNull('pjo_decided_at')->latest('pjo_decided_at')->limit(5)->get();
        $pendingLogbooks = $this->pendingQuery()->latest('verified_at')->limit(5)->get();

        return view('department-operation.dashboard', compact('operator', 'kpi', 'recentApprovals', 'pendingLogbooks'));
    }

    public function pending(Request $request)
    {
        $operator = $this->departmentOperator();
        $logbooks = $this->applyFilters($this->pendingQuery(), $request)->latest('verified_at')->paginate(10)->withQueryString();

        return view('department-operation.approvals.index', compact('operator', 'logbooks'));
    }

    public function history(Request $request)
    {
        $operator = $this->departmentOperator();
        $query = OjtLogbook::with(['trainee.department', 'trainer', 'department', 'equipment', 'evaluation', 'departmentOperation'])
            ->whereNotNull('pjo_decided_at');
        $logbooks = $this->applyFilters($query, $request)->latest('pjo_decided_at')->paginate(10)->withQueryString();

        return view('department-operation.approvals.history', compact('operator', 'logbooks'));
    }

    public function show($id)
    {
        $this->departmentOperator();
        $logbook = OjtLogbook::with(['trainee.department', 'trainer', 'supervisor', 'department', 'equipmentCategory', 'equipment', 'evidences', 'histories.user', 'evaluation.trainer', 'departmentOperation'])->findOrFail($id);
        $isPending = $logbook->status === 'verified' && $logbook->evaluation?->sent_to_pjo_at;
        abort_unless($isPending || $logbook->pjo_decided_at, 403);

        return view('ojt.logbooks.show', [
            'logbook' => $logbook,
            'trainerReview' => false,
            'departmentOperationApproval' => true,
            'isPending' => $isPending,
        ]);
    }

    public function decide(Request $request, $id)
    {
        $operator = $this->departmentOperator();
        $data = $request->validate([
            'action' => ['required', 'in:approve,revision'],
            'approval_notes' => ['required_if:action,revision', 'nullable', 'string', 'max:2000'],
        ]);
        $logbook = $this->pendingQuery()->findOrFail($id);
        $isApproved = $data['action'] === 'approve';

        DB::transaction(function () use ($logbook, $operator, $data, $isApproved) {
            $notes = $data['approval_notes'] ?? null;
            $logbook->update([
                'status' => $isApproved ? 'supervisor_approved' : 'revision',
                'approved_at' => null,
                'revision_notes' => $isApproved ? null : $notes,
                'pjo_id' => $operator->id,
                'pjo_notes' => $notes,
                'pjo_decided_at' => now(),
            ]);
            LogbookHistory::create([
                'ojt_logbook_id' => $logbook->id,
                'user_id' => $operator->id,
                'action' => $isApproved ? 'Approved by Supervisor' : 'Revision Requested by Supervisor',
                'from_status' => 'verified',
                'to_status' => $isApproved ? 'supervisor_approved' : 'revision',
                'comment' => $notes,
            ]);
        });

        return redirect()->route('department-operation.approvals.pending')
            ->with('success', $isApproved ? 'Logbook telah disetujui Pengawas dan diteruskan ke final approval Kabag Training Centre.' : 'Logbook telah dikembalikan oleh Pengawas untuk revisi.');
    }

    private function applyFilters($query, Request $request)
    {
        if ($request->filled('search')) {
            $term = $request->search;
            $query->where(fn ($q) => $q->where('logbook_number', 'like', "%{$term}%")
                ->orWhereHas('trainee', fn ($u) => $u->where('name', 'like', "%{$term}%")->orWhere('nrp', 'like', "%{$term}%")));
        }
        if ($request->filled('status') && $request->status !== 'all') {
            if ($request->status === 'approved') {
                $query->whereIn('status', ['approved', 'supervisor_approved']);
            } else {
                $query->where('status', $request->status);
            }
        }

        return $query;
    }
}
