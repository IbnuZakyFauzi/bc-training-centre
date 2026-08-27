<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$logbooks = App\Models\OjtLogbook::orderBy('id', 'desc')->get();
foreach ($logbooks as $l) {
    echo "ID: {$l->id} | Status: {$l->status} | trainer_ratings: " . json_encode($l->trainer_ratings) . "\n";
}
