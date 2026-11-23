<?php

namespace App\Http\Controllers;

use App\Models\LogbookHistory;
use App\Models\OjtLogbook;
use App\Models\User;
use App\Services\PhaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubmissionHistoryController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user() ?? User::where('role', 'trainee')->first();
        $traineeId = $user ? $user->id : 1;
        $cert = $user?->certification ?? 'Green';

        $phases = PhaseService::sequence($cert);
        $currentPhaseKey = PhaseService::currentPhaseKey($user);

        $query = OjtLogbook::where('trainee_id', $traineeId)
            ->with(['equipment', 'equipmentCategory', 'trainer', 'histories.user', 'evidences'])
            ->withCount(['histories as revision_count' => function ($q) {
                $q->where('to_status', 'revision');
            }]);

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = mb_strtolower(trim($request->search));
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(logbook_number) LIKE ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(location) LIKE ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(equipment_number) LIKE ?', ["%{$search}%"])
                    ->orWhereHas('trainer', fn ($t) => $t->whereRaw('LOWER(name) LIKE ?', ["%{$search}%"]));
            });
        }

        $allLogbooks = $query->orderBy('date', 'desc')->orderBy('created_at', 'desc')->get();

        // Group logbooks per evaluation phase
        $phaseGroups = collect($phases)->map(function ($phase) use ($allLogbooks, $currentPhaseKey) {
            $key = $phase['key'];
            $items = $allLogbooks->filter(function ($log) use ($key) {
                return $log->phase_key === $key;
            })->values();

            $totalHm = (float) $items->sum('total_hm');
            $dayHm = (float) $items->sum('hm_day');
            $nightHm = (float) $items->sum('hm_night');
            $count = $items->count();

            $targetHm = $phase['total_hm'] ?? null;
            $percent = 0;
            if (($phase['type'] ?? '') === 'bulanan') {
                $percent = min(100, round(($count / 4) * 100));
            } elseif ($targetHm && $targetHm > 0) {
                $percent = min(100, round(($totalHm / $targetHm) * 100));
            }

            return [
                'key' => $key,
                'label' => $phase['label'],
                'type' => $phase['type'] ?? 'evaluasi',
                'target_total_hm' => $targetHm,
                'target_day_hm' => $phase['day_hm'] ?? null,
                'target_night_hm' => $phase['night_hm'] ?? null,
                'total_hm' => $totalHm,
                'day_hm' => $dayHm,
                'night_hm' => $nightHm,
                'percent' => $percent,
                'logbooks' => $items,
                'count' => $count,
                'is_current' => ($key === $currentPhaseKey),
            ];
        });

        // Any logbooks whose phase_key is outside standard sequence
        $knownKeys = collect($phases)->pluck('key')->all();
        $otherItems = $allLogbooks->reject(fn ($l) => in_array($l->phase_key, $knownKeys))->values();
        if ($otherItems->isNotEmpty()) {
            $phaseGroups->push([
                'key' => 'other',
                'label' => 'Logbook Lainnya',
                'type' => 'other',
                'target_total_hm' => null,
                'target_day_hm' => null,
                'target_night_hm' => null,
                'total_hm' => (float) $otherItems->sum('total_hm'),
                'day_hm' => (float) $otherItems->sum('hm_day'),
                'night_hm' => (float) $otherItems->sum('hm_night'),
                'percent' => 0,
                'logbooks' => $otherItems,
                'count' => $otherItems->count(),
                'is_current' => false,
            ]);
        }

        // Summary KPI stats
        $stats = [
            'total_form' => $allLogbooks->count(),
            'total_hm' => (float) $allLogbooks->sum('total_hm'),
            'draft' => $allLogbooks->where('status', 'draft')->count(),
            'submitted' => $allLogbooks->where('status', 'submitted')->count(),
            'revision' => $allLogbooks->where('status', 'revision')->count(),
            'approved' => $allLogbooks->whereIn('status', ['verified', 'final_approved'])->count(),
        ];

        // Audit Trail Timeline
        $timelineHistories = LogbookHistory::whereHas('logbook', function ($q) use ($traineeId) {
            $q->where('trainee_id', $traineeId);
        })->with(['logbook.equipment', 'user'])->orderBy('created_at', 'desc')->limit(20)->get();

        return view('ojt.history', compact('user', 'phaseGroups', 'currentPhaseKey', 'timelineHistories', 'stats', 'allLogbooks'));
    }
}
