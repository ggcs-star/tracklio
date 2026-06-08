<?php

namespace App\Services\Post;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Post;
use App\Models\SocialAccount;
use App\Helpers\TextFormatter;

class YouTubePostService
{
    public function publish(Request $request, Post $post): void
    {
        
        $account = SocialAccount::find(
            $post->youtube_account_id
        );

        if (!$account) {
            throw new \Exception('YouTube account not connected');
        }

        $accessToken = $this->refreshToken($account);
        $videoPath = null;

        if ($request->hasFile('media')) {
            $videoPath = $request->file('media')->getRealPath();
        } elseif ($request->hasFile('media_files')) {
            $files = $request->file('media_files');
            if (count($files) > 0) {
                $videoPath = $files[0]->getRealPath();
            }
        } elseif ($post->media_path) {
            $videoPath = storage_path('app/public/' . $post->media_path);
        } elseif (!empty($post->media_paths)) {
            $videoPath = storage_path('app/public/' . $post->media_paths[0]);
        }

        if (!$videoPath) {
            throw new \Exception('YouTube requires a video file');
        }

        $init = Http::withToken($accessToken)
            ->asJson()
            ->post(
                'https://www.googleapis.com/upload/youtube/v3/videos?uploadType=resumable&part=snippet,status',
                [
                    'snippet' => [
                        'title' => $post->content ? TextFormatter::convert($post->content) : 'New Video',
                        'description' => TextFormatter::convert($post->content ?? ''),
                        'categoryId' => '22',
                    ],
                    'status' => ['privacyStatus' => 'public'],
                ]
            );

        if (!$init->successful()) {
            throw new \Exception('YouTube init failed');
        }

        $uploadUrl = $init->header('Location');

        $upload = Http::withToken($accessToken)
            ->withBody(
                file_get_contents($videoPath),
                'application/octet-stream'
            )
            ->put($uploadUrl);

            if (!$upload->successful()) {

    \Log::error(
        'YOUTUBE UPLOAD FAILED',
        [
            'response' =>
                $upload->body()
        ]
    );

        throw new \Exception(
            'YouTube upload failed'
        );
    }

        $responseData = $upload->json();
        $videoId = $responseData['id'] ?? null;

        if ($videoId) {
            $post->youtube_video_id = $videoId;
            $post->save();
        }
    }

    private function refreshToken(SocialAccount $account): string
        {
            $creds = $account->credentials;

            $res = Http::timeout(60)->asForm()->post(
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
