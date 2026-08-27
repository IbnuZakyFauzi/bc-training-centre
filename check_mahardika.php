<?php
require 'vendor/autoload.php';

use App\Models\User;
use App\Models\OjtLogbook;
use App\Models\FinalEvaluation;

$trainee = User::where('name', 'like', '%Mahardika%')->first();
if ($trainee) {
    echo 'Trainee ID: ' . $trainee->id . PHP_EOL;
    $logs = OjtLogbook::where('trainee_id', $trainee->id)->get();
    foreach ($logs as $l) {
        echo 'Logbook ' . $l->id . ': status=' . $l->status . ', tc_decided=' . ($l->training_centre_decided_at ?? 'null') . PHP_EOL;
    }
    $evals = FinalEvaluation::where('nama_operator', $trainee->name)->get();
    foreach ($evals as $e) {
        echo 'Eval ' . $e->id . ': status=' . $e->status . ', tanggal=' . $e->tanggal_penilaian . PHP_EOL;
    }
} else {
    echo 'Trainee not found' . PHP_EOL;
}
