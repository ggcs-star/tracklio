<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\Subscription;
use Carbon\Carbon;

class EnsureSubscriptionIsActive
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if (! $user) {
            abort(401, 'Unauthorized');
        }

        $subscription = Subscription::where('user_id', (string) $user->_id)
            ->where('status', 'active')
            ->first();

        if (! $subscription) {
            abort(403, 'Active subscription required.');
        }

        // 🔥 Expiry check
        if ($subscription->expires_at && Carbon::parse($subscription->expires_at)->isPast()) {

            // Auto-mark expired
            $subscription->update(['status' => 'expired']);

            abort(403, 'Subscription expired. Please renew.');
        }

        return $next($request);
    }
}
