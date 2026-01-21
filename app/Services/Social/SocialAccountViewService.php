<?php

namespace App\Services\Social;

use Illuminate\Support\Facades\Log;
use App\Models\SocialAccount;

class SocialAccountViewService
{
    public function index()
    {
        try {
            $accounts = SocialAccount::where('user_id', auth()->id())->get();
            return view('accounts.index', compact('accounts'));
        } catch (\Throwable $e) {
            Log::error('Social account index error', [
                'user_id' => auth()->id(),
                'error'   => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);
            abort(500);
        }
    }
}
