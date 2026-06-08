<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class LocationMapping extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'location_mappings';

    protected $fillable = [
        'osm_id',
        'facebook_place_id',
        'location_name',
        'full_address',
        'lat',
        'lon',
        'search_count',
    ];
}