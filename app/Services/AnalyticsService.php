<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\SocialAccount;
use Carbon\Carbon;

class AnalyticsService
{
    protected string $graphVersion = 'v24.0';

    public function getAllAnalytics(string $userId, string $platformFilter = 'all', string $pageFilter = 'all', int $days = 7, string $instagramFilter = 'all', string $youtubeFilter = 'all'): array    {
        $response = [
            'totalReach' => 0,
            'totalEngagement' => 0,
            'totalClicks' => 0,
            'followerGrowth' => 0,
            'labels' => [],
            'engagementData' => [],
            'likesData' => [],
            'sharesData' => [],
            'reachTrendData' => [],
            'platformReach' => [0, 0, 0],
            'platformEngagement' => [0, 0, 0],
            'pages' => [],
            'recentActivity' => []
        ];

        for ($i = $days - 1; $i >= 0; $i--) {
            $response['labels'][] = Carbon::now()->subDays($i)->format('d M');
            $response['engagementData'][] = 0;
            $response['likesData'][] = 0;
            $response['sharesData'][] = 0;
        }

        $accounts = SocialAccount::where('user_id', $userId)
            ->where('status', 'connected')
            ->get()
            ->keyBy('platform');

        // FACEBOOK
        if (($platformFilter === 'all' || $platformFilter === 'facebook') && isset($accounts['facebook'])) {
            try {
                $fbData = $this->fetchFacebookAnalytics($accounts['facebook'], $pageFilter, $days, $userId);
                
                $response['totalReach'] += $fbData['reach'];
                $response['totalEngagement'] += $fbData['engagement'];
                $response['followerGrowth'] += $fbData['followers'];
                $response['platformReach'][0] = $fbData['reach'];
                $response['platformEngagement'][0] = $fbData['engagement'];
                $response['pages'] = $fbData['pages'];
                
                foreach ($fbData['engagementData'] as $i => $val) {
                    if (isset($response['engagementData'][$i])) {
                        $response['engagementData'][$i] += $val;
                        $response['reachTrendData'][$i] = ($response['reachTrendData'][$i] ?? 0) + $val;
                    }
                }
                
                foreach ($fbData['likesData'] as $i => $val) {
                    if (isset($response['likesData'][$i])) {
                        $response['likesData'][$i] += $val;
                    }
                }
                
                foreach ($fbData['sharesData'] as $i => $val) {
                    if (isset($response['sharesData'][$i])) {
                        $response['sharesData'][$i] += $val;
                    }
                }
                
                $response['recentActivity'] = array_merge($response['recentActivity'], $fbData['recentActivity']);
            } catch (\Throwable $e) {
                Log::error('Facebook failed', ['error' => $e->getMessage()]);
            }
        }

        // INSTAGRAM
        if (($platformFilter === 'all' || $platformFilter === 'instagram') && isset($accounts['instagram'])) {
            try {
                $igData = $this->fetchInstagramAnalytics($accounts['instagram'], $days, $instagramFilter);
                
                $response['totalReach'] += $igData['reach'];
                $response['totalEngagement'] += $igData['engagement'];
                $response['platformReach'][1] = $igData['reach'];
                $response['platformEngagement'][1] = $igData['engagement'];
                $response['followerGrowth'] += $igData['followers'];
                
                foreach ($igData['engagementData'] as $i => $val) {
                    if (isset($response['engagementData'][$i])) {
                        $response['engagementData'][$i] += $val;
                        $response['reachTrendData'][$i] = ($response['reachTrendData'][$i] ?? 0) + $val;
                    }
                }
                
                $response['recentActivity'] = array_merge($response['recentActivity'], $igData['recentActivity']);
            } catch (\Throwable $e) {
                Log::error('Instagram failed', ['error' => $e->getMessage()]);
            }
        }

        // YOUTUBE
        if (($platformFilter === 'all' || $platformFilter === 'youtube') && isset($accounts['youtube'])) {
            try {
                $ytData = $this->fetchYoutubeAnalytics($accounts['youtube'], $days, $youtubeFilter);
                
                $response['totalReach'] += $ytData['views'];
                $response['totalEngagement'] += $ytData['engagement'];
                $response['followerGrowth'] += $ytData['subscribers'];
                $response['platformReach'][2] = $ytData['views'];
                $response['platformEngagement'][2] = $ytData['engagement'];
                
                foreach ($ytData['engagementData'] as $i => $val) {
                    if (isset($response['engagementData'][$i])) {
                        $response['engagementData'][$i] += $val;
                        $response['reachTrendData'][$i] = ($response['reachTrendData'][$i] ?? 0) + ($ytData['viewsData'][$i] ?? 0);
                    }
                }
                
                foreach ($ytData['likesData'] as $i => $val) {
                    if (isset($response['likesData'][$i])) {
                        $response['likesData'][$i] += $val;
                    }
                }
                
                $response['recentActivity'] = array_merge($response['recentActivity'], $ytData['recentActivity']);
            } catch (\Throwable $e) {
                Log::error('YouTube failed', ['error' => $e->getMessage()]);
            }
        }

        usort($response['recentActivity'], function($a, $b) {
            return strtotime($b['timestamp']) - strtotime($a['timestamp']);
        });

        $response['recentActivity'] = array_slice($response['recentActivity'], 0, 15);
        // If reachData is empty but totalReach has value, generate fake daily data
        if (empty($fbData['reachData']) && $response['totalReach'] > 0) {
            $daysCount = count($response['labels']);
            $dailyAvg = round($response['totalReach'] / $daysCount);
            $response['reachData'] = array_fill(0, $daysCount, $dailyAvg);
        } else {
            $response['reachData'] = $fbData['reachData'] ?? [];
        }
        $response['reachTrendData'] = array_reverse($response['reachTrendData']);

        return $response;
    }

private function fetchFacebookAnalytics($account, string $pageFilter = 'all', int $days = 7, string $userId = ''): array    {
        $result = [
            'reach' => 0,
            'engagement' => 0,
            'followers' => 0,
            'pages' => $account->pages ?? [],
            'engagementData' => array_fill(0, $days, 0),
            'likesData' => array_fill(0, $days, 0),
            'sharesData' => array_fill(0, $days, 0),
            'recentActivity' => []
        ];

        if (empty($account->pages)) {
            return $result;
        }

        $since = Carbon::now()->subDays($days - 1)->startOfDay();
        $until = Carbon::now()->endOfDay();

        foreach ($account->pages as $page) {
            $pageId = $page['page_id'] ?? null;
            $token = $page['page_access_token'] ?? $account->credentials['page_access_token'] ?? $account->credentials['access_token'] ?? null;

            if (!$pageId || !$token) {
                continue;
            }

            if ($pageFilter !== 'all' && $pageId !== $pageFilter) {
                continue;
            }

            // Followers
            try {
                $followersRes = Http::timeout(10)->get("https://graph.facebook.com/{$this->graphVersion}/{$pageId}", [
                    'fields' => 'followers_count,name,fan_count',
                    'access_token' => $token
                ])->json();

                if (!isset($followersRes['error'])) {
                    $result['followers'] += $followersRes['followers_count'] ?? $followersRes['fan_count'] ?? 0;
                }
            } catch (\Throwable $e) {
                Log::warning('FB followers error', ['error' => $e->getMessage()]);
            }

            // Views/Reach
            try {
                $viewsRes = Http::timeout(10)->get("https://graph.facebook.com/{$this->graphVersion}/{$pageId}/insights", [
                    'metric' => 'page_views_total',
                    'period' => 'day',
                    'since' => $since->toDateString(),
                    'until' => $until->toDateString(),
                    'access_token' => $token
                ])->json();

                $reachSeries = [];
                $totalReach = 0;

                foreach ($viewsRes['data'][0]['values'] ?? [] as $row) {
                    $val = (int) ($row['value'] ?? 0);
                    $reachSeries[] = $val;
                    $totalReach += $val;
                }

                $result['reach'] += $totalReach;

                // Accumulate reachData for multiple pages (FIX for multi-account)
                if (!isset($result['reachData'])) {
                    $result['reachData'] = array_fill(0, $days, 0);
                }
                for ($i = 0; $i < min(count($reachSeries), $days); $i++) {
                    $result['reachData'][$i] += $reachSeries[$i] ?? 0;
                }
                    
                for ($i = 0; $i < min(count($reachSeries), $days); $i++) {
                    $result['engagementData'][$i] += $reachSeries[$i] ?? 0;
                }
            } catch (\Throwable $e) {
                Log::warning('FB views error', ['error' => $e->getMessage()]);
            }

            // Engagement
            try {
                $engRes = Http::timeout(10)->get("https://graph.facebook.com/{$this->graphVersion}/{$pageId}/insights", [
                    'metric' => 'page_post_engagements',
                    'period' => 'day',
                    'since' => $since->toDateString(),
                    'until' => $until->toDateString(),
                    'access_token' => $token
                ])->json();

                $engagement = 0;
                foreach ($engRes['data'][0]['values'] ?? [] as $row) {
                    $engagement += (int) ($row['value'] ?? 0);
                }

                $result['engagement'] += $engagement;
            } catch (\Throwable $e) {
                Log::warning('FB engagement error', ['error' => $e->getMessage()]);
            }

            // Likes
            try {
                $likesRes = Http::timeout(10)->get("https://graph.facebook.com/{$this->graphVersion}/{$pageId}/insights", [
                    'metric' => 'page_actions_post_reactions_total',
                    'period' => 'day',
                    'since' => $since->toDateString(),
                    'until' => $until->toDateString(),
                    'access_token' => $token
                ])->json();

                $likesSeries = [];
                foreach ($likesRes['data'][0]['values'] ?? [] as $row) {
                    $likesSeries[] = (int) ($row['value'] ?? 0);
                }

                for ($i = 0; $i < min(count($likesSeries), $days); $i++) {
                    $result['likesData'][$i] += $likesSeries[$i] ?? 0;
                }
            } catch (\Throwable $e) {
                Log::warning('FB likes error', ['error' => $e->getMessage()]);
            }

            // Shares
            try {
                $sharesRes = Http::timeout(10)->get("https://graph.facebook.com/{$this->graphVersion}/{$pageId}/insights", [
                    'metric' => 'page_post_shares',
                    'period' => 'day',
                    'since' => $since->toDateString(),
                    'until' => $until->toDateString(),
                    'access_token' => $token
                ])->json();

                $sharesSeries = [];
                foreach ($sharesRes['data'][0]['values'] ?? [] as $row) {
                    $sharesSeries[] = (int) ($row['value'] ?? 0);
                }

                for ($i = 0; $i < min(count($sharesSeries), $days); $i++) {
                    $result['sharesData'][$i] += $sharesSeries[$i] ?? 0;
                }
            } catch (\Throwable $e) {
                Log::warning('FB shares error', ['error' => $e->getMessage()]);
            }

            // Recent Posts - Database + Facebook API dono se
try {
    $dbPosts = \App\Models\Post::where('user_id', $userId)
        ->where('platforms', 'facebook')
        ->where('status', 'published')
        ->orderBy('created_at', 'desc')
        ->limit(10)
        ->get();
    
    foreach ($dbPosts as $dbPost) {
        // Try to fetch real reactions from Facebook API
        $reactions = 0;
        $comments = 0;
        $shares = 0;
        
        if ($dbPost->facebook_post_id) {
            try {
                $fbPost = Http::timeout(5)->get("https://graph.facebook.com/v24.0/{$dbPost->facebook_post_id}", [
                    'fields' => 'reactions.summary(true),comments.summary(true),shares',
                    'access_token' => $token
                ])->json();
                
                $reactions = $fbPost['reactions']['summary']['total_count'] ?? 0;
                $comments = $fbPost['comments']['summary']['total_count'] ?? 0;
                $shares = $fbPost['shares']['count'] ?? 0;
            } catch (\Throwable $e) {
                // Fallback to 0
            }
        }
        
        $mediaCount = count($dbPost->media_paths ?? []);
        $mediaIcon = $mediaCount > 0 ? '📷' : '📝';
        
        $result['recentActivity'][] = [
            'platform' => 'facebook',
            'platform_name' => 'Facebook',
            'id' => (string) $dbPost->_id,
            'title' => substr(strip_tags($dbPost->content ?? 'Facebook Post'), 0, 120),
            'description' => '👍 ' . number_format($reactions) . ' reactions | 💬 ' . number_format($comments) . ' comments | 🔄 ' . number_format($shares) . ' shares',
            'timestamp' => $dbPost->created_at->toIso8601String(),
            'created_at' => $dbPost->created_at->diffForHumans(),
            'media_url' => $dbPost->media_path ? asset('storage/' . $dbPost->media_path) : null,
            'icon' => 'fab fa-facebook-f',
            'icon_color' => 'blue',
            'bg_color' => 'bg-blue-100',
            'text_color' => 'text-blue-600',
            'insights' => [
                'reactions' => $reactions,
                'comments' => $comments,
                'shares' => $shares
            ]
        ];
    }
} catch (\Throwable $e) {
    Log::warning('DB posts error', ['error' => $e->getMessage()]);
}
        }

        $result['engagementData'] = array_reverse($result['engagementData']);
        $result['likesData'] = array_reverse($result['likesData']);
        $result['sharesData'] = array_reverse($result['sharesData']);
        
        return $result;
    }

