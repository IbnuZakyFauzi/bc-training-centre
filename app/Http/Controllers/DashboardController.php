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

        $kpi = [
            'draft' => OjtLogbook::where('trainee_id', $traineeId)->where('status', 'draft')->count(),
            'submitted' => OjtLogbook::where('trainee_id', $traineeId)->where('status', 'submitted')->count(),
            'revision' => OjtLogbook::where('trainee_id', $traineeId)->where('status', 'revision')->count(),
            'approved' => $approvedLogbooks->count(),
            'total_logbooks' => OjtLogbook::where('trainee_id', $traineeId)->count(),
            'total_hm' => $approvedLogbooks->sum('total_hm'),
            'hm_day' => $approvedLogbooks->where('shift', 'day')->sum('total_hm'),
            'hm_night' => $approvedLogbooks->where('shift', 'night')->sum('total_hm'),
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

        // Weekly HM Chart Data (Last 7 days)
        $weeklyChartData = [
            'categories' => ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
            'series' => [
                [
                    'name' => 'HM Hours',
                    'data' => [8.5, 7.0, 8.5, 8.0, 7.5, 0, 8.5],
                ]
            ]
        ];

        return view('ojt.dashboard', compact('user', 'kpi', 'recentLogbooks', 'latestDraft', 'weeklyChartData'));
    }
}
