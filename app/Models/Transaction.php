<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Transaction extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'transactions';

    protected $fillable = [
        'user_id',
        'subscription_id',
        'plan_id',

        'razorpay_payment_id',
        'razorpay_subscription_id',
        'razorpay_order_id',

        'amount',
        'currency',
        'status',
        'method',

        // UPI
        'upi_app',
        'upi_vpa',

        // Card
        'card_last4',
        'card_network',
        'card_type',

        'bank',

        // ⏱️ DATE & TIME
        'paid_at',          // Razorpay payment time
        'created_at',       // Laravel auto
        'updated_at',

        'raw',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'raw' => 'array',
    ];
}
