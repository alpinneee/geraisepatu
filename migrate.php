<?php
// File untuk menjalankan migration di hosting
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

// Jalankan migration
$kernel->call('migrate', ['--force' => true]);

echo "Migration completed successfully!";
?>