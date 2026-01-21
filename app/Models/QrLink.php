<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class QrLink extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'qr_links';

    protected $fillable = [
        'user_id',
        'label',
        'original_url',
        'short_code',
        'short_url',
        'qr_image_path',

        // 📊 Analytics
        'visit_count',
        'qr_scan_count',

        'foreground_type',
        'foreground_color',
        'background_color',

        'gradient_start',
        'gradient_end',
        'gradient_dir',

        'qr_rotation',
        'logo_path',
        'design',
    ];

    protected $casts = [
        'visit_count'   => 'integer',
        'qr_scan_count' => 'integer', // ✅ added
        'qr_rotation'   => 'integer',
        'design'        => 'array',
    ];
}
