<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\OjtLogbook;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

$sqlite = new PDO('sqlite:' . __DIR__ . '/database/database.sqlite');
$sqlite->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

echo "=== MIGRATING SQLITE TO POSTGRESQL ===" . PHP_EOL;

// 1. Get all users from SQLite
$sqliteUsers = $sqlite->query('SELECT * FROM users')->fetchAll(PDO::FETCH_ASSOC);
echo "Found " . count($sqliteUsers) . " users in SQLite" . PHP_EOL;

// 2. Create mapping of old ID -> new ID
$idMapping = [];

foreach ($sqliteUsers as $oldUser) {
    $oldId = $oldUser['id'];
    
    // Check if user already exists in PostgreSQL by SID or email
    $existing = User::where('sid', $oldUser['sid'])
        ->orWhere('email', $oldUser['email'])
        ->first();
    
    if ($existing) {
        echo "User already exists: " . $oldUser['sid'] . " (PostgreSQL ID: " . $existing->id . ")" . PHP_EOL;
        $idMapping[$oldId] = $existing->id;
    } else {
        // Create new user
        $newUser = User::create([
            'sid' => $oldUser['sid'],
            'name' => $oldUser['name'],
            'email' => $oldUser['email'] ?: null,
            'password' => Hash::make('password'), // Default password for migrated users
            'role' => $oldUser['role'],
            'is_super_admin' => (bool)($oldUser['is_super_admin'] ?? false),
            'must_change_password' => true, // Force change password for migrated users
            'trainer_type' => $oldUser['trainer_type'] ?? null,
            'phone' => $oldUser['phone'] ?? null,
            'avatar' => $oldUser['avatar'] ?? null,
            'signature_path' => $oldUser['signature_path'] ?? null,
            'certification' => $oldUser['certification'] ?? 'Green',
            'current_phase' => $oldUser['current_phase'] ?? 'evaluasi_3',
            'company' => $oldUser['company'] ?? null,
            'equipment_category_id' => $oldUser['equipment_category_id'] ?? null,
            'equipment_number' => $oldUser['equipment_number'] ?? null,
            'initial_hm_day' => $oldUser['initial_hm_day'] ?? 0,
            'initial_hm_night' => $oldUser['initial_hm_night'] ?? 0,
            'sticker_expired_at' => $oldUser['sticker_expired_at'] ?? null,
        ]);
        
        echo "Migrated user: " . $oldUser['sid'] . " -> PostgreSQL ID: " . $newUser->id . PHP_EOL;
        $idMapping[$oldId] = $newUser->id;
    }
}

echo PHP_EOL . "=== MIGRATING LOGBOOKS ===" . PHP_EOL;

// 3. Get all logbooks from SQLite
$sqliteLogbooks = $sqlite->query('SELECT * FROM ojt_logbooks')->fetchAll(PDO::FETCH_ASSOC);
echo "Found " . count($sqliteLogbooks) . " logbooks in SQLite" . PHP_EOL;

$migratedLogbooks = 0;
foreach ($sqliteLogbooks as $oldLogbook) {
    $oldTraineeId = $oldLogbook['trainee_id'];
    
    if (!isset($idMapping[$oldTraineeId])) {
        echo "WARNING: Trainee ID $oldTraineeId not found in mapping, skipping logbook ID " . $oldLogbook['id'] . PHP_EOL;
        continue;
    }
    
    $newTraineeId = $idMapping[$oldTraineeId];
    
    // Check if logbook already exists
    $existing = OjtLogbook::where('id', $oldLogbook['id'])->first();
    if ($existing) {
        $migratedLogbooks++;
        continue;
    }
    
    // Map trainer_id if exists
    $newTrainerId = null;
    if ($oldLogbook['trainer_id'] && isset($idMapping[$oldLogbook['trainer_id']])) {
        $newTrainerId = $idMapping[$oldLogbook['trainer_id']];
    }
    
    // Map pengawas_trainer_id if exists
    $newPengawasId = null;
    if (isset($oldLogbook['pengawas_trainer_id']) && $oldLogbook['pengawas_trainer_id'] && isset($idMapping[$oldLogbook['pengawas_trainer_id']])) {
        $newPengawasId = $idMapping[$oldLogbook['pengawas_trainer_id']];
    }
    
    OjtLogbook::create([
        'id' => $oldLogbook['id'],
        'trainee_id' => $newTraineeId,
        'trainer_id' => $newTrainerId,
        'pengawas_trainer_id' => $newPengawasId,
        'date' => $oldLogbook['date'],
        'shift' => $oldLogbook['shift'],
        'location' => $oldLogbook['location'],
        'unit_code' => $oldLogbook['unit_code'] ?? null,
        'hm_start' => $oldLogbook['hm_start'] ?? 0,
        'hm_end' => $oldLogbook['hm_end'] ?? 0,
        'hm_day' => $oldLogbook['hm_day'] ?? 0,
        'hm_night' => $oldLogbook['hm_night'] ?? 0,
        'total_hm' => $oldLogbook['total_hm'] ?? 0,
        'sop_payload' => $oldLogbook['sop_payload'] ? json_decode($oldLogbook['sop_payload'], true) : null,
        'status' => $oldLogbook['status'],
        'comment' => $oldLogbook['comment'] ?? null,
        'created_at' => $oldLogbook['created_at'],
        'updated_at' => $oldLogbook['updated_at'],
    ]);
    
    $migratedLogbooks++;
}

echo "Migrated $migratedLogbooks logbooks" . PHP_EOL;

echo PHP_EOL . "=== MIGRATION COMPLETE ===" . PHP_EOL;
echo "Total users: " . User::count() . PHP_EOL;
echo "Total logbooks: " . OjtLogbook::count() . PHP_EOL;
