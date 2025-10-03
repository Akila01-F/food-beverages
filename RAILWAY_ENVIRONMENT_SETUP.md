# Railway Environment Variables Setup Guide

## 🚀 **Step-by-Step Environment Configuration**

### **Step 1: Access Railway Dashboard**
1. Go to [railway.app](https://railway.app)
2. Open your project
3. Click on your **Web Service** (Laravel app)
4. Click **"Variables"** tab

---

### **Step 2: Add Environment Variables**

Copy and paste these variables one by one in Railway:

#### **🔧 App Configuration**
```
APP_NAME = Food & Beverage App
APP_ENV = production  
APP_DEBUG = false
APP_KEY = base64:Ia0dY4/MOZwXB2ZReig3oMqsEhVghC05FXdCCPpjtFU=
```

#### **🐘 PostgreSQL Database** 
```
DB_CONNECTION = pgsql
DB_HOST = ${{Postgres.PGHOST}}
DB_PORT = ${{Postgres.PGPORT}}
DB_DATABASE = ${{Postgres.PGDATABASE}}
DB_USERNAME = ${{Postgres.PGUSER}}
DB_PASSWORD = ${{Postgres.PGPASSWORD}}
```

#### **🍃 MongoDB Database**
```
MONGO_DB_HOST = ${{MongoDB.MONGOHOST}}
MONGO_DB_PORT = ${{MongoDB.MONGOPORT}}
MONGO_DB_DATABASE = ${{MongoDB.MONGODATABASE}}
MONGO_DB_USERNAME = ${{MongoDB.MONGOUSER}}
MONGO_DB_PASSWORD = ${{MongoDB.MONGOPASSWORD}}
```

#### **⚙️ Laravel Configuration**
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

### **Step 3: Get Database Connection Details**

If Railway variables don't work, get manual connection details:

#### **For PostgreSQL:**
1. Click **PostgreSQL service** in Railway
2. Go to **"Connect"** tab
3. Copy connection details:
   - Host: `xxx.railway.internal`
   - Port: `5432`
   - Database: `railway`
   - Username: `postgres`
   - Password: `[long password]`

#### **For MongoDB:**
1. Click **MongoDB service** in Railway
2. Go to **"Connect"** tab  
3. Copy connection details:
   - Host: `xxx.railway.internal`
   - Port: `27017`
   - Database: `[database name]`
   - Username: `[username]`
   - Password: `[password]`

---

### **Step 4: Set APP_URL**

After deployment, update APP_URL:
```
APP_URL = https://your-app-name.up.railway.app
```

Replace `your-app-name` with your actual Railway app domain.

---

### **Step 5: Redeploy**

After adding variables:
1. Railway will **automatically redeploy** your app
2. Wait for deployment to complete
3. Check build logs for any errors

---

## ✅ **Verification**

Your variables are correct when:
- ✅ No database connection errors in logs
- ✅ App loads without 500 errors
- ✅ Database services show "Connected" status

---

## 🚨 **Common Issues**

### **Issue: Database connection refused**
**Solution:** Check if service names in variables match actual Railway service names

### **Issue: APP_KEY errors** 
**Solution:** Generate new key:
```bash
php artisan key:generate --show
```

### **Issue: MongoDB connection fails**
**Solution:** Verify MongoDB service is running and credentials are correct

---

## 📝 **Next Steps After Variables Set**

1. **Run Migrations** - Create PostgreSQL tables
2. **Test Connections** - Verify both databases work
3. **Seed Data** - Add initial data if needed
4. **Test Features** - Try user registration, products, reviews

Railway will handle the rest automatically! 🚀