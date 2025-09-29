<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Burgers',
                'slug' => 'burgers',
                'description' => 'Delicious beef, chicken, and veggie burgers with fresh ingredients',
                'image' => 'categories/burgers.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Pizza',
                'slug' => 'pizza',
                'description' => 'Authentic wood-fired pizzas with premium toppings',
                'image' => 'categories/pizza.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Chinese',
                'slug' => 'chinese',
                'description' => 'Traditional Chinese dishes with authentic flavors',
                'image' => 'categories/chinese.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Snacks',
                'slug' => 'snacks',
                'description' => 'Quick bites and finger foods for any time of day',
                'image' => 'categories/snacks.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Rice and Curry',
                'slug' => 'rice-and-curry',
                'description' => 'Traditional Sri Lankan rice and curry meals',
                'image' => 'categories/rice-curry.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Beverages',
                'slug' => 'beverages',
                'description' => 'Refreshing drinks, juices, and hot beverages',
                'image' => 'categories/beverages.jpg',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}