# 🍔 MongoDB & PostgreSQL Integration in Laravel Food & Beverage App

## 📊 **Database Architecture Overview**

Your Laravel ecommerce application uses a **dual-database architecture** to leverage the strengths of both relational and NoSQL databases:

### **Current Configuration:**
- **Primary Database**: SQLite (development) → PostgreSQL (production)
- **Secondary Database**: MongoDB for specific use cases
- **Session Storage**: Database-based sessions

---

## 🔧 **Database Configuration**

### **PostgreSQL (Relational Database)**
```php
// config/database.php - PostgreSQL connection
'pgsql' => [
    'driver' => 'pgsql',
    'host' => env('DB_HOST', '127.0.0.1'),
    'port' => env('DB_PORT', '5432'),
    'database' => env('DB_DATABASE', 'laravel'),
    'username' => env('DB_USERNAME', 'root'),
    'password' => env('DB_PASSWORD', ''),
    'charset' => env('DB_CHARSET', 'utf8'),
    'prefix' => '',
    'search_path' => 'public',
    'sslmode' => 'prefer',
]
```

### **MongoDB (Document Database)**
```php
// config/database.php - MongoDB connection  
'mongodb' => [
    'driver' => 'mongodb',
    'host' => env('MONGO_DB_HOST', '127.0.0.1'),
    'port' => env('MONGO_DB_PORT', 27017),
    'database' => env('MONGO_DB_DATABASE', 'food_beverage'),
    'username' => env('MONGO_DB_USERNAME', ''),
    'password' => env('MONGO_DB_PASSWORD', ''),
    'options' => [
        'database' => env('MONGO_DB_AUTHENTICATION_DATABASE', 'admin'),
    ],
]
```

---

## 📋 **Data Distribution Strategy**

### **PostgreSQL Handles:**
✅ **Structured Transactional Data**
- Users & Authentication
- Products & Categories  
- Orders & Order Items
- Cart Sessions
- Migrations & System Tables

### **MongoDB Handles:**
✅ **Flexible Document Data**
- Product Reviews & Ratings
- User Activity Logs
- Search Analytics
- Marketing Data
- Cache & Temporary Data

---

## 🛠 **Operations by Database**

### **PostgreSQL Operations**

#### **1. Product Management**
```php
// Creating products (PostgreSQL - Eloquent)
Product::create([
    'name' => 'Classic Beef Burger',
    'price' => 12.99,
    'category_id' => 1,
    'stock_quantity' => 50
]);

// Querying with relationships
$products = Product::with('category')
    ->where('is_active', true)
    ->where('stock_quantity', '>', 0)
    ->get();

// Complex joins for orders
$orderSummary = Order::join('order_items', 'orders.id', '=', 'order_items.order_id')
    ->join('products', 'order_items.product_id', '=', 'products.id')
    ->where('orders.user_id', auth()->id())
    ->select('orders.*', 'products.name', 'order_items.quantity')
    ->get();
```

#### **2. User & Authentication**
```php
// User registration (PostgreSQL)
User::create([
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'password' => Hash::make('password')
]);

// Order processing with transactions
DB::transaction(function () {
    $order = Order::create($orderData);
    
    foreach ($cartItems as $item) {
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $item['id'],
            'quantity' => $item['quantity']
        ]);
        
        // Update stock
        Product::find($item['id'])->decrement('stock_quantity', $item['quantity']);
    }
});
```

#### **3. Category & Inventory Management**
```php
// Hierarchical categories
$categories = Category::where('parent_id', null)
    ->with('children.products')
    ->get();

// Stock management
Product::where('stock_quantity', '<', 10)
    ->update(['is_active' => false]);
```

---

### **MongoDB Operations**

#### **1. Review System**
```php
// Review model uses MongoDB
class Review extends MongoDB\Laravel\Eloquent\Model
{
    protected $connection = 'mongodb';
    protected $collection = 'reviews';
}

// Creating reviews (MongoDB - Document storage)
Review::create([
    'product_id' => 1,
    'user_id' => auth()->id(),
    'rating' => 5,
    'title' => 'Excellent burger!',
    'comment' => 'Best burger I ever had...',
    'images' => ['review1.jpg', 'review2.jpg'],
    'metadata' => [
        'device' => 'mobile',
        'location' => 'New York',
        'verified_purchase' => true
    ]
]);

// Aggregating ratings
$averageRating = Review::where('product_id', 1)
    ->where('is_approved', true)
    ->avg('rating');

// Complex aggregation pipeline
$reviewStats = Review::raw(function ($collection) {
    return $collection->aggregate([
        ['$match' => ['product_id' => 1]],
        ['$group' => [
            '_id' => '$rating',
            'count' => ['$sum' => 1]
        ]],
        ['$sort' => ['_id' => -1]]
    ]);
});
```

