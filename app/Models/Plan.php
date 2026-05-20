<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Plan extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'plans';

    protected $fillable = [
        'plan_key',
        'name',
        'description',

        'amount',
        'currency',

        'interval',        // month | year
        'interval_count',

        'status',
    ];

    protected $casts = [
        'amount' => 'float',
        'interval_count' => 'integer',
    ];
}
