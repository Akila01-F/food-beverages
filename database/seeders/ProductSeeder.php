<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $categories = Category::all();

        // Sample products for each category
        $sampleProducts = [
            'burgers' => [
                ['name' => 'Classic Beef Burger', 'price' => 12.99, 'discounted_price' => 10.99, 'is_featured' => true],
                ['name' => 'Chicken Deluxe Burger', 'price' => 14.99, 'is_featured' => false],
                ['name' => 'Spicy BBQ Burger', 'price' => 15.99, 'is_featured' => true],
            ],
            'pizza' => [
                ['name' => 'Margherita Pizza', 'price' => 18.99, 'discounted_price' => 16.99, 'is_featured' => true],
                ['name' => 'Pepperoni Supreme', 'price' => 22.99, 'is_featured' => true],
            ],
            'chinese' => [
                ['name' => 'Sweet & Sour Chicken', 'price' => 16.99, 'is_featured' => false],
                ['name' => 'Kung Pao Chicken', 'price' => 17.99, 'is_featured' => true],
            ],
            'snacks' => [
                ['name' => 'Loaded Nachos', 'price' => 9.99, 'is_featured' => false],
                ['name' => 'Buffalo Wings', 'price' => 13.99, 'is_featured' => true],
            ],
            'rice-and-curry' => [
                ['name' => 'Sri Lankan Rice & Curry', 'price' => 19.99, 'discounted_price' => 17.99, 'is_featured' => true],
                ['name' => 'Fish Curry Special', 'price' => 21.99, 'is_featured' => false],
            ],
            'beverages' => [
                ['name' => 'Fresh Orange Juice', 'price' => 4.99, 'is_featured' => false],
                ['name' => 'Iced Coffee Latte', 'price' => 5.99, 'is_featured' => true],
                ['name' => 'Mango Smoothie', 'price' => 6.99, 'discounted_price' => 5.99, 'is_featured' => false],
            ]
        ];

        foreach ($sampleProducts as $categorySlug => $products) {
            $category = $categories->where('slug', $categorySlug)->first();
            
            if ($category) {
                foreach ($products as $productData) {
                    Product::create([
                        'name' => $productData['name'],
                        'slug' => Str::slug($productData['name']),
                        'description' => 'Delicious ' . $productData['name'] . ' made with fresh ingredients.',
                        'price' => $productData['price'],
                        'discounted_price' => $productData['discounted_price'] ?? null,
                        'stock_quantity' => rand(20, 100),
                        'sku' => 'FB-' . strtoupper(Str::random(8)),
                        'category_id' => $category->id,
                        'is_active' => true,
                        'is_featured' => $productData['is_featured'],
                        'ingredients' => 'Fresh ingredients and spices',
                        'spice_level' => in_array($categorySlug, ['chinese', 'rice-and-curry']) ? 'medium' : 'mild',
                        'preparation_time' => rand(10, 30)
                    ]);
                }
            }
        }
    }
}
