<?php
namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class WhatsAppCampaignSend extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'whatsapp_campaign_sends';

    protected $fillable = [
        'user_id',
        'campaign_id',
        'whatsapp_account_id',
        'template_name',
        'payload',
        'group_ids',
        'contact_ids',
        'sent',
        'failed',
        'created_at'
    ];

    protected $casts = [
        'payload' => 'array',
        'group_ids' => 'array',
        'contact_ids' => 'array'
    ];
}

