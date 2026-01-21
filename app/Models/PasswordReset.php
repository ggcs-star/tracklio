<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class PasswordReset extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'password_resets';

    protected $fillable = [
        'email',
        'token',
        'expires_at',
    ];

    protected $dates = ['expires_at'];
}
