<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Set the correct base path
$basePath = __DIR__;

// Check if Laravel is properly set up
if (!file_exists($basePath.'/vendor/autoload.php')) {
    die('Composer dependencies not installed. Run: composer install');
}

// Force set .env path
if (file_exists($basePath.'/.env')) {
    $_ENV['APP_ENV_PATH'] = $basePath.'/.env';
    putenv('APP_ENV_PATH='.$basePath.'/.env');
} else {
    die('.env file not found at: ' . $basePath . '/.env');
}

// Register the Composer autoloader...
require $basePath.'/vendor/autoload.php';

// Bootstrap Laravel and handle the request...
try {
    $app = require_once $basePath.'/bootstrap/app.php';
    
    // Set base path explicitly
    $app->useBasePath($basePath);
    
    $app->handleRequest(Request::capture());
} catch (Exception $e) {
    echo '<h1>Laravel Setup Error</h1>';
    echo '<p>Error: ' . $e->getMessage() . '</p>';
    echo '<p>Base Path: ' . $basePath . '</p>';
    echo '<p>.env exists: ' . (file_exists($basePath.'/.env') ? 'Yes' : 'No') . '</p>';
    echo '<p><a href="create-env.php">Create .env</a></p>';
    echo '<p><a href="check.php">Check Status</a></p>';
}