# Complete Application Testing Guide

## 🧪 **Test Your Railway Deployment**

After setting up environment variables, running migrations, and seeding data, here's how to test everything:

---

## 🚀 **Basic Connectivity Tests**

### **1. Test App Loading**
```
✅ Visit your Railway app URL: https://your-app.up.railway.app
✅ Should see Laravel welcome page or your app homepage
✅ No 500 errors in browser
```

### **2. Test Database Connections**
```bash
# Test PostgreSQL connection
railway run php artisan tinker --execute "
try { 
    echo 'PostgreSQL Users: ' . App\\Models\\User::count(); 
} catch(Exception \$e) { 
    echo 'PostgreSQL Error: ' . \$e->getMessage(); 
}"

# Test MongoDB connection  
railway run php artisan tinker --execute "
try { 
    echo 'MongoDB Reviews: ' . App\\Models\\Review::count(); 
} catch(Exception \$e) { 
    echo 'MongoDB Error: ' . \$e->getMessage(); 
}"
```

---

## 👤 **User Authentication Tests (PostgreSQL)**

### **Test User Registration:**
1. Go to `/register` on your app
2. Create new account with:
   - Name: Test User
   - Email: newuser@example.com  
   - Password: password
3. ✅ Should redirect to dashboard/home
4. ✅ User should be logged in

### **Test User Login:**
1. Go to `/login` on your app
2. Login with seeded account:
   - Email: `test@example.com`
   - Password: `password`
3. ✅ Should successfully log in
4. ✅ Dashboard should load

### **Verify User Data:**
```bash
# Check if new users are being created
railway run php artisan tinker --execute "
App\\Models\\User::latest()->take(3)->get(['name', 'email', 'created_at'])->each(function(\$user) {
    echo \$user->name . ' - ' . \$user->email . ' - ' . \$user->created_at . PHP_EOL;
});
"
```

---

## 🍔 **Product Management Tests (PostgreSQL)**

### **Test Product Browsing:**
1. Visit your app's products page
2. ✅ Should see categories (Burgers, Pizza, Drinks, etc.)
3. ✅ Should see products with prices and descriptions
4. ✅ Product images should load (or show placeholders)

### **Test Product Details:**
1. Click on any product
2. ✅ Product detail page should load
3. ✅ Should show product name, price, description
4. ✅ Review section should be present (even if empty)

### **Verify Product Data:**
```bash
# Check products and categories
railway run php artisan tinker --execute "
echo 'Categories: ' . App\\Models\\Category::count() . PHP_EOL;
echo 'Products: ' . App\\Models\\Product::count() . PHP_EOL;
App\\Models\\Category::with('products')->get()->each(function(\$cat) {
    echo \$cat->name . ': ' . \$cat->products->count() . ' products' . PHP_EOL;
});
"
```

---

## ⭐ **Review System Tests (MongoDB)**

### **Test Review Creation:**
1. Login to your app
2. Go to any product detail page
3. Try to leave a review:
   - Rating: 5 stars
   - Comment: "Great product!"
4. ✅ Review should be saved
5. ✅ Should appear on product page

### **Test Review Display:**
1. Visit product with reviews
2. ✅ Reviews should display with ratings
3. ✅ User names should show
4. ✅ Review dates should display

### **Verify Review Data (MongoDB):**
```bash
# Test MongoDB review functionality
railway run php artisan tinker --execute "
// Create test review
App\\Models\\Review::create([
    'product_id' => 1,
    'user_id' => 1,
    'rating' => 5,
    'title' => 'Test Review',
    'comment' => 'This is a test review to verify MongoDB works',
    'is_approved' => true
]);
echo 'Test review created. Total reviews: ' . App\\Models\\Review::count();
"

# Check reviews exist
railway run php artisan tinker --execute "
App\\Models\\Review::all()->each(function(\$review) {
    echo 'Review ID: ' . \$review->id . ' - Rating: ' . \$review->rating . ' - Comment: ' . \$review->comment . PHP_EOL;
});
"
```

---

## 🛒 **E-commerce Functionality Tests**

### **Test Shopping Cart (if implemented):**
1. Add products to cart
2. ✅ Cart should update with items
3. ✅ Quantities should be adjustable
4. ✅ Total should calculate correctly

