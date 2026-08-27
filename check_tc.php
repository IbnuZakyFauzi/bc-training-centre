<?php
require 'vendor/autoload.php';

use App\Models\OjtLogbook;

$logs = OjtLogbook::where('trainee_id', 21)->where('status', 'final_approved')->get();
foreach ($logs as $l) {
    echo 'Logbook ' . $l->id . ': tc_decided=' . ($l->training_centre_decided_at ?? 'null') . PHP_EOL;
}
