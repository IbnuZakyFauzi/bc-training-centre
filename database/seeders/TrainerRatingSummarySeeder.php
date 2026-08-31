<?php

namespace Database\Seeders;

use App\Models\OjtLogbook;
use App\Models\TrainerRatingSummary;
use Illuminate\Database\Seeder;

class TrainerRatingSummarySeeder extends Seeder
{
    public function run(): void
    {
        $ratings = OjtLogbook::whereNotNull('trainer_ratings')
            ->get()
            ->filter(fn ($logbook) => !empty($logbook->trainer_ratings))
            ->flatMap(fn ($logbook) => collect($logbook->trainer_ratings ?? []))
            ->filter(fn ($r) => isset($r['user_id'], $r['rating']))
            ->groupBy('user_id');

        foreach ($ratings as $userId => $items) {
            $ratingsList = $items->pluck('rating')->filter()->map(fn ($r) => (int) $r);

            TrainerRatingSummary::updateOrCreate(
                ['user_id' => $userId],
                [
                    'average_rating' => $ratingsList->isNotEmpty() ? round($ratingsList->avg(), 1) : 0,
                    'rating_count' => $ratingsList->count(),
                ]
            );
        }
    }
}
