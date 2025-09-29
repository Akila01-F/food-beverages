<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class ActivityLog extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'activity_logs';

    protected $fillable = [
        'user_id',
        'action',
        'model_type',
        'model_id',
        'ip_address',
        'user_agent',
        'session_id',
        'metadata'
    ];

    protected $casts = [
        'user_id' => 'integer',
        'model_id' => 'integer',
        'metadata' => 'array'
    ];

    // Relationship with user (PostgreSQL)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Scope for filtering by action
    public function scopeAction($query, $action)
    {
        return $query->where('action', $action);
    }

    // Scope for filtering by model
    public function scopeForModel($query, $modelType, $modelId = null)
    {
        $query = $query->where('model_type', $modelType);
        
        if ($modelId) {
            $query = $query->where('model_id', $modelId);
        }
        
        return $query;
    }
}