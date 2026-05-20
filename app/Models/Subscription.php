<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Carbon\Carbon;

class Subscription extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'subscriptions';

    protected $fillable = [
        'user_id',
        'plan_id',

        'plan_name',
        'price',
        'currency',
        'interval',           // month | year

        'razorpay_payment_id',

        'status',             // active | expired | cancelled

        'started_at',
        'expires_at',
        'cancelled_at',
    ];

    protected $casts = [
        'price' => 'float',
        'started_at' => 'datetime',
        'expires_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    /* =======================
       🔥 ACCESS HELPERS
    ======================= */

    public function isActive()
    {
        return $this->status === 'active'
            && $this->expires_at
            && $this->expires_at->isFuture();
    }

    public function isExpired()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function daysLeft()
    {
        return $this->expires_at
            ? now()->diffInDays($this->expires_at, false)
            : 0;
    }

    public function markExpired()
    {
        $this->update([
            'status' => 'expired'
        ]);
    }

    public function cancel()
    {
        $this->update([
            'status' => 'cancelled',
            'cancelled_at' => now()
        ]);
    }
}
