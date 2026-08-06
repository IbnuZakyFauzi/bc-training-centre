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
        $kpi = [
            'draft' => OjtLogbook::where('trainee_id', $traineeId)->where('status', 'draft')->count(),
            'submitted' => OjtLogbook::where('trainee_id', $traineeId)->where('status', 'submitted')->count(),
            'revision' => OjtLogbook::where('trainee_id', $traineeId)->where('status', 'revision')->count(),
            'approved' => OjtLogbook::where('trainee_id', $traineeId)->whereIn('status', ['approved', 'supervisor_approved', 'final_approved'])->count(),
            'total_logbooks' => OjtLogbook::where('trainee_id', $traineeId)->count(),
            'total_hm' => OjtLogbook::where('trainee_id', $traineeId)->whereIn('status', ['submitted', 'verified', 'approved', 'supervisor_approved', 'final_approved'])->sum('total_hm'),
            'target_hm' => 200.0, // 200 HM Hours required for OJT completion
        ];

        $kpi['progress_percentage'] = min(100, round(($kpi['total_hm'] / $kpi['target_hm']) * 100, 1));

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
