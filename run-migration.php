<?php
// Jalankan via browser: yourdomain.com/run-migration.php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

echo "<h2>Running Laravel Migration...</h2>";

try {
    // Install/update dependencies
    echo "<p>Installing dependencies...</p>";
    exec('composer install --no-dev --optimize-autoloader 2>&1', $output);
    echo "<pre>" . implode("\n", $output) . "</pre>";
    
    // Run migration
    echo "<p>Running migrations...</p>";
    $kernel->call('migrate', ['--force' => true]);
    echo "<p style='color: green;'>✅ Migration completed successfully!</p>";
    
    // Cache config
    echo "<p>Caching configuration...</p>";
    $kernel->call('config:cache');
    $kernel->call('route:cache');
    $kernel->call('view:cache');
    
    echo "<p style='color: green;'>✅ All tasks completed!</p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}

// Hapus file ini setelah selesai untuk keamanan
echo "<p><strong>PENTING:</strong> Hapus file ini setelah migration selesai!</p>";
?>