    private function fetchInstagramAnalytics($account, int $days = 7, string $profileFilter = 'all'): array
    {
        $result = [
            'reach' => 0,
            'engagement' => 0,
            'followers' => 0,
            'engagementData' => array_fill(0, $days, 0),
            'recentActivity' => []
        ];

        $instagramAccounts = \App\Models\SocialAccount::where('user_id', $account->user_id)
            ->where('platform', 'instagram')
            ->where('status', 'connected')
            ->get();

        if ($instagramAccounts->isEmpty()) {
            return $result;
        }

        foreach ($instagramAccounts as $igAccount) {
             if ($profileFilter !== 'all' && (string) $igAccount->_id !== $profileFilter) {
                continue;
            }
            $businessId = $igAccount->credentials['instagram_business_id'] ?? null;
            $token = $igAccount->credentials['page_access_token'] ?? null;

            if (!$businessId || !$token) {
                continue;
            }

            try {
                $profile = Http::timeout(10)->get("https://graph.facebook.com/v24.0/{$businessId}", [
                    'fields' => 'followers_count,username',
                    'access_token' => $token
                ])->json();

                if (!isset($profile['error'])) {
                    $result['followers'] += $profile['followers_count'] ?? 0;
                }

                $since = Carbon::now()->subDays($days - 1)->startOfDay();
                $until = Carbon::now()->endOfDay();

                $insights = Http::timeout(10)->get("https://graph.facebook.com/v24.0/{$businessId}/insights", [
                    'metric' => 'reach,engagement',
                    'period' => 'day',
                    'since' => $since->toDateString(),
                    'until' => $until->toDateString(),
                    'access_token' => $token
                ])->json();

                if (!isset($insights['error'])) {
                    foreach ($insights['data'] ?? [] as $metric) {
                        $values = $metric['values'] ?? [];
                        if ($metric['name'] === 'reach') {
                            foreach ($values as $idx => $row) {
                                $val = (int) ($row['value'] ?? 0);
                                $result['reach'] += $val;
                                if ($idx < $days) {
                                    $result['engagementData'][$idx] += $val;
                                }
                            }
                        }
                        if ($metric['name'] === 'engagement') {
                            foreach ($values as $row) {
                                $result['engagement'] += (int) ($row['value'] ?? 0);
                            }
                        }
                    }
                }
            } catch (\Throwable $e) {
                Log::error('Instagram fetch failed: ' . $e->getMessage());
            }
        }

        $dbPosts = \App\Models\Post::where('user_id', $account->user_id)
            ->where('platforms', 'instagram')
            ->where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->limit(15)
            ->get();

        foreach ($dbPosts as $dbPost) {
            $mediaCount = count($dbPost->media_paths ?? []);
            $postType = $dbPost->ig_post_type ?? 'post';
            
            $typeIcon = '📷';
            if ($postType === 'reel') $typeIcon = '🎥';
            if ($postType === 'story') $typeIcon = '📸';
            
            $result['recentActivity'][] = [
                'platform' => 'instagram',
                'platform_name' => 'Instagram',
                'id' => (string) $dbPost->_id,
                'title' => substr(strip_tags($dbPost->content ?? 'Instagram Post'), 0, 120),
                'description' => $typeIcon . ' ' . ($mediaCount > 0 ? $mediaCount . ' media' : 'Text post'),
                'timestamp' => $dbPost->created_at->toIso8601String(),
                'created_at' => $dbPost->created_at->diffForHumans(),
                'media_url' => $dbPost->media_path ? asset('storage/' . $dbPost->media_path) : null,
                'icon' => 'fab fa-instagram',
                'icon_color' => 'pink',
                'bg_color' => 'bg-pink-100',
                'text_color' => 'text-pink-600',
                'insights' => [
                    'likes' => 0,
                    'comments' => 0,
                    'media_type' => $postType
                ]
            ];
        }

        $result['engagementData'] = array_reverse($result['engagementData']);
        
        return $result;
    }