### **Test Order Process (if implemented):**
1. Proceed to checkout
2. ✅ Order form should load
3. ✅ Order should be saved to database

### **Verify Order Data:**
```bash
# Check orders (if any exist)
railway run php artisan tinker --execute "
echo 'Total Orders: ' . App\\Models\\Order::count() . PHP_EOL;
if (App\\Models\\Order::count() > 0) {
    App\\Models\\Order::with('items')->latest()->first()->items->each(function(\$item) {
        echo 'Item: ' . \$item->product->name . ' - Qty: ' . \$item->quantity . PHP_EOL;
    });
}
"
```

---

## 🔧 **Admin Panel Tests (if available)**

### **Test Admin Access:**
1. Login with admin account (from AdminUserSeeder)
2. ✅ Should have access to admin features
3. ✅ Can manage products, categories, users

### **Verify Admin User:**
```bash
# Check admin users
railway run php artisan tinker --execute "
App\\Models\\User::where('is_admin', true)->get(['name', 'email'])->each(function(\$admin) {
    echo 'Admin: ' . \$admin->name . ' - ' . \$admin->email . PHP_EOL;
});
"
```

---

## 📊 **Performance & Error Tests**

### **Check Application Logs:**
```bash
# View recent logs
railway logs --tail

# Check for errors
railway logs | grep -i error
```

### **Test Page Load Times:**
- ✅ Homepage: < 3 seconds
- ✅ Product pages: < 2 seconds  
- ✅ User registration: < 2 seconds
- ✅ Database queries: No timeout errors

### **Test Database Performance:**
```bash
# Test large query performance
railway run php artisan tinker --execute "
\$start = microtime(true);
App\\Models\\Product::with('category')->get();
\$end = microtime(true);
echo 'Product query took: ' . round((\$end - \$start) * 1000, 2) . 'ms';
"
```

---

## 🎯 **Complete Functionality Checklist**

### **✅ Core Features Working:**
- [ ] App loads without errors
- [ ] User registration works (PostgreSQL)
- [ ] User login works (PostgreSQL)
- [ ] Products display correctly (PostgreSQL)
- [ ] Categories work (PostgreSQL)
- [ ] Reviews can be created (MongoDB)
- [ ] Reviews display properly (MongoDB)
- [ ] No database connection errors
- [ ] Environment variables working
- [ ] Both databases accessible

### **✅ Advanced Features Working:**
- [ ] Shopping cart functionality
- [ ] Order processing
- [ ] Admin panel access
- [ ] Search functionality
- [ ] Image uploads (if implemented)
- [ ] Email notifications (if configured)

---

## 🚨 **Common Issues & Solutions**

### **Issue: 500 Internal Server Error**
```bash
# Check logs for specific error
railway logs --tail

# Common fixes:
railway run php artisan config:clear
railway run php artisan cache:clear
railway run php artisan view:clear
```

### **Issue: Database Connection Refused**
- ✅ Check environment variables match Railway database credentials
- ✅ Verify both PostgreSQL and MongoDB services are running
- ✅ Check Railway internal network connections

### **Issue: Reviews Not Saving (MongoDB)**
```bash
# Test MongoDB connection manually
railway run php artisan tinker --execute "
try {
    \$review = new App\\Models\\Review();
    \$review->fill(['product_id' => 1, 'user_id' => 1, 'rating' => 5, 'comment' => 'Test']);
    \$review->save();
    echo 'MongoDB working!';
} catch(Exception \$e) {
    echo 'MongoDB error: ' . \$e->getMessage();
}
"
```

### **Issue: Missing Tables**
```bash
# Re-run migrations
railway run php artisan migrate --force

# Check migration status
railway run php artisan migrate:status
```

---

## 🎉 **Success Criteria**

Your Railway deployment is successful when:

- ✅ **App loads** without 500 errors
- ✅ **PostgreSQL works** - users, products, orders function
- ✅ **MongoDB works** - reviews can be created and displayed  
- ✅ **Authentication works** - registration, login, logout
- ✅ **Data persists** - information saves and loads correctly
- ✅ **No connection errors** - both databases accessible
- ✅ **Performance acceptable** - pages load in reasonable time

Your Laravel Food & Beverage app with PostgreSQL + MongoDB is now fully operational on Railway! 🚀🎉