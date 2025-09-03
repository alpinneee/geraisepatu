<?php
echo "=== FIXING PRODUCTION STORAGE ===\n";

// 1. Remove existing storage link
$publicStorage = __DIR__ . '/public/storage';
if (file_exists($publicStorage)) {
    if (is_link($publicStorage)) {
        unlink($publicStorage);
        echo "✅ Removed existing symlink\n";
    } else {
        exec("rm -rf {$publicStorage}");
        echo "✅ Removed existing directory\n";
    }
}

// 2. Create new symlink
$storagePath = __DIR__ . '/storage/app/public';
if (symlink($storagePath, $publicStorage)) {
    echo "✅ Created new symlink\n";
} else {
    echo "❌ Failed to create symlink\n";
}

// 3. Set permissions
exec("chmod -R 755 {$storagePath}");
exec("chmod -R 755 {$publicStorage}");
echo "✅ Set permissions\n";

// 4. Test access
if (is_link($publicStorage) && file_exists($publicStorage)) {
    echo "✅ Symlink working\n";
    
    // List categories folder
    $categoriesPath = $publicStorage . '/categories';
    if (file_exists($categoriesPath)) {
        $files = scandir($categoriesPath);
        echo "Categories files: " . count($files) - 2 . "\n";
        foreach ($files as $file) {
            if ($file != '.' && $file != '..') {
                echo "- {$file}\n";
            }
        }
    }
} else {
    echo "❌ Symlink not working\n";
}

echo "\n=== DONE ===\n";
?>