<?php

namespace App\Http\Controllers\Evaluasi1;

use App\Http\Controllers\Controller;
use App\Models\Evaluasi1\Eval1Attendance;
use App\Models\Evaluasi1\Eval1Certificate;
use App\Models\Evaluasi1\Eval1Exam;
use App\Models\Evaluasi1\Eval1ExamAttempt;
use App\Models\Evaluasi1\Eval1Material;
use App\Models\Evaluasi1\Eval1MaterialProgress;
use App\Models\Evaluasi1\Eval1ProctoringLog;
use App\Services\PhaseService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TraineeLmsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'must.change.password']);
    }

    /**
     * Tampilan utama LMS Trainee untuk Evaluasi 1
     */
    public function index()
    {
        $user = Auth::user();
        abort_unless($user && $user->isTrainee(), 403, 'Akses terbatas untuk Trainee.');

        // SOP Evaluasi 1 Berdasarkan Sertifikasi Trainee (Green, Skill Up, Experience)
        $sop = PhaseService::evaluasi1Sop($user->certification);
        $targetHours = PhaseService::evaluasi1TargetHours($user->certification);
        $sopTopics = PhaseService::evaluasi1Topics($user->certification);
        $sopPassingScore = PhaseService::evaluasi1PassingScore();

        // Absensi Online In-Class LMS
        $attendances = Eval1Attendance::where('user_id', $user->id)
            ->orderBy('date', 'desc')
            ->latest()
            ->get();

        $todayAttendance = Eval1Attendance::where('user_id', $user->id)
            ->whereDate('date', Carbon::today())
            ->first();

        $totalAttendanceHours = (float) Eval1Attendance::where('user_id', $user->id)
            ->where('status', 'hadir')
            ->sum('total_hours');

        $attendancePercent = $targetHours > 0 ? min(100, round(($totalAttendanceHours / $targetHours) * 100)) : 0;

        $materials = Eval1Material::where('is_active', true)
            ->orderBy('order')
            ->get();

        $progresses = Eval1MaterialProgress::where('user_id', $user->id)
            ->get()
            ->keyBy('material_id');

        $completedCount = $progresses->where('is_completed', true)->count();
        $totalMaterials = $materials->count();
        $progressPercent = $totalMaterials > 0 ? round(($completedCount / $totalMaterials) * 100) : 0;

        $exams = Eval1Exam::where('is_active', true)->with('questions')->get();

        $attempts = Eval1ExamAttempt::where('user_id', $user->id)
            ->latest()
            ->get()
            ->keyBy('exam_id');

        $certificate = Eval1Certificate::where('user_id', $user->id)->latest()->first();

        // Ringkasan status post-test per materi & kelayakan Tes Akhir CBT
        $eval1Summary = PhaseService::evaluasi1Summary($user);

        return view('evaluasi-1.trainee.lms', compact(
            'sop',
            'targetHours',
            'sopTopics',
            'sopPassingScore',
            'attendances',
            'todayAttendance',
            'totalAttendanceHours',
            'attendancePercent',
            'materials',
            'progresses',
            'completedCount',
            'totalMaterials',
            'progressPercent',
            'exams',
            'attempts',
            'certificate',
            'eval1Summary'
        ));
    }

    /**
     * Buka materi / video pembelajaran
     */
    public function showMaterial($id)
    {
        $user = Auth::user();
        abort_unless($user && $user->isTrainee(), 403);

        $material = Eval1Material::where('is_active', true)->findOrFail($id);
        
        $progress = Eval1MaterialProgress::firstOrCreate(
            ['user_id' => $user->id, 'material_id' => $material->id],
            ['watch_time_seconds' => 0, 'is_completed' => false]
        );

        return view('evaluasi-1.trainee.material-view', compact('material', 'progress'));
    }

    /**
     * Simpan tracking progress tonton video
     */
    public function updateProgress(Request $request, $id): JsonResponse
    {
        $user = Auth::user();
        abort_unless($user && $user->isTrainee(), 403);

        $material = Eval1Material::findOrFail($id);
        $seconds = (int) $request->input('seconds', 0);
        $forceComplete = (bool) $request->input('force_complete', false);

        $progress = Eval1MaterialProgress::firstOrCreate(
            ['user_id' => $user->id, 'material_id' => $material->id]
        );

        $newWatchTime = max($progress->watch_time_seconds, $seconds);
        $isCompleted = $progress->is_completed || $forceComplete || ($newWatchTime >= $material->min_duration_seconds);

        $progress->update([
            'watch_time_seconds' => $newWatchTime,
            'is_completed' => $isCompleted,
            'completed_at' => ($isCompleted && ! $progress->completed_at) ? Carbon::now() : $progress->completed_at,
        ]);

        return response()->json([
            'success' => true,
            'watch_time' => $progress->watch_time_seconds,
            'is_completed' => $progress->is_completed,
        ]);
    }

    /**
     * Halaman instruksi & persiapan Camera Proctoring
     */
    public function examPreparation($id)
    {
        $user = Auth::user();
        abort_unless($user && $user->isTrainee(), 403);

        $exam = Eval1Exam::with(['questions', 'material'])->findOrFail($id);

        // Jika ujian adalah post-test materi, pastikan materi sudah selesai dipelajari
        if ($exam->material_id && $exam->material) {
            if (! $exam->material->isCompletedBy($user)) {
                return redirect()->route('evaluasi1.trainee.lms')
                    ->with('error', "Anda harus menyelesaikan modul pembelajaran '{$exam->material->title}' terlebih dahulu sebelum dapat mengikuti Post-Test.");
            }
        }

        // Jika ujian adalah Tes Akhir CBT Evaluasi 1, pastikan seluruh materi selesai & seluruh post-test lulus (>= 80)
        if ($exam->type === 'evaluasi_1') {
            $check = PhaseService::evaluasi1CanTakeFinalExam($user);
            if (! $check['can_take']) {
                $reasonText = implode(' ', $check['reasons']);
                return redirect()->route('evaluasi1.trainee.lms')
                    ->with('error', "Tes Akhir CBT terkunci! " . $reasonText);
            }
        }

        $latestAttempt = Eval1ExamAttempt::where('exam_id', $exam->id)
            ->where('user_id', $user->id)
            ->latest()
            ->first();

        return view('evaluasi-1.trainee.exam-prep', compact('exam', 'latestAttempt'));
    }

    /**
     * Mulai ujian CBT & Camera Proctoring
     */
    public function startExam($id)
    {
        $user = Auth::user();
        abort_unless($user && $user->isTrainee(), 403);

        $exam = Eval1Exam::with(['questions', 'material'])->findOrFail($id);

        // Validasi prasyarat modul untuk post-test materi
        if ($exam->material_id && $exam->material) {
            if (! $exam->material->isCompletedBy($user)) {
                return redirect()->route('evaluasi1.trainee.lms')
                    ->with('error', "Anda harus menyelesaikan modul pembelajaran '{$exam->material->title}' terlebih dahulu sebelum dapat mengikuti Post-Test.");
            }
        }

        // Validasi prasyarat kelulusan seluruh post-test untuk Tes Akhir CBT Evaluasi 1
        if ($exam->type === 'evaluasi_1') {
            $check = PhaseService::evaluasi1CanTakeFinalExam($user);
            if (! $check['can_take']) {
                $reasonText = implode(' ', $check['reasons']);
                return redirect()->route('evaluasi1.trainee.lms')
                    ->with('error', "Tes Akhir CBT terkunci! " . $reasonText);
            }
        }

        // Periksa apakah ada attempt yang masih berjalan
        $attempt = Eval1ExamAttempt::where('exam_id', $exam->id)
            ->where('user_id', $user->id)
            ->where('status', 'in_progress')
            ->first();

        if (! $attempt) {
            $attempt = Eval1ExamAttempt::create([
                'exam_id' => $exam->id,
                'user_id' => $user->id,
                'started_at' => Carbon::now(),
                'status' => 'in_progress',
                'answers' => [],
                'total_score' => 0,
                'is_passed' => false,
            ]);
        }

        return view('evaluasi-1.trainee.exam-run', compact('exam', 'attempt'));
    }

    /**
     * Terima snapshot gambar webcam Camera Proctoring via AJAX
     */
    public function proctoringSnapshot(Request $request, $attemptId): JsonResponse
    {
        $user = Auth::user();
        $attempt = Eval1ExamAttempt::where('user_id', $user->id)->findOrFail($attemptId);

        $imageData = $request->input('image');
        $eventType = $request->input('event_type', 'periodic_capture');
        $eventDetails = $request->input('details', 'Pengawasan berkala webcam CBT');

        $snapshotPath = null;

        if ($imageData && preg_match('/^data:image\/(\w+);base64,/', $imageData)) {
            $imageBinary = base64_decode(substr($imageData, strpos($imageData, ',') + 1));
            $fileName = 'proctoring/' . $attempt->id . '_' . time() . '_' . uniqid() . '.jpg';
            Storage::disk('public')->put($fileName, $imageBinary);
            $snapshotPath = $fileName;
        }

        Eval1ProctoringLog::create([
            'attempt_id' => $attempt->id,
            'user_id' => $user->id,
            'snapshot_path' => $snapshotPath,
            'event_type' => $eventType,
            'event_details' => $eventDetails,
            'created_at' => Carbon::now(),
        ]);

        if (in_array($eventType, ['tab_switch', 'multiple_faces', 'no_face'])) {
            $attempt->increment('suspicious_flags_count');
        }

        return response()->json(['success' => true]);
    }

    /**
     * Selesaikan & kumpulkan ujian CBT
     */
    public function submitExam(Request $request, $attemptId)
    {
        $user = Auth::user();
        $attempt = Eval1ExamAttempt::with(['exam.questions', 'exam.material'])->where('user_id', $user->id)->findOrFail($attemptId);
        $exam = $attempt->exam;

        $userAnswers = $request->input('answers', []);
        $totalScore = 0.0;
        $maxScore = 0.0;

        foreach ($exam->questions as $question) {
            $weight = (float) $question->score_weight;
            $maxScore += $weight;

            $chosen = $userAnswers[$question->id] ?? null;
            if ($chosen && strtoupper($chosen) === strtoupper($question->correct_answer)) {
                $totalScore += $weight;
            }
        }

        $finalPercentage = $maxScore > 0 ? round(($totalScore / $maxScore) * 100, 2) : 0;
        $passingThreshold = (float) ($exam->passing_score > 0 ? $exam->passing_score : 80.00);
        $isPassed = $finalPercentage >= $passingThreshold;

        $attempt->update([
            'submitted_at' => Carbon::now(),
            'answers' => $userAnswers,
            'total_score' => $finalPercentage,
            'is_passed' => $isPassed,
            'status' => 'completed',
        ]);

        // Auto-generate SIMPER L1 jika dan hanya jika trainee memenuhi SELURUH syarat kelulusan Evaluasi 1:
        // 1. Nilai minimal 80 di seluruh post-test modul materi (tidak ada remedial)
        // 2. Nilai minimal 80 di Tes Akhir CBT Evaluasi 1
        if (PhaseService::isTraineePassedEvaluasi1($user)) {
            $existingCert = Eval1Certificate::where('user_id', $user->id)->first();
            if (! $existingCert) {
                // Cari attempt tes akhir evaluasi 1
                $finalAttempt = ($exam->type === 'evaluasi_1')
                    ? $attempt
                    : Eval1ExamAttempt::where('user_id', $user->id)
                        ->whereHas('exam', fn ($q) => $q->where('type', 'evaluasi_1'))
                        ->where('status', 'completed')
                        ->orderBy('total_score', 'desc')
                        ->first();

                if ($finalAttempt && (float) $finalAttempt->total_score >= 80.0) {
                    Eval1Certificate::create([
                        'user_id' => $user->id,
                        'exam_attempt_id' => $finalAttempt->id,
                        'certificate_number' => Eval1Certificate::generateCertificateNumber($user),
                        'license_type' => 'SIMPER L1 (In-Class LMS & CBT)',
                        'issue_date' => Carbon::now()->format('Y-m-d'),
                        'valid_until' => Carbon::now()->addMonths(6)->format('Y-m-d'),
                        'score' => (float) $finalAttempt->total_score,
                        'status' => 'valid',
                        'notes' => 'Telah lulus seluruh Post-Test materi in-class LMS (skor min. 80) dan lulus Ujian Teori CBT Evaluasi 1 (skor min. 80) dengan Camera Proctoring.',
                    ]);
                }
            }
        }

        return redirect()->route('evaluasi1.trainee.exam.result', $attempt->id);
    }

    /**
     * Hasil ujian CBT
     */
    public function result($attemptId)
    {
        $user = Auth::user();
        $attempt = Eval1ExamAttempt::with(['exam.questions', 'certificate'])->where('user_id', $user->id)->findOrFail($attemptId);

        return view('evaluasi-1.trainee.exam-result', compact('attempt'));
    }

    /**
     * Absen Masuk (Clock In) Trainee di LMS
     */
    public function clockIn(Request $request)
    {
        $user = Auth::user();
        abort_unless($user && $user->isTrainee(), 403);

        $today = Carbon::today()->format('Y-m-d');
        $existing = Eval1Attendance::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->first();

        if ($existing && $existing->clock_in) {
            return back()->with('error', 'Anda sudah melakukan Absen Masuk untuk hari ini pada pukul ' . Carbon::parse($existing->clock_in)->format('H:i') . ' WITA.');
        }

        $request->validate([
            'topic' => 'required|string',
            'notes' => 'nullable|string',
            'location' => 'nullable|string',
        ]);

        $selfiePath = null;
        if ($request->filled('selfie_image')) {
            $imageData = $request->input('selfie_image');
            if (preg_match('/^data:image\/(\w+);base64,/', $imageData)) {
                $imageBinary = base64_decode(substr($imageData, strpos($imageData, ',') + 1));
                $fileName = 'attendances/' . $user->id . '_' . time() . '_' . uniqid() . '.jpg';
                Storage::disk('public')->put($fileName, $imageBinary);
                $selfiePath = $fileName;
            }
        }

        $nowTime = Carbon::now()->format('H:i:s');

        if ($existing) {
            $existing->update([
                'clock_in' => $nowTime,
                'topic' => $request->topic,
                'status' => 'hadir',
                'location' => $request->location ?? 'Training Centre Berau Coal (In-Class LMS)',
                'notes' => $request->notes,
                'selfie_path' => $selfiePath ?? $existing->selfie_path,
            ]);
        } else {
            Eval1Attendance::create([
                'user_id' => $user->id,
                'date' => $today,
                'clock_in' => $nowTime,
                'total_hours' => 8.00,
                'topic' => $request->topic,
                'status' => 'hadir',
                'location' => $request->location ?? 'Training Centre Berau Coal (In-Class LMS)',
                'notes' => $request->notes,
                'selfie_path' => $selfiePath,
            ]);
        }

        return back()->with('success', '✓ Absen Masuk berhasil dicatat pada pukul ' . Carbon::now()->format('H:i') . ' WITA.');
    }

    /**
     * Absen Pulang (Clock Out) Trainee di LMS
     */
    public function clockOut(Request $request)
    {
        $user = Auth::user();
        abort_unless($user && $user->isTrainee(), 403);

        $today = Carbon::today()->format('Y-m-d');
        $attendance = Eval1Attendance::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->first();

        if (! $attendance) {
            return back()->with('error', 'Anda belum melakukan Absen Masuk untuk hari ini.');
        }

        if ($attendance->clock_out) {
            return back()->with('info', 'Anda sudah melakukan Absen Pulang hari ini pada pukul ' . Carbon::parse($attendance->clock_out)->format('H:i') . ' WITA.');
        }

        $nowTime = Carbon::now()->format('H:i:s');

        // Hitung durasi jam aktual dari clock_in ke clock_out jika ada
        $hours = 8.00;
        if ($attendance->clock_in) {
            $in = Carbon::parse($attendance->clock_in);
            $out = Carbon::now();
            $diffHours = round($out->diffInMinutes($in) / 60, 2);
            $hours = max(1.0, min(10.0, $diffHours > 0 ? $diffHours : 8.00));
        }

        $attendance->update([
            'clock_out' => $nowTime,
            'total_hours' => $hours,
        ]);

        return back()->with('success', '✓ Absen Pulang berhasil dicatat pada pukul ' . Carbon::now()->format('H:i') . ' WITA. Total jam sesi hari ini: ' . $hours . ' Jam.');
    }

    /**
     * Simpan Presensi Manual (Izin / Sakit / Sesi Tambahan)
     */
    public function storeAttendance(Request $request)
    {
        $user = Auth::user();
        abort_unless($user && $user->isTrainee(), 403);

        $request->validate([
            'date' => 'required|date',
            'topic' => 'required|string',
            'status' => 'required|in:hadir,izin,sakit',
            'total_hours' => 'nullable|numeric|min:0|max:12',
            'notes' => 'nullable|string',
        ]);

        $selfiePath = null;
        if ($request->filled('selfie_image')) {
            $imageData = $request->input('selfie_image');
            if (preg_match('/^data:image\/(\w+);base64,/', $imageData)) {
                $imageBinary = base64_decode(substr($imageData, strpos($imageData, ',') + 1));
                $fileName = 'attendances/' . $user->id . '_' . time() . '_' . uniqid() . '.jpg';
                Storage::disk('public')->put($fileName, $imageBinary);
                $selfiePath = $fileName;
            }
        }

        $hours = $request->status === 'hadir' ? (float) ($request->total_hours ?? 8.00) : 0.00;

        Eval1Attendance::create([
            'user_id' => $user->id,
            'date' => $request->date,
            'clock_in' => $request->status === 'hadir' ? '08:00:00' : null,
            'clock_out' => $request->status === 'hadir' ? '16:00:00' : null,
            'total_hours' => $hours,
            'topic' => $request->topic,
            'status' => $request->status,
            'selfie_path' => $selfiePath,
            'notes' => $request->notes,
            'location' => 'Training Centre Berau Coal (In-Class LMS)',
        ]);

        return back()->with('success', '✓ Data presensi berhasil disimpan.');
    }
}
