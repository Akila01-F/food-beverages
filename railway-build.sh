#!/bin/bash
# Railway deployment build script
# Handles MongoDB + PostgreSQL extension requirements

echo "🚀 Starting Laravel build process..."

# Show PHP version and available extensions
echo "� PHP Information:"
php -v
echo "📋 Available PHP Extensions:"
php -m | grep -E "(mongodb|pgsql|pdo)" || echo "Extensions will be loaded at runtime"

# Install PHP dependencies with platform requirement bypass
echo "📦 Installing PHP dependencies..."
composer install --ignore-platform-reqs --no-dev --optimize-autoloader --no-interaction

# Install and build frontend assets
echo "🎨 Installing Node dependencies..."
npm ci --only=production

echo "🔨 Building frontend assets..."
npm run build

# Create storage directories and set permissions
echo "📁 Setting up storage directories..."
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views  
mkdir -p storage/framework/cache
mkdir -p storage/framework/testing
mkdir -p storage/logs
mkdir -p bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Laravel optimizations (with error handling)
echo "⚡ Optimizing Laravel..."
php artisan config:cache || echo "Config cache skipped"
php artisan route:cache || echo "Route cache skipped"
php artisan view:cache || echo "View cache skipped"

echo "✅ Build complete!"