<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<h2>Server Info</h2>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Current Directory: " . __DIR__ . "<br>";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "<br>";

echo "<h3>File Check</h3>";
$files = ['.env', 'vendor/autoload.php', 'bootstrap/app.php', 'composer.json'];
foreach ($files as $file) {
    $exists = file_exists($file);
    $readable = $exists ? is_readable($file) : false;
    echo "$file: " . ($exists ? '✅ EXISTS' : '❌ MISSING');
    if ($exists) echo " | " . ($readable ? '✅ READABLE' : '❌ NOT READABLE');
    echo "<br>";
}

echo "<h3>Directory Permissions</h3>";
$dirs = ['storage', 'bootstrap/cache', 'vendor'];
foreach ($dirs as $dir) {
    if (is_dir($dir)) {
        $perms = substr(sprintf('%o', fileperms($dir)), -4);
        $writable = is_writable($dir);
        echo "$dir: $perms " . ($writable ? '✅ WRITABLE' : '❌ NOT WRITABLE') . "<br>";
    } else {
        echo "$dir: ❌ NOT EXISTS<br>";
    }
}

echo "<h3>Test Autoload</h3>";
try {
    require 'vendor/autoload.php';
    echo "✅ Autoload successful<br>";
} catch (Exception $e) {
    echo "❌ Autoload failed: " . $e->getMessage() . "<br>";
}

echo "<h3>Test Laravel Bootstrap</h3>";
try {
    $app = require_once 'bootstrap/app.php';
    echo "✅ Laravel bootstrap successful<br>";
    echo "App class: " . get_class($app) . "<br>";
} catch (Exception $e) {
    echo "❌ Laravel bootstrap failed: " . $e->getMessage() . "<br>";
    echo "Stack trace:<br><pre>" . $e->getTraceAsString() . "</pre>";
}

phpinfo();
?>