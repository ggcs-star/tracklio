<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Plan extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'plans';

    protected $fillable = [
        // Internal / app level
        'plan_key',          // pro_monthly, pro_yearly, etc.
        'name',              // Pro Monthly
        'description',       // Optional

        // Pricing
        'amount',            // 999
        'currency',          // INR

        // Billing cycle
        'interval',          // month | year
        'interval_count',    // 1

        // Razorpay
        'razorpay_plan_id',  // plan_xxxxxx

        // Status
        'status',            // active | inactive
    ];

    protected $casts = [
        'amount' => 'float',
        'interval_count' => 'integer',
    ];
}
