<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plan;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        Plan::updateOrCreate(
            ['plan_key' => 'pro_monthly'], // Unique key
            [
                'name'           => 'Pro Monthly',
                'description'    => 'Monthly Pro subscription',
                'amount'         => 999,
                'currency'       => 'INR',
                'interval'       => 'month',
                'interval_count' => 1,
                'status'         => 'active',
            ]
        );
    }
}
