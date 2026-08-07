<?php

namespace App\Http\Controllers;

use App\Models\CompetencyEvaluation;
use App\Models\LogbookHistory;
use App\Models\OjtLogbook;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TrainerReviewController extends Controller
{
    public function index(Request $request)
    {
        $trainer = auth()->user();
        abort_unless($trainer && $trainer->isTrainer(), 403);
        $query = OjtLogbook::with(['trainee.department', 'equipment', 'department', 'evaluation'])
            ->where('trainer_id', $trainer->id)
            ->whereIn('status', ['submitted', 'verified']);

        if ($request->filled('status') && $request->status !== 'all') $query->where('status', $request->status);
        if ($request->filled('search')) {
            $term = $request->search;
            $query->where(fn ($q) => $q->where('logbook_number', 'like', "%{$term}%")
                ->orWhereHas('trainee', fn ($u) => $u->where('name', 'like', "%{$term}%")->orWhere('nrp', 'like', "%{$term}%")));
        }

        $logbooks = $query->latest('submitted_at')->paginate(10)->withQueryString();
        $counts = [
            'submitted' => OjtLogbook::where('trainer_id', $trainer->id)->where('status', 'submitted')->count(),
            'revision' => 0,
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

    public function edit($id)
    {
        $trainer = auth()->user();
        abort_unless($trainer && $trainer->isTrainer(), 403);
        $logbook = OjtLogbook::with(['equipmentCategory'])->findOrFail($id);
        abort_unless($logbook->trainer_id === $trainer->id && $logbook->status === 'submitted', 403);

        return view('trainer.reviews.edit', compact('logbook'));
    }

    public function updateLogbook(Request $request, $id)
    {
        $trainer = auth()->user();
        abort_unless($trainer && $trainer->isTrainer(), 403);
        $logbook = OjtLogbook::findOrFail($id);
        abort_unless($logbook->trainer_id === $trainer->id && $logbook->status === 'submitted', 403);

        $data = $request->validate([
            'date' => ['required', 'date'], 'shift' => ['required', 'in:day,night'],
            'location' => ['required', 'string', 'max:255'], 'equipment_number' => ['required', 'string', 'max:100'],
            'start_time' => ['required'], 'finish_time' => ['required'],
            'hm_start' => ['required', 'numeric', 'min:0'], 'hm_end' => ['required', 'numeric', 'gte:hm_start'],
            'daily_activity' => ['required', 'string', 'min:10'],
        ]);
        $data['total_hm'] = max(0, (float) $data['hm_end'] - (float) $data['hm_start']);
        $logbook->update($data);
        LogbookHistory::create(['ojt_logbook_id' => $logbook->id, 'user_id' => $trainer->id, 'action' => 'Logbook Edited by Trainer', 'from_status' => 'submitted', 'to_status' => 'submitted', 'comment' => 'Data logbook diperbarui oleh trainer sebelum approval.']);

        return redirect()->route('trainer.reviews.show', $logbook->id)->with('success', 'Perubahan logbook oleh trainer berhasil disimpan.');
    }

    public function updateChecklist(Request $request, $id)
    {
        $trainer = auth()->user();
        abort_unless($trainer && $trainer->isTrainer(), 403);
        $logbook = OjtLogbook::findOrFail($id);
        abort_unless($logbook->trainer_id === $trainer->id && $logbook->status === 'submitted', 403);
        $data = $request->validate(['checklist' => ['required', 'array']]);

        $payload = $logbook->sop_payload ?? [];
        $family = data_get($payload, 'meta.unit_family');
        abort_unless(in_array($family, ['track', 'excavator'], true), 422);
        foreach (['groups', 'behavior'] as $section) {
            foreach ($data['checklist'][$section] ?? [] as $groupIndex => $group) {
                $items = $section === 'groups' ? ($group['items'] ?? []) : [$groupIndex => $group];
                foreach ($items as $itemIndex => $item) {
                    $path = $section === 'groups' ? "{$family}.groups.{$groupIndex}.items.{$itemIndex}" : "{$family}.behavior.{$itemIndex}";
                    data_set($payload, "{$path}.status", $item['status'] ?? null);
                    data_set($payload, "{$path}.note", $item['note'] ?? null);
                }
            }
        }
        $logbook->update(['sop_payload' => $payload]);
        LogbookHistory::create(['ojt_logbook_id' => $logbook->id, 'user_id' => $trainer->id, 'action' => 'Checklist K/BK Edited by Trainer', 'from_status' => 'submitted', 'to_status' => 'submitted', 'comment' => 'Checklist SOP diperbarui oleh trainer.']);

        return redirect()->route('trainer.reviews.show', $logbook->id)->with('success', 'Checklist K/BK berhasil diperbarui.');
    }

    public function evaluate(Request $request, $id)
    {
        $data = $request->validate([
            'action' => ['required', 'in:verify'],
            'safety' => ['required', 'integer', 'min:1', 'max:4'],
            'operation' => ['required', 'integer', 'min:1', 'max:4'],
            'procedure' => ['required', 'integer', 'min:1', 'max:4'],
            'communication' => ['required', 'integer', 'min:1', 'max:4'],
            'training_phase' => ['nullable', 'string', 'max:100'],
            'trainer_comment' => ['nullable', 'string', 'max:2000'],
            'competency_status' => ['required', 'in:competent,not_yet_competent'],
            'send_to_pjo' => ['nullable', 'boolean'],
        ]);
        $trainer = auth()->user();
        abort_unless($trainer && $trainer->isTrainer(), 403);
        $logbook = OjtLogbook::findOrFail($id);
        abort_unless($logbook->trainer_id === $trainer->id, 403);
        abort_unless($logbook->status === 'submitted', 422, 'Logbook ini sudah selesai diproses.');
        $previousStatus = $logbook->status;
        if ($data['action'] === 'verify' && !$trainer->signature_path) {
            throw ValidationException::withMessages([
                'signature' => 'Simpan tanda tangan di My Profile dulu sebelum verifikasi.',
            ]);
        }

        $signaturePath = $trainer->signature_path;

        DB::transaction(function () use ($data, $trainer, $logbook, $previousStatus, $signaturePath) {
            $score = (int) round(collect(['safety', 'operation', 'procedure', 'communication'])->avg(fn ($field) => $data[$field]) * 25);
            $newStatus = 'verified';
            $evaluation = CompetencyEvaluation::updateOrCreate(
                ['ojt_logbook_id' => $logbook->id],
                [
                    'trainer_id' => $trainer->id,
                    'overall_score' => $score,
                    'competency_status' => $data['competency_status'],
                    'assessment_payload' => array_merge(
                        collect(['safety', 'operation', 'procedure', 'communication'])->mapWithKeys(fn ($field) => [$field => $data[$field]])->all(),
                        ['training_phase' => $data['training_phase'] ?? null]
                    ),
                    'trainer_comment' => $data['trainer_comment'] ?? null,
                    'revision_instruction' => null,
                    'trainer_signature_path' => $signaturePath,
                    'evaluated_at' => now(),
                    'sent_to_pjo_at' => now(),
                ]
            );
            $logbook->update([
                'status' => $newStatus,
                'revision_notes' => null,
                'verified_at' => now(),
                'pjo_id' => null, 'pjo_notes' => null, 'pjo_decided_at' => null,
                'training_centre_id' => null, 'training_centre_notes' => null, 'training_centre_decided_at' => null,
            ]);
            LogbookHistory::create([
                'ojt_logbook_id' => $logbook->id, 'user_id' => $trainer->id,
                'action' => 'Logbook Approved by Trainer',
                'from_status' => $previousStatus, 'to_status' => $newStatus,
                'comment' => 'Logbook disetujui trainer. Evaluasi: '.($data['competency_status'] === 'competent' ? 'Kompeten' : 'Belum Kompeten').'.',
            ]);
        });
        return redirect()->route('trainer.reviews.index')->with('success', 'Logbook berhasil disetujui trainer.');
    }
}
