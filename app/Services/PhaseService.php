<?php

namespace App\Services;

use App\Models\Evaluasi1\Eval1Certificate;
use App\Models\Evaluasi1\Eval1Exam;
use App\Models\Evaluasi1\Eval1ExamAttempt;
use App\Models\Evaluasi1\Eval1Material;
use App\Models\Evaluasi1\Eval1MaterialProgress;
use App\Models\OjtLogbook;
use App\Models\User;

class PhaseService
{
    public const CERTIFICATIONS = [
        'Green',
        'Skill-up',
        'Experience_internal',
        'Experience_external',
    ];

    public const PHASES = [
        'Green' => [
            ['key' => 'evaluasi_3', 'label' => 'Evaluasi 3', 'type' => 'evaluasi', 'total_hm' => 72, 'day_hm' => 56, 'night_hm' => 16],
            ['key' => 'evaluasi_4', 'label' => 'Evaluasi 4', 'type' => 'evaluasi', 'total_hm' => 172, 'day_hm' => 172, 'night_hm' => 0],
            ['key' => 'bulanan_5', 'label' => 'Evaluasi Bulanan 5', 'type' => 'bulanan'],
            ['key' => 'bulanan_6', 'label' => 'Evaluasi Bulanan 6', 'type' => 'bulanan'],
            ['key' => 'bulanan_7', 'label' => 'Evaluasi Bulanan 7', 'type' => 'bulanan'],
            ['key' => 'bulanan_8', 'label' => 'Evaluasi Bulanan 8', 'type' => 'bulanan'],
            ['key' => 'bulanan_9', 'label' => 'Evaluasi Bulanan 9', 'type' => 'bulanan'],
            ['key' => 'bulanan_10', 'label' => 'Evaluasi Bulanan 10', 'type' => 'bulanan'],
        ],
        'Skill-up' => [
            ['key' => 'evaluasi_3', 'label' => 'Evaluasi 3', 'type' => 'evaluasi', 'total_hm' => 32, 'day_hm' => 16, 'night_hm' => 16],
            ['key' => 'evaluasi_4', 'label' => 'Evaluasi 4', 'type' => 'evaluasi', 'total_hm' => 56, 'day_hm' => 56, 'night_hm' => 0],
            ['key' => 'bulanan_5', 'label' => 'Evaluasi Bulanan 5', 'type' => 'bulanan'],
            ['key' => 'bulanan_6', 'label' => 'Evaluasi Bulanan 6', 'type' => 'bulanan'],
            ['key' => 'bulanan_7', 'label' => 'Evaluasi Bulanan 7', 'type' => 'bulanan'],
            ['key' => 'bulanan_8', 'label' => 'Evaluasi Bulanan 8', 'type' => 'bulanan'],
            ['key' => 'bulanan_9', 'label' => 'Evaluasi Bulanan 9', 'type' => 'bulanan'],
            ['key' => 'bulanan_10', 'label' => 'Evaluasi Bulanan 10', 'type' => 'bulanan'],
        ],
        'Experience_external' => [
            ['key' => 'evaluasi_3', 'label' => 'Evaluasi 3', 'type' => 'evaluasi', 'total_hm' => 32, 'day_hm' => 16, 'night_hm' => 16],
            ['key' => 'evaluasi_4', 'label' => 'Evaluasi 4', 'type' => 'evaluasi', 'total_hm' => 56, 'day_hm' => 56, 'night_hm' => 0],
            ['key' => 'bulanan_5', 'label' => 'Evaluasi Bulanan 5', 'type' => 'bulanan'],
            ['key' => 'bulanan_6', 'label' => 'Evaluasi Bulanan 6', 'type' => 'bulanan'],
        ],
        'Experience_internal' => [
            ['key' => 'evaluasi_3', 'label' => 'Evaluasi 3', 'type' => 'evaluasi', 'total_hm' => 32, 'day_hm' => 16, 'night_hm' => 16],
            ['key' => 'evaluasi_4', 'label' => 'Evaluasi 4', 'type' => 'evaluasi', 'total_hm' => 32, 'day_hm' => 32, 'night_hm' => 0],
            ['key' => 'bulanan_5', 'label' => 'Evaluasi Bulanan 5', 'type' => 'bulanan'],
        ],
    ];

