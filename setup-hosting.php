<?php
// Setup script untuk hosting
echo "🚀 Starting Laravel setup...\n";

// 1. Copy .env file
if (!file_exists('.env') && file_exists('.env.production')) {
    copy('.env.production', '.env');
    echo "✅ .env file created\n";
}

// 2. Generate app key if not exists
$env = file_get_contents('.env');
if (strpos($env, 'APP_KEY=') === false || strpos($env, 'APP_KEY=base64:') === false) {
    exec('php artisan key:generate --force 2>&1', $output);
    echo "✅ App key generated\n";
}

// 3. Create storage link
if (!file_exists('public/storage')) {
    exec('php artisan storage:link 2>&1', $output);
    echo "✅ Storage link created\n";
}

// 4. Set permissions
exec('chmod -R 755 storage bootstrap/cache 2>&1');
echo "✅ Permissions set\n";

// 5. Cache config
exec('php artisan config:cache 2>&1');
echo "✅ Config cached\n";

echo "🎉 Setup completed! Please configure your .env file with database credentials.\n";
?>