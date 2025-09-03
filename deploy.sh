#!/bin/bash

echo "🚀 Starting deployment..."

# Pull latest changes
git pull origin main

# Install/update composer dependencies
composer install --no-dev --optimize-autoloader

# Install/update npm dependencies
npm ci

# Build assets
npm run build

# Run migrations
php artisan migrate --force

# Clear and cache config
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create storage link if not exists
php artisan storage:link

# Set permissions
chmod -R 755 storage
chmod -R 755 bootstrap/cache

echo "✅ Deployment completed!"