    /**
     * Standar Operasional Prosedur (SOP) In-Class Training & Evaluasi 1
     * Sesuai Dokumen Kurikulum Resmi PT Berau Coal & PT MTL.
     */
    public const EVALUASI_1_SOP = [
        'Green' => [
            'label' => 'Green',
            'target_hours' => 80,
            'passing_score' => 80.0,
            'topics' => [
                'Kebijakan dan Pengetahuan K3L',
                'Teknis Pengetahuan Alat',
                'Defensive Driving termasuk pengendalian unit dalam kondisi emergency',
            ],
            'description' => 'In-Class Training operator Green dengan durasi 80 Jam pelatihan.',
        ],
        'Skill-up' => [
            'label' => 'Skill Up',
            'target_hours' => 32,
            'passing_score' => 80.0,
            'topics' => [
                'Kebijakan dan Pengetahuan K3L',
                'Teknis Pengetahuan Alat',
                'Defensive Driving termasuk pengendalian unit dalam kondisi emergency',
            ],
            'description' => 'In-Class Training peningkatan keterampilan operator Skill-Up (32 Jam).',
        ],
        'Experience_external' => [
            'label' => 'Experience Eksternal',
            'target_hours' => 16,
            'passing_score' => 80.0,
            'topics' => [
                'Kebijakan dan Pengetahuan K3L',
                'Defensive Driving termasuk pengendalian unit dalam kondisi emergency',
            ],
            'description' => 'In-Class Training penyegaran Operator Experience Eksternal (16 Jam).',
        ],
        'Experience_internal' => [
            'label' => 'Experience Internal',
            'target_hours' => 16,
            'passing_score' => 80.0,
            'topics' => [
                'Kebijakan dan Pengetahuan K3L',
                'Defensive Driving termasuk pengendalian unit dalam kondisi emergency',
            ],
            'description' => 'In-Class Training penyegaran Operator Experience Internal (16 Jam).',
        ],
    ];

    public static function evaluasi1Sop(?string $certification): array
    {
        $cert = $certification ?? 'Green';
        return self::EVALUASI_1_SOP[$cert] ?? self::EVALUASI_1_SOP['Green'];
    }

    public static function evaluasi1TargetHours(?string $certification): int
    {
        return self::evaluasi1Sop($certification)['target_hours'] ?? 80;
    }

    public static function evaluasi1Topics(?string $certification): array
    {
        return self::evaluasi1Sop($certification)['topics'] ?? [];
    }

    public static function evaluasi1PassingScore(): float
    {
        return 80.0;
    }

    public static function evaluasi1PostTestPassingScore(): float
    {
        return 80.0;
    }

    /**
     * Cek apakah trainee berhak mengambil Tes Akhir CBT (Evaluasi 1)
     * Syarat: Seluruh materi harus sudah selesai ditonton DAN seluruh post-test modul materi harus lulus (skor >= 80).
     */
    public static function evaluasi1CanTakeFinalExam(User $trainee): array
    {
        $materials = Eval1Material::where('is_active', true)->orderBy('order')->get();
        $uncompletedMaterials = [];
        $remedialMaterials = [];
        $unattemptedMaterials = [];

        foreach ($materials as $mat) {
            if (! $mat->isCompletedBy($trainee)) {
                $uncompletedMaterials[] = $mat;
            }

            $postTest = $mat->postTest;
            if ($postTest) {
                $attempt = $postTest->bestAttemptFor($trainee);
                if (! $attempt) {
                    $unattemptedMaterials[] = $mat;
                } elseif ((float) $attempt->total_score < 80.0) {
                    $remedialMaterials[] = [
                        'material' => $mat,
                        'score' => (float) $attempt->total_score,
                    ];
                }
            }
        }

        $canTake = empty($uncompletedMaterials) && empty($remedialMaterials) && empty($unattemptedMaterials);
        $reasons = [];

        if (! empty($uncompletedMaterials)) {
            $reasons[] = count($uncompletedMaterials) . ' materi belum selesai dipelajari.';
        }
        if (! empty($unattemptedMaterials)) {
            $reasons[] = count($unattemptedMaterials) . ' materi belum dikerjakan Post-Test-nya.';
        }
        if (! empty($remedialMaterials)) {
            $reasons[] = count($remedialMaterials) . ' materi berstatus REMEDIAL (skor < 80). Trainee wajib mengulang post-test materi tersebut.';
        }

        return [
            'can_take' => $canTake,
            'reasons' => $reasons,
            'uncompleted_materials' => $uncompletedMaterials,
            'unattempted_materials' => $unattemptedMaterials,
            'remedial_materials' => $remedialMaterials,
        ];
    }

