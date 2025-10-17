<?php
// Script untuk deploy tanpa composer di hosting
// Upload file ini dan jalankan via browser

echo "<h2>Laravel Deployment Without Composer</h2>";

try {
    // 1. Cek apakah vendor folder ada
    if (!is_dir('vendor')) {
        echo "<p style='color: red;'>❌ Folder vendor tidak ditemukan!</p>";
        echo "<p><strong>Solusi:</strong> Upload folder vendor dari local ke hosting</p>";
        exit;
    }
    
    // 2. Cek file .env
    if (!file_exists('.env')) {
        if (file_exists('.env.example')) {
            copy('.env.example', '.env');
            echo "<p>✅ File .env dibuat dari .env.example</p>";
        } else {
            echo "<p style='color: red;'>❌ File .env tidak ditemukan!</p>";
            exit;
        }
    }
    
    // 3. Load Laravel
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    
    // 4. Generate key jika belum ada
    $envContent = file_get_contents('.env');
    if (strpos($envContent, 'APP_KEY=') === false || strpos($envContent, 'APP_KEY=base64:') === false) {
        $kernel->call('key:generate', ['--force' => true]);
        echo "<p>✅ APP_KEY generated</p>";
    }
    
    // 5. Jalankan migration
    echo "<p>🔄 Running migrations...</p>";
    $kernel->call('migrate', ['--force' => true]);
    echo "<p>✅ Migrations completed</p>";
    
    // 6. Cache optimization
    echo "<p>🔄 Optimizing cache...</p>";
    $kernel->call('config:cache');
    $kernel->call('route:cache');
    $kernel->call('view:cache');
    echo "<p>✅ Cache optimized</p>";
    
    echo "<h3 style='color: green;'>🎉 Deployment Successful!</h3>";
    echo "<p><strong>PENTING:</strong> Hapus file ini setelah deployment selesai!</p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
    echo "<p>Stack trace:</p><pre>" . $e->getTraceAsString() . "</pre>";
}
?>