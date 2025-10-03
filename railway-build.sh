#!/bin/bash
# Railway deployment build script
# Handles MongoDB extension requirements

echo "🚀 Starting Laravel build process..."

# Install PHP dependencies with MongoDB check
echo "📦 Installing PHP dependencies..."
if ! php -m | grep -q mongodb; then
    echo "⚠️  MongoDB extension not found, trying to continue..."
    composer install --ignore-platform-req=ext-mongodb --no-dev --optimize-autoloader --no-interaction
else
    echo "✅ MongoDB extension found!"
    composer install --no-dev --optimize-autoloader --no-interaction
fi

# Install and build frontend assets
echo "🎨 Installing Node dependencies..."
npm ci --only=production

echo "🔨 Building frontend assets..."
npm run build

# Create storage directories and set permissions
echo "📁 Setting up storage directories..."
mkdir -p storage/framework/{sessions,views,cache,testing}
mkdir -p storage/logs
mkdir -p bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Laravel optimizations
echo "⚡ Optimizing Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Build complete!"