    /**
     * Cek apakah trainee telah lulus Evaluasi 1 secara penuh.
     * Syarat kelulusan: Nilai minimal 80 pada SETIAP post-test materi DAN minimal 80 pada Tes Akhir CBT.
     */
    public static function isTraineePassedEvaluasi1(User $trainee): bool
    {
        // 1. Cek seluruh post-test materi
        $materials = Eval1Material::where('is_active', true)->get();
        if ($materials->isEmpty()) {
            return false;
        }

        foreach ($materials as $mat) {
            $postTest = $mat->postTest;
            if ($postTest) {
                $attempt = $postTest->bestAttemptFor($trainee);
                if (! $attempt || (float) $attempt->total_score < 80.0) {
                    return false;
                }
            } else {
                // Jika belum ada post-test dibuat untuk materi ini, minimal materi selesai
                if (! $mat->isCompletedBy($trainee)) {
                    return false;
                }
            }
        }

        // 2. Cek Tes Akhir CBT (tipe evaluasi_1)
        $finalExam = Eval1Exam::where('type', 'evaluasi_1')->where('is_active', true)->first();
        if (! $finalExam) {
            return false;
        }

        $finalAttempt = $finalExam->bestAttemptFor($trainee);
        if (! $finalAttempt || (float) $finalAttempt->total_score < 80.0) {
            return false;
        }

        return true;
    }

    /**
     * Ringkasan status kelulusan Evaluasi 1 Trainee (Post-test per materi & Tes Akhir CBT)
     */
    public static function evaluasi1Summary(User $trainee): array
    {
        $materials = Eval1Material::where('is_active', true)->with('postTest.attempts')->orderBy('order')->get();
        $materialDetails = [];

        $totalMaterials = $materials->count();
        $completedMaterials = 0;
        $passedPostTests = 0;
        $remedialPostTests = 0;
        $pendingPostTests = 0;

        foreach ($materials as $mat) {
            $isCompleted = $mat->isCompletedBy($trainee);
            if ($isCompleted) {
                $completedMaterials++;
            }

            $postTest = $mat->postTest;
            $bestAttempt = $postTest ? $postTest->bestAttemptFor($trainee) : null;
            $latestAttempt = $postTest ? $postTest->latestAttemptFor($trainee) : null;
            $score = $bestAttempt ? (float) $bestAttempt->total_score : null;

            $status = 'locked'; // jika materi belum selesai
            if ($isCompleted) {
                if ($bestAttempt) {
                    if ($score >= 80.0) {
                        $status = 'passed';
                        $passedPostTests++;
                    } else {
                        $status = 'remedial';
                        $remedialPostTests++;
                    }
                } else {
                    $status = 'pending';
                    $pendingPostTests++;
                }
            } else {
                $pendingPostTests++;
            }

            $materialDetails[] = [
                'material' => $mat,
                'is_completed' => $isCompleted,
                'post_test' => $postTest,
                'best_attempt' => $bestAttempt,
                'latest_attempt' => $latestAttempt,
                'score' => $score,
                'status' => $status, // locked, pending, passed, remedial
            ];
        }

        $finalExam = Eval1Exam::where('type', 'evaluasi_1')->where('is_active', true)->first();
        $finalAttempt = $finalExam ? $finalExam->bestAttemptFor($trainee) : null;
        $finalScore = $finalAttempt ? (float) $finalAttempt->total_score : null;
        $finalPassed = $finalScore !== null && $finalScore >= 80.0;
        $finalStatus = 'not_taken';
        if ($finalAttempt) {
            $finalStatus = $finalPassed ? 'passed' : 'remedial';
        }

        $canTakeFinalExam = ($totalMaterials > 0) && ($completedMaterials === $totalMaterials) && ($passedPostTests === $totalMaterials);
        $isOverallPassed = $canTakeFinalExam && $finalPassed;

        $certificate = Eval1Certificate::where('user_id', $trainee->id)->latest()->first();

        return [
            'total_materials' => $totalMaterials,
            'completed_materials' => $completedMaterials,
            'passed_post_tests' => $passedPostTests,
            'remedial_post_tests' => $remedialPostTests,
            'pending_post_tests' => $pendingPostTests,
            'can_take_final_exam' => $canTakeFinalExam,
            'final_exam' => $finalExam,
            'final_attempt' => $finalAttempt,
            'final_score' => $finalScore,
            'final_passed' => $finalPassed,
            'final_status' => $finalStatus,
            'is_overall_passed' => $isOverallPassed,
            'certificate' => $certificate,
            'material_details' => $materialDetails,
        ];
    }

