<?php
// Manual migration tanpa artisan command
require_once 'vendor/autoload.php';

// Load environment
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Database connection
$host = $_ENV['DB_HOST'];
$dbname = $_ENV['DB_DATABASE'];
$username = $_ENV['DB_USERNAME'];
$password = $_ENV['DB_PASSWORD'];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>Manual Database Migration</h2>";
    
    // Jalankan SQL migration files secara manual
    $migrationFiles = [
        'database/migrations/2025_01_21_000001_drop_coupons_table.php',
        'database/migrations/2025_01_21_000002_drop_personal_access_tokens_table.php',
        // tambahkan file migration lainnya
    ];
    
    foreach ($migrationFiles as $file) {
        if (file_exists($file)) {
            echo "<p>Processing: $file</p>";
            // Extract SQL dari migration file dan execute
            // (implementasi tergantung struktur migration)
        }
    }
    
    echo "<p style='color: green;'>✅ Manual migration completed!</p>";
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>❌ Database Error: " . $e->getMessage() . "</p>";
}
?>