<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Razorpay\Api\Api;
use App\Models\Plan;

class SyncRazorpayPlans extends Command
{
    protected $signature = 'razorpay:sync-plans';
    protected $description = 'Sync Razorpay plans into database';

    public function handle()
    {
        $this->info('🔄 Syncing Razorpay plans...');

        $api = new Api(
            config('services.razorpay.key'),
            config('services.razorpay.secret')
        );

        $plans = $api->plan->all(['count' => 100]);

        if (empty($plans['items'])) {
            $this->warn('⚠️ No plans found on Razorpay');
            return;
        }

        foreach ($plans['items'] as $plan) {

            Plan::updateOrCreate(
                ['razorpay_plan_id' => $plan['id']],
                [
                    'plan_key' => strtolower(
                        str_replace(' ', '_', $plan['item']['name'] . '_' . $plan['period'])
                    ),
                    'name' => $plan['item']['name'],
                    'amount' => $plan['item']['amount'] / 100,
                    'currency' => $plan['item']['currency'],
                    'interval' => $plan['period'],
                    'interval_count' => $plan['interval'],
                    'status' => 'active',
                ]
            );

            $this->line("✔ Synced: {$plan['item']['name']}");
        }

        $this->info('✅ Razorpay plans synced successfully');
    }
}