    private function fetchYoutubeAnalytics($account, int $days = 7, string $channelFilter = 'all'): array
    {
        $result = [
            'views' => 0,
            'engagement' => 0,
            'subscribers' => 0,
            'likes' => 0,
            'comments' => 0,
            'engagementData' => array_fill(0, $days, 0),
            'likesData' => array_fill(0, $days, 0),
            'viewsData' => array_fill(0, $days, 0),
            'recentActivity' => []
        ];

        $youtubeAccounts = \App\Models\SocialAccount::where('user_id', $account->user_id)
            ->where('platform', 'youtube')
            ->where('status', 'connected')
            ->get();

        if ($youtubeAccounts->isEmpty()) {
            return $result;
        }

        foreach ($youtubeAccounts as $ytAccount) {
            if ($channelFilter !== 'all' && (string) $ytAccount->_id !== $channelFilter) {
                continue;
            }
            $creds = $ytAccount->credentials;

            if (empty($creds['refresh_token'])) {
                continue;
            }

            try {
                $tokenRes = Http::timeout(10)->asForm()->post(
                    'https://oauth2.googleapis.com/token',
                    [
                        'client_id' => env('YOUTUBE_CLIENT_ID'),
                        'client_secret' => env('YOUTUBE_CLIENT_SECRET'),
                        'refresh_token' => $creds['refresh_token'],
                        'grant_type' => 'refresh_token',
                    ]
                );

                if (!$tokenRes->successful()) {
                    continue;
                }

                $accessToken = $tokenRes->json('access_token');

                $channel = Http::timeout(10)->withToken($accessToken)->get(
                    'https://www.googleapis.com/youtube/v3/channels',
                    ['part' => 'statistics', 'mine' => 'true']
                )->json();

                $stats = $channel['items'][0]['statistics'] ?? [];
                $result['views'] += (int) ($stats['viewCount'] ?? 0);
                $result['subscribers'] += (int) ($stats['subscriberCount'] ?? 0);
                $result['likes'] += (int) ($stats['likeCount'] ?? 0);
                $result['comments'] += (int) ($stats['commentCount'] ?? 0);
                $result['engagement'] = $result['likes'] + $result['comments'];

                $videos = Http::timeout(10)->withToken($accessToken)->get(
                    'https://www.googleapis.com/youtube/v3/search',
                    [
                        'part' => 'snippet',
                        'forMine' => 'true',
                        'maxResults' => 15,
                        'type' => 'video',
                        'order' => 'date'
                    ]
                )->json();

                if (!empty($videos['items'])) {
                    $videoIds = array_column($videos['items'], 'id.videoId');
                    
                    $videoStats = Http::timeout(10)->withToken($accessToken)->get(
                        'https://www.googleapis.com/youtube/v3/videos',
                        [
                            'part' => 'statistics',
                            'id' => implode(',', $videoIds)
                        ]
                    )->json();

                    $statsMap = [];
                    foreach ($videoStats['items'] ?? [] as $stat) {
                        $statsMap[$stat['id']] = $stat['statistics'];
                    }

                    foreach ($videos['items'] as $video) {
                        $videoId = $video['id']['videoId'];
                        $vidStats = $statsMap[$videoId] ?? [];
                        
                        $views = (int) ($vidStats['viewCount'] ?? 0);
                        $likes = (int) ($vidStats['likeCount'] ?? 0);
                        $comments = (int) ($vidStats['commentCount'] ?? 0);

                        $dbPost = \App\Models\Post::where('youtube_video_id', $videoId)->first();

                        if ($dbPost) {
                            $result['recentActivity'][] = [
                                'platform' => 'youtube',
                                'platform_name' => 'YouTube',
                                'id' => (string) $dbPost->_id,
                                'title' => substr($video['snippet']['title'], 0, 100),
                                'description' => '🎬 ' . number_format($views) . ' views | ❤️ ' . number_format($likes) . ' likes',
                                'timestamp' => $video['snippet']['publishedAt'],
                                'created_at' => Carbon::parse($video['snippet']['publishedAt'])->diffForHumans(),
                                'media_url' => $video['snippet']['thumbnails']['high']['url'] ?? null,
                                'permalink' => "https://youtube.com/watch?v={$videoId}",
                                'icon' => 'fab fa-youtube',
                                'icon_color' => 'red',
                                'bg_color' => 'bg-red-100',
                                'text_color' => 'text-red-600',
                                'insights' => [
                                    'views' => $views,
                                    'likes' => $likes,
                                    'comments' => $comments
                                ]
                            ];
                        }
                    }
                }
            } catch (\Throwable $e) {
                Log::warning('YouTube fetch failed: ' . $e->getMessage());
            }
        }

        return $result;
    }
}