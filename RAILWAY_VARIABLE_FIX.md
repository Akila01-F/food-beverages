# Fix Railway Environment Variables - Clean Setup

## 🚨 **Issue: Too Many Database Variables**

You've added PostgreSQL and MongoDB **service variables** to your **web app**. This causes conflicts because:
- Database services have their own internal variables
- Your app only needs connection variables
- Mixing them causes authentication warnings

---

## ✅ **CORRECT Environment Variables for Your Web App**

### **Delete ALL current variables and add ONLY these:**

```env
# Laravel App Configuration
APP_NAME=Food & Beverage App
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:Ia0dY4/MOZwXB2ZReig3oMqsEhVghC05FXdCCPpjtFU=

# PostgreSQL Connection (Laravel format)
DB_CONNECTION=pgsql
DB_HOST=${{Postgres.RAILWAY_PRIVATE_DOMAIN}}
DB_PORT=5432
DB_DATABASE=${{Postgres.POSTGRES_DB}}
DB_USERNAME=${{Postgres.POSTGRES_USER}}
DB_PASSWORD=${{Postgres.POSTGRES_PASSWORD}}

# MongoDB Connection (Laravel format)
MONGO_DB_HOST=${{MongoDB.RAILWAY_PRIVATE_DOMAIN}}
MONGO_DB_PORT=27017
MONGO_DB_DATABASE=food_beverage_mongo
MONGO_DB_USERNAME=${{MongoDB.MONGO_INITDB_ROOT_USERNAME}}
MONGO_DB_PASSWORD=${{MongoDB.MONGO_INITDB_ROOT_PASSWORD}}

# Laravel Session & Cache
SESSION_DRIVER=database
SESSION_LIFETIME=120
CACHE_STORE=database
QUEUE_CONNECTION=database
FILESYSTEM_DISK=local
MAIL_MAILER=log
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE_COOKIE=lax
```

---

## 🗑️ **Remove These Variables (They Don't Belong in Your App):**

❌ **DELETE these from your web app variables:**
- `DATABASE_PUBLIC_URL`
- `DATABASE_URL` 
- `PGDATA`
- `PGDATABASE`
- `PGHOST`
- `PGPASSWORD`
- `PGPORT`
- `PGUSER`
- `POSTGRES_DB`
- `POSTGRES_PASSWORD`
- `POSTGRES_USER`
- `RAILWAY_DEPLOYMENT_DRAINING_SECONDS`
- `SSL_CERT_DAYS`
- `MONGO_INITDB_ROOT_PASSWORD`
- `MONGO_INITDB_ROOT_USERNAME`
- `MONGO_PUBLIC_URL`
- `MONGO_URL`
- `MONGOHOST`
- `MONGOPASSWORD`
- `MONGOPORT`
- `MONGOUSER`

**These belong to the database services, NOT your web app!**

---

## 🔧 **Step-by-Step Fix**

### **Step 1: Clear All Variables**
1. Go to Railway dashboard → Your **Web Service** (not database services)
2. Click **Variables** tab
3. **Delete ALL variables** currently there

### **Step 2: Add Only App Variables**
Add these **one by one**:

```
APP_NAME = Food & Beverage App
APP_ENV = production  
APP_DEBUG = false
APP_KEY = base64:Ia0dY4/MOZwXB2ZReig3oMqsEhVghC05FXdCCPpjtFU=
```

### **Step 3: Add Database Connections**
```
DB_CONNECTION = pgsql
DB_HOST = ${{Postgres.RAILWAY_PRIVATE_DOMAIN}}
DB_PORT = 5432
DB_DATABASE = ${{Postgres.POSTGRES_DB}}
DB_USERNAME = ${{Postgres.POSTGRES_USER}}
DB_PASSWORD = ${{Postgres.POSTGRES_PASSWORD}}
```

### **Step 4: Add MongoDB Connections**
```
MONGO_DB_HOST = ${{MongoDB.RAILWAY_PRIVATE_DOMAIN}}
MONGO_DB_PORT = 27017
MONGO_DB_DATABASE = food_beverage_mongo
MONGO_DB_USERNAME = ${{MongoDB.MONGO_INITDB_ROOT_USERNAME}}
MONGO_DB_PASSWORD = ${{MongoDB.MONGO_INITDB_ROOT_PASSWORD}}
```

### **Step 5: Add Laravel Settings**
```
SESSION_DRIVER = database
CACHE_STORE = database
QUEUE_CONNECTION = database
FILESYSTEM_DISK = local
MAIL_MAILER = log
```

---

## 🎯 **Understanding Railway Variable References**

### **What `${{ServiceName.VARIABLE}}` Does:**
- `${{Postgres.POSTGRES_PASSWORD}}` = Gets password from PostgreSQL service
- `${{MongoDB.MONGO_INITDB_ROOT_USERNAME}}` = Gets username from MongoDB service
- Railway automatically fills these with correct values

### **Why This Approach Works:**
✅ **No hardcoded credentials** - Railway manages them  
✅ **Automatic updates** - If database password changes, app updates  
✅ **Security** - Credentials stored in Railway's secure system  
✅ **No conflicts** - Each service has its own variables  

---

## ⚠️ **Important Notes**

### **Database Services Keep Their Own Variables:**
- PostgreSQL service has its internal variables
- MongoDB service has its internal variables  
- **Your web app** only needs connection references

### **Don't Copy Service Variables:**
- Never copy variables from database service to web app
- Use Railway's `${{Service.VARIABLE}}` references instead
- This prevents authentication conflicts

---

## ✅ **After Fixing Variables**

1. **Railway will redeploy** your app automatically
2. **No more authentication warnings**
3. **Clean variable list** in your web service
4. **Database connections will work** properly

### **Test After Redeploy:**
```bash
# Install Railway CLI and test connections
npm install -g @railway/cli
railway login
railway link

# Test PostgreSQL
railway run php artisan tinker --execute "echo 'PostgreSQL: ' . App\\Models\\User::count() . ' users'"

# Test MongoDB  
railway run php artisan tinker --execute "echo 'MongoDB: ' . App\\Models\\Review::count() . ' reviews'"
```

---

## 🎉 **Expected Result**

Your web app variables should look clean like this:

```
APP_NAME: Food & Beverage App
APP_ENV: production
DB_CONNECTION: pgsql
DB_HOST: ${{Postgres.RAILWAY_PRIVATE_DOMAIN}}
MONGO_DB_HOST: ${{MongoDB.RAILWAY_PRIVATE_DOMAIN}}
SESSION_DRIVER: database
... (only app-specific variables)
```

**No more database service internals cluttering your app configuration!** 🚀