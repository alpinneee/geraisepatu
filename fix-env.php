<?php
// Fix .env path issue

echo "Fixing .env path issue...\n";

// Get current directory
$currentDir = __DIR__;
echo "Current directory: $currentDir\n";

// Check if .env exists in current directory
if (file_exists($currentDir . '/.env')) {
    echo "✅ .env found in current directory\n";
    
    // Read .env content
    $envContent = file_get_contents($currentDir . '/.env');
    
    // Check if APP_KEY exists
    if (strpos($envContent, 'APP_KEY=') === false || strpos($envContent, 'APP_KEY=base64:') === false) {
        echo "Generating APP_KEY...\n";
        
        // Generate a simple base64 key
        $key = base64_encode(random_bytes(32));
        
        // Add or replace APP_KEY
        if (strpos($envContent, 'APP_KEY=') !== false) {
            $envContent = preg_replace('/APP_KEY=.*/', 'APP_KEY=base64:' . $key, $envContent);
        } else {
            $envContent = "APP_KEY=base64:$key\n" . $envContent;
        }
        
        // Write back to .env
        file_put_contents($currentDir . '/.env', $envContent);
        echo "✅ APP_KEY generated and saved\n";
    } else {
        echo "✅ APP_KEY already exists\n";
    }
    
} else {
    echo "❌ .env not found, creating from .env.example\n";
    
    if (file_exists($currentDir . '/.env.example')) {
        copy($currentDir . '/.env.example', $currentDir . '/.env');
        echo "✅ .env created from .env.example\n";
    } else {
        // Create basic .env
        $basicEnv = 'APP_NAME="Gerai Sepatu"
APP_ENV=production
APP_KEY=base64:' . base64_encode(random_bytes(32)) . '
APP_DEBUG=false
APP_URL=https://geraisepatu.xyz

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=fouwy_geraisepatu
DB_USERNAME=fouwy_admin
DB_PASSWORD=

SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync
';
        file_put_contents($currentDir . '/.env', $basicEnv);
        echo "✅ Basic .env created\n";
    }
}

echo "✅ .env setup completed!\n";
?>