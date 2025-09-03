<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Content-Type: text/html\n\n";
echo "<h2>Category Images Test</h2>";

$categories = \App\Models\Category::where('is_active', true)->whereNotNull('image')->get();

foreach ($categories as $category) {
    echo "<div style='border: 1px solid #ccc; margin: 10px; padding: 10px;'>";
    echo "<h3>{$category->name}</h3>";
    echo "<p><strong>Image field:</strong> {$category->image}</p>";
    echo "<p><strong>Image URL:</strong> {$category->image_url}</p>";
    
    if ($category->image_url) {
        echo "<p><strong>Generated IMG tag:</strong></p>";
        echo "<img src='{$category->image_url}' alt='{$category->name}' style='width: 100px; height: 100px; object-fit: cover; border: 1px solid red;'>";
        
        // Test direct access
        $testUrl = url('storage/' . $category->image);
        echo "<p><strong>Direct storage URL:</strong> <a href='{$testUrl}' target='_blank'>{$testUrl}</a></p>";
    }
    echo "</div>";
}

echo "<hr>";
echo "<h3>Storage Test</h3>";
echo "<p>Public storage path: " . public_path('storage') . "</p>";
echo "<p>Storage exists: " . (file_exists(public_path('storage')) ? 'YES' : 'NO') . "</p>";
echo "<p>Is symlink: " . (is_link(public_path('storage')) ? 'YES' : 'NO') . "</p>";

if (is_link(public_path('storage'))) {
    echo "<p>Symlink target: " . readlink(public_path('storage')) . "</p>";
}
?>