<?php
echo "=== ENVIRONMENT CHECK ===\n";
echo "APP_ENV: " . env('APP_ENV', 'not set') . "\n";
echo "APP_URL: " . env('APP_URL', 'not set') . "\n";
echo "Current environment: " . app()->environment() . "\n";
echo "Is production: " . (app()->environment('production') ? 'YES' : 'NO') . "\n";

echo "\n=== URL GENERATION TEST ===\n";
echo "asset() test: " . asset('storage/test.jpg') . "\n";
echo "url() test: " . url('storage/test.jpg') . "\n";

// Test category image URL
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$category = \App\Models\Category::whereNotNull('image')->first();
if ($category) {
    echo "Category image URL: " . $category->image_url . "\n";
}
?>