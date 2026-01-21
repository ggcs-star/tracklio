<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class QrClickLog extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'qr_click_logs';

    protected $fillable = [
        'qr_id',
        'short_code',
        'type',         
        'ip_address',
        'city',
        'country',
        'device_type',  
        'browser',
    ];
}
