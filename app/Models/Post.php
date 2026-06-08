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
        'media_path',
        'media_paths',
        'platforms',     
        'status',        
        'facebook_page_id', 
        'facebook_post_id',
        'facebook_post_type',
        'instagram_post_id',
        'youtube_video_id',
        'youtube_account_id',
        'ig_post_type',
        'scheduled_at',
        'published_at',
        'error_message',   
        'location_id',
        'location_name', 
        'instagram_profile_id',
    ];

    protected $dates = [
        'scheduled_at',
        'created_at',
        'updated_at',
        
    ];
    // protected $casts = [
    //     'media_paths' => 'array',
    // ];
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
