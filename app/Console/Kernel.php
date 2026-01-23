<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\Subscription;
use App\Models\Notification;
use Carbon\Carbon;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule)
    {
        
        $schedule->command('facebook:hourly-stats')
            ->hourly()
            ->withoutOverlapping();

        $schedule->call(function () {
            \Log::info('Expiry reminder scheduler executed');
            $subs = Subscription::where('status', 'active')->get();

            foreach ($subs as $sub) {

                $start = Carbon::parse($sub->created_at);

                $expiry = $sub->interval === 'monthly'
                    ? $start->copy()->addMonth()
                    : $start->copy()->addYear();

                // ⏰ exactly 3 days before expiry
                if (now()->between(
                    $expiry->copy()->subDays(3)->startOfDay(),
                    $expiry->copy()->subDays(3)->endOfDay()
                )) {

                    Notification::firstOrCreate(
                        [
                            'user_id' => $sub->user_id,
                            'type'    => 'warning',
                            'message' => '⏰ Your Pro subscription will expire in 3 days. Renew to avoid interruption.',
                        ],
                        ['is_read' => false]
                    );
                }
            }

        })->daily(); 
    }

   
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