    public static function sequence(string $certification): array
    {
        return self::PHASES[$certification] ?? [];
    }

    public static function firstPhase(string $certification): string
    {
        $seq = self::sequence($certification);
        return $seq[0]['key'] ?? 'evaluasi_3';
    }

    public static function lastPhase(string $certification): string
    {
        $seq = self::sequence($certification);
        return end($seq)['key'] ?? 'evaluasi_3';
    }

    public static function nextPhase(string $certification, string $current): ?string
    {
        $seq = self::sequence($certification);
        foreach ($seq as $i => $phase) {
            if ($phase['key'] === $current) {
                return $seq[$i + 1]['key'] ?? null;
            }
        }
        return null;
    }

    public static function meta(string $certification, string $phase): ?array
    {
        foreach (self::sequence($certification) as $p) {
            if ($p['key'] === $phase) {
                return $p;
            }
        }
        return null;
    }

    public static function currentPhaseKey(User $trainee): string
    {
        return $trainee->current_phase
            ?? self::firstPhase($trainee->certification ?? 'Green');
    }

    public static function currentPhaseMeta(User $trainee): ?array
    {
        return self::meta($trainee->certification ?? 'Green', self::currentPhaseKey($trainee));
    }

    public static function hmProgress(User $trainee, ?string $phase = null): array
    {
        $phase = $phase ?? self::currentPhaseKey($trainee);
        $query = OjtLogbook::where('trainee_id', $trainee->id)
            ->whereIn('status', ['verified', 'final_approved']);

        $startDate = self::currentPhaseStartDate($trainee, $phase);
        if ($startDate) {
            $query->where('created_at', '>=', $startDate);
        }

        $approved = $query->get();

        $day = (float) $approved->where('shift', 'day')->sum('total_hm');
        $night = (float) $approved->where('shift', 'night')->sum('total_hm');

        $firstPhase = self::firstPhase($trainee->certification ?? 'Green');
        $isFirstPhase = $phase === $firstPhase;
        $initialHmDay = $isFirstPhase ? (float) ($trainee->initial_hm_day ?? 0) : 0;
        $initialHmNight = $isFirstPhase ? (float) ($trainee->initial_hm_night ?? 0) : 0;

        return [
            'day' => $day + $initialHmDay,
            'night' => $night + $initialHmNight,
            'total' => $day + $night + $initialHmDay + $initialHmNight,
            'initial_hm_day' => $initialHmDay,
            'initial_hm_night' => $initialHmNight,
        ];
    }

