# Railway Environment Variables - EXACT Configuration

## 🎯 **Use Railway's Auto-Generated Database Variables**

Based on your Railway dashboard, here are the **exact environment variables** to add:

---

## 📝 **Environment Variables to Add in Railway Dashboard**

### **Go to: Your Web Service → Variables Tab → Add these:**

### **🔧 App Configuration**
```
APP_NAME = Food & Beverage App
APP_ENV = production
APP_DEBUG = false
APP_KEY = base64:Ia0dY4/MOZwXB2ZReig3oMqsEhVghC05FXdCCPpjtFU=
APP_URL = https://your-railway-app-domain.up.railway.app
```

### **🐘 PostgreSQL Database (Use Railway's Variables)**
```
DB_CONNECTION = pgsql
DB_HOST = ${{Postgres.RAILWAY_PRIVATE_DOMAIN}}
DB_PORT = 5432
DB_DATABASE = ${{Postgres.POSTGRES_DB}}
DB_USERNAME = ${{Postgres.POSTGRES_USER}}
DB_PASSWORD = ${{Postgres.POSTGRES_PASSWORD}}
```

**OR use the simpler DATABASE_URL approach:**
```
DATABASE_URL = ${{Postgres.DATABASE_URL}}
```

### **🍃 MongoDB Database (Check MongoDB Service Variables)**
```
MONGO_DB_HOST = ${{MongoDB.RAILWAY_PRIVATE_DOMAIN}}
MONGO_DB_PORT = 27017
MONGO_DB_DATABASE = ${{MongoDB.MONGO_INITDB_DATABASE}}
MONGO_DB_USERNAME = ${{MongoDB.MONGO_INITDB_ROOT_USERNAME}}
MONGO_DB_PASSWORD = ${{MongoDB.MONGO_INITDB_ROOT_PASSWORD}}
```

### **⚙️ Laravel Configuration**
```
SESSION_DRIVER = database
SESSION_LIFETIME = 120
CACHE_STORE = database
QUEUE_CONNECTION = database
FILESYSTEM_DISK = local
MAIL_MAILER = log
SESSION_SECURE_COOKIE = true
SESSION_SAME_SITE_COOKIE = lax
```

---

## 🔍 **How to Get MongoDB Variables**

1. **Click on your MongoDB service** in Railway dashboard
2. **Go to Variables tab**
3. **Look for variables like:**
   - `RAILWAY_PRIVATE_DOMAIN`
   - `MONGO_INITDB_ROOT_USERNAME`
   - `MONGO_INITDB_ROOT_PASSWORD`
   - `MONGO_INITDB_DATABASE`

**Or check the Connect tab for MongoDB connection details**

---

## ⚡ **Simplified Approach (Recommended)**

### **Use Railway's DATABASE_URL for PostgreSQL:**
```
DATABASE_URL = ${{Postgres.DATABASE_URL}}
```

### **Use Railway's MONGO_URL for MongoDB (if available):**
```
MONGO_URL = ${{MongoDB.MONGO_URL}}
```
*Check if MongoDB service provides MONGO_URL variable*

---

## 🎯 **Step-by-Step Setup**

### **Step 1: Add PostgreSQL Variables**
```
DB_CONNECTION = pgsql
DB_HOST = ${{Postgres.RAILWAY_PRIVATE_DOMAIN}}
DB_PORT = 5432
DB_DATABASE = railway
DB_USERNAME = postgres
DB_PASSWORD = ${{Postgres.POSTGRES_PASSWORD}}
```

### **Step 2: Check MongoDB Service**
1. Click MongoDB service in Railway
2. Copy the connection variables (similar format to PostgreSQL)
3. Add them using the MongoDB variable references

### **Step 3: Add App Variables**
```
APP_NAME = Food & Beverage App
APP_ENV = production
APP_DEBUG = false
APP_KEY = base64:Ia0dY4/MOZwXB2ZReig3oMqsEhVghC05FXdCCPpjtFU=
SESSION_DRIVER = database
CACHE_STORE = database
```

---

## 🔧 **Alternative: Manual Values (If Variables Don't Work)**

If Railway's variable references don't work, use the actual values:

### **PostgreSQL (From your Railway dashboard):**
```
DB_CONNECTION = pgsql
DB_HOST = [your-railway-private-domain]
DB_PORT = 5432
DB_DATABASE = railway
DB_USERNAME = postgres
DB_PASSWORD = emBeTzhApycOMIiCIyWxQKIDCyImEfko
```

### **MongoDB (Check MongoDB service for similar values):**
```
MONGO_DB_HOST = [mongodb-private-domain]
MONGO_DB_PORT = 27017
MONGO_DB_DATABASE = [database-name]
MONGO_DB_USERNAME = [username]
MONGO_DB_PASSWORD = [password]
```

---

## ✅ **Verification**

After adding variables:

1. **Railway will auto-redeploy** your app
2. **Check deployment logs** for database connection success
3. **Test app URL** - should load without 500 errors

### **Test Database Connections:**
```bash
# Install Railway CLI
npm install -g @railway/cli
railway login
railway link

# Test PostgreSQL
railway run php artisan tinker --execute "echo 'PostgreSQL Users: ' . App\\Models\\User::count()"

# Test MongoDB  
railway run php artisan tinker --execute "echo 'MongoDB Reviews: ' . App\\Models\\Review::count()"
```

---

## 🚨 **Common Issues**

### **Issue: Variable references not working**
**Solution:** Use actual values instead of `${{Postgres.VARIABLE_NAME}}`

### **Issue: Connection refused**
**Solution:** Use `RAILWAY_PRIVATE_DOMAIN` instead of public domains

### **Issue: MongoDB variables not found**
**Solution:** Check MongoDB service → Variables tab for exact variable names

---

## 🎯 **Quick Setup Order**

1. **Add PostgreSQL variables** using Railway's format ✅
2. **Add MongoDB variables** (check MongoDB service) ✅
3. **Add Laravel app variables** ✅
4. **Wait for auto-redeploy** ✅
5. **Run migrations** via Railway CLI ✅
6. **Test connections** ✅

Your Railway deployment will be ready once these variables are configured! 🚀