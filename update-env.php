<?php
echo "=== UPDATING ENVIRONMENT ===\n";

$envFile = __DIR__ . '/.env';

if (!file_exists($envFile)) {
    echo "❌ .env file not found\n";
    exit;
}

$envContent = file_get_contents($envFile);

// Update APP_ENV to production
if (strpos($envContent, 'APP_ENV=') !== false) {
    $envContent = preg_replace('/APP_ENV=.*/', 'APP_ENV=production', $envContent);
    echo "✅ Updated APP_ENV to production\n";
} else {
    $envContent .= "\nAPP_ENV=production\n";
    echo "✅ Added APP_ENV=production\n";
}

// Update APP_URL to HTTPS
if (strpos($envContent, 'APP_URL=') !== false) {
    $envContent = preg_replace('/APP_URL=.*/', 'APP_URL=https://geraisepatu.xyz', $envContent);
    echo "✅ Updated APP_URL to HTTPS\n";
} else {
    $envContent .= "\nAPP_URL=https://geraisepatu.xyz\n";
    echo "✅ Added APP_URL=https://geraisepatu.xyz\n";
}

// Write back to file
if (file_put_contents($envFile, $envContent)) {
    echo "✅ .env file updated successfully\n";
} else {
    echo "❌ Failed to update .env file\n";
}

echo "\n=== CLEARING CACHE ===\n";
exec('php artisan config:clear', $output1);
exec('php artisan cache:clear', $output2);
exec('php artisan view:clear', $output3);
echo "✅ Cache cleared\n";

echo "\n=== DONE ===\n";
echo "Please test the images again.\n";
?>