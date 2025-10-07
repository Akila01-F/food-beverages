# Production Deployment Guide

## Overview
This guide covers the steps to deploy the Food & Beverage Laravel API to production with all the new authentication features.

## Current Status
✅ **Local Development**: All APIs implemented and tested  
🚀 **Production Deployment**: Ready for Railway deployment  
📱 **Flutter Integration**: Complete documentation provided  

---

## Pre-Deployment Checklist

### 1. Database Migrations
Ensure all database tables are up to date:

```bash
# Run migrations in production
php artisan migrate --force

# Check migration status
php artisan migrate:status
```

### 2. Required Laravel Packages
The following packages are already installed and configured:
- ✅ Laravel Sanctum (for API authentication)
- ✅ Laravel Jetstream (for web authentication)
- ✅ Laravel Fortify (for authentication features)

### 3. Environment Variables
Update your Railway environment variables to include:

```env
# Authentication Configuration
AUTH_GUARD=sanctum
SANCTUM_STATEFUL_DOMAINS=your-production-domain.com

# Session Configuration  
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_DOMAIN=.your-production-domain.com

# API Configuration
API_URL=https://your-production-domain.com/api/v1

# CORS Configuration
APP_URL=https://your-production-domain.com
```

---

## Railway Deployment Steps

### 1. Current Railway Configuration
The project is already configured for Railway with:
- ✅ `railway.json` - Deployment configuration
- ✅ `nixpacks.toml` - Build configuration with PHP extensions
- ✅ PostgreSQL database connected
- ✅ MongoDB database connected (if needed)

### 2. Deploy to Production
The deployment should work automatically via Railway's GitHub integration. If manual deployment is needed:

```bash
# Using Railway CLI
railway login
railway link [project-id]
railway up
```

### 3. Post-Deployment Tasks

#### A. Run Database Migrations
```bash
# Via Railway CLI
railway run php artisan migrate --force

# Or via Railway dashboard (Run Command)
php artisan migrate --force
```

#### B. Clear Caches
```bash
# Clear all caches
railway run php artisan config:clear
railway run php artisan cache:clear
railway run php artisan route:clear
railway run php artisan view:clear

# Optimize for production
railway run php artisan config:cache
railway run php artisan route:cache
```

#### C. Create Storage Link
```bash
# Create symbolic link for public storage access
railway run php artisan storage:link
```

---

## API Endpoints Summary

### Production Base URL
```
https://food-beverages-production.up.railway.app/api/v1
```

### Authentication Endpoints
```
POST /auth/register          - Register new user
POST /auth/login            - Login with email
POST /auth/login-username   - Login with username
GET  /auth/profile          - Get user profile (protected)
PUT  /auth/profile          - Update profile (protected)
POST /auth/change-password  - Change password (protected)
POST /auth/logout           - Logout current device (protected)
POST /auth/logout-all       - Logout all devices (protected)
POST /auth/refresh-token    - Refresh token (protected)
```

### Product & Category Endpoints (Public)
```
GET  /categories                     - Get all categories
GET  /categories/{id}                - Get category by ID
GET  /products                       - Get products (with filtering)
GET  /products/featured              - Get featured products
GET  /products/search                - Search products
GET  /products/{id}                  - Get product by ID
GET  /categories/{id}/products       - Get products by category
```

---

## Security Configuration

### 1. CORS Settings
The API is configured to accept cross-origin requests from Flutter apps. The `bootstrap/app.php` includes:

```php
// CORS middleware for API routes
$middleware->api(prepend: [
    \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
]);
```

### 2. Authentication Guards
Updated `config/auth.php` includes Sanctum guard:

```php
'guards' => [
    'web' => [
        'driver' => 'session',
        'provider' => 'users',
    ],
    'sanctum' => [
        'driver' => 'sanctum',
        'provider' => 'users',
    ],
],
```

### 3. Token Security
- Tokens expire after 30 days
- Old tokens are revoked on new login
- Password changes revoke all tokens
- Secure token storage recommended for Flutter

---

## Testing Production API

### 1. Health Check
```bash
# Test basic connectivity
curl -I https://food-beverages-production.up.railway.app/api/v1/categories

# Should return: HTTP/2 200
```

