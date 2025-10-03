# Railway Deployment Guide - Easiest Laravel Hosting
## For Dual Database App (PostgreSQL + MongoDB)

## Why Railway is Perfect for Your Stack

✅ **PostgreSQL + MongoDB** - Both databases supported natively  
✅ **One-click database provisioning** - No manual setup needed  
✅ **Automatic deployments** from GitHub  
✅ **Zero server configuration** required  
✅ **Built-in SSL certificates**  
✅ **Dual database management** in one platform  
✅ **Affordable pricing** - $8-15/month total

---

## Step-by-Step Deployment

### Step 1: Prepare Your Repository

Ensure your repository has these files (you already have them):

```
├── composer.json
├── package.json
├── .env.example
└── public/index.php
```

### Step 2: Sign Up & Connect Repository

1. Go to [Railway.app](https://railway.app)
2. Click **"Start a New Project"**
3. Choose **"Deploy from GitHub repo"**
4. Connect your GitHub account
5. Select your `food-beverages` repository

### Step 3: Add Both Databases

**Add PostgreSQL (Primary Database):**
1. In your Railway project dashboard
2. Click **"+ New Service"**
3. Select **"Database"**
4. Choose **"PostgreSQL"**
5. Railway provisions PostgreSQL instantly

**Add MongoDB (Secondary Database):**
1. Click **"+ New Service"** again
2. Select **"Database"**
3. Choose **"MongoDB"**
4. Railway provisions MongoDB instantly

You'll now have both databases running in the same project!

### Step 4: Configure Environment Variables

In Railway dashboard, go to your web service **Variables** tab:

```env
# App Configuration
APP_NAME="Food & Beverage App"
APP_ENV=production
APP_KEY=base64:YOUR_32_CHARACTER_KEY_HERE
APP_DEBUG=false
APP_URL=${{RAILWAY_STATIC_URL}}

# Primary Database - PostgreSQL (Railway auto-populates)
DB_CONNECTION=pgsql
DB_HOST=${{PGHOST}}
DB_PORT=${{PGPORT}}
DB_DATABASE=${{PGDATABASE}}
DB_USERNAME=${{PGUSER}}
DB_PASSWORD=${{PGPASSWORD}}

# Secondary Database - MongoDB (Railway auto-populates)
MONGO_DB_HOST=${{MONGOHOST}}
MONGO_DB_PORT=${{MONGOPORT}}
MONGO_DB_DATABASE=${{MONGODATABASE}}
MONGO_DB_USERNAME=${{MONGOUSER}}
MONGO_DB_PASSWORD=${{MONGOPASSWORD}}

# Session & Cache (using PostgreSQL for sessions)
SESSION_DRIVER=database
CACHE_DRIVER=database
QUEUE_CONNECTION=database

# Mail (configure if needed)
MAIL_MAILER=log
```

### Step 5: Add Build Configuration

Railway auto-detects Laravel, but create `nixpacks.toml` for optimization:

```toml
[phases.build]
cmds = [
    "composer install --no-dev --optimize-autoloader",
    "npm ci",
    "npm run build",
    "php artisan config:cache",
    "php artisan route:cache",
    "php artisan view:cache"
]

[phases.setup]
nixPkgs = ["nginx", "php82", "nodejs-18_x", "php82Extensions.mongodb"]

[start]
cmd = "php artisan serve --host=0.0.0.0 --port=$PORT"
```

### Step 6: Deploy

1. Push your changes to GitHub
2. Railway automatically deploys
3. Your app will be available at a Railway subdomain

---

## Alternative: Render (Free Tier Option)

**⚠️ Challenge: Dual Database Setup More Complex**

### Render Setup

1. **Sign up** at [Render.com](https://render.com)
2. **Connect GitHub** repository
3. **Create Web Service** from your repo
4. **Set build command:** `composer install --no-dev && npm ci && npm run build`
5. **Set start command:** `php artisan serve --host=0.0.0.0 --port=$PORT`

### Database Setup for Render

**PostgreSQL (Render Native):**
1. Create **PostgreSQL** service in Render
2. Copy connection details to environment variables

**MongoDB (External - Atlas):**
1. Sign up at [MongoDB Atlas](https://cloud.mongodb.com)
2. Create free cluster (512MB)
3. Get connection string
4. Add to Render environment variables

**Environment Variables for Render:**
```env
# App
APP_ENV=production
APP_KEY=base64:YOUR_KEY_HERE
APP_DEBUG=false

# PostgreSQL (from Render)
DB_CONNECTION=pgsql
DB_HOST=your-render-postgres-host
DB_PORT=5432
DB_DATABASE=your_db
DB_USERNAME=your_user
DB_PASSWORD=your_password

# MongoDB (from Atlas)
MONGO_DB_HOST=cluster0.xxxxx.mongodb.net
MONGO_DB_PORT=27017
MONGO_DB_DATABASE=food_beverage_mongo
MONGO_DB_USERNAME=your_mongo_user
MONGO_DB_PASSWORD=your_mongo_password

# Session & Cache
SESSION_DRIVER=database
CACHE_DRIVER=database
```

---

## Comparison Table (PostgreSQL + MongoDB)

| Platform | Setup Time | Cost/Month | PostgreSQL | MongoDB | Dual DB Support | SSL |
|----------|------------|------------|------------|---------|-----------------|-----|
| **Railway** | 10 minutes | $8-15 | ✅ Native | ✅ Native | ✅ Excellent | ✅ Free |
| **Render** | 25 minutes | $7-25 | ✅ Native | ⚠️ External Atlas | ⚠️ Manual setup | ✅ Free |
| **DigitalOcean** | 45 minutes | $15-35 | ✅ Managed | ✅ Managed | ✅ Good | ✅ Free |
| **AWS Lightsail** | 90+ minutes | $10-50 | 🔧 Manual | 🔧 Manual | 🔧 Complex | 🔧 Manual |
| **Heroku** | 20 minutes | $25-50 | ✅ Add-on | 💸 Expensive | ⚠️ Costly | ✅ Free |

---

## Recommended Approach: Railway

### Why Railway is Perfect for Your Dual-Database App:

1. **PostgreSQL + MongoDB** - Both databases provisioned with one click each
2. **Laravel Optimized** - Automatically detects and configures Laravel
3. **Dual Database Support** - Handles multiple database connections seamlessly
4. **Environment Variables** - Auto-populates database credentials
5. **Livewire + Jetstream** - Real-time features work out of the box
6. **Asset Building** - Automatically builds your Tailwind CSS with Vite
7. **Cost Effective** - $3-5 per database, total ~$8-15/month

### Deployment Steps Summary:

```bash
# 1. Ensure your code is on GitHub
git add .
git commit -m "Prepare for Railway dual-database deployment"
git push origin main

# 2. Go to Railway.app and connect your repo
# 3. Add PostgreSQL service (one click)
# 4. Add MongoDB service (one click) 
# 5. Set environment variables (Railway auto-populates most)
# 6. Deploy automatically happens!
```

### Expected Timeline:
- **Account setup:** 2 minutes
- **Repository connection:** 1 minute
- **PostgreSQL provisioning:** 2 minutes  
- **MongoDB provisioning:** 2 minutes
- **Environment setup:** 5 minutes
- **First deployment:** 8 minutes
- **Total time:** ~20 minutes

---

## If You Want Even Simpler...

### Laravel Forge + DigitalOcean

**Most automated but costs more:**

1. **Laravel Forge** ($12/month) + **DigitalOcean** ($6/month)
2. **One-click Laravel deployment**
3. **Automatic server management**
4. **Built-in database management**

**Setup:**
1. Sign up for Forge
2. Connect DigitalOcean account  
3. Create server (1 click)
4. Deploy repository (1 click)
5. Add database (1 click)

**Total cost:** ~$18/month but **zero technical setup**

---

## My Recommendation for PostgreSQL + MongoDB

🏆 **Railway is the Clear Winner** because:
- **Dual database support** - Both PostgreSQL & MongoDB natively
- **Fastest dual-db setup** (~20 minutes total)
- **Auto environment variables** - Databases connect automatically  
- **Affordable** ($8-15/month for both databases)
- **Zero server management** - Focus on your app, not infrastructure
- **Perfect for your stack** - Laravel + Jetstream + Livewire + PostgreSQL + MongoDB

**Next best:** DigitalOcean App Platform (if you need more control)

**Avoid for dual-DB:** Vercel, Netlify (no database support)

## 🚨 **Important for Your Dual-Database App**

Most platforms struggle with dual databases. Railway excels here because:
- ✅ **Native support** for both PostgreSQL and MongoDB
- ✅ **Automatic connection strings** - No manual configuration  
- ✅ **Same network** - Both databases in same project for fast connections
- ✅ **Unified billing** - One platform, one bill
- ✅ **Consistent backups** - Both databases backed up together

Would you like me to help you set up Railway deployment with both databases right now?