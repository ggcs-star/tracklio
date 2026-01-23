<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Currency
    |--------------------------------------------------------------------------
    */
    'currency' => 'INR',

    /*
    |--------------------------------------------------------------------------
    | Subscription Plans
    |--------------------------------------------------------------------------
    | This is the SINGLE SOURCE OF TRUTH for all plans.
    | Never trust price coming from Razorpay.
    |--------------------------------------------------------------------------
    */

    'plans' => [

        /*
        |--------------------------------------------------
        | Pro Monthly Plan
        |--------------------------------------------------
        */
        'pro_monthly' => [
            'plan_key'   => 'pro_monthly',

            'plan_type'  => 'monthly',
            'plan_name'  => 'Pro Monthly',

            'price'      => 999,        // ₹999
            'currency'   => 'INR',

            'interval'   => 'month',
            'interval_count' => 1,

            'total_count' => 12,         // 12 months subscription

            'razorpay_plan_id' => env('RAZORPAY_PRO_MONTHLY_PLAN_ID'),
        ],

        'pro_yearly' => [
            'plan_key'   => 'pro_yearly',

            'plan_type'  => 'yearly',
            'plan_name'  => 'Pro Yearly',

            'price'      => 9999,       // ₹9999
            'currency'   => 'INR',

            'interval'   => 'year',
            'interval_count' => 1,

            'total_count' => 1,          // 1 year

            'razorpay_plan_id' => env('RAZORPAY_PRO_YEARLY_PLAN_ID'),
        ],

    ],

];
        