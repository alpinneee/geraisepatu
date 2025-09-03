#!/bin/bash

# E-Commerce Deployment Script
echo "Starting deployment..."

# Create storage symlink
echo "Creating storage symlink..."
php artisan storage:link

# Clear caches
echo "Clearing caches..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Optimize for production
echo "Optimizing for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Deployment completed!"