<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=e_commerce_toko_sepatu', 'root', '');
    echo "✓ MySQL Connection OK\n";
    
    // Check if sessions table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'sessions'");
    if ($stmt->rowCount() > 0) {
        echo "✓ Sessions table exists\n";
    } else {
        echo "✗ Sessions table NOT found - run: php artisan migrate\n";
    }
} catch (Exception $e) {
    echo "✗ Connection ERROR: " . $e->getMessage() . "\n";
}
?>
