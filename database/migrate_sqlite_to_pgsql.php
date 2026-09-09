<?php

declare(strict_types=1);

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\DB;

require __DIR__.'/../vendor/autoload.php';

$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

if (($argv[1] ?? null) !== '--execute') {
    fwrite(STDERR, "Dry-run only. Use: php database/migrate_sqlite_to_pgsql.php --execute".PHP_EOL);
    exit(1);
}

$sqlitePath = __DIR__.'/database.sqlite';

if (! is_file($sqlitePath)) {
    fwrite(STDERR, "SQLite database not found: {$sqlitePath}".PHP_EOL);
    exit(1);
}

$sqlite = new PDO('sqlite:'.$sqlitePath, null, null, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
$tables = [
    'equipment_categories',
    'equipments',
    'users',
    'ojt_logbooks',
    'competency_evaluations',
    'logbook_evidences',
    'logbook_histories',
    'trainee_trainer',
    'logbook_assignments',
    'ojt_final_evaluations',
    'trainee_phase_histories',
    'notifications',
    'password_reset_tokens',
    'sessions',
    'cache',
    'cache_locks',
];

$sequenceTables = [
    'equipment_categories',
    'equipments',
    'users',
    'ojt_logbooks',
    'competency_evaluations',
    'logbook_evidences',
    'logbook_histories',
    'trainee_trainer',
    'logbook_assignments',
    'ojt_final_evaluations',
    'trainee_phase_histories',
];

foreach ($tables as $table) {
    $sourceExists = (bool) $sqlite->query("SELECT EXISTS(SELECT 1 FROM sqlite_master WHERE type = 'table' AND name = ".$sqlite->quote($table).')')->fetchColumn();

    if (! $sourceExists) {
        throw new RuntimeException("Source table missing: {$table}");
    }

    if (DB::table($table)->exists()) {
        throw new RuntimeException("Target table is not empty: {$table}. Import stopped to prevent overwriting PostgreSQL data.");
    }
}

DB::transaction(function () use ($sqlite, $tables, $sequenceTables, &$logbooks, &$summaries): void {
    foreach ($tables as $table) {
        $statement = $sqlite->query('SELECT * FROM "'.$table.'"');

        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            // trainer_ratings belongs to the retired feature and is archived below.
            unset($row['trainer_ratings']);
            DB::table($table)->insert($row);
        }
    }

    $logbooks = $sqlite->query('SELECT id, trainer_ratings FROM ojt_logbooks')->fetchAll(PDO::FETCH_ASSOC);
    foreach ($logbooks as $logbook) {
        DB::table('legacy_sqlite_archives')->insert([
            'source_table' => 'ojt_logbooks',
            'source_id' => (string) $logbook['id'],
            'source_column' => 'trainer_ratings',
            // The source JSON text is retained verbatim inside the archive payload.
            'payload' => json_encode(['raw_value' => $logbook['trainer_ratings']], JSON_THROW_ON_ERROR),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    $summaries = $sqlite->query('SELECT * FROM trainer_rating_summaries')->fetchAll(PDO::FETCH_ASSOC);
    foreach ($summaries as $summary) {
        DB::table('legacy_sqlite_archives')->insert([
            'source_table' => 'trainer_rating_summaries',
            'source_id' => (string) $summary['id'],
            'source_column' => null,
            'payload' => json_encode($summary, JSON_THROW_ON_ERROR),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    foreach ($sequenceTables as $table) {
        $sequence = DB::selectOne("SELECT pg_get_serial_sequence(?, 'id') AS sequence", [$table])->sequence ?? null;

        if ($sequence === null) {
            continue;
        }

        $maximumId = (int) DB::table($table)->max('id');
        DB::select('SELECT setval(CAST(? AS regclass), ?, ?)', [$sequence, max(1, $maximumId), $maximumId > 0]);
    }
});

foreach ($tables as $table) {
    $sourceCount = (int) $sqlite->query('SELECT COUNT(*) FROM "'.$table.'"')->fetchColumn();
    $targetCount = DB::table($table)->count();

    if ($sourceCount !== $targetCount) {
        throw new RuntimeException("Row-count verification failed for {$table}: SQLite={$sourceCount}, PostgreSQL={$targetCount}");
    }

    echo "Verified {$table}: {$targetCount} rows".PHP_EOL;
}

$archiveCount = DB::table('legacy_sqlite_archives')->count();

if ($archiveCount !== count($logbooks) + count($summaries)) {
    throw new RuntimeException("Archive verification failed: expected ".(count($logbooks) + count($summaries)).", got {$archiveCount}");
}

echo "Verified legacy_sqlite_archives: {$archiveCount} archived records".PHP_EOL;
