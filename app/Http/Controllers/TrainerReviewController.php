<?php

namespace App\Http\Controllers;

use App\Models\CompetencyEvaluation;
use App\Models\Equipment;
use App\Models\EquipmentCategory;
use App\Models\LogbookHistory;
use App\Models\OjtLogbook;
use App\Models\User;
use App\Models\LogbookAssignment;
use App\Support\CompetencyScale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class TrainerReviewController extends Controller
{
    public function index(Request $request)
    {
        $trainer = auth()->user();
        abort_unless($trainer && $trainer->isTrainer(), 403);

        // OJT Logbook Review Queue
        $query = OjtLogbook::with(['trainee', 'equipment', 'evaluation'])
            ->whereHas('assignments', function ($q) use ($trainer) {
                $q->where('user_id', $trainer->id)
                  ->where('status', 'pending');
            });

        $activeStatus = $request->get('status', 'submitted');

        if ($activeStatus === 'verified') {
            $query->whereIn('status', ['verified', 'final_approved']);
        } elseif ($activeStatus === 'revision') {
            $query->where('status', 'revision');
        } else {
            $activeStatus = 'submitted';
            $query->whereIn('status', ['submitted']);
        }

        if ($request->filled('search')) {
            $term = $request->search;
            $query->where(fn ($q) => $q->where('logbook_number', 'like', "%{$term}%")
                ->orWhereHas('trainee', fn ($u) => $u->where('name', 'like', "%{$term}%")->orWhere('sid', 'like', "%{$term}%")));
        }

        $logbooks = $query->latest('submitted_at')->paginate(10)->withQueryString();

        $baseQuery = OjtLogbook::whereHas('assignments', function ($q) use ($trainer) {
            $q->where('user_id', $trainer->id)->where('status', 'pending');
        });

        $counts = [
            'submitted' => (clone $baseQuery)->whereIn('status', ['submitted'])->count(),
            'verified' => (clone $baseQuery)->whereIn('status', ['verified', 'final_approved'])->count(),
            'revision' => (clone $baseQuery)->where('status', 'revision')->count(),
        ];

        $eligibleTrainees = User::where('role', 'trainee')
            ->whereHas('assignedTrainers', fn ($q) => $q->where('trainer_id', $trainer->id))
            ->with('equipmentCategory')
            ->get()
            ->map(function ($trainee) {
                $phase = $trainee->currentPhaseKey();
                $hasOpenEval = \App\Models\FinalEvaluation::where('nama_operator', $trainee->name)
                    ->where('phase', $phase)
                    ->whereIn('status', ['submitted', 'tc_approved', 'pjo_approved'])
                    ->exists();

                return [
                    'id' => $trainee->id,
                    'name' => $trainee->name,
                    'certification' => $trainee->certification,
                    'phase' => $phase,
                    'phase_label' => $trainee->currentPhaseMeta()['label'] ?? '',
                    'eligible' => $trainee->isPhaseEligible() && ! $hasOpenEval,
                    'progress' => $trainee->phaseProgressPercent(),
                ];
            })
            ->where('eligible', true)
            ->values();

        // Evaluation Queue for Trainer
        $evaluationQuery = \App\Models\FinalEvaluation::with(['trainer', 'trainee'])
            ->where('trainer_id', $trainer->id)
            ->whereNotIn('status', ['hse_approved']);

        $evaluationStatus = $request->get('eval_status', 'submitted');

        if ($evaluationStatus === 'rejected') {
            $evaluationQuery->where('status', 'rejected');
        } elseif ($evaluationStatus === 'tc_approved') {
            $evaluationQuery->where('status', 'tc_approved');
        } elseif ($evaluationStatus === 'pjo_approved') {
            $evaluationQuery->where('status', 'pjo_approved');
        } else {
            $evaluationStatus = 'submitted';
            $evaluationQuery->where('status', 'submitted');
        }

        $evaluationQueue = $evaluationQuery->latest()->paginate(10, ['*'], 'eval_page')->withQueryString();

        $evaluationCounts = [
            'submitted' => \App\Models\FinalEvaluation::where('trainer_id', $trainer->id)->where('status', 'submitted')->count(),
            'rejected' => \App\Models\FinalEvaluation::where('trainer_id', $trainer->id)->where('status', 'rejected')->count(),
            'tc_approved' => \App\Models\FinalEvaluation::where('trainer_id', $trainer->id)->where('status', 'tc_approved')->count(),
            'pjo_approved' => \App\Models\FinalEvaluation::where('trainer_id', $trainer->id)->where('status', 'pjo_approved')->count(),
        ];

        return view('trainer.reviews.index', compact(
            'trainer', 'logbooks', 'counts', 'activeStatus', 'eligibleTrainees',
            'evaluationQueue', 'evaluationCounts', 'evaluationStatus'
        ));
    }

    public function show($id)
    {
        $logbook = OjtLogbook::with(['trainee', 'trainer', 'equipment', 'equipmentCategory', 'histories.user', 'evaluation', 'assignedPjo', 'assignedTc'])->findOrFail($id);
        $trainer = auth()->user();
        abort_unless($logbook->status !== 'draft', 403);
        abort_unless($logbook->assignments()->where('user_id', $trainer->id)->where('status', 'pending')->exists(), 403);

        return view('ojt.logbooks.show', ['logbook' => $logbook, 'trainerReview' => true, 'assignedPengawas' => collect($logbook->selected_pengawas_ids ?? [])->map(fn ($id) => User::find($id))->filter(), 'assignedOperators' => collect($logbook->selected_operator_pendamping_ids ?? [])->map(fn ($id) => User::find($id))->filter()]);
    }

    public function edit($id)
    {
        $trainer = auth()->user();
        abort_unless($trainer && $trainer->isTrainer(), 403);
        $logbook = OjtLogbook::with(['equipmentCategory'])->findOrFail($id);
        abort_unless(in_array($logbook->status, ['submitted', 'revision']), 403);
        abort_unless($logbook->assignments()->where('user_id', $trainer->id)->where('status', 'pending')->exists(), 403);

        $user = $logbook->trainee;
        $categories = EquipmentCategory::whereIn('code', ['EXC', 'DZ', 'MG', 'HDT', 'SDT', 'WL'])->get();
        $trainers = User::where('role', 'trainer')->get();
        $equipments = Equipment::where('status', 'active')->get();
        $assignedPengawas = !empty($logbook->selected_pengawas_ids)
            ? User::whereIn('id', $logbook->selected_pengawas_ids)->get()
            : collect();
        $assignedOperators = !empty($logbook->selected_operator_pendamping_ids)
            ? User::whereIn('id', $logbook->selected_operator_pendamping_ids)->get()
            : collect();

        return view('trainer.reviews.edit', compact('logbook', 'user', 'categories', 'trainers', 'equipments', 'assignedPengawas', 'assignedOperators'));
    }

    public function updateLogbook(Request $request, $id)
    {
        $trainer = auth()->user();
        abort_unless($trainer && $trainer->isTrainer(), 403);
        $logbook = OjtLogbook::findOrFail($id);
        abort_unless(in_array($logbook->status, ['submitted', 'revision']), 403);
        abort_unless($logbook->assignments()->where('user_id', $trainer->id)->where('status', 'pending')->exists(), 403);

        // Nilai item evaluasi memakai skala 1-4; nilai legacy 'K'/'BK' dikonversi
        // lebih dulu agar tetap kompatibel dengan logbook lama.
        if (is_array($request->input('sop_payload'))) {
            $request->merge([
                'sop_payload' => CompetencyScale::normalizePayload($request->input('sop_payload'), true),
            ]);
        }

        $data = $request->validate([
            'date' => ['required', 'date'], 'shift' => ['required', 'in:day,night'],
            'location' => ['required', 'string', 'max:255'], 'equipment_number' => ['required', 'string', 'max:100'],
            'hm_start' => ['required', 'numeric', 'min:0'], 'hm_end' => ['required', 'numeric', 'gte:hm_start'],
            'sop_payload' => ['nullable', 'array'],
            'sop_payload.meta.unit_type' => ['nullable', 'string', 'in:DZ,GR,HDT,LDT,SDT,ADT', Rule::requiredIf(function () use ($request) {
                return in_array($request->input('sop_payload.meta.unit_family'), ['track', 'dumptruck', 'semidump']);
            })],
            'sop_payload.*.groups.*.items.*.status' => ['nullable', 'integer', 'between:1,4'],
            'sop_payload.*.compliance.*.status' => ['nullable', 'integer', 'between:1,4'],
            'sop_payload.*.behavior.*.status' => ['nullable', 'integer', 'between:1,4'],
            'sop_payload.*.groups.*.items.*.trainee_feedback' => ['nullable', 'string'],
            'sop_payload.*.compliance.*.trainee_feedback' => ['nullable', 'string'],
            'sop_payload.*.behavior.*.trainee_feedback' => ['nullable', 'string'],
        ], [
            'sop_payload.meta.unit_type.required' => 'Pilih tipe unit (DZ/GR, HDT/LDT, atau SDT/ADT) sesuai kategori alat.',
            'sop_payload.meta.unit_type.in' => 'Tipe unit yang dipilih tidak valid.',
            'sop_payload.*.groups.*.items.*.status.integer' => 'Nilai item evaluasi harus berupa angka 1 (Belum), 2 (Cukup), 3 (Mampu), atau 4 (Mahir).',
            'sop_payload.*.groups.*.items.*.status.between' => 'Nilai item evaluasi harus berupa angka 1 (Belum), 2 (Cukup), 3 (Mampu), atau 4 (Mahir).',
            'sop_payload.*.compliance.*.status.integer' => 'Nilai item evaluasi harus berupa angka 1 (Belum), 2 (Cukup), 3 (Mampu), atau 4 (Mahir).',
            'sop_payload.*.compliance.*.status.between' => 'Nilai item evaluasi harus berupa angka 1 (Belum), 2 (Cukup), 3 (Mampu), atau 4 (Mahir).',
            'sop_payload.*.behavior.*.status.integer' => 'Nilai item evaluasi harus berupa angka 1 (Belum), 2 (Cukup), 3 (Mampu), atau 4 (Mahir).',
            'sop_payload.*.behavior.*.status.between' => 'Nilai item evaluasi harus berupa angka 1 (Belum), 2 (Cukup), 3 (Mampu), atau 4 (Mahir).',
        ]);

        // Nilai item evaluasi selalu disimpan sebagai integer 1-4 (legacy 'K'/'BK' dikonversi).
        if (isset($data['sop_payload']) && is_array($data['sop_payload'])) {
            $data['sop_payload'] = CompetencyScale::normalizePayload($data['sop_payload']);
        }

        if (isset($data['sop_payload']) && is_array($data['sop_payload'])) {
            $existingUnitType = data_get($logbook->sop_payload, 'meta.unit_type');
            if ($existingUnitType && empty($data['sop_payload']['meta']['unit_type'])) {
                data_set($data['sop_payload'], 'meta.unit_type', $existingUnitType);
            }
        }

        $data['total_hm'] = max(0, (float) $data['hm_end'] - (float) $data['hm_start']);
        $previousStatus = $logbook->status;
        $logbook->update($data);

        $newStatus = $previousStatus === 'revision' ? 'verified' : $logbook->status;
        if ($newStatus !== $previousStatus) {
            $logbook->update(['status' => $newStatus, 'verified_at' => now(), 'training_centre_decided_at' => null]);
            LogbookHistory::create(['ojt_logbook_id' => $logbook->id, 'user_id' => $trainer->id, 'action' => 'Revision Resubmitted by Trainer', 'from_status' => $previousStatus, 'to_status' => $newStatus, 'comment' => 'Logbook telah diperbaiki dan dikirim kembali ke Admin TC.']);
        } else {
            LogbookHistory::create(['ojt_logbook_id' => $logbook->id, 'user_id' => $trainer->id, 'action' => 'Logbook Edited by Trainer', 'from_status' => $previousStatus, 'to_status' => $previousStatus, 'comment' => 'Data logbook diperbarui oleh trainer sebelum approval.']);
        }

        return redirect()->route('trainer.reviews.show', $logbook->id)->with('success', $previousStatus === 'revision' ? 'Revisi logbook berhasil disimpan dan dikirim ke Admin TC.' : 'Perubahan logbook oleh trainer berhasil disimpan.');
    }

    public function updateChecklist(Request $request, $id)
    {
        $trainer = auth()->user();
        abort_unless($trainer && $trainer->isTrainer(), 403);
        $logbook = OjtLogbook::findOrFail($id);
        abort_unless(in_array($logbook->status, ['submitted', 'revision']), 403);
        abort_unless($logbook->assignments()->where('user_id', $trainer->id)->where('status', 'pending')->exists(), 403);
        $data = $request->validate(['checklist' => ['required', 'array']]);

        $payload = $logbook->sop_payload ?? [];
        $family = data_get($payload, 'meta.unit_family');
        abort_unless(in_array($family, ['track', 'excavator', 'dumptruck', 'semidump', 'wheelloader'], true), 422);
        foreach (['groups', 'compliance', 'behavior'] as $section) {
            foreach ($data['checklist'][$section] ?? [] as $groupIndex => $group) {
                $items = $section === 'groups' ? ($group['items'] ?? []) : [$groupIndex => $group];
                foreach ($items as $itemIndex => $item) {
                    $path = $section === 'groups' ? "{$family}.groups.{$groupIndex}.items.{$itemIndex}" : ($section === 'compliance' ? "{$family}.compliance.{$itemIndex}" : "{$family}.behavior.{$itemIndex}");
                    data_set($payload, "{$path}.status", CompetencyScale::toScale($item['status'] ?? null));
                    $feedback = $item['trainee_feedback'] ?? $item['note'] ?? null;
                    data_set($payload, "{$path}.trainee_feedback", $feedback);
                }
            }
        }
        $logbook->update(['sop_payload' => $payload]);

        $previousStatus = $logbook->status;
        if ($previousStatus === 'revision') {
            $logbook->update(['status' => 'verified', 'verified_at' => now(), 'training_centre_decided_at' => null]);
            LogbookHistory::create(['ojt_logbook_id' => $logbook->id, 'user_id' => $trainer->id, 'action' => 'Revision Resubmitted by Trainer', 'from_status' => $previousStatus, 'to_status' => 'verified', 'comment' => 'Checklist SOP revisi telah diperbarui dan dikirim kembali ke Admin TC.']);
        } else {
            LogbookHistory::create(['ojt_logbook_id' => $logbook->id, 'user_id' => $trainer->id, 'action' => 'Checklist K/BK Edited by Trainer', 'from_status' => $previousStatus, 'to_status' => $previousStatus, 'comment' => 'Checklist SOP diperbarui oleh trainer.']);
        }

        return redirect()->route('trainer.reviews.show', $logbook->id)->with('success', $previousStatus === 'revision' ? 'Checklist revisi berhasil disimpan dan dikirim ke Admin TC.' : 'Checklist K/BK berhasil diperbarui.');
    }

    public function evaluate(Request $request, $id)
    {
        $trainer = auth()->user();
        abort_unless($trainer && $trainer->isTrainer(), 403);
        $logbook = OjtLogbook::findOrFail($id);
        abort_unless($logbook->assignments()->where('user_id', $trainer->id)->where('status', 'pending')->exists(), 403);
        abort_unless($logbook->status === 'submitted', 422, 'Logbook ini sudah selesai diproses.');

        $data = $request->validate([
            'action' => ['required', 'in:verify,revision'],
            'safety' => ['required_if:action,verify', 'integer', 'min:1', 'max:4'],
            'operation' => ['required_if:action,verify', 'integer', 'min:1', 'max:4'],
            'procedure' => ['required_if:action,verify', 'integer', 'min:1', 'max:4'],
            'communication' => ['required_if:action,verify', 'integer', 'min:1', 'max:4'],
            'training_phase' => ['nullable', 'string', 'max:100'],
            'trainer_comment' => ['nullable', 'string', 'max:2000'],
            'competency_status' => ['required_if:action,verify', 'in:competent,not_yet_competent'],
            'assigned_tc_id' => ['nullable', 'exists:users,id'],
            'revision_instruction' => ['required_if:action,revision', 'nullable', 'string', 'max:2000'],
        ]);

        if ($data['action'] === 'revision') {
            $logbook->update([
                'status' => 'revision',
                'revision_notes' => $data['revision_instruction'],
                'verified_at' => null,
                'training_centre_id' => null,
                'training_centre_notes' => null,
                'training_centre_decided_at' => null,
            ]);
            LogbookHistory::create([
                'ojt_logbook_id' => $logbook->id,
                'user_id' => $trainer->id,
                'action' => 'Revision Requested by Trainer',
                'from_status' => 'submitted',
                'to_status' => 'revision',
                'comment' => $data['revision_instruction'],
            ]);

            $logbook->trainee?->notify(new \App\Notifications\LogbookRevisionRequestedNotification([
                'title' => 'Logbook Memerlukan Revisi',
                'message' => "Trainer meminta revisi pada logbook {$logbook->logbook_number}. Silakan perbaiki sesuai catatan.",
                'url' => route('ojt.logbooks.edit', $logbook->id),
            ]));

            return redirect()->route('trainer.reviews.index')->with('success', 'Logbook telah dikembalikan untuk revisi.');
        }

        $previousStatus = $logbook->status;
        if (!$trainer->signature_path) {
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
                ]
            );
            $logbook->update([
                'status' => $newStatus,
                'revision_notes' => null,
                'verified_at' => now(),
                'training_centre_id' => null, 'training_centre_notes' => null, 'training_centre_decided_at' => null,
                'assigned_tc_id' => $data['assigned_tc_id'] ?? null,
                'assigned_pjo_id' => !empty($logbook->selected_pengawas_ids) ? $logbook->selected_pengawas_ids[0] : null,
            ]);
            LogbookHistory::create([
                'ojt_logbook_id' => $logbook->id, 'user_id' => $trainer->id,
                'action' => 'Logbook Verified by Trainer',
                'from_status' => $previousStatus, 'to_status' => $newStatus,
                'comment' => 'Logbook diverifikasi trainer. Evaluasi: '.($data['competency_status'] === 'competent' ? 'Kompeten' : 'Belum Kompeten').'.',
            ]);

            \App\Models\User::where('role', 'admin')->get()->each(function ($adminTc) use ($logbook) {
                $adminTc->notify(new \App\Notifications\LogbookSubmittedNotification([
                    'title' => 'Form OJT Baru Menunggu Persetujuan Final',
                    'message' => "Form OJT {$logbook->logbook_number} dari {$logbook->trainee->name} telah diverifikasi trainer dan menunggu persetujuan Anda.",
                    'url' => route('training-centre.approvals.show', $logbook->id),
                ]));
            });

            $logbook->trainee?->notify(new \App\Notifications\LogbookApprovedNotification([
                'title' => 'Form OJT Telah Diverifikasi Trainer',
                'message' => "Form OJT {$logbook->logbook_number} Anda telah diverifikasi oleh trainer dan dikirim ke Admin TC untuk persetujuan final.",
                'url' => route('ojt.logbooks.show', $logbook->id),
            ]));

            if (!empty($logbook->selected_pengawas_ids)) {
                foreach ($logbook->selected_pengawas_ids as $pengawasId) {
                    LogbookAssignment::updateOrCreate([
                        'ojt_logbook_id' => $logbook->id,
                        'user_id' => $pengawasId,
                        'role_type' => 'pengawas',
                    ], [
                        'status' => 'pending',
                    ]);
                }
            }
            if (!empty($logbook->selected_operator_pendamping_ids)) {
                foreach ($logbook->selected_operator_pendamping_ids as $operatorId) {
                    LogbookAssignment::updateOrCreate([
                        'ojt_logbook_id' => $logbook->id,
                        'user_id' => $operatorId,
                        'role_type' => 'operator_pendamping',
                    ], [
                        'status' => 'pending',
                    ]);
                }
            }
        });
        return redirect()->route('trainer.reviews.index')->with('success', 'Logbook berhasil diverifikasi dan dikirim ke Final Approval Kabag Training Centre.');
    }
}