    public static function currentPhaseStartDate(User $trainee, ?string $phase = null): ?\Carbon\Carbon
    {
        $phase = $phase ?? self::currentPhaseKey($trainee);

        $history = \App\Models\TraineePhaseHistory::where('user_id', $trainee->id)
            ->where('to_phase', $phase)
            ->orderBy('created_at', 'desc')
            ->first();

        if ($history) {
            return $history->created_at;
        }

        $firstLogbook = OjtLogbook::where('trainee_id', $trainee->id)
            ->orderBy('created_at', 'asc')
            ->first();

        if ($firstLogbook && $firstLogbook->created_at) {
            return $firstLogbook->created_at->startOfDay();
        }

        return null;
    }

    public static function phaseDateRange(User $trainee, string $phase): array
    {
        $seq = self::sequence($trainee->certification ?? 'Green');
        $phaseKeys = array_column($seq, 'key');
        $phaseIndex = array_search($phase, $phaseKeys);

        if ($phaseIndex === false) {
            return ['start' => null, 'end' => null, 'unreached' => false];
        }

        $start = null;
        $unreached = false;
        if ($phaseIndex === 0) {
            $start = null;
        } else {
            $startHistory = \App\Models\TraineePhaseHistory::where('user_id', $trainee->id)
                ->where('to_phase', $phase)
                ->orderBy('created_at', 'asc')
                ->first();
            if ($startHistory) {
                $start = $startHistory->created_at;
            } else {
                if (($trainee->current_phase ?? '') === $phase) {
                    $start = null;
                } else {
                    $unreached = true;
                }
            }
        }

        $nextPhaseKey = $phaseKeys[$phaseIndex + 1] ?? null;
        $end = null;
        if ($nextPhaseKey) {
            $endHistory = \App\Models\TraineePhaseHistory::where('user_id', $trainee->id)
                ->where('to_phase', $nextPhaseKey)
                ->orderBy('created_at', 'asc')
                ->first();
            if ($endHistory) {
                $end = $endHistory->created_at;
            }
        }

        return ['start' => $start, 'end' => $end, 'unreached' => $unreached];
    }

    public static function applyPhaseFilter($query, User $trainee, string $phase)
    {
        $range = self::phaseDateRange($trainee, $phase);
        $phasePattern = '%"phase":"' . $phase . '"%';

        if ($range['unreached']) {
            return $query->where(function ($q) use ($phasePattern) {
                $q->where('sop_payload', 'like', $phasePattern);
            });
        }

        return $query->where(function ($q) use ($phasePattern, $range) {
            $q->where('sop_payload', 'like', $phasePattern)
              ->orWhere(function ($sub) use ($range) {
                  $sub->where(function ($legacy) {
                      $legacy->whereNull('sop_payload')
                             ->orWhere('sop_payload', 'not like', '%"phase":%');
                  });
                  if ($range['start']) {
                      $sub->where('created_at', '>=', $range['start']);
                  }
                  if ($range['end']) {
                      $sub->where('created_at', '<', $range['end']);
                  }
              });
        });
    }

    public static function resolveLogbookPhase(OjtLogbook $log, ?User $trainee = null): string
    {
        $metaPhase = is_array($log->sop_payload) ? ($log->sop_payload['meta']['phase'] ?? null) : null;
        if (! empty($metaPhase)) {
            return $metaPhase;
        }

        $user = $trainee ?? $log->trainee;
        if (! $user) {
            return 'evaluasi_3';
        }

        $seq = self::sequence($user->certification ?? 'Green');
        $firstPhase = $seq[0]['key'] ?? 'evaluasi_3';

        $histories = \App\Models\TraineePhaseHistory::where('user_id', $user->id)
            ->orderBy('created_at', 'asc')
            ->get();

        if ($histories->isEmpty()) {
            return $firstPhase;
        }

        $currentMatched = $firstPhase;
        foreach ($histories as $h) {
            if ($log->created_at >= $h->created_at) {
                $currentMatched = $h->to_phase;
            } else {
                break;
            }
        }

        return $currentMatched;
    }

