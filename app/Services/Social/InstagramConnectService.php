<?php

namespace App\Services\Social;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\SocialAccount;

class InstagramConnectService
{
    public function connect()
    {
        try {
            $businessId  = env('INSTAGRAM_BUSINESS_ID');
            $accessToken = env('INSTAGRAM_ACCESS_TOKEN');

            if (!$businessId || !$accessToken) {
                return back()->with('error', 'Instagram Business ID or Access Token missing in .env');
            }

            $check = Http::get(
                "https://graph.facebook.com/v19.0/{$businessId}",
                [
                    'fields' => 'id,username',
                    'access_token' => $accessToken,
                ]
            );

            if (!$check->successful()) {
                return back()->with('error', 'Invalid Instagram access token');
            }

            SocialAccount::updateOrCreate(
                ['user_id' => auth()->id(), 'platform' => 'instagram'],
                [
                    'status' => 'connected',
                    'credentials' => [
                        'instagram_business_id' => $businessId,
                        'access_token' => $accessToken,
                        'username' => $check->json('username'),
                    ],
                ]
            );
            
                \App\Models\Notification::create([
                    'user_id' => (string) auth()->id(),
                    'type'    => 'instagram_connected',
                    'message' => 'Instagram account connected successfully',
                    'is_read' => false,
                ]);


            return back()->with('success', 'Instagram connected successfully ');
        } catch (\Throwable $e) {
            Log::error('Instagram connect error', [
                'user_id' => auth()->id(),
                'error'   => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return back()->with('error', 'Failed to connect Instagram');
        }
    }
}
