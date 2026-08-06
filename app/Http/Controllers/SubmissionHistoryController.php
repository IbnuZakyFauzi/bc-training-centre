<?php

namespace App\Http\Controllers;

use App\Models\OjtLogbook;
use App\Models\LogbookHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubmissionHistoryController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user() ?? User::where('role', 'trainee')->first();
        $traineeId = $user ? $user->id : 1;

        $query = OjtLogbook::where('trainee_id', $traineeId)
            ->with(['equipment', 'trainer', 'histories.user'])
            ->withCount(['histories as revision_count' => function($q) {
                $q->where('to_status', 'revision');
            }]);

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $logbooks = $query->orderBy('updated_at', 'desc')->paginate(15);

        // System audit trail history
        $timelineHistories = LogbookHistory::whereHas('logbook', function($q) use ($traineeId) {
            $q->where('trainee_id', $traineeId);
        })->with(['logbook.equipment', 'user'])->orderBy('created_at', 'desc')->limit(20)->get();

        return view('ojt.history', compact('logbooks', 'timelineHistories'));
    }
}
