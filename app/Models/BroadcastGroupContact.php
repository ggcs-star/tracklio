<?php
namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class BroadcastGroupContact extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'broadcast_group_contacts';

    protected $fillable = [
        'user_id',
        'group_id',
        'contact_id'
    ];
}
 