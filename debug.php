<?php
// Debug script untuk melihat error
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<h2>Laravel Debug Info</h2>";

// Check PHP version
echo "<p>PHP Version: " . phpversion() . "</p>";

// Check if files exist
$files = ['.env', 'vendor/autoload.php', 'bootstrap/app.php'];
foreach ($files as $file) {
    echo "<p>$file: " . (file_exists($file) ? '✅ EXISTS' : '❌ MISSING') . "</p>";
}

// Check permissions
$dirs = ['storage', 'bootstrap/cache'];
foreach ($dirs as $dir) {
    if (is_dir($dir)) {
        echo "<p>$dir permissions: " . substr(sprintf('%o', fileperms($dir)), -4) . "</p>";
    }
}

// Try to load Laravel
try {
    require 'vendor/autoload.php';
    echo "<p>✅ Composer autoload OK</p>";
    
    $app = require_once 'bootstrap/app.php';
    echo "<p>✅ Laravel bootstrap OK</p>";
    
} catch (Exception $e) {
    echo "<p>❌ Error: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?>