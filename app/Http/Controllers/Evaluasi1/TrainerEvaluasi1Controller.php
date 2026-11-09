<?php

namespace App\Http\Controllers\Evaluasi1;

use App\Http\Controllers\Controller;
use App\Models\Evaluasi1\Eval1Attendance;
use App\Models\Evaluasi1\Eval1Certificate;
use App\Models\Evaluasi1\Eval1Exam;
use App\Models\Evaluasi1\Eval1ExamAttempt;
use App\Models\Evaluasi1\Eval1Material;
use App\Models\Evaluasi1\Eval1ProctoringLog;
use App\Models\Evaluasi1\Eval1Question;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TrainerEvaluasi1Controller extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'must.change.password']);
    }

    private function authorizeTrainerOrAdmin(): void
    {
        $user = Auth::user();
        abort_unless($user && ($user->isTrainer() || $user->isTrainingCentre() || $user->isSuperAdmin()), 403, 'Akses terbatas untuk Trainer & Admin TC.');
    }

    /**
     * Dashboard Trainer / TC untuk Evaluasi 1
     */
    public function index()
    {
        $this->authorizeTrainerOrAdmin();

        $materialsCount = Eval1Material::count();
        $examsCount = Eval1Exam::count();
        $attempts = Eval1ExamAttempt::with(['user', 'exam'])->latest()->take(10)->get();
        $totalPassed = Eval1ExamAttempt::where('is_passed', true)->count();
        $totalAttempts = Eval1ExamAttempt::where('status', 'completed')->count();
        $passRate = $totalAttempts > 0 ? round(($totalPassed / $totalAttempts) * 100) : 0;
        $totalCertificates = Eval1Certificate::count();

        $suspiciousLogsCount = Eval1ProctoringLog::whereIn('event_type', ['tab_switch', 'multiple_faces', 'no_face'])->count();

        return view('evaluasi-1.trainer.dashboard', compact(
            'materialsCount',
            'examsCount',
            'attempts',
            'passRate',
            'totalCertificates',
            'suspiciousLogsCount'
        ));
    }

    /**
     * Daftar Materi LMS
     */
    public function materials()
    {
        $this->authorizeTrainerOrAdmin();
        $materials = Eval1Material::with('creator')->orderBy('order')->get();
        return view('evaluasi-1.trainer.materials.index', compact('materials'));
    }

    /**
     * Form Tambah Materi
     */
    public function createMaterial()
    {
        $this->authorizeTrainerOrAdmin();
        return view('evaluasi-1.trainer.materials.create');
    }

    /**
     * Simpan Materi Baru
     */
    public function storeMaterial(Request $request)
    {
        $this->authorizeTrainerOrAdmin();
        $user = Auth::user();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:video,document,slide,pdf',
            'content_url' => 'nullable|string|max:500',
            'min_duration_seconds' => 'nullable|integer|min:0',
            'order' => 'nullable|integer',
            'material_file' => 'nullable|file|mimes:pdf,mp4,ppt,pptx,zip|max:51200',
        ]);

        $filePath = null;
        if ($request->hasFile('material_file')) {
            $filePath = $request->file('material_file')->store('lms_materials', 'public');
        }

        Eval1Material::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'type' => $validated['type'],
            'content_url' => $validated['content_url'] ?? null,
            'file_path' => $filePath,
            'min_duration_seconds' => $validated['min_duration_seconds'] ?? 0,
            'order' => $validated['order'] ?? (Eval1Material::max('order') + 1),
            'is_active' => true,
            'created_by' => $user->id,
        ]);

        return redirect()->route('evaluasi1.trainer.materials.index')->with('success', 'Materi berhasil ditambahkan ke modul LMS In-Class.');
    }

    /**
     * Hapus Materi
     */
    public function destroyMaterial($id)
    {
        $this->authorizeTrainerOrAdmin();
        $material = Eval1Material::findOrFail($id);
        if ($material->file_path && Storage::disk('public')->exists($material->file_path)) {
            Storage::disk('public')->delete($material->file_path);
        }
        $material->delete();

        return redirect()->route('evaluasi1.trainer.materials.index')->with('success', 'Materi berhasil dihapus.');
    }

    /**
     * Kelola Ujian & Bank Soal CBT
     */
    public function exams()
    {
        $this->authorizeTrainerOrAdmin();
        $exams = Eval1Exam::withCount('questions', 'attempts')->get();
        return view('evaluasi-1.trainer.exams.index', compact('exams'));
    }

    /**
     * Form Tambah Ujian CBT
     */
    public function createExam()
    {
        $this->authorizeTrainerOrAdmin();
        $materials = Eval1Material::where('is_active', true)->orderBy('order')->get();
        return view('evaluasi-1.trainer.exams.create', compact('materials'));
    }

    /**
     * Simpan Ujian Baru
     */
    public function storeExam(Request $request)
    {
        $this->authorizeTrainerOrAdmin();
        $user = Auth::user();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:pre_test,post_test,evaluasi_1',
            'material_id' => 'nullable|exists:eval1_materials,id',
            'description' => 'nullable|string',
            'duration_minutes' => 'required|integer|min:5|max:180',
            'passing_score' => 'required|numeric|min:0|max:100',
            'proctoring_enabled' => 'boolean',
        ]);

        $exam = Eval1Exam::create([
            'title' => $validated['title'],
            'type' => $validated['type'],
            'material_id' => $validated['material_id'] ?? null,
            'description' => $validated['description'] ?? null,
            'duration_minutes' => $validated['duration_minutes'],
            'passing_score' => $validated['passing_score'],
            'proctoring_enabled' => $request->boolean('proctoring_enabled', true),
            'is_active' => true,
            'created_by' => $user->id,
        ]);

        return redirect()->route('evaluasi1.trainer.exams.questions', $exam->id)->with('success', 'Ujian berhasil dibuat. Silakan tambahkan butir soal.');
    }

    /**
     * Kelola Soal untuk Ujian Tertentu
     */
    public function questions($id)
    {
        $this->authorizeTrainerOrAdmin();
        $exam = Eval1Exam::with('questions')->findOrFail($id);
        return view('evaluasi-1.trainer.exams.questions', compact('exam'));
    }

    /**
     * Simpan Butir Soal Baru
     */
    public function storeQuestion(Request $request, $examId)
    {
        $this->authorizeTrainerOrAdmin();
        $exam = Eval1Exam::findOrFail($examId);

        $validated = $request->validate([
            'question_text' => 'required|string',
            'option_a' => 'required|string',
            'option_b' => 'required|string',
            'option_c' => 'required|string',
            'option_d' => 'required|string',
            'correct_answer' => 'required|in:A,B,C,D',
            'score_weight' => 'required|numeric|min:1',
        ]);

        $options = [
            ['key' => 'A', 'text' => $validated['option_a']],
            ['key' => 'B', 'text' => $validated['option_b']],
            ['key' => 'C', 'text' => $validated['option_c']],
            ['key' => 'D', 'text' => $validated['option_d']],
        ];

        Eval1Question::create([
            'exam_id' => $exam->id,
            'question_text' => $validated['question_text'],
            'options' => $options,
            'correct_answer' => $validated['correct_answer'],
            'score_weight' => $validated['score_weight'],
            'order' => $exam->questions()->count() + 1,
        ]);

        return redirect()->route('evaluasi1.trainer.exams.questions', $exam->id)->with('success', 'Soal berhasil ditambahkan ke bank soal ujian.');
    }

    /**
     * Dashboard Pemantauan Live / Rekaman Camera Proctoring Trainee
     */
    public function proctoringMonitor(Request $request)
    {
        $this->authorizeTrainerOrAdmin();

        $query = Eval1ProctoringLog::with(['user', 'attempt.exam'])->latest();

        if ($request->filled('trainee_id')) {
            $query->where('user_id', $request->trainee_id);
        }

        if ($request->filled('event_type') && $request->event_type !== 'all') {
            $query->where('event_type', $request->event_type);
        }

        $logs = $query->paginate(24)->withQueryString();
        $trainees = User::where('role', 'trainee')->orderBy('name')->get();

        // Attempts yang sedang in_progress untuk live feed
        $liveAttempts = Eval1ExamAttempt::where('status', 'in_progress')
            ->with(['user', 'exam', 'proctoringLogs' => fn ($q) => $q->latest()->take(1)])
            ->get();

        return view('evaluasi-1.trainer.proctoring.index', compact('logs', 'trainees', 'liveAttempts'));
    }

    /**
     * Monitoring & Rekap Absensi Online In-Class Training Trainee
     */
    public function attendances(Request $request)
    {
        $this->authorizeTrainerOrAdmin();

        $query = Eval1Attendance::with(['trainee', 'verifier'])->latest('date');

        if ($request->filled('trainee_id')) {
            $query->where('user_id', $request->trainee_id);
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        $attendances = $query->paginate(20)->withQueryString();
        $trainees = User::where('role', 'trainee')->orderBy('name')->get();

        $totalPresent = Eval1Attendance::where('status', 'hadir')->count();
        $totalHours = (float) Eval1Attendance::where('status', 'hadir')->sum('total_hours');
        $totalUnverified = Eval1Attendance::whereNull('verified_at')->count();

        return view('evaluasi-1.trainer.attendances.index', compact(
            'attendances',
            'trainees',
            'totalPresent',
            'totalHours',
            'totalUnverified'
        ));
    }

    /**
     * Verifikasi Presensi oleh Instruktur / Trainer
     */
    public function verifyAttendance($id, Request $request)
    {
        $this->authorizeTrainerOrAdmin();

        $attendance = Eval1Attendance::findOrFail($id);
        $attendance->update([
            'verified_by' => Auth::id(),
            'verified_at' => Carbon::now(),
            'verification_notes' => $request->input('verification_notes', 'Telah diverifikasi oleh Instruktur.'),
        ]);

        return back()->with('success', 'Kehadiran trainee ' . ($attendance->trainee->name ?? '') . ' berhasil diverifikasi.');
    }
}
