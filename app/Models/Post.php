<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Post extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'posts';

    protected $fillable = [
        'user_id',
        'content',
        'media_url',
        'platforms',     
        'status',        
        'facebook_page_id', 
        'scheduled_at',     
    ];

    protected $dates = [
        'scheduled_at',
        'created_at',
        'updated_at',
    ];

    /* ===============================
       SCOPES
    =============================== */

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', (string) $userId);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /* ===============================
       HELPERS
    =============================== */

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }
}
