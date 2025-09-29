#!/bin/bash

# Build assets
npm install
npm run build

# Install PHP dependencies
composer install --no-dev --optimize-autoloader

# Clear and cache configuration
php artisan config:clear
php artisan config:cache
# Skip route caching due to potential conflicts - can be enabled after resolving route naming issues
# php artisan route:cache
php artisan view:cache

# Generate application key if not set
php artisan key:generate --force

# Run migrations
php artisan migrate --force

# Create symbolic link for storage
php artisan storage:link