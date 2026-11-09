<?php

namespace App\Http\Controllers\EvaluasiMonitoring;

use App\Http\Controllers\Controller;
use App\Models\EquipmentCategory;
use App\Models\Evaluasi1\Eval1Certificate;
use App\Models\Evaluasi1\Eval1ExamAttempt;
use App\Models\Evaluasi2\Eval2Certificate;
use App\Models\Evaluasi2\Eval2GerdasAssessment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EvaluasiMonitoringController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'must.change.password']);
    }

    /**
     * Dashboard Visual & Interaktif Monitoring Nilai Evaluasi 1 & 2 untuk Admin TC & Trainer
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        abort_unless($user && ($user->isTrainingCentre() || $user->isTrainer() || $user->isSuperAdmin() || $user->isPjo() || $user->isHseCt()), 403, 'Akses terbatas untuk Admin TC & Manajemen.');

        // 1. KPI Metrik Utama
        $totalTrainees = User::where('role', 'trainee')->count();
        
        $avgScoreEval1 = round(Eval1ExamAttempt::where('status', 'completed')->avg('total_score') ?? 0, 1);
        $avgScoreEval2 = round(Eval2GerdasAssessment::where('status', 'completed')->avg('total_score') ?? 0, 1);

        $simperL1Count = Eval1Certificate::count();
        $simperL2Count = Eval2Certificate::count();

        // 2. Data Tabel Trainee dengan Nilai Evaluasi 1 & 2
        $traineesQuery = User::where('role', 'trainee')
            ->with(['equipmentCategory']);

        if ($request->filled('category_id')) {
            $traineesQuery->where('equipment_category_id', $request->category_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $traineesQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sid', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%");
            });
        }

        $trainees = $traineesQuery->paginate(15)->withQueryString();

        // Kumpulkan data riwayat & tren per trainee
        $traineeRows = $trainees->map(function ($t) {
            // Evaluasi 1 Attempt & Cert
            $eval1 = Eval1ExamAttempt::where('user_id', $t->id)
                ->where('status', 'completed')
                ->latest()
                ->first();
            $certL1 = Eval1Certificate::where('user_id', $t->id)->latest()->first();

            // Evaluasi 2 Assessment & Cert
            $eval2 = Eval2GerdasAssessment::where('trainee_id', $t->id)->latest()->first();
            $certL2 = Eval2Certificate::where('trainee_id', $t->id)->latest()->first();

            $score1 = $eval1 ? (float) $eval1->total_score : null;
            $score2 = $eval2 ? (float) $eval2->total_score : null;

            $combinedAvg = null;
            if ($score1 !== null && $score2 !== null) {
                $combinedAvg = round(($score1 + $score2) / 2, 1);
            } elseif ($score1 !== null) {
                $combinedAvg = $score1;
            } elseif ($score2 !== null) {
                $combinedAvg = $score2;
            }

            return [
                'trainee' => $t,
                'eval1_score' => $score1,
                'eval1_passed' => $eval1?->is_passed ?? false,
                'has_cert_l1' => (bool) $certL1,
                'cert_l1_number' => $certL1?->certificate_number,
                'eval2_score' => $score2,
                'eval2_passed' => $eval2?->isKompeten() ?? false,
                'has_cert_l2' => (bool) $certL2,
                'cert_l2_number' => $certL2?->certificate_number,
                'combined_avg' => $combinedAvg,
                'is_ready_rekap' => ($score1 !== null && $score2 !== null),
            ];
        });

        // 3. Distribusi Nilai & Data Tren untuk Grafik Visual (Histogram / Kategori Nilai)
        $eval1Scores = Eval1ExamAttempt::where('status', 'completed')->pluck('total_score');
        $eval2Scores = Eval2GerdasAssessment::where('status', 'completed')->pluck('total_score');

        $distributionEval1 = [
            'A (90-100)' => $eval1Scores->filter(fn ($s) => $s >= 90)->count(),
            'B (80-89)'  => $eval1Scores->filter(fn ($s) => $s >= 80 && $s < 90)->count(),
            'C (75-79)'  => $eval1Scores->filter(fn ($s) => $s >= 75 && $s < 80)->count(),
            'D (<75)'    => $eval1Scores->filter(fn ($s) => $s < 75)->count(),
        ];

        $distributionEval2 = [
            'A (90-100)' => $eval2Scores->filter(fn ($s) => $s >= 90)->count(),
            'B (80-89)'  => $eval2Scores->filter(fn ($s) => $s >= 80 && $s < 90)->count(),
            'C (75-79)'  => $eval2Scores->filter(fn ($s) => $s >= 75 && $s < 80)->count(),
            'D (<75)'    => $eval2Scores->filter(fn ($s) => $s < 75)->count(),
        ];

        $categories = EquipmentCategory::all();

        return view('evaluasi-monitoring.index', compact(
            'totalTrainees',
            'avgScoreEval1',
            'avgScoreEval2',
            'simperL1Count',
            'simperL2Count',
            'trainees',
            'traineeRows',
            'distributionEval1',
            'distributionEval2',
            'categories'
        ));
    }
}
