<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\Subscription;

class EnsureSubscriptionIsActive
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        $active = Subscription::where('user_id', (string)$user->_id)
            ->where('status', 'active')
            ->exists();

        if (! $active) {
            abort(403, 'Active subscription required.');
        }

        return $next($request);
    }
}
