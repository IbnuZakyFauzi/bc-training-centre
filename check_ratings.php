<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$logbooks = App\Models\OjtLogbook::all();

$withRatings = 0;
$emptyRatings = 0;

foreach ($logbooks as $l) {
    $ratings = $l->trainer_ratings ?? [];
    if (!empty($ratings)) {
        $withRatings++;
        echo 'ID: ' . $l->id . ' ratings=' . json_encode($ratings) . PHP_EOL;
    } else {
        $emptyRatings++;
    }
}

echo 'Total logbooks: ' . $logbooks->count() . PHP_EOL;
echo 'With ratings: ' . $withRatings . PHP_EOL;
echo 'Empty ratings: ' . $emptyRatings . PHP_EOL;
