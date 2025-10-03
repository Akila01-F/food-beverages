# AWS Lightsail Hosting Guide for Laravel Food & Beverage Application

## Table of Contents
1. [Prerequisites](#prerequisites)
2. [Creating a Lightsail Instance](#creating-a-lightsail-instance)
3. [Server Setup and Configuration](#server-setup-and-configuration)
4. [Application Deployment](#application-deployment)
5. [Database Configuration](#database-configuration)
6. [Domain and SSL Setup](#domain-and-ssl-setup)
7. [Monitoring and Maintenance](#monitoring-and-maintenance)
8. [Troubleshooting](#troubleshooting)

---

## Prerequisites

Before starting, ensure you have:

- AWS Account with billing set up
- Your Laravel application source code ready
- Domain name (optional but recommended)
- Basic knowledge of Linux command line

### Application Requirements Analysis

Based on your `composer.json`, your application uses:
- **PHP 8.2+**
- **Laravel 12.0**
- **Laravel Jetstream** (Authentication & Teams)
- **Livewire** (Frontend interactivity)
- **MongoDB** (Database)
- **Tailwind CSS** (Styling)
- **SQLite** (Secondary database)

---

## Creating a Lightsail Instance

### Step 1: Launch Lightsail Instance

1. Log into [AWS Lightsail Console](https://lightsail.aws.amazon.com/)
2. Click **"Create instance"**
3. Choose instance location (closest to your users)
4. Select **"Linux/Unix"** platform
5. Choose **"Ubuntu 22.04 LTS"** blueprint

### Step 2: Choose Instance Plan

For a Laravel application with MongoDB, recommended plans:

| Plan | vCPUs | RAM | Storage | Bandwidth | Price/month |
|------|-------|-----|---------|-----------|-------------|
| **Development** | 1 | 1 GB | 40 GB | 2 TB | $5 |
| **Small Production** | 2 | 2 GB | 60 GB | 3 TB | $10 |
| **Medium Production** | 2 | 4 GB | 80 GB | 4 TB | $20 |
| **Large Production** | 2 | 8 GB | 160 GB | 5 TB | $40 |

**Recommendation**: Start with the $10 plan for small to medium applications.

### Step 3: Configure Instance

1. **Instance name**: `laravel-food-app`
2. **Key pair**: Create new or use existing
3. **Enable automatic snapshots** (recommended)
4. Click **"Create instance"**

---

## Server Setup and Configuration

### Step 1: Connect to Your Instance

```bash
# Using Lightsail browser terminal or SSH
ssh -i /path/to/your-key.pem ubuntu@YOUR_INSTANCE_IP
```

### Step 2: Update System

```bash
sudo apt update && sudo apt upgrade -y
```

### Step 3: Install Required Software

```bash
# Install PHP 8.2 and extensions
sudo apt install software-properties-common -y
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update

sudo apt install -y \
    nginx \
    php8.2 \
    php8.2-fpm \
    php8.2-mysql \
    php8.2-sqlite3 \
    php8.2-xml \
    php8.2-curl \
    php8.2-mbstring \
    php8.2-zip \
    php8.2-bcmath \
    php8.2-tokenizer \
    php8.2-json \
    php8.2-gd \
    php8.2-mongodb \
    unzip \
    git \
    curl

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
sudo chmod +x /usr/local/bin/composer

# Install Node.js and npm
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt-get install -y nodejs

# Install MongoDB
sudo apt-get install gnupg curl
curl -fsSL https://www.mongodb.org/static/pgp/server-7.0.asc | \
   sudo gpg -o /usr/share/keyrings/mongodb-server-7.0.gpg \
   --dearmor
echo "deb [ arch=amd64,arm64 signed-by=/usr/share/keyrings/mongodb-server-7.0.gpg ] https://repo.mongodb.org/apt/ubuntu jammy/mongodb-org/7.0 multiverse" | sudo tee /etc/apt/sources.list.d/mongodb-org-7.0.list
sudo apt-get update
sudo apt-get install -y mongodb-org

# Start and enable services
sudo systemctl start mongod
sudo systemctl enable mongod
sudo systemctl start nginx
sudo systemctl enable nginx
sudo systemctl start php8.2-fpm
sudo systemctl enable php8.2-fpm
```

### Step 4: Configure Nginx

Create Nginx configuration for your Laravel app:

```bash
sudo nano /etc/nginx/sites-available/laravel-food-app
```

Add the following configuration:

```nginx
server {
    listen 80;
    server_name YOUR_DOMAIN_OR_IP;
    root /var/www/laravel-food-app/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    client_max_body_size 100M;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Handle Laravel storage files
    location /storage/ {
        alias /var/www/laravel-food-app/storage/app/public/;
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}
```

Enable the site:

```bash
sudo ln -s /etc/nginx/sites-available/laravel-food-app /etc/nginx/sites-enabled/
sudo unlink /etc/nginx/sites-enabled/default
sudo nginx -t
sudo systemctl reload nginx
```

---

## Application Deployment

### Step 1: Prepare Application Directory

```bash
sudo mkdir -p /var/www/laravel-food-app
sudo chown -R ubuntu:ubuntu /var/www/laravel-food-app
cd /var/www
```

### Step 2: Deploy Application

**Option A: Git Clone (Recommended)**

```bash
# Clone your repository
git clone https://github.com/YOUR_USERNAME/YOUR_REPO.git laravel-food-app
cd laravel-food-app

# Or if you're uploading files manually:
# Upload your application files to /var/www/laravel-food-app
```

**Option B: Upload via SCP**

```bash
# From your local machine
scp -i /path/to/your-key.pem -r /path/to/your/project/* ubuntu@YOUR_INSTANCE_IP:/var/www/laravel-food-app/
```

### Step 3: Configure Application

```bash
cd /var/www/laravel-food-app

# Install PHP dependencies
composer install --no-dev --optimize-autoloader

# Install and build frontend assets
npm install
npm run build

# Set up environment
cp .env.example .env
nano .env
```

### Step 4: Environment Configuration

Edit `.env` file with production settings:

```env
APP_NAME="Food & Beverage App"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Database Configuration
DB_CONNECTION=mongodb
MONGODB_DSN=mongodb://127.0.0.1:27017/food_beverage_db

# Session Configuration
SESSION_DRIVER=database
SESSION_LIFETIME=120

# Cache Configuration
CACHE_DRIVER=file
QUEUE_CONNECTION=database

# Mail Configuration (configure based on your email service)
MAIL_MAILER=smtp
MAIL_HOST=
MAIL_PORT=587
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
```

### Step 5: Finalize Laravel Setup

```bash
# Generate application key
php artisan key:generate

# Clear and cache configuration
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan config:cache
php artisan view:cache
php artisan route:cache

# Set up storage link
php artisan storage:link

# Run migrations (if you have any)
php artisan migrate --force

# Seed database (if needed)
php artisan db:seed --force
```

### Step 6: Set Permissions

```bash
# Set proper ownership and permissions
sudo chown -R www-data:www-data /var/www/laravel-food-app/storage
sudo chown -R www-data:www-data /var/www/laravel-food-app/bootstrap/cache
sudo chmod -R 775 /var/www/laravel-food-app/storage
sudo chmod -R 775 /var/www/laravel-food-app/bootstrap/cache

# Set general permissions
sudo find /var/www/laravel-food-app -type f -exec chmod 644 {} \;
sudo find /var/www/laravel-food-app -type d -exec chmod 755 {} \;
```

---

## Database Configuration

### MongoDB Setup

```bash
# Start MongoDB shell
mongosh

# Create database and user
use food_beverage_db
db.createUser({
  user: "laravel_user",
  pwd: "your_secure_password_here",
  roles: [ { role: "readWrite", db: "food_beverage_db" } ]
});

# Exit MongoDB shell
exit
```

Update your `.env` file:

```env
MONGODB_DSN=mongodb://laravel_user:your_secure_password_here@127.0.0.1:27017/food_beverage_db
```

### Configure MongoDB for Production

```bash
sudo nano /etc/mongod.conf
```

Ensure the following settings:

```yaml
# Network interfaces
net:
  port: 27017
  bindIp: 127.0.0.1

# Security
security:
  authorization: enabled
```

Restart MongoDB:

```bash
sudo systemctl restart mongod
```

---

## Domain and SSL Setup

### Step 1: Configure Static IP (Optional but Recommended)

1. In Lightsail console, go to **Networking**
2. Click **"Create static IP"**
3. Attach it to your instance

### Step 2: Configure Domain

1. Point your domain's A record to your static IP
2. Update Nginx configuration with your domain name:

```bash
sudo nano /etc/nginx/sites-available/laravel-food-app
```

Replace `YOUR_DOMAIN_OR_IP` with your actual domain.

### Step 3: Install SSL Certificate

Use Let's Encrypt for free SSL:

```bash
# Install Certbot
sudo apt install snapd
sudo snap install --classic certbot
sudo ln -s /snap/bin/certbot /usr/bin/certbot

# Get SSL certificate
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com

# Test auto-renewal
sudo certbot renew --dry-run
```

---

## Monitoring and Maintenance

### Step 1: Set Up Logging

```bash
# Create log rotation
sudo nano /etc/logrotate.d/laravel
```

Add:

```
/var/www/laravel-food-app/storage/logs/*.log {
    daily
    missingok
    rotate 14
    compress
    notifempty
    create 644 www-data www-data
}
```

### Step 2: Create Maintenance Scripts

Create backup script:

```bash
nano /home/ubuntu/backup.sh
```

```bash
#!/bin/bash
DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/home/ubuntu/backups"
APP_DIR="/var/www/laravel-food-app"

mkdir -p $BACKUP_DIR

# Backup MongoDB
mongodump --db food_beverage_db --out $BACKUP_DIR/mongodb_$DATE

# Backup application files
tar -czf $BACKUP_DIR/app_$DATE.tar.gz -C /var/www laravel-food-app

# Remove backups older than 7 days
find $BACKUP_DIR -name "*" -mtime +7 -delete

echo "Backup completed: $DATE"
```

Make executable and set up cron:

```bash
chmod +x /home/ubuntu/backup.sh
crontab -e
```

Add daily backup at 2 AM:

```
0 2 * * * /home/ubuntu/backup.sh
```

### Step 3: Performance Optimization

```bash
# Install Redis for caching (optional)
sudo apt install redis-server
sudo systemctl enable redis-server
sudo systemctl start redis-server

# Install and configure PHP OpCache
sudo nano /etc/php/8.2/fpm/conf.d/10-opcache.ini
```

Add:

```ini
opcache.enable=1
opcache.memory_consumption=256
opcache.interned_strings_buffer=8
opcache.max_accelerated_files=4000
opcache.revalidate_freq=2
opcache.fast_shutdown=1
```

Restart PHP-FPM:

```bash
sudo systemctl restart php8.2-fpm
```

---

## Troubleshooting

### Common Issues and Solutions

#### 1. Permission Errors

```bash
# Fix Laravel storage permissions
sudo chown -R www-data:www-data /var/www/laravel-food-app/storage
sudo chown -R www-data:www-data /var/www/laravel-food-app/bootstrap/cache
sudo chmod -R 775 /var/www/laravel-food-app/storage
sudo chmod -R 775 /var/www/laravel-food-app/bootstrap/cache
```

#### 2. MongoDB Connection Issues

```bash
# Check MongoDB status
sudo systemctl status mongod

# Check MongoDB logs
sudo tail -f /var/log/mongodb/mongod.log

# Test connection
mongosh "mongodb://laravel_user:password@127.0.0.1:27017/food_beverage_db"
```

#### 3. Nginx 502 Bad Gateway

```bash
# Check PHP-FPM status
sudo systemctl status php8.2-fpm

# Check PHP-FPM logs
sudo tail -f /var/log/php8.2-fpm.log

# Restart services
sudo systemctl restart php8.2-fpm nginx
```

#### 4. Laravel Application Errors

```bash
# Check Laravel logs
sudo tail -f /var/www/laravel-food-app/storage/logs/laravel.log

# Clear caches
cd /var/www/laravel-food-app
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

### Useful Commands

```bash
# Check service status
sudo systemctl status nginx php8.2-fpm mongod

# Monitor real-time logs
sudo tail -f /var/log/nginx/error.log
sudo tail -f /var/www/laravel-food-app/storage/logs/laravel.log

# Check disk space
df -h

# Check memory usage
free -h
htop

# Monitor MongoDB
mongosh --eval "db.stats()"
```

---

## Scaling and Advanced Configuration

### Horizontal Scaling

For high-traffic applications:

1. **Load Balancer**: Use Lightsail Load Balancer
2. **Database Scaling**: Consider MongoDB Atlas
3. **CDN**: Use CloudFront for static assets
4. **Caching**: Implement Redis for session/cache storage

### Security Enhancements

```bash
# Install fail2ban
sudo apt install fail2ban

# Configure UFW firewall
sudo ufw allow ssh
sudo ufw allow 'Nginx Full'
sudo ufw --force enable

# Regular updates
echo "0 4 * * 0 apt update && apt upgrade -y" | sudo crontab -
```

### Monitoring Setup

Consider integrating:
- **CloudWatch**: For AWS native monitoring
- **New Relic**: For application performance monitoring
- **Uptime Robot**: For uptime monitoring

---

## Cost Optimization

### Monthly Cost Breakdown

- **Lightsail Instance** ($10/month): $120/year
- **Static IP** ($5/month when unattached): $0 when attached
- **Snapshots**: ~$2/month for daily backups
- **Data Transfer**: Usually within free tier limits

### Cost-Saving Tips

1. Use automatic snapshots instead of manual backups
2. Monitor bandwidth usage
3. Optimize images and assets
4. Implement proper caching strategies

---

## Deployment Automation

### Create Deployment Script

```bash
nano /home/ubuntu/deploy.sh
```

```bash
#!/bin/bash

APP_DIR="/var/www/laravel-food-app"
cd $APP_DIR

# Pull latest changes
git pull origin main

# Install/update dependencies
composer install --no-dev --optimize-autoloader
npm install
npm run build

# Clear and cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan config:cache
php artisan view:cache
php artisan route:cache

# Run migrations
php artisan migrate --force

# Set permissions
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

echo "Deployment completed successfully!"
```

```bash
chmod +x /home/ubuntu/deploy.sh
```

---

## Support and Resources

### AWS Lightsail Resources
- [Lightsail Documentation](https://docs.aws.amazon.com/lightsail/)
- [Lightsail Pricing](https://aws.amazon.com/lightsail/pricing/)

### Laravel Resources
- [Laravel Deployment Documentation](https://laravel.com/docs/deployment)
- [Laravel Performance Optimization](https://laravel.com/docs/performance)

### Emergency Contacts
- Keep your AWS account recovery information updated
- Document your application's architecture
- Maintain regular backups and test restore procedures

---

## Conclusion

This guide provides a comprehensive setup for hosting your Laravel Food & Beverage application on AWS Lightsail. The configuration supports:

- ✅ Laravel 12 with Jetstream authentication
- ✅ MongoDB database with proper security
- ✅ Livewire real-time functionality
- ✅ SSL encryption with automatic renewal
- ✅ Automated backups and monitoring
- ✅ Production-ready performance optimizations

Remember to:
1. Regularly update your system and application
2. Monitor application performance and logs
3. Test your backup and restore procedures
4. Keep your domain and SSL certificates up to date

For any issues or advanced configurations, refer to the troubleshooting section or consult the AWS Lightsail documentation.