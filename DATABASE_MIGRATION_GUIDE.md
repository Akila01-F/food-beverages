# Database Migration Guide for Railway

## 🚀 **Automatic Migration (Recommended)**

### **Method 1: Railway CLI**
```bash
# Install Railway CLI
npm install -g @railway/cli

# Login to Railway
railway login

# Link to your project (get project ID from Railway dashboard URL)
railway link

# Run migrations
railway run php artisan migrate --force

# Optional: Seed database
railway run php artisan db:seed --force
```

### **Method 2: Deploy Command**
1. Go to Railway dashboard → Your web service
2. Click "Settings" → "Deploy"
3. Set **Deploy Command**: `php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=$PORT`
4. Click "Deploy"

---

## 📋 **Tables That Will Be Created**

### **PostgreSQL Tables (Main App Data):**
- ✅ `users` - User accounts, authentication
- ✅ `teams` - Team management (Jetstream)
- ✅ `team_users` - Team membership
- ✅ `team_invitations` - Team invites
- ✅ `categories` - Product categories
- ✅ `products` - Food & beverage products  
- ✅ `orders` - Customer orders
- ✅ `order_items` - Items in each order
- ✅ `personal_access_tokens` - API tokens
- ✅ `cache` - Laravel cache storage
- ✅ `sessions` - User sessions
- ✅ `jobs` - Queue jobs
- ✅ `migrations` - Migration tracking

### **MongoDB Collections (Flexible Data):**
- ✅ `reviews` - Product reviews (auto-created)
- ✅ `activity_logs` - User activity tracking (auto-created)
- ✅ `analytics` - App analytics (auto-created)

*MongoDB collections are created automatically when first used*

---

## 🔧 **Manual Migration (If Automatic Fails)**

### **Connect to PostgreSQL Database:**
1. In Railway dashboard → PostgreSQL service
2. Click "Connect" → "psql"
3. Copy and run the connection command in terminal

### **Essential Tables SQL:**

```sql
-- Users table (essential for authentication)
CREATE TABLE users (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    two_factor_secret TEXT NULL,
    two_factor_recovery_codes TEXT NULL,
    two_factor_confirmed_at TIMESTAMP NULL,
    remember_token VARCHAR(100) NULL,
    current_team_id BIGINT NULL,
    profile_photo_path VARCHAR(2048) NULL,
    username VARCHAR(255) NULL,
    is_admin BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

-- Categories table
CREATE TABLE categories (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    description TEXT NULL,
    image_url VARCHAR(500) NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

-- Products table  
CREATE TABLE products (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    description TEXT NULL,
    price DECIMAL(8,2) NOT NULL,
    category_id BIGINT NOT NULL,
    image_url VARCHAR(500) NULL,
    stock_quantity INTEGER DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

-- Sessions table (for Laravel sessions)
CREATE TABLE sessions (
    id VARCHAR(255) PRIMARY KEY,
    user_id BIGINT NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    payload LONGTEXT NOT NULL,
    last_activity INTEGER NOT NULL,
    INDEX sessions_user_id_index (user_id),
    INDEX sessions_last_activity_index (last_activity)
);
```

---

## ✅ **Verification Steps**

### **Check PostgreSQL Tables:**
```bash
# Using Railway CLI
railway run php artisan migrate:status

# Check user table specifically  
railway run php artisan tinker --execute "echo App\\Models\\User::count() . ' users found'"
```

### **Check MongoDB Connection:**
```bash
# Test MongoDB connection
railway run php artisan tinker --execute "try { new App\\Models\\Review(); echo 'MongoDB: Connected'; } catch(Exception \$e) { echo 'MongoDB Error: ' . \$e->getMessage(); }"
```

### **Test App Functionality:**
1. Visit your Railway app URL
2. Try to register a new user
3. Check if registration works (uses PostgreSQL)
4. Try to create a product review (uses MongoDB)

---

## 🚨 **Common Issues & Solutions**

### **Issue: Migration timeout**
```bash
# Run migrations in smaller batches
railway run php artisan migrate --path=/database/migrations/0001_01_01_000000_create_users_table.php
railway run php artisan migrate --path=/database/migrations/2025_09_14_031001_create_categories_table.php
# ... continue with other migrations
```

### **Issue: Database connection refused**
- ✅ Verify environment variables are set correctly
- ✅ Check PostgreSQL service is running in Railway
- ✅ Ensure `DB_HOST` uses Railway internal URL

### **Issue: MongoDB not working**
- ✅ Verify MongoDB service is running
- ✅ Check `MONGO_DB_HOST` variable is correct
- ✅ MongoDB collections create automatically (no migrations needed)

---

## 🎯 **Next Steps After Migration**

1. **✅ Verify tables exist**
2. **✅ Test user registration**
3. **✅ Seed initial data (categories, sample products)**
4. **✅ Test product review system (MongoDB)**
5. **✅ Check both databases in Railway dashboard**

Your Laravel app with PostgreSQL + MongoDB should now be fully functional! 🚀