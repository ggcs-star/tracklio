<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Subscription extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'subscriptions';

    protected $fillable = [
        'user_id',
        'razorpay_subscription_id',
        'razorpay_payment_id',
        'status', // pending | active | cancelled
    ];
}
