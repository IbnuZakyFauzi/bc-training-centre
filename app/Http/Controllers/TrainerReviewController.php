<?php

namespace App\Http\Controllers;

use App\Models\CompetencyEvaluation;
use App\Models\LogbookHistory;
use App\Models\OjtLogbook;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrainerReviewController extends Controller
{
    public function index(Request $request)
    {
        $trainer = auth()->user();
        abort_unless($trainer && $trainer->isTrainer(), 403);
        $query = OjtLogbook::with(['trainee.department', 'equipment', 'department', 'evaluation'])
            ->where('trainer_id', $trainer->id)
            ->whereIn('status', ['submitted', 'revision', 'verified']);

        if ($request->filled('status') && $request->status !== 'all') $query->where('status', $request->status);
        if ($request->filled('search')) {
            $term = $request->search;
            $query->where(fn ($q) => $q->where('logbook_number', 'like', "%{$term}%")
                ->orWhereHas('trainee', fn ($u) => $u->where('name', 'like', "%{$term}%")->orWhere('nrp', 'like', "%{$term}%")));
        }

        $logbooks = $query->latest('submitted_at')->paginate(10)->withQueryString();
        $counts = [
            'submitted' => OjtLogbook::where('trainer_id', $trainer->id)->where('status', 'submitted')->count(),
            'revision' => OjtLogbook::where('trainer_id', $trainer->id)->where('status', 'revision')->count(),
            'verified' => OjtLogbook::where('trainer_id', $trainer->id)->where('status', 'verified')->count(),
        ];
        return view('trainer.reviews.index', compact('trainer', 'logbooks', 'counts'));
    }

    public function show($id)
    {
        $logbook = OjtLogbook::with(['trainee.department', 'trainer', 'department', 'equipment', 'equipmentCategory', 'evidences', 'histories.user', 'evaluation'])->findOrFail($id);
        abort_unless($logbook->status !== 'draft', 403);
        return view('ojt.logbooks.show', ['logbook' => $logbook, 'trainerReview' => true]);
    }

    public function evaluate(Request $request, $id)
    {
        $data = $request->validate([
            'action' => ['required', 'in:revision,verify'],
            'safety' => ['required', 'integer', 'min:1', 'max:4'],
            'operation' => ['required', 'integer', 'min:1', 'max:4'],
            'procedure' => ['required', 'integer', 'min:1', 'max:4'],
            'communication' => ['required', 'integer', 'min:1', 'max:4'],
            'training_phase' => ['nullable', 'string', 'max:100'],
            'trainer_comment' => ['nullable', 'string', 'max:2000'],
            'revision_instruction' => ['required_if:action,revision', 'nullable', 'string', 'max:2000'],
            'competency_status' => ['required_if:action,verify', 'nullable', 'in:competent,not_yet_competent'],
            'send_to_pjo' => ['nullable', 'boolean'],
            'trainer_signature' => ['required_if:action,verify', 'nullable', 'file', 'mimes:png,jpg,jpeg', 'max:2048'],
        ]);
        $trainer = auth()->user();
        abort_unless($trainer && $trainer->isTrainer(), 403);
        $logbook = OjtLogbook::findOrFail($id);
        abort_unless($logbook->trainer_id === $trainer->id, 403);
        abort_unless(in_array($logbook->status, ['submitted', 'revision']), 422, 'Logbook ini sudah selesai diproses.');
        $previousStatus = $logbook->status;

        $signaturePath = $request->hasFile('trainer_signature')
            ? $request->file('trainer_signature')->store('trainer-signatures', 'public')
            : $logbook->evaluation?->trainer_signature_path;

        DB::transaction(function () use ($data, $trainer, $logbook, $previousStatus, $signaturePath) {
            $score = (int) round(collect(['safety', 'operation', 'procedure', 'communication'])->avg(fn ($field) => $data[$field]) * 25);
            $isRevision = $data['action'] === 'revision';
            $newStatus = $isRevision ? 'revision' : 'verified';
            $evaluation = CompetencyEvaluation::updateOrCreate(
                ['ojt_logbook_id' => $logbook->id],
                [
                    'trainer_id' => $trainer->id,
                    'overall_score' => $score,
                    'competency_status' => $isRevision ? 'not_yet_competent' : $data['competency_status'],
                    'assessment_payload' => array_merge(
                        collect(['safety', 'operation', 'procedure', 'communication'])->mapWithKeys(fn ($field) => [$field => $data[$field]])->all(),
                        ['training_phase' => $data['training_phase'] ?? null]
                    ),
                    'trainer_comment' => $data['trainer_comment'] ?? null,
                    'revision_instruction' => $isRevision ? $data['revision_instruction'] : null,
                    'trainer_signature_path' => !$isRevision ? $signaturePath : null,
                    'evaluated_at' => now(),
                    'sent_to_pjo_at' => !$isRevision ? now() : null,
                ]
            );
            $logbook->update([
                'status' => $newStatus,
                'revision_notes' => $isRevision ? $data['revision_instruction'] : null,
                'verified_at' => !$isRevision ? now() : null,
                'pjo_id' => null, 'pjo_notes' => null, 'pjo_decided_at' => null,
                'training_centre_id' => null, 'training_centre_notes' => null, 'training_centre_decided_at' => null,
            ]);
            LogbookHistory::create([
                'ojt_logbook_id' => $logbook->id, 'user_id' => $trainer->id,
                'action' => $isRevision ? 'Revision Requested by Trainer' : 'Competency Evaluation Verified',
                'from_status' => $previousStatus, 'to_status' => $newStatus,
                'comment' => $isRevision ? $data['revision_instruction'] : 'Evaluation completed: '.($data['competency_status'] === 'competent' ? 'Kompeten' : 'Belum Kompeten').'. Forwarded to Supervisor approval.',
            ]);
        });
        return redirect()->route('trainer.reviews.index')->with('success', $data['action'] === 'revision' ? 'Revisi telah dikirim kepada Trainee.' : 'Evaluasi kompetensi berhasil diverifikasi.');
    }
}
