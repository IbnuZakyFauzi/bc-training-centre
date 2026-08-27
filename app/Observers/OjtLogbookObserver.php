<?php

namespace App\Observers;

use App\Models\OjtLogbook;
use App\Notifications\HmThresholdReachedNotification;

class OjtLogbookObserver
{
    public function updated(OjtLogbook $logbook): void
    {
        if (! $logbook->wasChanged('status')) {
            return;
        }

        if (! in_array($logbook->status, ['verified', 'final_approved'], true)) {
            return;
        }

        $trainee = $logbook->trainee;
        if (! $trainee || ! $trainee->isTrainee()) {
            return;
        }

        if (! $trainee->isPhaseEligible()) {
            return;
        }

        $phase = $trainee->currentPhaseKey();
        $cert = $trainee->certification ?? 'Green';
        $phaseLabel = $trainee->currentPhaseMeta()['label'] ?? $phase;
        $url = url('/trainer/final-evaluation/create?trainee=' . $trainee->id);

        foreach ($trainee->assignedTrainers as $trainer) {
            $alreadyNotified = $trainer->unreadNotifications()
                ->where('type', HmThresholdReachedNotification::class)
                ->get()
                ->contains(fn ($n) => ($n->data['trainee_id'] ?? null) == $trainee->id
                    && ($n->data['phase'] ?? null) === $phase);

            if ($alreadyNotified) {
                continue;
            }

            $trainer->notify(new HmThresholdReachedNotification([
                'title' => 'Syarat Jam HM Terpenuhi',
                'message' => "Trainee {$trainee->name} sudah memenuhi syarat evaluasi fase {$phaseLabel} ({$cert}).",
                'url' => $url,
                'trainee_id' => $trainee->id,
                'trainee_name' => $trainee->name,
                'certification' => $cert,
                'phase' => $phase,
                'phase_label' => $phaseLabel,
            ]));
        }
    }
}
