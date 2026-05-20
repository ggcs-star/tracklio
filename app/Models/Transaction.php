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
        'razorpay_order_id',
        'amount',
        'currency',
        'status',
        'method',
        'upi_app',
        'upi_vpa',
        'card_last4',
        'card_network',
        'card_type',
        'bank',
        'paid_at',
        'raw',
    ];

    // 🔥 IMPORTANT FIX
    protected $casts = [
        'created_at' => 'datetime',   // ADD THIS
        'updated_at' => 'datetime',   // ADD THIS
        'paid_at'    => 'datetime',
        'raw'        => 'array',
    ];
}
