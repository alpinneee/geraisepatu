<?php
echo "<h2>Storage Check</h2>";

// Check storage link
$publicStoragePath = 'public/storage';
$storageAppPublic = 'storage/app/public';

echo "Public storage path exists: " . (is_dir($publicStoragePath) ? '✅ YES' : '❌ NO') . "<br>";
echo "Storage app public exists: " . (is_dir($storageAppPublic) ? '✅ YES' : '❌ NO') . "<br>";

// Check if it's a symlink
if (is_link($publicStoragePath)) {
    echo "Storage link target: " . readlink($publicStoragePath) . "<br>";
} else {
    echo "Storage is not a symlink<br>";
}

// Check categories folder
$categoriesPath = 'storage/app/public/categories';
echo "Categories folder exists: " . (is_dir($categoriesPath) ? '✅ YES' : '❌ NO') . "<br>";

if (is_dir($categoriesPath)) {
    $files = scandir($categoriesPath);
    echo "Files in categories: " . count($files) - 2 . "<br>";
    foreach ($files as $file) {
        if ($file != '.' && $file != '..') {
            echo "- $file<br>";
        }
    }
}

// Test image URL
$testImage = 'categories/test.jpg';
$fullUrl = asset('storage/' . $testImage);
echo "<br>Test image URL: <a href='$fullUrl' target='_blank'>$fullUrl</a><br>";

// Create storage link manually
echo "<h3>Create Storage Link</h3>";
if (!is_dir($publicStoragePath)) {
    if (symlink('../storage/app/public', $publicStoragePath)) {
        echo "✅ Storage link created successfully<br>";
    } else {
        echo "❌ Failed to create storage link<br>";
    }
} else {
    echo "Storage link already exists<br>";
}

// Create categories directory
if (!is_dir($categoriesPath)) {
    if (mkdir($categoriesPath, 0755, true)) {
        echo "✅ Categories directory created<br>";
    } else {
        echo "❌ Failed to create categories directory<br>";
    }
}
?>