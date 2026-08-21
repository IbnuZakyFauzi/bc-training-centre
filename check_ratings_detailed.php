<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$logbooks = App\Models\OjtLogbook::all();

foreach ($logbooks as $l) {
    $ratings = $l->trainer_ratings ?? [];
    $ratingCount = is_array($ratings) ? count($ratings) : 0;
    $hasNonEmpty = false;
    $filledCount = 0;
    
    if (is_array($ratings)) {
        foreach ($ratings as $r) {
            if (!empty($r['rating'])) {
                $hasNonEmpty = true;
                $filledCount++;
            }
        }
    }
    
    echo "ID: {$l->id} | Status: {$l->status} | Total ratings array: {$ratingCount} | Filled ratings: {$filledCount} | Has data: " . ($hasNonEmpty ? 'YES' : 'NO') . PHP_EOL;
    
    if ($hasNonEmpty) {
        echo "  Data: " . json_encode($ratings) . PHP_EOL;
    }
}
