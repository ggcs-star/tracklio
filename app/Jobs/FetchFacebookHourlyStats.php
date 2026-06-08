<?php

namespace App\Jobs;

use App\Models\SocialAccount;
use App\Models\SocialHourlyStat;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class FetchFacebookHourlyStats implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $fbVersion = 'v24.0';

    public function handle()
    {
        $time = now()->subHour();
        $date = $time->toDateString();
        $hour = $time->hour;

        // ========== 1. FACEBOOK ==========
        $fbAccounts = SocialAccount::where('platform', 'facebook')
            ->where('status', 'connected')
            ->get();

        foreach ($fbAccounts as $account) {
            foreach ($account->pages as $page) {
                $pageId = $page['page_id'];
                $token = $page['page_access_token'];

                if (!$pageId || !$token) continue;

                $info = Http::get("https://graph.facebook.com/{$this->fbVersion}/{$pageId}", [
                    'fields' => 'followers_count',
                    'access_token' => $token
                ])->json();

                $eng = Http::get("https://graph.facebook.com/{$this->fbVersion}/{$pageId}/insights", [
                    'metric' => 'page_post_engagements',
                    'period' => 'day',
                    'since' => $date,
                    'until' => $date,
                    'access_token' => $token
                ])->json();

                $engagement = collect($eng['data'][0]['values'] ?? [])->sum('value');

                $reachRes = Http::get("https://graph.facebook.com/{$this->fbVersion}/{$pageId}/insights", [
                    'metric' => 'page_impressions_unique',
                    'period' => 'day',
                    'since' => $date,
                    'until' => $date,
                    'access_token' => $token
                ])->json();

                $reach = collect($reachRes['data'][0]['values'] ?? [])->sum('value');

                SocialHourlyStat::updateOrCreate(
                    [
                        'platform' => 'facebook',
                        'page_id' => $pageId,
                        'stat_date' => $date,
                        'stat_hour' => $hour,
                    ],
                    [
                        'user_id' => (string) $account->user_id,
                        'reach' => (int) $reach,
                        'engagement' => (int) $engagement,
                        'followers' => (int) ($info['followers_count'] ?? 0),
                    ]
                );
            }
        }

        // ========== 2. INSTAGRAM ==========
        $igAccounts = SocialAccount::where('platform', 'instagram')
            ->where('status', 'connected')
            ->get();

        foreach ($igAccounts as $account) {
            $businessId = $account->credentials['instagram_business_id'] ?? null;
            $token = $account->credentials['page_access_token'] ?? null;

            if (!$businessId || !$token) continue;

            try {
                $profile = Http::get("https://graph.facebook.com/{$this->fbVersion}/{$businessId}", [
                    'fields' => 'followers_count,username',
                    'access_token' => $token
                ])->json();

                $followers = $profile['followers_count'] ?? 0;

                $insights = Http::get("https://graph.facebook.com/{$this->fbVersion}/{$businessId}/insights", [
                    'metric' => 'reach,likes,comments',
                    'period' => 'day',
                    'since' => $date,
                    'until' => $date,
                    'access_token' => $token
                ])->json();

                $reach = 0;
                $engagement = 0;

                foreach ($insights['data'] ?? [] as $metric) {
                    $values = $metric['values'] ?? [];
                    $metricName = $metric['name'] ?? '';

                    if ($metricName === 'reach') {
                        $reach = collect($values)->sum('value');
                    }
                    if ($metricName === 'likes' || $metricName === 'comments') {
                        $engagement += collect($values)->sum('value');
                    }
                }

                SocialHourlyStat::updateOrCreate(
                    [
                        'platform' => 'instagram',
                        'page_id' => $businessId,
                        'stat_date' => $date,
                        'stat_hour' => $hour,
                    ],
                    [
                        'user_id' => (string) $account->user_id,
                        'reach' => (int) $reach,
                        'engagement' => (int) $engagement,
                        'followers' => (int) $followers,
                    ]
                );

            } catch (\Throwable $e) {
                \Log::error('Instagram hourly fetch failed: ' . $e->getMessage());
            }
        }

        // ========== 3. YOUTUBE ==========
        $ytAccounts = SocialAccount::where('platform', 'youtube')
            ->where('status', 'connected')
            ->get();

        foreach ($ytAccounts as $account) {
            $creds = $account->credentials;

            if (empty($creds['refresh_token'])) continue;

            try {
                $tokenRes = Http::asForm()->post('https://oauth2.googleapis.com/token', [
                    'client_id' => env('YOUTUBE_CLIENT_ID'),
                    'client_secret' => env('YOUTUBE_CLIENT_SECRET'),
                    'refresh_token' => $creds['refresh_token'],
                    'grant_type' => 'refresh_token',
                ]);

                if (!$tokenRes->successful()) continue;

                $accessToken = $tokenRes->json('access_token');
                $channelId = $creds['channel_id'] ?? null;

                if (!$channelId) continue;

                $channel = Http::withToken($accessToken)->get('https://www.googleapis.com/youtube/v3/channels', [
                    'part' => 'statistics',
                    'id' => $channelId
                ])->json();

                $stats = $channel['items'][0]['statistics'] ?? [];
                $subscribers = (int) ($stats['subscriberCount'] ?? 0);
                $views = (int) ($stats['viewCount'] ?? 0);
                $likes = (int) ($stats['likeCount'] ?? 0);
                $comments = (int) ($stats['commentCount'] ?? 0);
                $engagement = $likes + $comments;

                SocialHourlyStat::updateOrCreate(
                    [
                        'platform' => 'youtube',
                        'page_id' => $channelId,
                        'stat_date' => $date,
                        'stat_hour' => $hour,
                    ],
                    [
                        'user_id' => (string) $account->user_id,
                        'reach' => (int) $views,
                        'engagement' => (int) $engagement,
                        'followers' => (int) $subscribers,
                    ]
                );

            } catch (\Throwable $e) {
                \Log::error('YouTube hourly fetch failed: ' . $e->getMessage());
            }
        }
    }
}