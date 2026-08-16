<?php

namespace App\Http\Controllers;

use App\Models\CompetencyEvaluation;
use App\Models\Department;
use App\Models\Equipment;
use App\Models\EquipmentCategory;
use App\Models\LogbookHistory;
use App\Models\OjtLogbook;
use App\Models\User;
use App\Models\LogbookAssignment;
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
            ->whereHas('assignments', function ($q) use ($trainer) {
                $q->where('user_id', $trainer->id)
                  ->where('status', 'pending');
            });

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        } else {
            $query->whereIn('status', ['submitted']);
        }
        if ($request->filled('search')) {
            $term = $request->search;
            $query->where(fn ($q) => $q->where('logbook_number', 'like', "%{$term}%")
                ->orWhereHas('trainee', fn ($u) => $u->where('name', 'like', "%{$term}%")->orWhere('sid', 'like', "%{$term}%")));
        }

        $logbooks = $query->latest('submitted_at')->paginate(10)->withQueryString();

        $counts = [
            'submitted' => OjtLogbook::where('status', 'submitted')->count(),
            'revision' => 0,
            'verified' => 0,
        ];
        return view('trainer.reviews.index', compact('trainer', 'logbooks', 'counts'));
    }

    public function show($id)
    {
        $logbook = OjtLogbook::with(['trainee.department', 'trainer', 'department', 'equipment', 'equipmentCategory', 'histories.user', 'evaluation', 'assignedPjo', 'assignedTc'])->findOrFail($id);
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
        abort_unless($logbook->status === 'submitted', 403);
        abort_unless($logbook->assignments()->where('user_id', $trainer->id)->where('status', 'pending')->exists(), 403);

        $user = $logbook->trainee;
        $departments = Department::whereIn('code', ['CHCPP', 'RIM', 'PLANT'])->get();
        $categories = EquipmentCategory::whereIn('code', ['EXC', 'DZ', 'MG', 'HDT', 'SDT', 'WL'])->get();
        $trainers = User::where('role', 'trainer')->get();
        $equipments = Equipment::where('status', 'active')->get();
        $assignedPengawas = !empty($logbook->selected_pengawas_ids)
            ? User::whereIn('id', $logbook->selected_pengawas_ids)->get()
            : collect();
        $assignedOperators = !empty($logbook->selected_operator_pendamping_ids)
            ? User::whereIn('id', $logbook->selected_operator_pendamping_ids)->get()
            : collect();

        return view('trainer.reviews.edit', compact('logbook', 'user', 'departments', 'categories', 'trainers', 'equipments', 'assignedPengawas', 'assignedOperators'));
    }

    public function updateLogbook(Request $request, $id)
    {
        $trainer = auth()->user();
        abort_unless($trainer && $trainer->isTrainer(), 403);
        $logbook = OjtLogbook::findOrFail($id);
        abort_unless($logbook->status === 'submitted', 403);
        abort_unless($logbook->assignments()->where('user_id', $trainer->id)->where('status', 'pending')->exists(), 403);

        $data = $request->validate([
            'date' => ['required', 'date'], 'shift' => ['required', 'in:day,night'],
            'location' => ['required', 'string', 'max:255'], 'equipment_number' => ['required', 'string', 'max:100'],
            'hm_start' => ['required', 'numeric', 'min:0'], 'hm_end' => ['required', 'numeric', 'gte:hm_start'],
            'daily_activity' => ['nullable', 'string'],
            'daily_activity_backup' => ['nullable', 'string'],
            'sop_payload' => ['nullable', 'array'],
        ]);

        $activity = trim((string)($data['daily_activity'] ?? ''));
        $backup = trim((string)($data['daily_activity_backup'] ?? ''));
        $data['daily_activity'] = $activity !== '' ? $activity : $backup;

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
        abort_unless($logbook->status === 'submitted', 403);
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
