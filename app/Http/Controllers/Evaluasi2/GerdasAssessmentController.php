<?php

namespace App\Http\Controllers\Evaluasi2;

use App\Http\Controllers\Controller;
use App\Models\EquipmentCategory;
use App\Models\Evaluasi1\Eval1Certificate;
use App\Models\Evaluasi1\Eval1ExamAttempt;
use App\Models\Evaluasi2\Eval2Certificate;
use App\Models\Evaluasi2\Eval2GerdasAssessment;
use App\Models\User;
use App\Services\PhaseService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GerdasAssessmentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'must.change.password']);
    }

    /**
     * Daftar Penilaian Gerakan Dasar (Gerdas)
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Eval2GerdasAssessment::with(['trainee.equipmentCategory', 'trainer', 'certificate']);

        if ($user->isTrainee()) {
            $query->where('trainee_id', $user->id);
        } elseif ($user->isTrainer()) {
            // Instruktur bisa melihat yang dinilainya atau semua jika admin
            if ($request->filled('filter_mine')) {
                $query->where('trainer_id', $user->id);
            }
        }

        if ($request->filled('kesimpulan') && $request->kesimpulan !== 'all') {
            $query->where('kesimpulan', $request->kesimpulan);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('trainee', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sid', 'like', "%{$search}%");
            });
        }

        $assessments = $query->latest('tanggal_penilaian')->paginate(15)->withQueryString();

        $stats = [
            'total' => Eval2GerdasAssessment::count(),
            'kompeten' => Eval2GerdasAssessment::where('kesimpulan', 'kompeten')->count(),
            'belum_kompeten' => Eval2GerdasAssessment::where('kesimpulan', 'belum_kompeten')->count(),
            'certificates' => Eval2Certificate::count(),
        ];

        $isEval1Passed = $user->isTrainee() ? PhaseService::isTraineePassedEvaluasi1($user) : true;

        return view('evaluasi-2.assessments.index', compact('assessments', 'stats', 'isEval1Passed'));
    }

    /**
     * Form Input Penilaian Praktek Gerakan Dasar oleh Instruktur
     */
    public function create()
    {
        $user = Auth::user();
        abort_unless($user && ($user->isTrainer() || $user->isTrainingCentre() || $user->isSuperAdmin()), 403, 'Hanya Instruktur / Admin TC yang dapat menilai Gerdas.');

        $trainees = User::where('role', 'trainee')
            ->with(['equipmentCategory', 'assignedInstruktur'])
            ->orderBy('name')
            ->get()
            ->map(function ($t) {
                $t->eval1_passed = PhaseService::isTraineePassedEvaluasi1($t);
                return $t;
            });

        $categories = EquipmentCategory::all();
        $locations = ['Training Ground Pit BMO 1', 'Training Area Pit BMO 2', 'Training Area BMO 3', 'Training Ground GMO', 'Training Ground LMO', 'Training Ground SMO'];

        return view('evaluasi-2.assessments.create', compact('trainees', 'categories', 'locations'));
    }

    /**
     * Simpan Penilaian Gerakan Dasar & Auto-Generate SIMPER L2
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        abort_unless($user && ($user->isTrainer() || $user->isTrainingCentre() || $user->isSuperAdmin()), 403);

        $validated = $request->validate([
            'trainee_id' => 'required|exists:users,id',
            'equipment_category_id' => 'nullable|exists:equipment_categories,id',
            'unit_model' => 'required|string|max:100',
            'unit_number' => 'required|string|max:50',
            'lokasi_kerja' => 'required|string|max:100',
            'tanggal_penilaian' => 'required|date',
            'shift' => 'required|in:day,night',
            'score_p2h' => 'required|numeric|min:0|max:100',
            'score_basic_control' => 'required|numeric|min:0|max:100',
            'score_maneuver' => 'required|numeric|min:0|max:100',
            'score_safety_k3' => 'required|numeric|min:0|max:100',
            'catatan_instruktur' => 'nullable|string|max:1000',
        ]);

        $trainee = User::findOrFail($validated['trainee_id']);

        // Validasi prasyarat kelulusan Evaluasi 1
        if (! PhaseService::isTraineePassedEvaluasi1($trainee)) {
            return back()->withInput()->with('error', "Gagal memproses penilaian: Trainee {$trainee->name} belum memenuhi syarat kelulusan Evaluasi 1. Trainee wajib memiliki nilai minimal 80 pada setiap Post-Test modul dan minimal 80 pada Tes Akhir CBT sebelum dinilai pada Evaluasi 2 (Gerakan Dasar).");
        }

        // Hitung skor berbobot standar:
        // P2H: 20%, Kontrol Dasar: 25%, Manuver (Maju/Mundur/Parkir): 35%, K3LH: 20%
        $totalScore = round(
            ($validated['score_p2h'] * 0.20) +
            ($validated['score_basic_control'] * 0.25) +
            ($validated['score_maneuver'] * 0.35) +
            ($validated['score_safety_k3'] * 0.20),
            2
        );

        $isKompeten = ($totalScore >= 75.0) &&
                      ($validated['score_p2h'] >= 70.0) &&
                      ($validated['score_safety_k3'] >= 75.0);

        $kesimpulan = $isKompeten ? 'kompeten' : 'belum_kompeten';

        $criteriaScores = [
            [
                'aspek' => 'Pemeriksaan Keliling & Prosedur P2H Awal',
                'bobot' => 20,
                'nilai' => $validated['score_p2h'],
                'status' => $validated['score_p2h'] >= 70 ? 'K' : 'BK',
            ],
            [
                'aspek' => 'Kontrol Dasar & Mekanisme Pengoperasian Awal',
                'bobot' => 25,
                'nilai' => $validated['score_basic_control'],
                'status' => $validated['score_basic_control'] >= 70 ? 'K' : 'BK',
            ],
            [
                'aspek' => 'Manuver Gerakan Dasar (Maju, Mundur, Radius Belok, Parkir)',
                'bobot' => 35,
                'nilai' => $validated['score_maneuver'],
                'status' => $validated['score_maneuver'] >= 70 ? 'K' : 'BK',
            ],
            [
                'aspek' => 'Kepatuhan K3LH & Pengendalian Resiko Tambang',
                'bobot' => 20,
                'nilai' => $validated['score_safety_k3'],
                'status' => $validated['score_safety_k3'] >= 75 ? 'K' : 'BK',
            ],
        ];

        $assessment = Eval2GerdasAssessment::create([
            'trainee_id' => $validated['trainee_id'],
            'trainer_id' => $user->id,
            'equipment_category_id' => $validated['equipment_category_id'],
            'unit_model' => $validated['unit_model'],
            'unit_number' => $validated['unit_number'],
            'lokasi_kerja' => $validated['lokasi_kerja'],
            'tanggal_penilaian' => $validated['tanggal_penilaian'],
            'shift' => $validated['shift'],
            'criteria_scores' => $criteriaScores,
            'score_p2h' => $validated['score_p2h'],
            'score_basic_control' => $validated['score_basic_control'],
            'score_maneuver' => $validated['score_maneuver'],
            'score_safety_k3' => $validated['score_safety_k3'],
            'total_score' => $totalScore,
            'kesimpulan' => $kesimpulan,
            'catatan_instruktur' => $validated['catatan_instruktur'],
            'instruktur_signature_path' => $user->signature_path,
            'status' => 'completed',
        ]);

        $trainee = User::findOrFail($validated['trainee_id']);

        // Auto-generate SIMPER L2 jika kompeten
        if ($isKompeten) {
            $existingCert = Eval2Certificate::where('trainee_id', $trainee->id)->first();
            if (! $existingCert) {
                Eval2Certificate::create([
                    'trainee_id' => $trainee->id,
                    'assessment_id' => $assessment->id,
                    'certificate_number' => Eval2Certificate::generateCertificateNumber($trainee),
                    'license_type' => 'SIMPER L2 (Gerakan Dasar / Gerdas)',
                    'issue_date' => Carbon::now()->format('Y-m-d'),
                    'valid_until' => Carbon::now()->addMonths(6)->format('Y-m-d'),
                    'total_score' => $totalScore,
                    'status' => 'valid',
                    'notes' => "Lulus Uji Praktik Gerakan Dasar unit {$validated['unit_model']} ({$validated['unit_number']}) dengan nilai {$totalScore}.",
                ]);
            }
        }

        return redirect()->route('evaluasi2.assessments.show', $assessment->id)
            ->with('success', "Penilaian Gerakan Dasar berhasil disimpan. Status: " . strtoupper(str_replace('_', ' ', $kesimpulan)));
    }

    /**
     * Detail Penilaian Gerakan Dasar
     */
    public function show($id)
    {
        $assessment = Eval2GerdasAssessment::with(['trainee.equipmentCategory', 'trainer', 'certificate'])->findOrFail($id);
        $user = Auth::user();

        if ($user->isTrainee() && $assessment->trainee_id !== $user->id) {
            abort(403);
        }

        return view('evaluasi-2.assessments.show', compact('assessment'));
    }

    /**
     * Cetak Sertifikat SIMPER L2
     */
    public function printCertificateL2($id)
    {
        $certificate = Eval2Certificate::with(['trainee.equipmentCategory', 'assessment.trainer'])->findOrFail($id);
        $user = Auth::user();

        if ($user->isTrainee() && $certificate->trainee_id !== $user->id) {
            abort(403);
        }

        return view('evaluasi-2.certificates.simper-l2-print', compact('certificate'));
    }

    /**
     * Auto-generate & Cetak Formulir Rekapitulasi Evaluasi Nilai Uji Kompetensi Evaluasi 1 & 2 Format Standar PT MTL
     */
    public function printRekapPTMTL($traineeId)
    {
        $user = Auth::user();
        $trainee = User::with(['equipmentCategory', 'assignedInstruktur'])->findOrFail($traineeId);

        if ($user->isTrainee() && $trainee->id !== $user->id) {
            abort(403);
        }

        // Ambil data Evaluasi 1
        $eval1Attempt = Eval1ExamAttempt::where('user_id', $trainee->id)
            ->whereHas('exam', fn ($q) => $q->where('type', 'evaluasi_1'))
            ->where('status', 'completed')
            ->latest()
            ->first();

        $eval1Cert = Eval1Certificate::where('user_id', $trainee->id)->latest()->first();

        // Ambil data Evaluasi 2
        $eval2Assessment = Eval2GerdasAssessment::where('trainee_id', $trainee->id)
            ->with('trainer')
            ->latest('tanggal_penilaian')
            ->first();

        $eval2Cert = Eval2Certificate::where('trainee_id', $trainee->id)->latest()->first();

        // Hitung Skor Rata-rata Gabungan Evaluasi 1 & 2
        $scoreEval1 = $eval1Attempt ? (float) $eval1Attempt->total_score : 0.0;
        $scoreEval2 = $eval2Assessment ? (float) $eval2Assessment->total_score : 0.0;
        $combinedAverage = ($scoreEval1 > 0 && $scoreEval2 > 0) ? round(($scoreEval1 + $scoreEval2) / 2, 2) : max($scoreEval1, $scoreEval2);

        $isOverallPassed = ($scoreEval1 >= 75.0) && ($scoreEval2 >= 75.0);

        return view('evaluasi-2.reports.rekap-pt-mtl', compact(
            'trainee',
            'eval1Attempt',
            'eval1Cert',
            'eval2Assessment',
            'eval2Cert',
            'scoreEval1',
            'scoreEval2',
            'combinedAverage',
            'isOverallPassed'
        ));
    }
}
