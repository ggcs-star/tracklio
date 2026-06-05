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
                'prompt' => 'select_account consent',
                'scope' => implode(' ', [
                    'https://www.googleapis.com/auth/youtube.upload',
                    'https://www.googleapis.com/auth/youtube',
                    'https://www.googleapis.com/auth/yt-analytics.readonly',
                    'https://www.googleapis.com/auth/youtube.readonly',
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

            $token = Http::timeout(60)->asForm()->post(
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

            // GET CHANNEL INFO
            $channel = Http::timeout(60)->withToken($token['access_token'])->get(
                    'https://www.googleapis.com/youtube/v3/channels',
                    [
                        'part' => 'id,snippet',
                        'mine' => true,
                    ]
                )->json();

            $channelId =
                $channel['items'][0]['id']
                ?? null;
            $channelName =
                $channel['items'][0]['snippet']['title']
                ?? 'YouTube Channel';

            $channelImage =
                $channel['items'][0]['snippet']['thumbnails']['default']['url']
                ?? null;

            $existing = SocialAccount::where('user_id', auth()->id())
    ->where('platform', 'youtube')
    ->get()
    ->first(function ($account) use ($channelId) {
        return data_get(
            $account->credentials,
            'channel_id'
        ) === $channelId;
    });

if ($existing) {

    $existing->update([
        'status' => 'connected',
        'credentials' => [

            'access_token' =>
                $token['access_token'],

            'refresh_token' =>
                $token['refresh_token'],

            'expires_at' =>
                now()
                ->addSeconds($token['expires_in'])
                ->toISOString(),

            'scope' =>
                $token['scope'] ?? '',

            'channel_id' =>
                $channelId,

            'channel_name' =>
                $channelName,

            'channel_image' =>
                $channelImage,
        ]
    ]);

} else {

    $newAccount = SocialAccount::create([
        'user_id' => auth()->id(),
        'platform' => 'youtube',
        'status' => 'connected',

        'credentials' => [

            'access_token' =>
                $token['access_token'],

            'refresh_token' =>
                $token['refresh_token'],

            'expires_at' =>
                now()
                ->addSeconds($token['expires_in'])
                ->toISOString(),

            'scope' =>
                $token['scope'] ?? '',

            'channel_id' =>
                $channelId,

            'channel_name' =>
                $channelName,

            'channel_image' =>
                $channelImage,
        ]
    ]);
}

            // NOTIFICATION
            \App\Models\Notification::create([
                'user_id' => (string) auth()->id(),
                'type'    => 'youtube_connected',
                'message' => 'YouTube account connected successfully',
                'is_read' => false,
            ]);

            return redirect()->route('accounts')
                ->with('youtube_connected_popup', true)
                ->with('youtube_connected_account_id',$existing ? $existing->id : $newAccount->id
            );

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
