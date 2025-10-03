# Laravel Environment Variables Explained

## 🔧 **Laravel App Variables - What They Do**

Here's what each environment variable controls in your Laravel application:

---

## 🚀 **Core Application Settings**

### **`APP_NAME`**
```env
APP_NAME=Food & Beverage App
```
**What it does:** 
- Sets the application name displayed in emails, notifications, and UI
- Used in mail templates and system messages
- Shows up in browser titles and headers

### **`APP_ENV`**
```env
APP_ENV=production
```
**What it does:**
- Tells Laravel what environment it's running in
- **Options:** `local`, `development`, `staging`, `production`
- **Production:** Optimizes performance, disables debug info
- **Local:** Enables debugging, detailed error messages

### **`APP_DEBUG`**
```env
APP_DEBUG=false
```
**What it does:**
- Controls detailed error messages and stack traces
- **`true`:** Shows detailed errors (for development)
- **`false`:** Hides errors from users (for production security)
- **IMPORTANT:** Always `false` in production for security

### **`APP_KEY`**
```env
APP_KEY=base64:Ia0dY4/MOZwXB2ZReig3oMqsEhVghC05FXdCCPpjtFU=
```
**What it does:**
- **Critical security key** for encryption
- Encrypts cookies, sessions, passwords, and sensitive data
- **Must be 32 characters** and unique per application
- **Generate new one:** `php artisan key:generate --show`

### **`APP_URL`**
```env
APP_URL=https://your-app.up.railway.app
```
**What it does:**
- Base URL for your application
- Used for generating links in emails and notifications
- Required for proper asset URLs and redirects
- **Set this to your Railway domain after deployment**

---

## 💾 **Session & Caching Settings**

### **`SESSION_DRIVER`**
```env
SESSION_DRIVER=database
```
**What it does:**
- Where user login sessions are stored
- **Options:** `file`, `cookie`, `database`, `redis`
- **`database`:** Stores sessions in PostgreSQL (recommended for Railway)
- **Why database:** More reliable than files on cloud platforms

### **`SESSION_LIFETIME`**
```env
SESSION_LIFETIME=120
```
**What it does:**
- How long users stay logged in (in minutes)
- `120` = 2 hours of inactivity before logout
- Adjust based on your security needs

### **`CACHE_STORE`**
```env
CACHE_STORE=database
```
**What it does:**
- Where Laravel stores cached data for faster performance
- **Options:** `file`, `database`, `redis`, `array`
- **`database`:** Uses PostgreSQL for caching (good for Railway)
- Speeds up database queries and view rendering

### **`QUEUE_CONNECTION`**
```env
QUEUE_CONNECTION=database
```
**What it does:**
- How background jobs are processed
- **Options:** `sync`, `database`, `redis`, `sqs`
- **`database`:** Stores jobs in PostgreSQL
- **`sync`:** Process immediately (simpler but slower)

---

## 📁 **File Storage Settings**

### **`FILESYSTEM_DISK`**
```env
FILESYSTEM_DISK=local
```
**What it does:**
- Where uploaded files (images, documents) are stored
- **Options:** `local`, `s3`, `azure`, `gcs`
- **`local`:** Stores files on Railway's filesystem
- **For production:** Consider cloud storage like S3

---

## 📧 **Mail Settings**

### **`MAIL_MAILER`**
```env
MAIL_MAILER=log
```
**What it does:**
- How emails are sent from your app
- **Options:** `smtp`, `sendmail`, `mailgun`, `ses`, `log`
- **`log`:** Emails written to logs (testing/development)
- **For production:** Use `smtp` with real email service

---

## 🔒 **Security Settings**

### **`SESSION_SECURE_COOKIE`**
```env
SESSION_SECURE_COOKIE=true
```
**What it does:**
- Requires HTTPS for session cookies
- **`true`:** Only works over HTTPS (required for Railway)
- **`false`:** Works over HTTP (local development only)

### **`SESSION_SAME_SITE_COOKIE`**
```env
SESSION_SAME_SITE_COOKIE=lax
```
**What it does:**
- CSRF protection for cookies
- **Options:** `strict`, `lax`, `none`
- **`lax`:** Good balance of security and functionality

---

## 🗄️ **Database Connection Settings**

### **PostgreSQL (Main Database)**
```env
DB_CONNECTION=pgsql                    # Database type
DB_HOST=${{Postgres.RAILWAY_PRIVATE_DOMAIN}}  # Database server
DB_PORT=5432                          # Database port
DB_DATABASE=${{Postgres.POSTGRES_DB}}         # Database name
DB_USERNAME=${{Postgres.POSTGRES_USER}}       # Database user
DB_PASSWORD=${{Postgres.POSTGRES_PASSWORD}}   # Database password
```

### **MongoDB (Reviews/Analytics)**
```env
MONGO_DB_HOST=${{MongoDB.RAILWAY_PRIVATE_DOMAIN}}     # MongoDB server
MONGO_DB_PORT=27017                                   # MongoDB port
MONGO_DB_DATABASE=${{MongoDB.MONGO_INITDB_DATABASE}}  # MongoDB database
MONGO_DB_USERNAME=${{MongoDB.MONGO_INITDB_ROOT_USERNAME}} # MongoDB user
MONGO_DB_PASSWORD=${{MongoDB.MONGO_INITDB_ROOT_PASSWORD}} # MongoDB password
```

---

## ⚙️ **Why Each Setting Matters**

### **For Your Food & Beverage App:**

| Variable | Impact on Your App |
|----------|-------------------|
| `APP_KEY` | 🔒 **Encrypts user passwords and login sessions** |
| `APP_DEBUG=false` | 🛡️ **Hides errors from customers** |
| `SESSION_DRIVER=database` | 👤 **User logins work across server restarts** |
| `CACHE_STORE=database` | ⚡ **Product pages load faster** |
| `DB_*` variables | 🗄️ **Users, products, orders stored in PostgreSQL** |
| `MONGO_DB_*` variables | ⭐ **Reviews and analytics stored in MongoDB** |

---

## 🎯 **Essential vs Optional Variables**

### **🚨 MUST HAVE (App won't work without these):**
- `APP_KEY` - Security encryption
- `DB_CONNECTION` + database credentials - Main database access
- `APP_ENV=production` - Proper environment setup

### **⚙️ RECOMMENDED (Better performance/security):**
- `APP_DEBUG=false` - Security
- `SESSION_DRIVER=database` - Reliability  
- `CACHE_STORE=database` - Performance
- MongoDB variables - For reviews system

### **📈 NICE TO HAVE (Can set later):**
- `APP_URL` - Better link generation
- `MAIL_MAILER=smtp` - Real email sending
- `FILESYSTEM_DISK=s3` - Cloud file storage

---

## 🔑 **Generate New APP_KEY (If Needed)**

If you need a new application key:

```bash
# Locally
php artisan key:generate --show

# Or via Railway CLI
railway run php artisan key:generate --show
```

Copy the output (starts with `base64:`) and use it as your `APP_KEY` value.

---

## 🎯 **Summary for Railway Setup**

For your Railway deployment, you **need** these core variables:

```env
# Essential
APP_ENV=production
APP_DEBUG=false  
APP_KEY=base64:your-32-char-key-here

# Database connections
DB_CONNECTION=pgsql
DB_HOST=${{Postgres.RAILWAY_PRIVATE_DOMAIN}}
# ... other DB variables

# Performance
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
```

These variables make your Laravel app secure, fast, and production-ready on Railway! 🚀