#### **2. Analytics & Logging**
```php
// User activity tracking (MongoDB)
DB::connection('mongodb')->collection('user_activities')->insert([
    'user_id' => auth()->id(),
    'action' => 'product_view',
    'product_id' => $product->id,
    'timestamp' => new MongoDB\BSON\UTCDateTime(),
    'session_data' => [
        'ip' => request()->ip(),
        'user_agent' => request()->userAgent(),
        'referrer' => request()->header('referer')
    ]
]);

// Search analytics
DB::connection('mongodb')->collection('search_logs')->insert([
    'query' => 'pizza',
    'results_count' => 15,
    'user_id' => auth()->id() ?? null,
    'filters' => ['category' => 'food'],
    'timestamp' => now()
]);
```

#### **3. Flexible Product Data**
```php
// Dynamic product attributes (MongoDB)
DB::connection('mongodb')->collection('product_extensions')->insert([
    'product_id' => 1,
    'nutritional_info' => [
        'calories' => 650,
        'protein' => '25g',
        'carbs' => '45g',
        'allergens' => ['gluten', 'dairy']
    ],
    'chef_notes' => 'Hand-formed patty with special sauce',
    'cooking_instructions' => [
        'prep_time' => '5 minutes',
        'cook_time' => '12 minutes',
        'temperature' => 'medium'
    ]
]);
```

---

## 🔄 **Cross-Database Operations**

### **Hybrid Queries**
```php
// Get product with PostgreSQL data and MongoDB reviews
class Product extends Model 
{
    public function getReviewsAttribute()
    {
        return Review::where('product_id', $this->id)
            ->where('is_approved', true)
            ->get();
    }
    
    public function getAverageRatingAttribute()
    {
        return Review::where('product_id', $this->id)
            ->avg('rating') ?? 0;
    }
}

// Usage in controllers
$product = Product::find(1);
$product->load('category');
$reviews = $product->reviews; // From MongoDB
$rating = $product->average_rating; // Calculated from MongoDB
```

### **Data Synchronization**
```php
// When order is completed, log to both databases
public function completeOrder(Order $order)
{
    // Update order status in PostgreSQL
    $order->update(['status' => 'completed']);
    
    // Log completion analytics in MongoDB
    DB::connection('mongodb')
        ->collection('order_analytics')
        ->insert([
            'order_id' => $order->id,
            'completion_time' => now(),
            'total_amount' => $order->total_amount,
            'items_count' => $order->orderItems->count(),
            'customer_data' => [
                'returning_customer' => $order->user->orders()->count() > 1,
                'preferred_category' => $this->getPreferredCategory($order->user)
            ]
        ]);
}
```

---

## ⚡ **Performance Benefits**

### **PostgreSQL Advantages:**
- **ACID Transactions**: Perfect for order processing
- **Complex Joins**: Efficient category/product relationships
- **Data Integrity**: Foreign key constraints
- **SQL Queries**: Familiar query language
- **Indexing**: Excellent for search and filtering

### **MongoDB Advantages:**
- **Flexible Schema**: Reviews can have varying fields
- **Horizontal Scaling**: Easy to scale for high-volume data
- **JSON Documents**: Native support for complex data structures
- **Aggregation Pipeline**: Powerful analytics capabilities
- **Fast Writes**: Excellent for logging and analytics

---

## 📚 **Environment Configuration**

### **To Switch to PostgreSQL:**
```bash
# Update .env file
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=food_beverage_db
DB_USERNAME=postgres
DB_PASSWORD=your_password

# Keep MongoDB config
MONGO_DB_HOST=127.0.0.1
MONGO_DB_PORT=27017
MONGO_DB_DATABASE=food_beverage_mongo

# Run migrations
php artisan migrate
php artisan db:seed
```

### **MongoDB Setup:**
```bash
# Install MongoDB PHP extension
# For Mac with Homebrew:
brew install mongodb/brew/mongodb-community
brew services start mongodb/brew/mongodb-community

# Install via Composer (already done)
composer require mongodb/laravel-mongodb

# Test connection
php artisan tinker
DB::connection('mongodb')->collection('test')->insert(['message' => 'Hello MongoDB!']);
```

---

## 🎯 **Best Practices**

1. **Use PostgreSQL for**: Transactions, relationships, critical business data
2. **Use MongoDB for**: Analytics, logs, flexible documents, caching
3. **Keep data consistency**: Sync important data between databases
4. **Monitor performance**: Use appropriate database for each use case
5. **Backup both**: Ensure both databases are backed up regularly

This dual-database architecture gives your ecommerce app the best of both worlds - relational data integrity and NoSQL flexibility! 🚀