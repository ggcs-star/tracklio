<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class BioPageView extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'bio_page_views';

    protected $fillable = [
        'bio_page_id',
        'ip',
        'country',
        'city',
        'device',
        'platform',
        'browser',
        'referrer',
    ];

    public $timestamps = true;
}
