<?php

namespace App\Http\Controllers;

use App\Models\OjtLogbook;
use App\Models\EquipmentCategory;
use App\Models\Equipment;
use App\Models\User;
use App\Models\LogbookHistory;
use App\Models\LogbookAssignment;
use App\Models\FinalEvaluation;
use App\Http\Requests\StoreLogbookRequest;
use App\Http\Requests\UpdateLogbookRequest;
use App\Support\CompetencyScale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class OjtLogbookController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user() ?? User::where('role', 'trainee')->first();
        $traineeId = $user ? $user->id : 1;

        $query = OjtLogbook::where('trainee_id', $traineeId)
            ->with(['equipment', 'equipmentCategory', 'trainer']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('logbook_number', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhereHas('equipment', function($eqQuery) use ($search) {
                      $eqQuery->where('unit_code', 'like', "%{$search}%")
                              ->orWhere('model_name', 'like', "%{$search}%");
                  });
            });
        }

        // Status Filter
        if ($request->filled('status') && $request->status !== 'all') {
            if ($request->status === 'approved') {
                $query->whereIn('status', ['verified', 'final_approved']);
            } elseif ($request->status === 'revision') {
                $query->where('status', 'revision')->whereNull('training_centre_decided_at');
            } else {
                $query->where('status', $request->status);
            }
        }

        // Equipment Filter
        if ($request->filled('equipment_id')) {
            $query->where('equipment_id', $request->equipment_id);
        }

        // Date Range Filter
        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'date');
        $sortDir = $request->get('sort_dir', 'desc');

        if ($sortBy === 'hm_start') {
            $query->orderBy('hm_start', $sortDir)->orderBy('date', 'desc');
        } elseif ($sortBy === 'status') {
            $statusOrder = match ($sortDir) {
                'asc' => ['revision', 'submitted', 'draft', 'verified', 'final_approved'],
                'desc' => ['final_approved', 'verified', 'submitted', 'draft', 'revision'],
                default => ['revision', 'submitted', 'draft', 'verified', 'final_approved'],
            };

            $query->orderByRaw("CASE WHEN status IN ('" . implode("','", $statusOrder) . "') THEN 0 ELSE 1 END")
                ->orderByRaw("FIELD(status, '" . implode("','", $statusOrder) . "')")
                ->orderBy('date', 'desc');
        } elseif ($sortBy === 'logbook_number') {
            $query->orderBy('logbook_number', $sortDir);
        } else {
            $query->orderBy('date', $sortDir);
        }

        $logbooks = $query->paginate(10)->withQueryString();

        $allPengawasIds = $logbooks->flatMap(fn ($log) => $log->selected_pengawas_ids ?? [])->filter()->unique()->values();
        $allOperatorIds = $logbooks->flatMap(fn ($log) => $log->selected_operator_pendamping_ids ?? [])->filter()->unique()->values();

        $usersMap = User::whereIn('id', $allPengawasIds->merge($allOperatorIds))->get()->mapWithKeys(fn ($user) => [$user->id => $user]);

        $equipments = Equipment::with('category')->where('status', 'active')->get();
        $statusCounts = [
            'all' => OjtLogbook::where('trainee_id', $traineeId)->count(),
            'draft' => OjtLogbook::where('trainee_id', $traineeId)->where('status', 'draft')->count(),
            'submitted' => OjtLogbook::where('trainee_id', $traineeId)->where('status', 'submitted')->count(),
            'revision' => OjtLogbook::where('trainee_id', $traineeId)->where('status', 'revision')->whereNull('training_centre_decided_at')->count(),
            'approved' => OjtLogbook::where('trainee_id', $traineeId)->whereIn('status', ['verified', 'final_approved'])->count(),
        ];

        return view('ojt.logbooks.index', compact('logbooks', 'equipments', 'statusCounts', 'usersMap'));
    }

    public function create()
    {
        $user = Auth::user() ?? User::where('role', 'trainee')->first();
        $categories = EquipmentCategory::whereIn('code', ['EXC', 'DZ', 'MG', 'HDT', 'SDT', 'WL'])->with('equipments')->get();
        $trainers = User::where('role', 'trainer')->get();
        $equipments = Equipment::with('category')->where('status', 'active')->get();

        $assignedPengawas = $user->assignedPengawas()->get();
        $assignedOperators = $user->assignedOperatorPendamping()->get();

        return view('ojt.logbooks.create', compact('user', 'categories', 'trainers', 'equipments', 'assignedPengawas', 'assignedOperators'));
    }

    public function store(StoreLogbookRequest $request)
    {
        $user = Auth::user() ?? User::where('role', 'trainee')->first();
        $traineeId = $user ? $user->id : 1;

        $status = $request->action_type === 'submit' ? 'submitted' : 'draft';
        $totalHm = max(0, floatval($request->hm_end) - floatval($request->hm_start));
        $hmDay = $request->shift === 'day' ? $totalHm : 0;
        $hmNight = $request->shift === 'night' ? $totalHm : 0;

        $logbookNumber = 'LOG-' . date('Ym') . '-' . str_pad(OjtLogbook::count() + 1, 4, '0', STR_PAD_LEFT);

        $logbook = OjtLogbook::create([
            'logbook_number' => $logbookNumber,
            'trainee_id' => $traineeId,
            'equipment_category_id' => $request->equipment_category_id,
            'equipment_id' => $request->equipment_id,
            'equipment_number' => $request->equipment_number,
            'date' => $request->date,
            'shift' => $request->shift,
            'location' => $request->location,
            'trainer_id' => $request->trainer_id,
            'selected_pengawas_ids' => $request->input('selected_pengawas_ids', []),
            'selected_operator_pendamping_ids' => $request->input('selected_operator_pendamping_ids', []),
            'hm_start' => $request->hm_start,
            'hm_end' => $request->hm_end,
            'total_hm' => $totalHm,
            'hm_day' => $hmDay,
            'hm_night' => $hmNight,
            'sop_payload' => $request->input('sop_payload', []),
            'status' => $status,
            'submitted_at' => $status === 'submitted' ? now() : null,
        ]);

        if ($status === 'submitted') {
            $assignments = [];
            if ($request->trainer_id) {
                $assignments[] = ['user_id' => $request->trainer_id, 'role_type' => 'instruktur'];
            }
            foreach ($request->input('selected_pengawas_ids', []) as $pengawasId) {
                $assignments[] = ['user_id' => $pengawasId, 'role_type' => 'pengawas'];
            }
            foreach ($request->input('selected_operator_pendamping_ids', []) as $operatorId) {
                $assignments[] = ['user_id' => $operatorId, 'role_type' => 'operator_pendamping'];
            }
            foreach ($assignments as $assignment) {
                LogbookAssignment::create(array_merge($assignment, ['ojt_logbook_id' => $logbook->id, 'status' => 'pending']));
            }

            $submitPayload = [
                'title' => 'Form OJT Baru Menunggu Verifikasi',
                'message' => "Form OJT {$logbook->logbook_number} dari {$logbook->trainee->name} telah disubmit dan menunggu verifikasi Anda.",
                'url' => route('trainer.reviews.show', $logbook->id),
            ];

            if ($request->trainer_id) {
                \App\Models\User::find($request->trainer_id)?->notify(new \App\Notifications\LogbookSubmittedNotification($submitPayload));
            }
            foreach ($request->input('selected_pengawas_ids', []) as $pengawasId) {
                \App\Models\User::find($pengawasId)?->notify(new \App\Notifications\LogbookSubmittedNotification($submitPayload));
            }
            foreach ($request->input('selected_operator_pendamping_ids', []) as $operatorId) {
                \App\Models\User::find($operatorId)?->notify(new \App\Notifications\LogbookSubmittedNotification($submitPayload));
            }
        }

        // History Log
        LogbookHistory::create([
            'ojt_logbook_id' => $logbook->id,
            'user_id' => $traineeId,
            'action' => $status === 'submitted' ? 'Logbook Submitted to Trainer' : 'Draft Created',
            'from_status' => null,
            'to_status' => $status,
            'comment' => $status === 'submitted' ? 'Logbook submitted for verification.' : 'Draft saved successfully.',
        ]);

        $message = $status === 'submitted' ? 'Logbook berhasil dikirim ke Trainer untuk verifikasi.' : 'Draft Logbook berhasil disimpan.';

        return redirect()->route('ojt.logbooks.index')->with('success', $message);
    }

    public function show($id)
    {
        $logbook = OjtLogbook::with(['trainee', 'trainer', 'equipmentCategory', 'equipment', 'histories.user'])->findOrFail($id);
        $assignedPengawas = collect($logbook->selected_pengawas_ids ?? [])->map(fn ($id) => User::find($id))->filter();
        $assignedOperators = collect($logbook->selected_operator_pendamping_ids ?? [])->map(fn ($id) => User::find($id))->filter();

        return view('ojt.logbooks.show', compact('logbook', 'assignedPengawas', 'assignedOperators'));
    }

    public function edit($id)
    {
        $logbook = OjtLogbook::findOrFail($id);

        // Check editable
        if (!in_array($logbook->status, ['draft', 'revision'])) {
            return redirect()->route('ojt.logbooks.show', $logbook->id)
                ->with('error', 'Logbook yang sudah dikirim atau diverifikasi tidak dapat diubah.');
        }

        $user = Auth::user() ?? User::where('role', 'trainee')->first();
        $categories = EquipmentCategory::whereIn('code', ['EXC', 'DZ', 'MG', 'HDT', 'SDT', 'WL'])->with('equipments')->get();
        $trainers = User::where('role', 'trainer')->get();
        $equipments = Equipment::with('category')->where('status', 'active')->get();
        $assignedPengawas = $user->assignedPengawas()->get();
        $assignedOperators = $user->assignedOperatorPendamping()->get();

        return view('ojt.logbooks.edit', compact('logbook', 'user', 'categories', 'trainers', 'equipments', 'assignedPengawas', 'assignedOperators'));
    }

    public function update(UpdateLogbookRequest $request, $id)
    {
        $logbook = OjtLogbook::findOrFail($id);

        if (!in_array($logbook->status, ['draft', 'revision'])) {
            return redirect()->route('ojt.logbooks.show', $logbook->id)->with('error', 'Logbook yang sudah dikirim atau diverifikasi tidak dapat diubah.');
        }

        $user = Auth::user() ?? User::where('role', 'trainee')->first();
        $oldStatus = $logbook->status;
        $newStatus = $request->action_type === 'submit' ? 'submitted' : 'draft';
        $totalHm = max(0, floatval($request->hm_end) - floatval($request->hm_start));
        $hmDay = $request->shift === 'day' ? $totalHm : 0;
        $hmNight = $request->shift === 'night' ? $totalHm : 0;

        $payload = $request->input('sop_payload', $logbook->sop_payload ?? []);
        if (is_array($payload)) {
            $existingUnitType = data_get($logbook->sop_payload, 'meta.unit_type');
            if ($existingUnitType && empty($payload['meta']['unit_type'])) {
                data_set($payload, 'meta.unit_type', $existingUnitType);
            }
        }

        $logbook->update([
            'equipment_category_id' => $request->equipment_category_id,
            'equipment_id' => $request->equipment_id,
            'equipment_number' => $request->equipment_number,
            'date' => $request->date,
            'shift' => $request->shift,
            'location' => $request->location,
            'trainer_id' => $request->trainer_id,
            'selected_pengawas_ids' => $request->input('selected_pengawas_ids', []),
            'selected_operator_pendamping_ids' => $request->input('selected_operator_pendamping_ids', []),
            'hm_start' => $request->hm_start,
            'hm_end' => $request->hm_end,
            'total_hm' => $totalHm,
            'hm_day' => $hmDay,
            'hm_night' => $hmNight,
            'sop_payload' => $payload,
            'status' => $newStatus,
            'revision_notes' => $newStatus === 'submitted' ? null : $logbook->revision_notes,
            'submitted_at' => $newStatus === 'submitted' ? now() : $logbook->submitted_at,
            'verified_at' => $newStatus === 'submitted' ? null : $logbook->verified_at,
            'approved_at' => $newStatus === 'submitted' ? null : $logbook->approved_at,
            'pjo_id' => $newStatus === 'submitted' ? null : $logbook->pjo_id,
            'pjo_notes' => $newStatus === 'submitted' ? null : $logbook->pjo_notes,
            'pjo_decided_at' => $newStatus === 'submitted' ? null : $logbook->pjo_decided_at,
            'pjo_signature_path' => $newStatus === 'submitted' ? null : $logbook->pjo_signature_path,
            'training_centre_id' => $newStatus === 'submitted' ? null : $logbook->training_centre_id,
            'training_centre_notes' => $newStatus === 'submitted' ? null : $logbook->training_centre_notes,
            'training_centre_decided_at' => $newStatus === 'submitted' ? null : $logbook->training_centre_decided_at,
            'training_centre_signature_path' => $newStatus === 'submitted' ? null : $logbook->training_centre_signature_path,
        ]);

        if ($newStatus === 'submitted') {
            LogbookAssignment::where('ojt_logbook_id', $logbook->id)->delete();
            $assignments = [];

            if ($request->trainer_id) {
                $assignments[] = ['user_id' => $request->trainer_id, 'role_type' => 'instruktur'];
            }
            foreach ($request->input('selected_pengawas_ids', []) as $pengawasId) {
                $assignments[] = ['user_id' => $pengawasId, 'role_type' => 'pengawas'];
            }
            foreach ($request->input('selected_operator_pendamping_ids', []) as $operatorId) {
                $assignments[] = ['user_id' => $operatorId, 'role_type' => 'operator_pendamping'];
            }
            foreach ($assignments as $assignment) {
                LogbookAssignment::create(array_merge($assignment, ['ojt_logbook_id' => $logbook->id, 'status' => 'pending']));
            }

            if (in_array($oldStatus, ['draft', 'revision'], true)) {
                $submitPayload = [
                    'title' => $oldStatus === 'revision' ? 'Form OJT Revisi Baru Menunggu Verifikasi' : 'Form OJT Baru Menunggu Verifikasi',
                    'message' => "Form OJT {$logbook->logbook_number} dari {$logbook->trainee->name} telah disubmit dan menunggu verifikasi Anda.",
                    'url' => route('trainer.reviews.show', $logbook->id),
                ];

                if ($request->trainer_id) {
                    \App\Models\User::find($request->trainer_id)?->notify(new \App\Notifications\LogbookSubmittedNotification($submitPayload));
                }
                foreach ($request->input('selected_pengawas_ids', []) as $pengawasId) {
                    \App\Models\User::find($pengawasId)?->notify(new \App\Notifications\LogbookSubmittedNotification($submitPayload));
                }
                foreach ($request->input('selected_operator_pendamping_ids', []) as $operatorId) {
                    \App\Models\User::find($operatorId)?->notify(new \App\Notifications\LogbookSubmittedNotification($submitPayload));
                }
            }
        }

        // History Log
        LogbookHistory::create([
            'ojt_logbook_id' => $logbook->id,
            'user_id' => $user ? $user->id : 1,
            'action' => $newStatus === 'submitted' ? 'Logbook Resubmitted after Revision/Draft' : 'Draft Updated',
            'from_status' => $oldStatus,
            'to_status' => $newStatus,
            'comment' => $newStatus === 'submitted' ? 'Resubmitted logbook with updated information.' : 'Updated draft details.',
        ]);

        $message = $newStatus === 'submitted' ? 'Logbook berhasil dikirim ulang ke Trainer.' : 'Perubahan draft logbook berhasil disimpan.';

        return redirect()->route('ojt.logbooks.index')->with('success', $message);
    }

    public function duplicate($id)
    {
        $original = OjtLogbook::findOrFail($id);
        $user = Auth::user() ?? User::where('role', 'trainee')->first();
        $traineeId = $user ? $user->id : 1;

        $newLogbookNumber = 'LOG-' . date('Ym') . '-' . str_pad(OjtLogbook::count() + 1, 4, '0', STR_PAD_LEFT);

        $newLogbook = OjtLogbook::create([
            'logbook_number' => $newLogbookNumber,
            'trainee_id' => $traineeId,
            'trainer_id' => $original->trainer_id,
            'pjo_id' => $original->pjo_id,
            'assigned_pjo_id' => $original->assigned_pjo_id,
            'equipment_category_id' => $original->equipment_category_id,
            'equipment_id' => $original->equipment_id,
            'date' => now()->format('Y-m-d'),
            'shift' => $original->shift,
            'location' => $original->location,
            'hm_start' => $original->hm_end, // Continue from previous HM
            'hm_end' => $original->hm_end,
            'total_hm' => 0,
            'sop_payload' => $original->sop_payload ?? [],
            'selected_pengawas_ids' => $original->selected_pengawas_ids ?? [],
            'selected_operator_pendamping_ids' => $original->selected_operator_pendamping_ids ?? [],
            'status' => 'draft',
        ]);

        LogbookHistory::create([
            'ojt_logbook_id' => $newLogbook->id,
            'user_id' => $traineeId,
            'action' => 'Duplicated from Logbook ' . $original->logbook_number,
            'from_status' => null,
            'to_status' => 'draft',
            'comment' => 'Created as draft via duplication.',
        ]);

        return redirect()->route('ojt.logbooks.edit', $newLogbook->id)->with('success', 'Logbook berhasil diduplikasi ke Draft baru.');
    }

    public function updateChecklist(Request $request, $id)
    {
        $user = Auth::user() ?? User::where('role', 'trainee')->first();
        $traineeId = $user ? $user->id : 1;

        $logbook = OjtLogbook::where('trainee_id', $traineeId)->findOrFail($id);
        abort_unless(in_array($logbook->status, ['draft']), 403, 'Checklist hanya dapat diubah untuk logbook draft.');

        $data = $request->validate(['checklist' => ['required', 'array']]);

        $payload = $logbook->sop_payload ?? [];
        $family = data_get($payload, 'meta.unit_family');
        abort_unless(in_array($family, ['track', 'excavator', 'dumptruck', 'semidump', 'wheelloader'], true), 422, 'Tipe alat tidak valid untuk checklist SOP.');

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

        LogbookHistory::create([
            'ojt_logbook_id' => $logbook->id,
            'user_id' => $traineeId,
            'action' => 'Checklist K/BK Updated by Trainee',
            'from_status' => $logbook->status,
            'to_status' => $logbook->status,
            'comment' => 'Checklist SOP diperbarui oleh trainee saat mengedit draft.',
        ]);

        return redirect()->route('ojt.logbooks.edit', $logbook->id)->with('success', 'Checklist K/BK berhasil diperbarui.');
    }

    public function print($id)
    {
        $user = Auth::user();
        abort_unless($user && $user->isTrainingCentre(), 403, 'Hanya Admin Training Centre yang dapat mencetak atau mengunduh logbook.');

        $logbook = OjtLogbook::with(['trainee', 'trainer', 'equipmentCategory', 'equipment', 'histories.user', 'trainingCentre', 'pengawasTrainer'])->findOrFail($id);

        abort_unless($logbook->status === 'final_approved', 403, 'Hanya logbook yang telah disahkan (Final Approved) oleh Training Centre yang dapat dicetak.');

        return view('ojt.logbooks.print', compact('logbook'));
    }

    public function printTrainee($traineeId)
    {
        $user = Auth::user();
        abort_unless($user && $user->isTrainingCentre(), 403, 'Hanya Admin Training Centre yang dapat mencetak atau mengunduh logbook.');

        $trainee = User::where('role', 'trainee')->findOrFail($traineeId);

        $logbooks = OjtLogbook::where('trainee_id', $traineeId)
            ->whereIn('status', ['verified', 'final_approved'])
            ->with(['trainee', 'trainer', 'equipmentCategory', 'equipment', 'histories.user', 'trainingCentre', 'pengawasTrainer'])
            ->get()
            ->map(fn ($logbook) => [
                'type' => 'logbook',
                'date' => $logbook->date ?? $logbook->training_centre_decided_at,
                'model' => $logbook,
            ]);

        $evaluations = FinalEvaluation::where('nama_operator', $trainee->name)
            ->whereIn('status', ['tc_approved', 'pjo_approved', 'hse_approved'])
            ->with(['trainer', 'tcApprover', 'pjoApprover', 'hseApprover', 'logbook'])
            ->get()
            ->map(fn ($evaluation) => [
                'type' => 'evaluation',
                'date' => $evaluation->tanggal_penilaian,
                'model' => $evaluation,
            ]);

        $combined = $logbooks->merge($evaluations)
            ->sortBy(fn ($item) => $item['date'] ?? now())
            ->values();

        abort_if($combined->isEmpty(), 404, 'Tidak ada dokumen final untuk trainee ini.');

        return view('ojt.logbooks.print-trainee', compact('trainee', 'combined'));
    }
}
