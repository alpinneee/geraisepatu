<?php
echo "=== VERIFYING DIRECTORY STRUCTURE ===\n\n";

$paths = [
    'Root directory' => __DIR__,
    'Public directory' => __DIR__ . '/public',
    'Public storage' => __DIR__ . '/public/storage',
    'Storage app public' => __DIR__ . '/storage/app/public',
    'Categories folder' => __DIR__ . '/storage/app/public/categories'
];

foreach ($paths as $name => $path) {
    echo "{$name}: {$path}\n";
    echo "Exists: " . (file_exists($path) ? '✅' : '❌') . "\n";
    if (file_exists($path)) {
        echo "Type: " . (is_dir($path) ? 'Directory' : (is_link($path) ? 'Symlink' : 'File')) . "\n";
        if (is_link($path)) {
            echo "Target: " . readlink($path) . "\n";
        }
    }
    echo "---\n";
}

echo "\n=== CHECKING CATEGORY FILES ===\n";
$categoriesPath = __DIR__ . '/storage/app/public/categories';
if (is_dir($categoriesPath)) {
    $files = scandir($categoriesPath);
    foreach ($files as $file) {
        if ($file != '.' && $file != '..') {
            $fullPath = $categoriesPath . '/' . $file;
            echo "File: {$file}\n";
            echo "Size: " . filesize($fullPath) . " bytes\n";
            echo "Permissions: " . substr(sprintf('%o', fileperms($fullPath)), -4) . "\n";
            echo "---\n";
        }
    }
}

echo "\n=== TESTING WEB ACCESS ===\n";
$testFile = __DIR__ . '/public/storage/categories/test.txt';
file_put_contents($testFile, 'Test file for web access');
echo "Created test file: {$testFile}\n";
echo "Test URL: " . (isset($_SERVER['HTTP_HOST']) ? 'https://' . $_SERVER['HTTP_HOST'] : 'https://geraisepatu.xyz') . "/storage/categories/test.txt\n";
?>