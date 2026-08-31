<?php

namespace App\Services;

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

        return [
            'day' => $day,
            'night' => $night,
            'total' => $day + $night,
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

    public static function isEligible(User $trainee, ?string $phase = null): bool
    {
        $phase = $phase ?? self::currentPhaseKey($trainee);
        $meta = self::meta($trainee->certification ?? 'Green', $phase);

        if (! $meta) {
            return false;
        }

        if ($meta['type'] === 'bulanan') {
            return true;
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
        $progress = self::hmProgress($trainee, $phase);

        if (! $meta || $meta['type'] === 'bulanan') {
            return ['total' => 100, 'day' => 100, 'night' => 100, 'eligible' => true];
        }

        $totalPct = $meta['total_hm'] > 0 ? min(100, (int) round($progress['total'] / $meta['total_hm'] * 100)) : 100;
        $dayPct = (! is_null($meta['day_hm']) && $meta['day_hm'] > 0)
            ? min(100, (int) round($progress['day'] / $meta['day_hm'] * 100)) : 100;
        $nightPct = (! is_null($meta['night_hm']) && $meta['night_hm'] > 0)
            ? min(100, (int) round($progress['night'] / $meta['night_hm'] * 100)) : 100;

        return [
            'total' => $totalPct,
            'day' => $dayPct,
            'night' => $nightPct,
            'eligible' => self::isEligible($trainee, $phase),
        ];
    }
}
