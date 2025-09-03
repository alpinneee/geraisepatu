#!/bin/bash

echo "Setting up Laravel..."

# Copy .env file
cp .env.hosting .env
echo "✅ .env file created"

# Generate app key
php artisan key:generate --force
echo "✅ App key generated"

# Create storage directories
mkdir -p storage/logs
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p bootstrap/cache

# Set permissions
chmod -R 755 storage
chmod -R 755 bootstrap/cache
echo "✅ Permissions set"

# Create storage link
php artisan storage:link
echo "✅ Storage link created"

echo "✅ Setup completed!"