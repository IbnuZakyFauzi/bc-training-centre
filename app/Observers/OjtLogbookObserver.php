<?php

namespace App\Observers;

use App\Models\OjtLogbook;
use App\Notifications\TraineeEligibleNotification;

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
        $url = route('trainer.final-evaluations.create', ['trainee' => $trainee->id]);

        foreach ($trainee->assignedTrainers as $trainer) {
            $alreadyNotified = $trainer->unreadNotifications()
                ->where('type', TraineeEligibleNotification::class)
                ->get()
                ->contains(fn ($n) => ($n->data['trainee_id'] ?? null) == $trainee->id
                    && ($n->data['phase'] ?? null) === $phase);

            if ($alreadyNotified) {
                continue;
            }

            $trainer->notify(new TraineeEligibleNotification([
                'trainee_id' => $trainee->id,
                'trainee_name' => $trainee->name,
                'certification' => $cert,
                'phase' => $phase,
                'phase_label' => $phaseLabel,
                'url' => $url,
                'message' => "Trainee {$trainee->name} sudah memenuhi syarat evaluasi fase {$phaseLabel} ({$cert}).",
            ]));
        }
    }
}
