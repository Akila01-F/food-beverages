<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Review extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'reviews';

    protected $fillable = [
        'product_id',
        'user_id',
        'rating',
        'title',
        'comment',
        'is_approved',
        'helpful_count'
    ];

    protected $casts = [
        'rating' => 'integer',
        'product_id' => 'integer',
        'user_id' => 'integer',
        'is_approved' => 'boolean',
        'helpful_count' => 'integer'
    ];

    // Relationship with user (PostgreSQL)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relationship with product (PostgreSQL)
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
