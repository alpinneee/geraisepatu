<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== FIXING CATEGORY IMAGES ===\n\n";

$categories = \App\Models\Category::whereNotNull('image')->get();

echo "Found " . $categories->count() . " categories with images\n\n";

foreach ($categories as $category) {
    echo "Category: {$category->name}\n";
    echo "Current image field: {$category->image}\n";
    
    // Check if image field already has correct format
    if (strpos($category->image, 'categories/') === 0) {
        echo "✅ Image path is correct\n";
        
        // Check if file exists
        $fullPath = storage_path('app/public/' . $category->image);
        if (file_exists($fullPath)) {
            echo "✅ File exists: {$fullPath}\n";
        } else {
            echo "❌ File missing: {$fullPath}\n";
        }
        
        // Check public access
        $publicPath = public_path('storage/' . $category->image);
        if (file_exists($publicPath)) {
            echo "✅ Public accessible\n";
        } else {
            echo "❌ Not public accessible\n";
        }
        
        echo "Image URL: {$category->image_url}\n";
        
    } else {
        echo "⚠️ Image path needs fixing\n";
        
        // Try to fix the path
        $newPath = 'categories/' . basename($category->image);
        $fullPath = storage_path('app/public/' . $newPath);
        
        if (file_exists($fullPath)) {
            echo "✅ Found file at: {$fullPath}\n";
            echo "Updating database...\n";
            
            $category->image = $newPath;
            $category->save();
            
            echo "✅ Updated image path to: {$newPath}\n";
        } else {
            echo "❌ File not found at: {$fullPath}\n";
        }
    }
    
    echo "---\n";
}

echo "\n=== TESTING STORAGE SYMLINK ===\n";
$publicStorage = public_path('storage');
echo "Public storage exists: " . (file_exists($publicStorage) ? '✅' : '❌') . "\n";
echo "Is symlink: " . (is_link($publicStorage) ? '✅' : '❌') . "\n";

if (is_link($publicStorage)) {
    echo "Symlink target: " . readlink($publicStorage) . "\n";
}

echo "\n=== DONE ===\n";
?>