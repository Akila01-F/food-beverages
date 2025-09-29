<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Analytics extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'analytics';

    protected $fillable = [
        'event_type',
        'page_url',
        'user_id',
        'session_id',
        'ip_address',
        'user_agent',
        'referrer',
        'device_type',
        'browser',
        'os',
        'country',
        'city',
        'data'
    ];

    protected $casts = [
        'user_id' => 'integer',
        'data' => 'array'
    ];

    // Relationship with user (PostgreSQL)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Scope for filtering by event type
    public function scopeEventType($query, $eventType)
    {
        return $query->where('event_type', $eventType);
    }

    // Scope for date range
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    // Scope for page visits
    public function scopePageViews($query)
    {
        return $query->where('event_type', 'page_view');
    }

    // Scope for product views
    public function scopeProductViews($query)
    {
        return $query->where('event_type', 'product_view');
    }
}