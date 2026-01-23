<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    
    public function register(): void
    {
        
    }

   
    public function boot(): void
    {
        ini_set('upload_max_filesize', '200M');
        ini_set('post_max_size', '200M');

        Response::macro('svg', function ($content) {
            return response($content, 200)
                ->header('Content-Type', 'image/svg+xml');
        });

        View::composer('*', function ($view) {

            $proPlan = Plan::where('status', 'active')
                ->orderBy('amount') 
                ->first();

            $view->with('proPlan', $proPlan);
        });

        View::composer('*', function ($view) {

            if (!Auth::check()) {
                $view->with('isProUser', false);
                return;
            }

            $userId = (string) Auth::user()->_id;

            $subscription = Subscription::where('user_id', $userId)
                ->where('status', 'active')
                ->latest()
                ->first();

            if (!$subscription) {
                $view->with('isProUser', false);
                return;
            }

            $startDate = Carbon::parse($subscription->created_at);

            if ($subscription->interval === 'monthly') {
                $expiryDate = $startDate->copy()->addMonth();
            } elseif ($subscription->interval === 'yearly') {
                $expiryDate = $startDate->copy()->addYear();
            } else {
                $view->with('isProUser', false);
                return;
            }

            $view->with('isProUser', now()->lt($expiryDate));
        });
    }
}
