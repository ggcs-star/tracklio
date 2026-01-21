<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class BroadcastGroup extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'broadcast_groups';

    protected $fillable = [
        'user_id',
        'name',
        'description',
    ];
}
