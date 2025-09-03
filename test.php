<?php
echo "PHP is working!<br>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Current directory: " . __DIR__ . "<br>";

if (file_exists('.env')) {
    echo ".env file exists<br>";
} else {
    echo ".env file missing<br>";
}

if (file_exists('vendor/autoload.php')) {
    echo "Composer installed<br>";
} else {
    echo "Composer NOT installed<br>";
}

phpinfo();
?>