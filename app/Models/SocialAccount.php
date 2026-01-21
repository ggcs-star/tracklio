<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class SocialAccount extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'social_accounts';

    protected $fillable = [
        'user_id',
        'platform',
        'status',
        'credentials', 
        'pages',       
    ];

    protected $casts = [
        'credentials' => 'array',
        'pages'       => 'array',
    ];

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', (string) $userId);
    }

    public function scopeConnected($query)
    {
        return $query->where('status', 'connected');
    }

    public function facebookPages(): array
    {
        return $this->pages ?? [];
    }

    public function userToken(): ?string
    {
        return $this->credentials['user_access_token'] ?? null;
    }
}
