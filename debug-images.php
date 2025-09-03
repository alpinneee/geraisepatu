<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== DEBUG GAMBAR KATEGORI ===\n\n";

// Check storage paths
echo "1. STORAGE PATHS:\n";
echo "Storage path: " . storage_path('app/public') . "\n";
echo "Public storage path: " . public_path('storage') . "\n";
echo "Storage exists: " . (file_exists(storage_path('app/public')) ? '✅' : '❌') . "\n";
echo "Public storage exists: " . (file_exists(public_path('storage')) ? '✅' : '❌') . "\n";
echo "Is symlink: " . (is_link(public_path('storage')) ? '✅' : '❌') . "\n\n";

// Check categories
echo "2. CATEGORIES DATA:\n";
$categories = \App\Models\Category::where('is_active', true)->get();
foreach ($categories as $category) {
    echo "Category: {$category->name}\n";
    echo "Image field: {$category->image}\n";
    echo "Image URL: {$category->image_url}\n";
    
    if ($category->image) {
        $fullPath = storage_path('app/public/' . $category->image);
        echo "Full path: {$fullPath}\n";
        echo "File exists: " . (file_exists($fullPath) ? '✅' : '❌') . "\n";
        
        $publicPath = public_path('storage/' . $category->image);
        echo "Public path: {$publicPath}\n";
        echo "Public accessible: " . (file_exists($publicPath) ? '✅' : '❌') . "\n";
    }
    echo "---\n";
}

// Check .htaccess
echo "\n3. HTACCESS CHECK:\n";
$htaccess = file_get_contents(__DIR__ . '/.htaccess');
echo "Contains storage rule: " . (strpos($htaccess, 'storage') !== false ? '✅' : '❌') . "\n";
echo "Current .htaccess:\n";
echo $htaccess . "\n";
?>