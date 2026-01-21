<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Notification extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'notifications';
 protected $primaryKey = '_id';     // 🔥 REQUIRED
    protected $keyType = 'string';     // 🔥 REQUIRED
    public $incrementing = false;
    protected $fillable = [
        'user_id',      // jis user ko notification mile
        'type',         // qr_scan | profile_view | system
        'title',        // short heading
        'message',      // full message
        'data',         // extra json (views count, qr id etc)
        'is_read',      // true / false
    ];

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
    ];
    
}
