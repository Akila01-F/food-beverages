# Complete Guide to Hosting Laravel Food & Beverage Application on Microsoft Azure

This comprehensive guide will walk you through hosting your Laravel Food & Beverage application on Microsoft Azure using Azure App Service, with additional Azure services for database, storage, and monitoring.

## Table of Contents
1. [Prerequisites](#prerequisites)
2. [Azure Services Overview](#azure-services-overview)
3. [Step 1: Azure Account Setup](#step-1-azure-account-setup)
4. [Step 2: Create Resource Group](#step-2-create-resource-group)
5. [Step 3: Database Setup (Azure Database for MySQL)](#step-3-database-setup-azure-database-for-mysql)
6. [Step 4: Storage Setup (Azure Blob Storage)](#step-4-storage-setup-azure-blob-storage)
7. [Step 5: Create Azure App Service](#step-5-create-azure-app-service)
8. [Step 6: Configure Application Settings](#step-6-configure-application-settings)
9. [Step 7: Deploy Application Code](#step-7-deploy-application-code)
10. [Step 8: Configure Custom Domain (Optional)](#step-8-configure-custom-domain-optional)
11. [Step 9: SSL Certificate Setup](#step-9-ssl-certificate-setup)
12. [Step 10: Performance Monitoring](#step-10-performance-monitoring)
13. [Step 11: Backup and Disaster Recovery](#step-11-backup-and-disaster-recovery)
14. [Troubleshooting](#troubleshooting)
15. [Cost Optimization](#cost-optimization)
16. [Security Best Practices](#security-best-practices)

## Prerequisites

Before starting, ensure you have:

- **Azure Account**: Active Microsoft Azure subscription
- **Local Development Environment**: 
  - PHP 8.2 or higher
  - Composer
  - Node.js and npm
  - Git
- **Application Requirements**:
  - Laravel 12.x
  - MongoDB (via mongodb/laravel-mongodb)
  - Livewire 3.6+
  - Jetstream 5.3+
  - SQLite (for development) or MySQL (for production)
- **Domain Name**: (Optional) for custom domain setup

## Azure Services Overview

Your application will use the following Azure services:

1. **Azure App Service**: Web application hosting
2. **Azure Database for PostgreSQL**: Production database
3. **Azure Cosmos DB**: MongoDB-compatible NoSQL database
4. **Azure Blob Storage**: File and media storage
5. **Azure Key Vault**: Secure configuration management
6. **Azure Application Insights**: Performance monitoring
7. **Azure CDN**: Content delivery network

## Step 1: Azure Account Setup

### 1.1 Create Azure Account
1. Visit [https://azure.microsoft.com](https://azure.microsoft.com)
2. Click "Start free" or "Sign in" if you have an account
3. Complete the registration process
4. Verify your account with a credit card (required even for free tier)

### 1.2 Install Azure CLI (Optional but Recommended)
```bash
# On macOS (using Homebrew)
brew install azure-cli

# Verify installation
az --version

# Login to Azure
az login
```

### 1.3 Install Azure Tools for VS Code (Optional)
1. Open VS Code
2. Install the "Azure App Service" extension
3. Install the "Azure Databases" extension

## Step 2: Create Resource Group

### 2.1 Using Azure Portal
1. Log into [Azure Portal](https://portal.azure.com)
2. Click "Resource groups" in the left sidebar
3. Click "+ Create"
4. Fill in the details:
   - **Subscription**: Select your subscription
   - **Resource group name**: `food-beverage-rg`
   - **Region**: Choose closest to your users (e.g., East US, West Europe)
5. Click "Review + create" → "Create"

### 2.2 Using Azure CLI
```bash
# Create resource group
az group create --name food-beverage-rg --location eastus
```

## Step 3: Database Setup (Azure Database for PostgreSQL)

### 3.1 Create PostgreSQL Database Server

#### Using Azure Portal:
1. In Azure Portal, click "Create a resource"
2. Search for "Azure Database for PostgreSQL"
3. Select "Azure Database for PostgreSQL flexible server"
4. Click "Create"
5. Configure the server:
   - **Resource group**: `food-beverage-rg`
   - **Server name**: `food-beverage-postgres-server` (must be globally unique)
   - **Region**: Same as resource group
   - **PostgreSQL version**: 15
   - **Workload type**: Development (for cost optimization) or Production
   - **Compute + storage**: 
     - Compute tier: Burstable
     - Compute size: B1ms (1 vCore, 2 GB RAM) for development
     - Storage: 32 GB (auto-grow enabled)
   - **Admin username**: `foodadmin`
   - **Password**: Create a strong password (save it securely)

#### Using Azure CLI:
```bash
# Create PostgreSQL server
az postgres flexible-server create \
  --resource-group food-beverage-rg \
  --name food-beverage-postgres-server \
  --location eastus \
  --admin-user foodadmin \
  --admin-password 'YourSecurePassword123!' \
  --sku-name Standard_B1ms \
  --tier Burstable \
  --storage-size 32 \
  --version 15
```

### 3.2 Configure Firewall Rules
1. Go to your PostgreSQL server in Azure Portal
2. Click "Networking" in the left sidebar
3. Under "Firewall rules":
   - Add rule: "Allow Azure Services" (0.0.0.0 to 0.0.0.0)
   - Add your IP for management access
   - Later, add your App Service's outbound IP addresses

### 3.3 Create Database
```bash
# Create database
az postgres flexible-server db create \
  --resource-group food-beverage-rg \
  --server-name food-beverage-postgres-server \
  --database-name food_beverage_db
```

### 3.4 Setup MongoDB (Azure Cosmos DB)

Since your app uses MongoDB, create Azure Cosmos DB:

1. In Azure Portal, click "Create a resource"
2. Search for "Azure Cosmos DB"
3. Select "Azure Cosmos DB for MongoDB"
4. Configure:
   - **Resource group**: `food-beverage-rg`
   - **Account name**: `food-beverage-cosmos`
   - **API**: MongoDB
   - **Location**: Same region
   - **Capacity mode**: Provisioned throughput (for predictable costs)
   - **Apply Free Tier Discount**: Yes (if available)

## Step 4: Storage Setup (Azure Blob Storage)

### 4.1 Create Storage Account
1. In Azure Portal, click "Create a resource"
2. Search for "Storage account"
3. Configure:
   - **Resource group**: `food-beverage-rg`
   - **Storage account name**: `foodbeveragestorage` (must be unique)
   - **Region**: Same region
   - **Performance**: Standard
   - **Redundancy**: LRS (Locally-redundant storage) for cost optimization
4. Click "Review + create" → "Create"

### 4.2 Create Blob Container
1. Go to your storage account
2. Click "Containers" in the left sidebar
3. Click "+ Container"
4. Name: `uploads`
5. Public access level: Blob (allow public read access to blobs)

### 4.3 Get Access Keys
1. In your storage account, click "Access keys"
2. Copy the "Connection string" for key1 (save for later configuration)

## Step 5: Create Azure App Service

### 5.1 Create App Service Plan
1. In Azure Portal, click "Create a resource"
2. Search for "App Service Plan"
3. Configure:
   - **Resource group**: `food-beverage-rg`
   - **Name**: `food-beverage-plan`
   - **Operating System**: Linux
   - **Region**: Same region
   - **Pricing tier**: 
     - Development: F1 (Free) or B1 (Basic)
     - Production: S1 (Standard) or higher

### 5.2 Create Web App
1. In Azure Portal, click "Create a resource"
2. Search for "Web App"
3. Configure:
   - **Resource group**: `food-beverage-rg`
   - **Name**: `food-beverage-app` (this will be your URL: food-beverage-app.azurewebsites.net)
   - **Publish**: Code
   - **Runtime stack**: PHP 8.2
   - **Operating System**: Linux
   - **Region**: Same region
   - **App Service Plan**: Select the plan created above
4. Click "Review + create" → "Create"

## Step 6: Configure Application Settings

### 6.1 Set Environment Variables
1. Go to your App Service in Azure Portal
2. Click "Configuration" in the left sidebar
3. Under "Application settings", add the following:

```bash
# Application Settings
APP_NAME=FoodBeverage
APP_ENV=production
APP_KEY=base64:GENERATE_THIS_LATER
APP_DEBUG=false
APP_URL=https://food-beverage-app.azurewebsites.net

# Database Settings (PostgreSQL)
DB_CONNECTION=pgsql
DB_HOST=food-beverage-postgres-server.postgres.database.azure.com
DB_PORT=5432
DB_DATABASE=food_beverage_db
DB_USERNAME=foodadmin
DB_PASSWORD=YourSecurePassword123!

# MongoDB Settings
MONGO_DB_HOST=food-beverage-cosmos.mongo.cosmos.azure.com
MONGO_DB_PORT=10255
MONGO_DB_DATABASE=food_beverage_mongo
MONGO_DB_USERNAME=food-beverage-cosmos
MONGO_DB_PASSWORD=PRIMARY_CONNECTION_STRING_KEY
MONGO_DB_AUTHENTICATION_DATABASE=admin

# File Storage
FILESYSTEM_DISK=azure
AZURE_STORAGE_NAME=foodbeveragestorage
AZURE_STORAGE_KEY=YOUR_STORAGE_KEY
AZURE_STORAGE_CONTAINER=uploads

# Session & Cache
SESSION_DRIVER=database
CACHE_DRIVER=file
QUEUE_CONNECTION=database

# Mail Settings (Configure based on your mail service)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls

# Laravel Specific
LOG_CHANNEL=errorlog
LOG_LEVEL=error
BCRYPT_ROUNDS=12
```

### 6.2 Configure Connection Strings
1. In the same "Configuration" page
2. Click "Connection strings" tab
3. Add PostgreSQL connection:
   - **Name**: `DefaultConnection`
   - **Value**: `Server=food-beverage-postgres-server.postgres.database.azure.com;Database=food_beverage_db;Port=5432;User Id=foodadmin;Password=YourSecurePassword123!;Ssl Mode=Require;`
   - **Type**: PostgreSQL

## Step 7: Deploy Application Code

### 7.1 Prepare Your Application

#### 7.1.1 Install Azure Blob Storage Package
```bash
# In your local project directory
composer require league/flysystem-azure-blob-storage
```

#### 7.1.2 Update Configuration Files

**config/filesystems.php** - Add Azure disk configuration:
```php
'disks' => [
    // ... existing disks

    'azure' => [
        'driver' => 'azure',
        'name' => env('AZURE_STORAGE_NAME'),
        'key' => env('AZURE_STORAGE_KEY'),
        'container' => env('AZURE_STORAGE_CONTAINER'),
        'url' => env('AZURE_STORAGE_URL'),
        'prefix' => env('AZURE_STORAGE_PREFIX', ''),
    ],
],
```

**config/database.php** - Ensure MongoDB configuration:
```php
'mongodb' => [
    'driver' => 'mongodb',
    'host' => env('MONGO_DB_HOST', '127.0.0.1'),
    'port' => env('MONGO_DB_PORT', 27017),
    'database' => env('MONGO_DB_DATABASE', 'food_beverage_mongo'),
    'username' => env('MONGO_DB_USERNAME'),
    'password' => env('MONGO_DB_PASSWORD'),
    'options' => [
        'authSource' => env('MONGO_DB_AUTHENTICATION_DATABASE', 'admin'),
        'ssl' => true,
        'retryWrites' => false,
    ],
],
```

#### 7.1.3 Create Deployment Script
Create `deploy.sh` in your project root:
```bash
#!/bin/bash

# Build assets
npm install
npm run build

# Install PHP dependencies
composer install --no-dev --optimize-autoloader

# Clear and cache configuration
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Generate application key if not set
php artisan key:generate --force

# Run migrations
php artisan migrate --force

# Create symbolic link for storage
php artisan storage:link
```

### 7.2 Deploy Using Git (Recommended)

#### 7.2.1 Enable Git Deployment
1. In your App Service, go to "Deployment Center"
2. Select "GitHub" (or "Local Git" for manual deployment)
3. Authorize Azure to access your GitHub account
4. Select your repository and branch
5. Azure will automatically detect it's a PHP app

#### 7.2.2 Configure Build Process
Create `.deployment` file in your project root:
```ini
[config]
command = bash deploy.sh
```

Create `web.config` in your public directory:
```xml
<?xml version="1.0" encoding="UTF-8"?>
<configuration>
  <system.webServer>
    <rewrite>
      <rules>
        <rule name="Imported Rule 1" stopProcessing="true">
          <match url="^(.*)/$" ignoreCase="false" />
          <conditions>
            <add input="{REQUEST_FILENAME}" matchType="IsDirectory" ignoreCase="false" negate="true" />
          </conditions>
          <action type="Redirect" redirectType="Permanent" url="/{R:1}" />
        </rule>
        <rule name="Imported Rule 2" stopProcessing="true">
          <match url="^" ignoreCase="false" />
          <conditions>
            <add input="{REQUEST_FILENAME}" matchType="IsDirectory" ignoreCase="false" negate="true" />
            <add input="{REQUEST_FILENAME}" matchType="IsFile" ignoreCase="false" negate="true" />
          </conditions>
          <action type="Rewrite" url="index.php" />
        </rule>
      </rules>
    </rewrite>
  </system.webServer>
</configuration>
```

### 7.3 Deploy Using FTP (Alternative)
1. In App Service, go to "Deployment Center"
2. Select "FTP"
3. Copy FTP credentials
4. Upload your project files to `/site/wwwroot`
5. Ensure the document root points to `/site/wwwroot/public`

### 7.4 Configure Document Root
1. In App Service, go to "Configuration"
2. Under "Path mappings"
3. Set Virtual path: `/` to Physical path: `/site/wwwroot/public`

## Step 8: Configure Custom Domain (Optional)

### 8.1 Add Custom Domain
1. In your App Service, click "Custom domains"
2. Click "Add custom domain"
3. Enter your domain name (e.g., `www.yourfoodbeverage.com`)
4. Validate domain ownership by adding DNS records

### 8.2 DNS Configuration
Add these DNS records to your domain provider:
```
Type: CNAME
Name: www
Value: food-beverage-app.azurewebsites.net

Type: A
Name: @
Value: [App Service IP Address from Azure]
```

## Step 9: SSL Certificate Setup

### 9.1 Free SSL Certificate (Recommended)
1. In your App Service, go to "TLS/SSL settings"
2. Click "Private Key Certificates (.pfx)"
3. Click "Create App Service Managed Certificate"
4. Select your custom domain
5. Click "Create"

### 9.2 Bind SSL Certificate
1. Go to "TLS/SSL settings"
2. Click "Bindings" tab
3. Click "Add TLS/SSL Binding"
4. Select your domain and certificate
5. TLS/SSL Type: SNI SSL

## Step 10: Performance Monitoring

### 10.1 Enable Application Insights
1. In your App Service, go to "Application Insights"
2. Click "Turn on Application Insights"
3. Create new resource or use existing
4. Click "Apply"

### 10.2 Configure Monitoring
1. Add to your environment variables:
```bash
APPINSIGHTS_INSTRUMENTATIONKEY=your-instrumentation-key
APPINSIGHTS_LIVEMETRICS_ENABLED=true
```

### 10.3 Set Up Alerts
1. Go to Azure Monitor
2. Create alert rules for:
   - High CPU usage
   - Memory consumption
   - Response time
   - Error rates

## Step 11: Backup and Disaster Recovery

### 11.1 Configure App Service Backup
1. In App Service, go to "Backups"
2. Click "Configure"
3. Configure storage account for backups
4. Set backup schedule (daily recommended)

### 11.2 Database Backup
MySQL backups are automatic in Azure, but configure:
1. Go to your MySQL server
2. Click "Backup and Restore"
3. Configure backup retention period
4. Test restore procedure

## Step 12: Security Configuration

### 12.1 Configure App Service Authentication
1. In App Service, go to "Authentication"
2. Click "Add identity provider"
3. Configure providers (Azure AD, Google, Facebook, etc.)

### 12.2 IP Restrictions
1. Go to "Networking"
2. Click "Access Restrictions"
3. Add rules to restrict access by IP if needed

### 12.3 Always Use HTTPS
1. In "TLS/SSL settings"
2. Set "HTTPS Only" to "On"
3. Set "Minimum TLS Version" to "1.2"

## Step 13: Testing and Validation

### 13.1 Test Application
1. Visit your application URL
2. Test all major features:
   - User registration/login
   - Product catalog browsing
   - Shopping cart functionality
   - Order placement
   - Admin dashboard
   - File uploads

### 13.2 Performance Testing
```bash
# Use Apache Bench for basic load testing
ab -n 100 -c 10 https://your-app.azurewebsites.net/

# Or use Azure Load Testing service for comprehensive testing
```

### 13.3 Database Testing
1. Connect to PostgreSQL using Azure Data Studio, pgAdmin, or psql
2. Verify tables are created correctly
3. Test sample data insertion
4. Test MongoDB connection to Cosmos DB

## Troubleshooting

### Common Issues and Solutions

#### 1. Application Key Not Set
**Error**: "No application encryption key has been specified"
**Solution**: 
1. Generate key locally: `php artisan key:generate --show`
2. Add to App Settings: `APP_KEY=base64:YOUR_GENERATED_KEY`

#### 2. Database Connection Failed
**Error**: "SQLSTATE[08006] [7] Connection refused" or "could not connect to server"
**Solution**:
1. Check firewall rules on PostgreSQL server
2. Verify connection string format
3. Ensure SSL is configured correctly
4. Check if PostgreSQL extensions are enabled

#### 3. File Upload Issues
**Error**: Files not uploading or displaying
**Solution**:
1. Verify Azure Storage configuration
2. Check container permissions
3. Test storage connection

#### 4. Composer Dependencies
**Error**: "Class not found" errors
**Solution**:
1. Ensure all dependencies are in composer.json
2. Run `composer install --no-dev` during deployment
3. Check autoloader optimization

#### 5. Laravel Mix/Vite Assets
**Error**: Assets not loading
**Solution**:
1. Run `npm run build` during deployment
2. Verify asset paths in templates
3. Check if CDN is properly configured

## Cost Optimization

### Development Environment Costs (Monthly Estimates)
- **App Service (B1 Basic)**: ~$13.14
- **PostgreSQL (B1ms Burstable)**: ~$12.41  
- **Storage Account (LRS)**: ~$2.00
- **Cosmos DB (Free Tier)**: $0.00
- **Total**: ~$27.55/month

### Production Environment Costs (Monthly Estimates)
- **App Service (S1 Standard)**: ~$70.08
- **PostgreSQL (GP_Standard_D2s_v3)**: ~$73.00
- **Storage Account (LRS)**: ~$10.00
- **Cosmos DB (400 RU/s)**: ~$24.00
- **Application Insights**: ~$5.00
- **Total**: ~$182.08/month

### Cost Reduction Tips
1. **Use Azure Free Tier**: Up to 12 months free for eligible services
2. **Reserved Instances**: Save up to 72% with 1-3 year commitments
3. **Auto-scaling**: Scale down during low traffic periods
4. **CDN**: Reduce bandwidth costs with Azure CDN
5. **Monitoring**: Set budget alerts to track spending

## Security Best Practices

### 1. Environment Variables
- Never commit `.env` files to version control
- Use Azure Key Vault for sensitive data
- Rotate passwords regularly

### 2. Database Security
- Use strong passwords (20+ characters)
- Enable SSL/TLS connections
- Regularly update PostgreSQL version
- Implement backup encryption

### 3. Application Security
- Keep Laravel and packages updated
- Use HTTPS everywhere
- Implement CSRF protection
- Validate all user inputs
- Use parameterized queries

### 4. Network Security
- Configure proper firewall rules
- Use private endpoints when possible
- Enable DDoS protection
- Monitor for suspicious activity

## Maintenance and Updates

### Regular Tasks
1. **Weekly**:
   - Review Application Insights metrics
   - Check error logs
   - Monitor resource usage

2. **Monthly**:
   - Update dependencies (`composer update`)
   - Review and optimize database
   - Test backup restore procedures
   - Security patches

3. **Quarterly**:
   - Review and optimize costs
   - Conduct security audit
   - Performance optimization
   - Disaster recovery testing

## Support and Documentation

### Azure Resources
- [Azure Documentation](https://docs.microsoft.com/azure/)
- [Azure Support Plans](https://azure.microsoft.com/support/plans/)
- [Azure Status Page](https://status.azure.com/)

### Laravel Resources
- [Laravel Documentation](https://laravel.com/docs)
- [Laravel Azure Deployment Guide](https://laravel.com/docs/deployment#azure)

### Community Support
- [Azure Community Forum](https://docs.microsoft.com/answers/products/azure)
- [Laravel Community](https://laracasts.com/discuss)
- [Stack Overflow](https://stackoverflow.com/questions/tagged/azure+laravel)

## Conclusion

This guide provides a comprehensive approach to hosting your Laravel Food & Beverage application on Microsoft Azure. The setup includes production-ready configurations for security, performance, and scalability. 

Remember to:
1. Start with development/staging environment first
2. Test thoroughly before going to production
3. Monitor costs and optimize regularly
4. Keep security as a top priority
5. Maintain regular backups

For any issues or questions, refer to the troubleshooting section or Azure support documentation.

---

**Last Updated**: September 2025  
**Author**: GitHub Copilot  
**Version**: 1.0