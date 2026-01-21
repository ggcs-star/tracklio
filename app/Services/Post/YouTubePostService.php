<?php

namespace App\Services\Post;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Post;
use App\Models\SocialAccount;

class YouTubePostService
{
    public function publish(Request $request, Post $post): void
    {
        if (!$request->hasFile('media')) {
            throw new \Exception('YouTube requires a video file');
        }

        $account = SocialAccount::forUser(auth()->user()->_id)
            ->where('platform', 'youtube')
            ->first();

        if (!$account) {
            throw new \Exception('YouTube account not connected');
        }

        $accessToken = $this->refreshToken($account);
        $video = $request->file('media');

        $init = Http::withToken($accessToken)
            ->asJson()
            ->post(
                'https://www.googleapis.com/upload/youtube/v3/videos?uploadType=resumable&part=snippet,status',
                [
                    'snippet' => [
                        'title' => $post->content ?: 'New Video',
                        'description' => $post->content ?? '',
                        'categoryId' => '22',
                    ],
                    'status' => ['privacyStatus' => 'public'],
                ]
            );

        if (!$init->successful()) {
            throw new \Exception('YouTube init failed');
        }

        $uploadUrl = $init->header('Location');

        Http::withToken($accessToken)
            ->withBody(
                file_get_contents($video->getRealPath()),
                'application/octet-stream'
            )
            ->put($uploadUrl);
    }

    private function refreshToken(SocialAccount $account): string
    {
        $creds = $account->credentials;

        $res = Http::asForm()->post(
            'https://oauth2.googleapis.com/token',
            [
                'client_id' => env('YOUTUBE_CLIENT_ID'),
                'client_secret' => env('YOUTUBE_CLIENT_SECRET'),
                'refresh_token' => $creds['refresh_token'],
                'grant_type' => 'refresh_token',
            ]
        );

        if (!$res->successful()) {
            throw new \Exception('Failed to refresh YouTube token');
        }

        return $res->json('access_token');
    }
}
