<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Plan;
use App\Models\Subscription;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Upload limits
        ini_set('upload_max_filesize', '200M');
        ini_set('post_max_size', '200M');

        // SVG Response Macro
        Response::macro('svg', function ($content) {
            return response($content, 200)
                ->header('Content-Type', 'image/svg+xml');
        });

        /*
        |--------------------------------------------------------------------------
        | Global View Data
        |--------------------------------------------------------------------------
        */

        View::composer('*', function ($view) {

            // 🔹 Active Pro Plan (cheapest)
            $proPlan = Plan::where('status', 'active')
                ->orderBy('amount')
                ->first();

            // 🔹 Default user state
            $isProUser = false;

            // 🔹 Check subscription only if logged in
            if (Auth::check()) {

                $userId = (string) Auth::user()->_id;

                $subscription = Subscription::where('user_id', $userId)
                    ->where('status', 'active')
                    ->where('expires_at', '>', now()) // ⭐ Correct expiry check
                    ->latest()
                    ->first();

                $isProUser = !!$subscription;
            }

            $view->with([
                'proPlan'   => $proPlan,
                'isProUser' => $isProUser,
            ]);
        });
    }
}
