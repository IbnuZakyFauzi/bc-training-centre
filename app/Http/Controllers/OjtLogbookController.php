<?php

namespace App\Http\Controllers;

use App\Models\OjtLogbook;
use App\Models\Department;
use App\Models\EquipmentCategory;
use App\Models\Equipment;
use App\Models\User;
use App\Models\LogbookEvidence;
use App\Models\LogbookHistory;
use App\Http\Requests\StoreLogbookRequest;
use App\Http\Requests\UpdateLogbookRequest;
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
            ->with(['equipment', 'equipmentCategory', 'trainer', 'department']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('logbook_number', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhere('daily_activity', 'like', "%{$search}%")
                  ->orWhereHas('equipment', function($eqQuery) use ($search) {
                      $eqQuery->where('unit_code', 'like', "%{$search}%")
                              ->orWhere('model_name', 'like', "%{$search}%");
                  });
            });
        }

        // Status Filter
        if ($request->filled('status') && $request->status !== 'all') {
            if ($request->status === 'approved') {
                $query->whereIn('status', ['approved', 'supervisor_approved', 'final_approved']);
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

        $logbooks = $query->orderBy('date', 'desc')->paginate(10)->withQueryString();

        $equipments = Equipment::where('status', 'active')->get();
        $statusCounts = [
            'all' => OjtLogbook::where('trainee_id', $traineeId)->count(),
            'draft' => OjtLogbook::where('trainee_id', $traineeId)->where('status', 'draft')->count(),
            'submitted' => OjtLogbook::where('trainee_id', $traineeId)->where('status', 'submitted')->count(),
            'revision' => OjtLogbook::where('trainee_id', $traineeId)->where('status', 'revision')->count(),
            'approved' => OjtLogbook::where('trainee_id', $traineeId)->whereIn('status', ['approved', 'supervisor_approved', 'final_approved'])->count(),
        ];

        return view('ojt.logbooks.index', compact('logbooks', 'equipments', 'statusCounts'));
    }

    public function create()
    {
        $user = Auth::user() ?? User::where('role', 'trainee')->first();
        $departments = Department::all();
        $categories = EquipmentCategory::with('equipments')->get();
        $trainers = User::where('role', 'trainer')->get();
        $supervisors = User::where('role', 'supervisor')->get();
        $equipments = Equipment::where('status', 'active')->get();

        return view('ojt.logbooks.create', compact('user', 'departments', 'categories', 'trainers', 'supervisors', 'equipments'));
    }

    public function store(StoreLogbookRequest $request)
    {
        $user = Auth::user() ?? User::where('role', 'trainee')->first();
        $traineeId = $user ? $user->id : 1;

        $status = $request->action_type === 'submit' ? 'submitted' : 'draft';
        $totalHm = floatval($request->hm_end) - floatval($request->hm_start);

        $logbookNumber = 'LOG-' . date('Ym') . '-' . str_pad(OjtLogbook::count() + 1, 4, '0', STR_PAD_LEFT);

        $logbook = OjtLogbook::create([
            'logbook_number' => $logbookNumber,
            'trainee_id' => $traineeId,
            'trainer_id' => $request->trainer_id,
            'supervisor_id' => $request->supervisor_id,
            'department_id' => $request->department_id,
            'equipment_category_id' => $request->equipment_category_id,
            'equipment_id' => $request->equipment_id,
            'equipment_number' => $request->equipment_number,
            'date' => $request->date,
            'shift' => $request->shift,
            'location' => $request->location,
            'start_time' => $request->start_time,
            'finish_time' => $request->finish_time,
            'hm_start' => $request->hm_start,
            'hm_end' => $request->hm_end,
            'total_hm' => max(0, $totalHm),
            'daily_activity' => $request->daily_activity,
            'sop_payload' => $request->input('sop_payload', []),
            'status' => $status,
            'submitted_at' => $status === 'submitted' ? now() : null,
        ]);

        // Upload Evidences
        if ($request->hasFile('evidences')) {
            foreach ($request->file('evidences') as $file) {
                $path = $file->store('evidences', 'public');
                $mime = $file->getClientMimeType();
                $fileType = str_contains($mime, 'video') ? 'video' : (str_contains($mime, 'pdf') ? 'document' : 'image');

                LogbookEvidence::create([
                    'ojt_logbook_id' => $logbook->id,
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                    'file_type' => $fileType,
                    'file_size' => round($file->getSize() / 1024, 1) . ' KB',
                ]);
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
        $logbook = OjtLogbook::with(['trainee', 'trainer', 'supervisor', 'department', 'equipmentCategory', 'equipment', 'evidences', 'histories.user'])->findOrFail($id);

        return view('ojt.logbooks.show', compact('logbook'));
    }

    public function edit($id)
    {
        $logbook = OjtLogbook::with(['evidences'])->findOrFail($id);

        // Check editable
        if (!in_array($logbook->status, ['draft', 'revision'])) {
            return redirect()->route('ojt.logbooks.show', $logbook->id)
                ->with('error', 'Logbook yang sudah dikirim atau diverifikasi tidak dapat diubah.');
        }

        $user = Auth::user() ?? User::where('role', 'trainee')->first();
        $departments = Department::all();
        $categories = EquipmentCategory::all();
        $trainers = User::where('role', 'trainer')->get();
        $supervisors = User::where('role', 'supervisor')->get();
        $equipments = Equipment::where('status', 'active')->get();

        return view('ojt.logbooks.edit', compact('logbook', 'user', 'departments', 'categories', 'trainers', 'supervisors', 'equipments'));
    }

    public function update(UpdateLogbookRequest $request, $id)
    {
        $logbook = OjtLogbook::findOrFail($id);

        if (!in_array($logbook->status, ['draft', 'revision'])) {
            return redirect()->route('ojt.logbooks.show', $logbook->id)->with('error', 'Logbook tidak dapat diubah.');
        }

        $user = Auth::user() ?? User::where('role', 'trainee')->first();
        $oldStatus = $logbook->status;
        $newStatus = $request->action_type === 'submit' ? 'submitted' : 'draft';
        $totalHm = floatval($request->hm_end) - floatval($request->hm_start);

        $logbook->update([
            'trainer_id' => $request->trainer_id,
            'supervisor_id' => $request->supervisor_id,
            'department_id' => $request->department_id,
            'equipment_category_id' => $request->equipment_category_id,
            'equipment_id' => $request->equipment_id,
            'equipment_number' => $request->equipment_number,
            'date' => $request->date,
            'shift' => $request->shift,
            'location' => $request->location,
            'start_time' => $request->start_time,
            'finish_time' => $request->finish_time,
            'hm_start' => $request->hm_start,
            'hm_end' => $request->hm_end,
            'total_hm' => max(0, $totalHm),
            'daily_activity' => $request->daily_activity,
            'sop_payload' => $request->input('sop_payload', $logbook->sop_payload ?? []),
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

        // Upload Evidences if any
        if ($request->hasFile('evidences')) {
            foreach ($request->file('evidences') as $file) {
                $path = $file->store('evidences', 'public');
                $mime = $file->getClientMimeType();
                $fileType = str_contains($mime, 'video') ? 'video' : (str_contains($mime, 'pdf') ? 'document' : 'image');

                LogbookEvidence::create([
                    'ojt_logbook_id' => $logbook->id,
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                    'file_type' => $fileType,
                    'file_size' => round($file->getSize() / 1024, 1) . ' KB',
                ]);
            }
        }

        // History Log
        LogbookHistory::create([
            'ojt_logbook_id' => $logbook->id,
            'user_id' => $user ? $user->id : 1,
            'action' => $newStatus === 'submitted' ? 'Logbook Resubmitted after Revision/Draft' : 'Draft Updated',
            'from_status' => $oldStatus,
            'to_status' => $newStatus,
            'comment' => $newStatus === 'submitted' ? 'Resubmitted logbook with updated information and evidence.' : 'Updated draft details.',
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
            'supervisor_id' => $original->supervisor_id,
            'department_id' => $original->department_id,
            'equipment_category_id' => $original->equipment_category_id,
            'equipment_id' => $original->equipment_id,
            'date' => now()->format('Y-m-d'),
            'shift' => $original->shift,
            'location' => $original->location,
            'start_time' => $original->start_time,
            'finish_time' => $original->finish_time,
            'hm_start' => $original->hm_end, // Continue from previous HM
            'hm_end' => $original->hm_end,
            'total_hm' => 0,
            'daily_activity' => "[Duplicated from {$original->logbook_number}]\n" . $original->daily_activity,
            'sop_payload' => $original->sop_payload ?? [],
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
        abort_unless(in_array($logbook->status, ['draft', 'revision']), 403, 'Checklist hanya dapat diubah untuk logbook draft atau revisi.');

        $data = $request->validate(['checklist' => ['required', 'array']]);

        $payload = $logbook->sop_payload ?? [];
        $family = data_get($payload, 'meta.unit_family');
        abort_unless(in_array($family, ['track', 'excavator'], true), 422, 'Tipe alat tidak valid untuk checklist SOP.');

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

        $logbook = OjtLogbook::with(['trainee', 'trainer', 'supervisor', 'department', 'equipmentCategory', 'equipment', 'evidences', 'histories.user', 'trainingCentre', 'departmentOperation'])->findOrFail($id);

        abort_unless($logbook->status === 'final_approved', 403, 'Hanya logbook yang telah disahkan (Final Approved) oleh Training Centre yang dapat dicetak.');

        return view('ojt.logbooks.print', compact('logbook'));
    }
}
