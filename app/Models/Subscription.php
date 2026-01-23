<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Subscription extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'subscriptions';

    protected $fillable = [
        'user_id',

        // 🔥 LINK TO PLAN (VERY IMPORTANT)
        'plan_id',          // ObjectId of plans collection

        // Plan snapshot (at time of purchase)
        'plan_type',        // monthly / yearly
        'plan_name',        // Pro Monthly
        'price',            // 999
        'currency',         // INR
        'interval',         // month / year

        // Razorpay references
        'razorpay_plan_id',
        'razorpay_subscription_id',
        'razorpay_payment_id',

        // Status
        'status',           // pending | active | cancelled | failed
    ];

    protected $casts = [
        'price' => 'float',
    ];
}
