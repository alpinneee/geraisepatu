<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Content-Type: text/html\n\n";
echo "<h2>HTTPS Fix Test</h2>";

echo "<h3>Environment Info:</h3>";
echo "<p>APP_ENV: " . env('APP_ENV') . "</p>";
echo "<p>APP_URL: " . env('APP_URL') . "</p>";
echo "<p>Is Production: " . (app()->environment('production') ? 'YES' : 'NO') . "</p>";
echo "<p>Request Secure: " . (request()->isSecure() ? 'YES' : 'NO') . "</p>";

echo "<h3>URL Generation Test:</h3>";
$testAsset = asset('storage/test.jpg');
echo "<p>asset() result: {$testAsset}</p>";

$category = \App\Models\Category::whereNotNull('image')->first();
if ($category) {
    echo "<h3>Category Image Test:</h3>";
    echo "<p><strong>Category:</strong> {$category->name}</p>";
    echo "<p><strong>Image field:</strong> {$category->image}</p>";
    echo "<p><strong>Image URL (new):</strong> {$category->image_url}</p>";
    
    echo "<h3>Image Preview:</h3>";
    echo "<img src='{$category->image_url}' alt='{$category->name}' style='width: 150px; height: 150px; object-fit: cover; border: 2px solid blue;'>";
    
    echo "<h3>Direct Link Test:</h3>";
    echo "<a href='{$category->image_url}' target='_blank'>Open image in new tab</a>";
}
?>