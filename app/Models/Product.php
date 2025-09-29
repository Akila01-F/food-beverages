<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'discounted_price',
        'stock_quantity',
        'sku',
        'images',
        'category_id',
        'is_active',
        'is_featured',
        'ingredients',
        'spice_level',
        'preparation_time'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discounted_price' => 'decimal:2',
        'images' => 'array',
        'is_featured' => 'boolean',
        'is_active' => 'boolean'
    ];

    // Boot method to auto-generate slug
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
                
                // Ensure slug is unique
                $count = 1;
                $originalSlug = $product->slug;
                while (static::where('slug', $product->slug)->exists()) {
                    $product->slug = $originalSlug . '-' . $count++;
                }
            }
            
            if (empty($product->sku)) {
                $product->sku = 'PROD-' . strtoupper(Str::random(8));
            }
        });
        
        static::updating(function ($product) {
            if ($product->isDirty('name') && !$product->isDirty('slug')) {
                $product->slug = Str::slug($product->name);
                
                // Ensure slug is unique (excluding current product)
                $count = 1;
                $originalSlug = $product->slug;
                while (static::where('slug', $product->slug)->where('id', '!=', $product->id)->exists()) {
                    $product->slug = $originalSlug . '-' . $count++;
                }
            }
        });
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeAvailable($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    // Get the final price (discounted price if available, otherwise regular price)
    public function getFinalPriceAttribute()
    {
        return $this->discounted_price ?? $this->price;
    }

    // Check if product has discount
    public function getHasDiscountAttribute()
    {
        return !is_null($this->discounted_price) && $this->discounted_price < $this->price;
    }

    // Get discount percentage
    public function getDiscountPercentageAttribute()
    {
        if ($this->has_discount) {
            return round((($this->price - $this->discounted_price) / $this->price) * 100);
        }
        return 0;
    }

    // Get formatted price
    public function getFormattedPriceAttribute()
    {
        return '$' . number_format($this->price, 2);
    }

    // Get formatted final price
    public function getFormattedFinalPriceAttribute()
    {
        return '$' . number_format($this->final_price, 2);
    }

    // Check if product is in stock
    public function getInStockAttribute()
    {
        return $this->stock_quantity > 0;
    }

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
