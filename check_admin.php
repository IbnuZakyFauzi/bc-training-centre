<?php
try {
    $pdo = new PDO('pgsql:host=127.0.0.1;port=5432;dbname=ojt_evaluation_db', 'postgres', 'Zaky123!');
    
    // List all tables
    $stmt = $pdo->query("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public'");
    echo "Tables in database:\n";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "- " . $row['table_name'] . "\n";
    }
    
    // Look for users table
    $stmt = $pdo->query("SELECT * FROM users LIMIT 10");
    echo "\nUsers data:\n";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo json_encode($row, JSON_PRETTY_PRINT) . "\n";
    }
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage() . "\n";
}