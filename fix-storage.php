<?php
// Quick fix for storage symlink on production
echo "Fixing storage symlink...\n";

// Remove existing storage link if it exists
$publicStoragePath = __DIR__ . '/public/storage';
if (file_exists($publicStoragePath)) {
    if (is_link($publicStoragePath)) {
        unlink($publicStoragePath);
    } else {
        exec('rm -rf ' . $publicStoragePath);
    }
}

// Create new symlink
$storagePath = __DIR__ . '/storage/app/public';
if (symlink($storagePath, $publicStoragePath)) {
    echo "✅ Storage symlink created successfully!\n";
} else {
    echo "❌ Failed to create storage symlink\n";
}

// Verify
if (is_link($publicStoragePath) && file_exists($publicStoragePath)) {
    echo "✅ Storage symlink is working\n";
} else {
    echo "❌ Storage symlink verification failed\n";
}
?>