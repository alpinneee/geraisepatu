<?php
// Create .env file directly

$envContent = 'APP_NAME="Gerai Sepatu"
APP_ENV=production
APP_KEY=base64:Orygo3MLBI13yZOBI4n3quxfc3UF3nrfKH3p9LXmqx8=
APP_DEBUG=false
APP_URL=https://geraisepatu.xyz

APP_LOCALE=id
APP_FALLBACK_LOCALE=id
APP_FAKER_LOCALE=id_ID

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=fouwy_geraisepatu
DB_USERNAME=fouwy_admin
DB_PASSWORD=

SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_ENCRYPT=false

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=public
QUEUE_CONNECTION=sync

CACHE_STORE=file

MAIL_MAILER=resend
MAIL_HOST=smtp.resend.com
MAIL_PORT=587
MAIL_USERNAME=resend
MAIL_PASSWORD=re_WEStXXvh_3GVQEvXZqz2md1QVdLHKN9Vj
MAIL_FROM_ADDRESS="noreply@geraisepatu.xyz"
MAIL_FROM_NAME="${APP_NAME}"
MAIL_ENCRYPTION=tls

RESEND_API_KEY=re_WEStXXvh_3GVQEvXZqz2md1QVdLHKN9Vj

MIDTRANS_SERVER_KEY=SB-Mid-server-WIP-L3ENoCuXtYhLEFKTwX0w
MIDTRANS_CLIENT_KEY=SB-Mid-client-YizrRmrjsEHS0zFx
MIDTRANS_MERCHANT_ID=G006929734
MIDTRANS_IS_PRODUCTION=false
';

// Write .env file
$result = file_put_contents(__DIR__ . '/.env', $envContent);

if ($result !== false) {
    echo "✅ .env file created successfully!<br>";
    echo "File size: " . filesize(__DIR__ . '/.env') . " bytes<br>";
    echo "File path: " . __DIR__ . '/.env<br>';
    
    // Set permission
    chmod(__DIR__ . '/.env', 0644);
    echo "✅ Permission set to 644<br>";
    
    // Verify file exists
    if (file_exists(__DIR__ . '/.env')) {
        echo "✅ File verified to exist<br>";
        echo '<a href="/">Go to Homepage</a>';
    } else {
        echo "❌ File verification failed<br>";
    }
} else {
    echo "❌ Failed to create .env file<br>";
    echo "Current directory: " . __DIR__ . "<br>";
    echo "Is writable: " . (is_writable(__DIR__) ? 'Yes' : 'No') . "<br>";
}
?>