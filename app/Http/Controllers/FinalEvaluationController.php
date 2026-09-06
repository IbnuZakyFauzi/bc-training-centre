<?php

namespace App\Http\Controllers;

use App\Models\FinalEvaluation;
use App\Models\OjtLogbook;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FinalEvaluationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'must.change.password']);
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        abort_unless($user && $user->isTrainer(), 403);

        $query = FinalEvaluation::where('trainer_id', $user->id);

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('kesimpulan', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_operator', 'like', "%{$search}%")
                  ->orWhere('jenis_unit_a2b', 'like', "%{$search}%")
                  ->orWhere('lokasi_kerja', 'like', "%{$search}%");
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('tanggal_penilaian', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('tanggal_penilaian', '<=', $request->date_to);
        }

        $evaluations = $query->latest()->paginate(10)->withQueryString();

        $statusCounts = [
            'all' => FinalEvaluation::where('trainer_id', $user->id)->count(),
            'kompeten' => FinalEvaluation::where('trainer_id', $user->id)->where('kesimpulan', 'kompeten')->count(),
            'belum_kompeten' => FinalEvaluation::where('trainer_id', $user->id)->where('kesimpulan', 'belum_kompeten')->count(),
        ];

        return view('final-evaluations.index', compact('evaluations', 'statusCounts'));
    }

    public function createStandalone(Request $request)
    {
        $user = Auth::user();
        abort_unless($user && $user->isTrainer(), 403, 'Hanya Trainer yang dapat mengisi formulir evaluasi.');

        $trainees = User::where('role', 'trainee')
            ->with(['equipmentCategory', 'assignedOperatorPendamping'])
            ->get()
            ->map(fn ($trainee) => [
                'id' => $trainee->id,
                'name' => $trainee->name,
                'company' => $trainee->company,
                'equipment_category_name' => $trainee->equipmentCategory->name ?? '',
                'certification' => $trainee->certification,
                'operator_pendamping' => $trainee->assignedOperatorPendamping->pluck('name')->join(', '),
                'current_phase' => $trainee->currentPhaseKey(),
                'current_phase_label' => $trainee->currentPhaseMeta()['label'] ?? '',
                'eligible' => $trainee->isPhaseEligible(),
            ])
            ->values();

        $locations = ['BMO 1', 'BMO 2', 'BMO 3', 'GMO', 'LMO', 'SMO'];
        $certifications = \App\Services\PhaseService::CERTIFICATIONS;
        $selectedTraineeId = $request->query('trainee');

        return view('final-evaluations.create', compact('trainees', 'locations', 'certifications'));
    }

    public function edit($id)
    {
        $user = Auth::user();
        abort_unless($user && $user->isTrainer(), 403);

        $evaluation = FinalEvaluation::where('trainer_id', $user->id)->findOrFail($id);
        $trainees = User::where('role', 'trainee')
            ->with(['equipmentCategory', 'assignedOperatorPendamping'])
            ->get()
            ->map(fn ($trainee) => [
                'id' => $trainee->id,
                'name' => $trainee->name,
                'company' => $trainee->company,
                'equipment_category_name' => $trainee->equipmentCategory->name ?? '',
                'certification' => $trainee->certification,
                'operator_pendamping' => $trainee->assignedOperatorPendamping->pluck('name')->join(', '),
                'current_phase' => $trainee->currentPhaseKey(),
                'current_phase_label' => $trainee->currentPhaseMeta()['label'] ?? '',
                'eligible' => $trainee->isPhaseEligible(),
            ])
            ->values();

        $locations = ['BMO 1', 'BMO 2', 'BMO 3', 'GMO', 'LMO', 'SMO'];
        $certifications = \App\Services\PhaseService::CERTIFICATIONS;

        return view('final-evaluations.create', compact('evaluation', 'trainees', 'locations', 'certifications'));
    }

    public function storeStandalone(Request $request)
    {
        $user = Auth::user();
        abort_unless($user && $user->isTrainer(), 403);

        $validated = $request->validate([
            'nama_operator' => ['required', 'string', 'max:255'],
            'perusahaan' => ['required', 'string', 'max:255'],
            'lokasi_kerja' => ['required', 'string', 'max:255'],
            'jenis_unit_a2b' => ['required', 'string', 'max:255'],
            'jenis_sertifikasi' => ['required', 'in:Green,Skill-up,Experience_internal,Experience_external'],
            'instruktur' => ['required', 'string', 'max:255'],
            'operator_pendamping' => ['required', 'string', 'max:255'],
            'tanggal_penilaian' => ['required', 'date'],
            'tahap_penilaian' => ['required', 'in:pendampingan,tanpa_pendampingan'],
            'sub_tahap' => ['nullable', 'string', 'max:255'],
            'sub_tahap_keterangan' => ['nullable', 'string', 'max:255'],
            'p2h_status' => ['required', 'in:K,BK'],
            'teknik_pengoperasian_status' => ['required', 'in:K,BK'],
            'kepatuhan_status' => ['required', 'in:K,BK'],
            'kedisiplinan_status' => ['required', 'in:K,BK'],
            'catatan' => ['nullable', 'string', 'max:2000'],
        ]);

        $validated['trainer_id'] = $user->id;
        $validated['ojt_logbook_id'] = null;
        $validated['instruktur_signature_path'] = $user->signature_path;
        $trainee = User::where('name', $request->input('nama_operator'))->where('role', 'trainee')->first();
        if ($trainee) {
            $validated['nama_operator'] = $trainee->name;
            $validated['perusahaan'] = $trainee->company ?? '';
            $validated['jenis_unit_a2b'] = $trainee->equipmentCategory->name ?? '';
            $validated['jenis_sertifikasi'] = $trainee->certification ?? 'Green';
            $validated['operator_pendamping'] = $trainee->assignedOperatorPendamping->pluck('name')->join(', ');
        }
        $validated['phase'] = $trainee ? $trainee->currentPhaseKey() : \App\Services\PhaseService::firstPhase($request->input('jenis_sertifikasi'));
        $validated['status'] = 'submitted';
        $validated['kesimpulan'] = in_array($validated['p2h_status'], ['BK']) || in_array($validated['teknik_pengoperasian_status'], ['BK']) || in_array($validated['kepatuhan_status'], ['BK']) || in_array($validated['kedisiplinan_status'], ['BK']) ? 'belum_kompeten' : 'kompeten';

        $evaluation = FinalEvaluation::create($validated);

        \App\Models\User::where('role', 'admin')->get()->each(function ($adminTc) use ($evaluation) {
            $adminTc->notify(new \App\Notifications\LogbookSubmittedNotification([
                'title' => 'Evaluasi Baru Menunggu Persetujuan',
                'message' => "Evaluasi final untuk {$evaluation->nama_operator} telah disubmit dan menunggu persetujuan Anda.",
                'url' => route('training-centre.final-evaluations.show', $evaluation->id),
            ]));
        });

        return redirect()->route('trainer.final-evaluations.index')->with('success', 'Formulir evaluasi berhasil disimpan.');
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        abort_unless($user && $user->isTrainer(), 403);

        $evaluation = FinalEvaluation::where('trainer_id', $user->id)->findOrFail($id);

        $validated = $request->validate([
            'nama_operator' => ['required', 'string', 'max:255'],
            'perusahaan' => ['required', 'string', 'max:255'],
            'lokasi_kerja' => ['required', 'string', 'max:255'],
            'jenis_unit_a2b' => ['required', 'string', 'max:255'],
            'jenis_sertifikasi' => ['required', 'in:Green,Skill-up,Experience_internal,Experience_external'],
            'instruktur' => ['required', 'string', 'max:255'],
            'operator_pendamping' => ['required', 'string', 'max:255'],
            'tanggal_penilaian' => ['required', 'date'],
            'tahap_penilaian' => ['required', 'in:pendampingan,tanpa_pendampingan'],
            'sub_tahap' => ['nullable', 'string', 'max:255'],
            'sub_tahap_keterangan' => ['nullable', 'string', 'max:255'],
            'p2h_status' => ['required', 'in:K,BK'],
            'teknik_pengoperasian_status' => ['required', 'in:K,BK'],
            'kepatuhan_status' => ['required', 'in:K,BK'],
            'kedisiplinan_status' => ['required', 'in:K,BK'],
            'catatan' => ['nullable', 'string', 'max:2000'],
        ]);

        $validated['kesimpulan'] = in_array($validated['p2h_status'], ['BK']) || in_array($validated['teknik_pengoperasian_status'], ['BK']) || in_array($validated['kepatuhan_status'], ['BK']) || in_array($validated['kedisiplinan_status'], ['BK']) ? 'belum_kompeten' : 'kompeten';
        $validated['instruktur_signature_path'] = $user->signature_path;

        $trainee = User::where('name', $validated['nama_operator'] ?? '')->where('role', 'trainee')->first();
        if ($trainee) {
            $validated['nama_operator'] = $trainee->name;
            $validated['perusahaan'] = $trainee->company ?? '';
            $validated['jenis_unit_a2b'] = $trainee->equipmentCategory->name ?? '';
            $validated['jenis_sertifikasi'] = $trainee->certification ?? 'Green';
            $validated['operator_pendamping'] = $trainee->assignedOperatorPendamping->pluck('name')->join(', ');
        }

        $evaluation->update($validated);

        $evaluation->update([
            'status' => 'submitted',
            'tc_approved_at' => null,
            'tc_approved_by' => null,
            'tc_notes' => null,
            'pjo_approved_at' => null,
            'pjo_approved_by' => null,
            'pjo_notes' => null,
            'hse_approved_at' => null,
            'hse_approved_by' => null,
            'hse_notes' => null,
        ]);

        \App\Models\User::where('role', 'admin')->get()->each(function ($adminTc) use ($evaluation) {
            $adminTc->notify(new \App\Notifications\LogbookSubmittedNotification([
                'title' => 'Evaluasi Revisi Menunggu Persetujuan',
                'message' => "Evaluasi final untuk {$evaluation->nama_operator} telah diperbarui dan menunggu persetujuan Anda.",
                'url' => route('training-centre.final-evaluations.show', $evaluation->id),
            ]));
        });

        return redirect()->route('trainer.final-evaluations.index')->with('success', 'Formulir evaluasi berhasil diperbarui dan dikirim ke Admin TC.');
    }

    public function show($id)
    {
        $evaluation = FinalEvaluation::with(['logbook.trainee', 'logbook.equipmentCategory', 'trainer'])->findOrFail($id);
        $user = Auth::user();
        abort_unless($user && ($user->isTrainer() || $user->isTrainingCentre() || $user->isPjo() || $user->isHseCt() || $user->isSuperAdmin()), 403);

        return view('final-evaluations.show', compact('evaluation'));
    }

    public function print($id)
    {
        $user = Auth::user();
        abort_unless($user && $user->isTrainingCentre(), 403);

        $evaluation = FinalEvaluation::with(['logbook.trainee', 'logbook.equipmentCategory', 'trainer', 'tcApprover', 'pjoApprover', 'hseApprover'])->findOrFail($id);

        return view('final-evaluations.print', compact('evaluation'));
    }
}
