<?php

namespace App\Services\Social;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\SocialAccount;

class SocialDisconnectService
{
    public function handle(Request $request)
    {
        try {
            $request->validate(['platform' => 'required|string']);

            SocialAccount::where('user_id', auth()->id())
                ->where('platform', strtolower($request->platform))
                ->delete();
            
            \App\Models\Notification::create([
                'user_id' => (string) auth()->id(),
                'type'    => 'social_disconnected',
                'message' => ucfirst($request->platform) . ' account disconnected',
                'is_read' => false,
            ]);


            return back()->with('success', ucfirst($request->platform) . ' disconnected');
        } catch (\Throwable $e) {
            Log::error('Social account disconnect error', [
                'user_id'  => auth()->id(),
                'platform' => $request->platform ?? null,
                'error'    => $e->getMessage(),
                'trace'    => $e->getTraceAsString(),
            ]);

            return back()->with('error', 'Failed to disconnect account');
        }
    }
}