### 2. Authentication Flow Test
```bash
# 1. Register user
curl -X POST "https://food-beverages-production.up.railway.app/api/v1/auth/register" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Test User",
    "username": "testuser",
    "email": "test@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'

# 2. Login user
curl -X POST "https://food-beverages-production.up.railway.app/api/v1/auth/login" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{
    "email": "test@example.com",
    "password": "password123"
  }'

# 3. Access protected endpoint (use token from login response)
curl -X GET "https://food-beverages-production.up.railway.app/api/v1/auth/profile" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer {your-token-here}"
```

### 3. Product API Test
```bash
# Get categories
curl "https://food-beverages-production.up.railway.app/api/v1/categories"

# Get products with pagination
curl "https://food-beverages-production.up.railway.app/api/v1/products?per_page=5"

# Get featured products
curl "https://food-beverages-production.up.railway.app/api/v1/products/featured?limit=3"
```

---

## Flutter Integration Checklist

### 1. Update Base URL
Update your Flutter app's base URL to point to production:

```dart
class ApiConfig {
  static const String baseUrl = 'https://food-beverages-production.up.railway.app/api/v1';
  static const String imageBaseUrl = 'https://food-beverages-production.up.railway.app/storage';
}
```

### 2. Authentication Implementation
- ✅ Use provided `AuthService` class
- ✅ Implement secure token storage
- ✅ Add error handling for all auth flows
- ✅ Implement automatic token refresh

### 3. Product Integration
- ✅ Update image URL generation
- ✅ Test pagination with production data
- ✅ Implement search functionality
- ✅ Add category filtering

### 4. Error Handling
- ✅ Handle network timeouts
- ✅ Show user-friendly error messages
- ✅ Implement retry logic for failed requests
- ✅ Log errors for debugging

---

## Monitoring & Maintenance

### 1. Production Monitoring
- Monitor Railway dashboard for performance metrics
- Check error logs regularly
- Monitor database usage and performance
- Set up alerts for downtime or errors

### 2. Database Backups
Railway automatically handles database backups, but you can also:
```bash
# Manual backup (already created local backups)
# PostgreSQL backup: ~/Downloads/food_beverage_db_backup_*.sql
# MongoDB backup: ~/Downloads/mongodb_backup_*.tar.gz
```

### 3. Scaling Considerations
- Monitor API response times
- Consider implementing caching for frequently accessed data
- Add rate limiting if needed
- Monitor concurrent user capacity

---

## Troubleshooting

### Common Issues

#### 1. CORS Errors
If Flutter app can't access API due to CORS:
- Verify CORS middleware is properly configured
- Check that APP_URL environment variable is correct
- Ensure proper headers are sent from Flutter

#### 2. Authentication Errors
If token authentication fails:
- Check that Sanctum middleware is properly configured
- Verify token format in Authorization header
- Ensure user model has HasApiTokens trait

#### 3. Image Access Issues
If product images don't load:
- Verify storage link is created: `php artisan storage:link`
- Check file permissions in storage directory
- Ensure images use correct base URL

#### 4. Database Connection Issues
If API returns database errors:
- Check PostgreSQL connection in Railway dashboard
- Verify environment variables are correct
- Run migrations if needed

---

## Production URLs Summary

```
Main Application: https://food-beverages-production.up.railway.app
API Base URL: https://food-beverages-production.up.railway.app/api/v1
Image Base URL: https://food-beverages-production.up.railway.app/storage
Admin Panel: https://food-beverages-production.up.railway.app/admin
```

---

## Next Steps

### For Backend Team:
1. ✅ Deploy to Railway (if not already done)
2. ✅ Run post-deployment tasks
3. ✅ Test all API endpoints in production
4. ✅ Provide Flutter team with production URLs
5. ✅ Monitor initial production usage

### For Flutter Team:
1. ✅ Review complete documentation
2. ✅ Update app configuration for production
3. ✅ Implement authentication using provided examples
4. ✅ Test integration with production API
5. ✅ Implement error handling and user feedback

## Status: Ready for Production 🚀

The API is fully implemented, tested, and ready for Flutter integration. All authentication and product endpoints are working and documented.

**Last Updated**: October 7, 2025  
**API Version**: 1.0  
**Production Status**: ✅ Deployed and Ready