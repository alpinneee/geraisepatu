<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<h2>Laravel Error Check</h2>";

// Check basic PHP
echo "PHP Version: " . phpversion() . "<br>";

// Check if vendor exists
if (is_dir('vendor')) {
    echo "✅ Vendor folder exists<br>";
} else {
    echo "❌ Vendor folder missing - run 'composer install'<br>";
}

// Check .env
if (file_exists('.env')) {
    echo "✅ .env file exists<br>";
    $env = file_get_contents('.env');
    if (strpos($env, 'APP_KEY=') !== false) {
        echo "✅ APP_KEY found in .env<br>";
    } else {
        echo "❌ APP_KEY missing in .env<br>";
    }
} else {
    echo "❌ .env file missing<br>";
}

// Check storage permissions
$dirs = ['storage/logs', 'storage/framework', 'bootstrap/cache'];
foreach ($dirs as $dir) {
    if (is_writable($dir)) {
        echo "✅ $dir is writable<br>";
    } else {
        echo "❌ $dir is not writable<br>";
    }
}

// Try to load Laravel
echo "<h3>Testing Laravel Bootstrap:</h3>";
try {
    if (file_exists('vendor/autoload.php')) {
        require 'vendor/autoload.php';
        echo "✅ Autoload successful<br>";
        
        if (file_exists('bootstrap/app.php')) {
            $app = require_once 'bootstrap/app.php';
            echo "✅ Laravel app loaded<br>";
        } else {
            echo "❌ bootstrap/app.php missing<br>";
        }
    } else {
        echo "❌ vendor/autoload.php missing<br>";
    }
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

// Check error logs
echo "<h3>Recent Error Logs:</h3>";
$logFile = 'storage/logs/laravel.log';
if (file_exists($logFile)) {
    $logs = file_get_contents($logFile);
    $lines = explode("\n", $logs);
    $recent = array_slice($lines, -20);
    echo "<pre>" . implode("\n", $recent) . "</pre>";
} else {
    echo "No log file found<br>";
}
?>