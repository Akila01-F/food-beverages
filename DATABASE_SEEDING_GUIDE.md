# Database Seeding Guide for Railway

## 🌱 **Seed Your Database with Initial Data**

Your app has pre-built seeders for categories, products, and users. Here's how to run them:

---

## 🚀 **Run Seeders on Railway**

### **Option 1: Railway CLI (Recommended)**
```bash
# Seed all data
railway run php artisan db:seed --force

# Or seed specific seeders
railway run php artisan db:seed --class=CategorySeeder --force
railway run php artisan db:seed --class=ProductSeeder --force
railway run php artisan db:seed --class=AdminUserSeeder --force
```

### **Option 2: Add to Deploy Command**
In Railway dashboard:
1. Go to Settings → Deploy
2. Update Deploy Command to:
```bash
php artisan migrate --force && php artisan db:seed --force && php artisan serve --host=0.0.0.0 --port=$PORT
```

---

## 📊 **What Gets Seeded**

### **✅ Categories (Food & Beverage Types)**
Your `CategorySeeder` will create categories like:
- Burgers
- Pizza  
- Drinks
- Desserts
- Appetizers

### **✅ Products (Food & Beverage Items)**
Your `ProductSeeder` will create sample products with:
- Names, descriptions, prices
- Category associations
- Stock quantities
- Active status

### **✅ Users**
- **Test User**: `test@example.com`
- **Admin User**: From `AdminUserSeeder`

---

## 🔍 **Verify Seeding Success**

### **Check Data Created:**
```bash
# Count categories
railway run php artisan tinker --execute "echo 'Categories: ' . App\\Models\\Category::count()"

# Count products  
railway run php artisan tinker --execute "echo 'Products: ' . App\\Models\\Product::count()"

# Count users
railway run php artisan tinker --execute "echo 'Users: ' . App\\Models\\User::count()"

# List categories
railway run php artisan tinker --execute "App\\Models\\Category::all(['name'])->pluck('name')->each(function(\$name) { echo \$name . PHP_EOL; })"
```

### **Test in Browser:**
1. Visit your Railway app URL
2. Register/login with test account: `test@example.com` / `password`
3. Browse products by category
4. Try leaving a product review (tests MongoDB)

---

## 🛠️ **Custom Seeding (Optional)**

If you want to add more data, you can run individual commands:

### **Add More Users:**
```bash
railway run php artisan tinker --execute "
App\\Models\\User::create([
    'name' => 'John Doe', 
    'email' => 'john@example.com',
    'password' => bcrypt('password')
]);"
```

### **Add Sample Reviews (MongoDB):**
```bash
railway run php artisan tinker --execute "
App\\Models\\Review::create([
    'product_id' => 1,
    'user_id' => 1, 
    'rating' => 5,
    'title' => 'Great product!',
    'comment' => 'Really enjoyed this item. Highly recommended!',
    'is_approved' => true
]);"
```

---

## ⚡ **Quick Complete Setup**

Run this single command to set up everything:

```bash
# Complete setup: migrate + seed
railway run php artisan migrate --seed --force
```

This will:
1. ✅ Create all PostgreSQL tables
2. ✅ Seed categories and products
3. ✅ Create test users
4. ✅ Set up admin account

---

## 🎯 **Expected Results**

After seeding, your app should have:

### **PostgreSQL Data:**
- ✅ 5+ categories (Burgers, Pizza, etc.)
- ✅ 10+ products with prices and descriptions
- ✅ 2+ users (test user + admin)
- ✅ All necessary Laravel system tables

### **MongoDB Collections:**
- ✅ Empty `reviews` collection (ready for use)
- ✅ Empty `activity_logs` collection (ready for use)  
- ✅ Empty `analytics` collection (ready for use)

*MongoDB collections will populate as users interact with the app*

---

## 🚨 **Troubleshooting**

### **Error: Class not found**
```bash
# Clear cache and try again
railway run php artisan config:clear
railway run php artisan cache:clear
railway run php artisan db:seed --force
```

### **Error: Database connection**
- ✅ Ensure environment variables are set correctly
- ✅ Check PostgreSQL service is running
- ✅ Verify migrations ran successfully first

### **Error: Duplicate entries**
```bash
# Reset and reseed
railway run php artisan migrate:fresh --seed --force
```
⚠️ **Warning**: This deletes all existing data

---

## 🎉 **Success Checklist**

Your seeding is complete when:

- ✅ Categories appear in your app
- ✅ Products are browsable by category  
- ✅ User registration/login works
- ✅ Admin panel accessible (if you have one)
- ✅ Review system ready for testing
- ✅ No database errors in Railway logs

Your Laravel Food & Beverage app is now fully set up with sample data! 🚀