    public static function phaseLabel(string $phaseKey): string
    {
        $labels = [
            'evaluasi_1' => 'Evaluasi 1',
            'evaluasi_2' => 'Evaluasi 2',
            'evaluasi_3' => 'Evaluasi 3',
            'evaluasi_4' => 'Evaluasi 4',
            'bulanan_5' => 'Evaluasi 5',
            'bulanan_6' => 'Evaluasi 6',
            'bulanan_7' => 'Evaluasi 7',
            'bulanan_8' => 'Evaluasi 8',
            'bulanan_9' => 'Evaluasi 9',
            'bulanan_10' => 'Evaluasi 10',
        ];

        return $labels[$phaseKey] ?? ucfirst(str_replace('_', ' ', $phaseKey));
    }

    public static function phaseLogbookCount(User $trainee, ?string $phase = null): int
    {
        $phase = $phase ?? self::currentPhaseKey($trainee);
        $query = OjtLogbook::where('trainee_id', $trainee->id)
            ->whereIn('status', ['submitted', 'verified', 'final_approved']);

        $startDate = self::currentPhaseStartDate($trainee, $phase);
        if ($startDate) {
            $query->where('created_at', '>=', $startDate);
        }

        return $query->count();
    }

    public static function isEligible(User $trainee, ?string $phase = null): bool
    {
        $phase = $phase ?? self::currentPhaseKey($trainee);
        $meta = self::meta($trainee->certification ?? 'Green', $phase);

        if (! $meta) {
            return false;
        }

        if ($meta['type'] === 'bulanan') {
            return self::phaseLogbookCount($trainee, $phase) >= 4;
        }

        $progress = self::hmProgress($trainee, $phase);

        if ($progress['total'] < (float) ($meta['total_hm'] ?? 0)) {
            return false;
        }
        if (! is_null($meta['day_hm']) && $progress['day'] < (float) $meta['day_hm']) {
            return false;
        }
        if (! is_null($meta['night_hm']) && $progress['night'] < (float) $meta['night_hm']) {
            return false;
        }

        return true;
    }

    public static function progressPercent(User $trainee, ?string $phase = null): array
    {
        $phase = $phase ?? self::currentPhaseKey($trainee);
        $meta = self::meta($trainee->certification ?? 'Green', $phase);

        if (! $meta) {
            return [
                'type' => 'evaluasi',
                'total' => 100,
                'day' => 100,
                'night' => 100,
                'eligible' => true,
                'count' => 0,
                'target' => 0,
                'fraction' => '0/0',
            ];
        }

        if ($meta['type'] === 'bulanan') {
            $count = self::phaseLogbookCount($trainee, $phase);
            $target = 4;
            $fraction = min($count, $target) . '/' . $target;
            $pct = min(100, (int) round(($count / $target) * 100));

            return [
                'type' => 'bulanan',
                'total' => $pct,
                'day' => $pct,
                'night' => $pct,
                'count' => $count,
                'target' => $target,
                'fraction' => $fraction,
                'eligible' => $count >= $target,
            ];
        }

        $progress = self::hmProgress($trainee, $phase);
        $totalPct = $meta['total_hm'] > 0 ? min(100, (int) round($progress['total'] / $meta['total_hm'] * 100)) : 100;
        $dayPct = (! is_null($meta['day_hm']) && $meta['day_hm'] > 0)
            ? min(100, (int) round($progress['day'] / $meta['day_hm'] * 100)) : 100;
        $nightPct = (! is_null($meta['night_hm']) && $meta['night_hm'] > 0)
            ? min(100, (int) round($progress['night'] / $meta['night_hm'] * 100)) : 100;

        return [
            'type' => 'evaluasi',
            'total' => $totalPct,
            'day' => $dayPct,
            'night' => $nightPct,
            'count' => 0,
            'target' => 0,
            'fraction' => '',
            'eligible' => self::isEligible($trainee, $phase),
        ];
    }
}
