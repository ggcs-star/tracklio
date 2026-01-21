<?php

namespace App\Services\Social;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\SocialAccount;

class YouTubeConnectService
{
    public function connect()
    {
        try {
            $authUrl = 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query([
                'client_id'     => env('YOUTUBE_CLIENT_ID'),
                'redirect_uri'  => env('YOUTUBE_REDIRECT_URI'),
                'response_type' => 'code',
                'access_type'   => 'offline',
                'prompt'        => 'consent',
                'scope' => implode(' ', [
                    'https://www.googleapis.com/auth/youtube.upload',
                    'https://www.googleapis.com/auth/youtube'
                ]),
            ]);

            return redirect($authUrl);
        } catch (\Throwable $e) {
            Log::critical('YouTube connect init failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->with('error', 'Failed to connect YouTube');
        }
    }

    public function callback(Request $request)
    {
        try {
            if (!$request->code) {
                return redirect()->route('accounts')
                    ->with('error', 'YouTube authorization failed');
            }

            $token = Http::asForm()->post(
                'https://oauth2.googleapis.com/token',
                [
                    'code'          => $request->code,
                    'client_id'     => env('YOUTUBE_CLIENT_ID'),
                    'client_secret' => env('YOUTUBE_CLIENT_SECRET'),
                    'redirect_uri'  => env('YOUTUBE_REDIRECT_URI'),
                    'grant_type'    => 'authorization_code',
                ]
            )->json();

            if (empty($token['refresh_token'])) {
                return redirect()->route('accounts')
                    ->with('error', 'YouTube refresh token missing');
            }

            SocialAccount::updateOrCreate(
                ['user_id' => auth()->id(), 'platform' => 'youtube'],
                [
                    'status' => 'connected',
                    'credentials' => [
                        'access_token'  => $token['access_token'],
                        'refresh_token' => $token['refresh_token'],
                        'expires_at'    => now()->addSeconds($token['expires_in'])->toISOString(),
                        'scope'         => $token['scope'] ?? '',
                    ],
                ]
            );
            // 🔔 NOTIFICATION – YOUTUBE CONNECTED
\App\Models\Notification::create([
    'user_id' => (string) auth()->id(),
    'type'    => 'youtube_connected',
    'message' => 'YouTube account connected successfully',
    'is_read' => false,
]);


            return redirect()->route('accounts')
                ->with('success', 'YouTube connected successfully');
        } catch (\Throwable $e) {
            Log::critical('YouTube callback failed', [
                'user_id' => auth()->id(),
                'error'   => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return redirect()->route('accounts')
                ->with('error', 'YouTube connection failed');
        }
    }
}
