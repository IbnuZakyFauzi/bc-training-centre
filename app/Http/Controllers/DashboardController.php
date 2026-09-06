<?php

namespace App\Http\Controllers;

use App\Models\OjtLogbook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        abort_unless($user && $user->isTrainee(), 403);

        $traineeId = $user ? $user->id : 1;

        // KPI Counts
        $approvedLogbooks = OjtLogbook::where('trainee_id', $traineeId)
            ->whereIn('status', ['verified', 'final_approved'])
            ->get();

        $initialHmDay = (float) ($user->initial_hm_day ?? 0);
        $initialHmNight = (float) ($user->initial_hm_night ?? 0);

        $kpi = [
            'draft' => OjtLogbook::where('trainee_id', $traineeId)->where('status', 'draft')->count(),
            'submitted' => OjtLogbook::where('trainee_id', $traineeId)->where('status', 'submitted')->count(),
            'revision' => OjtLogbook::where('trainee_id', $traineeId)->where('status', 'revision')->count(),
            'approved' => $approvedLogbooks->count(),
            'total_logbooks' => OjtLogbook::where('trainee_id', $traineeId)->count(),
            'total_hm' => $approvedLogbooks->sum('total_hm') + $initialHmDay + $initialHmNight,
            'hm_day' => $approvedLogbooks->where('shift', 'day')->sum('total_hm') + $initialHmDay,
            'hm_night' => $approvedLogbooks->where('shift', 'night')->sum('total_hm') + $initialHmNight,
            'initial_hm_day' => $initialHmDay,
            'initial_hm_night' => $initialHmNight,
        ];

        // Recent Activity
        $recentLogbooks = OjtLogbook::where('trainee_id', $traineeId)
            ->with(['equipment', 'trainer'])
            ->orderBy('date', 'desc')
            ->limit(5)
            ->get();

        // Continue Draft
        $latestDraft = OjtLogbook::where('trainee_id', $traineeId)
            ->where('status', 'draft')
            ->latest()
            ->first();

        // Phase & HM progress (OJT Multi-Phase)
        $phaseMeta = $user->currentPhaseMeta();
        $hmProgress = $user->hmProgress();
        $phaseProgress = $user->phaseProgressPercent();
        $currentEvaluation = \App\Models\FinalEvaluation::where('nama_operator', $user->name)
            ->where('phase', $user->currentPhaseKey())
            ->latest()
            ->first();

        return view('ojt.dashboard', compact(
            'user', 'kpi', 'recentLogbooks', 'latestDraft',
            'phaseMeta', 'hmProgress', 'phaseProgress', 'currentEvaluation'
        ));
    }
}
