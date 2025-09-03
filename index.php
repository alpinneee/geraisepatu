<?php
// Simple redirect to public folder
if (file_exists('public/index.php')) {
    header('Location: /public/');
    exit;
} else {
    echo '<h1>Laravel E-Commerce Toko Sepatu</h1>';
    echo '<p>Application is being set up...</p>';
    echo '<p><a href="test.php">Test PHP</a></p>';
    echo '<p><a href="debug.php">Debug Info</a></p>';
}