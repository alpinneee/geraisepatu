<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Check if Laravel is properly set up
if (!file_exists(__DIR__.'/vendor/autoload.php')) {
    die('Composer dependencies not installed. Run: composer install');
}

if (!file_exists(__DIR__.'/.env')) {
    die('.env file not found. Please create .env file.');
}

// Register the Composer autoloader...
require __DIR__.'/vendor/autoload.php';

// Bootstrap Laravel and handle the request...
try {
    $app = require_once __DIR__.'/bootstrap/app.php';
    $app->handleRequest(Request::capture());
} catch (Exception $e) {
    echo '<h1>Laravel Setup Error</h1>';
    echo '<p>Error: ' . $e->getMessage() . '</p>';
    echo '<p><a href="fix-env.php">Fix Environment</a></p>';
    echo '<p><a href="check.php">Check Status</a></p>';
}