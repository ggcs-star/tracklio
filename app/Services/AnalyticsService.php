<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\SocialAccount;
use App\Models\Post;
use Carbon\Carbon;

class AnalyticsService
{
    protected string $graphVersion = 'v24.0';

    public function getAllAnalytics(string $userId, string $platformFilter = 'all', string $pageFilter = 'all', int $days = 7, string $instagramFilter = 'all', string $youtubeFilter = 'all'): array    
    {
        $response = [
            'totalReach' => 0,
            'totalEngagement' => 0,
            'totalClicks' => 0,
            'followerGrowth' => 0,
            'labels' => [],
            'engagementData' => [],
            'likesData' => [],
            'sharesData' => [],
            'reachData' => [],
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
            $response['reachData'][] = 0;
        }

        $accounts = SocialAccount::where('user_id', $userId)
            ->where('status', 'connected')
            ->get()
            ->keyBy('platform');

        if (($platformFilter === 'all' || $platformFilter === 'facebook') && isset($accounts['facebook'])) {
            try {
                $fbData = $this->fetchFacebookAnalytics($accounts['facebook'], $pageFilter, $days, $userId);
                
                $response['totalReach'] += $fbData['reach'];
                $response['totalEngagement'] += $fbData['engagement'];
                $response['followerGrowth'] += $fbData['followers'];
                $response['platformReach'][0] = $fbData['reach'];
                $response['platformEngagement'][0] = $fbData['engagement'];
                $response['pages'] = $fbData['pages'];
                
                for ($i = 0; $i < $days; $i++) {
                    if (isset($fbData['reachData'][$i])) {
                        $response['reachData'][$i] += $fbData['reachData'][$i];
                    }
                    if (isset($fbData['engagementData'][$i])) {
                        $response['engagementData'][$i] += $fbData['engagementData'][$i];
                    }
                    if (isset($fbData['likesData'][$i])) {
                        $response['likesData'][$i] += $fbData['likesData'][$i];
                    }
                    if (isset($fbData['sharesData'][$i])) {
                        $response['sharesData'][$i] += $fbData['sharesData'][$i];
                    }
                }
                
$response['recentActivity'] = array_merge($response['recentActivity'], $fbData['recentActivity']);            } catch (\Throwable $e) {
                Log::error('Facebook failed', ['error' => $e->getMessage()]);
            }
        }

        if (($platformFilter === 'all' || $platformFilter === 'instagram') && isset($accounts['instagram'])) {
            try {
                $igData = $this->fetchInstagramAnalytics($accounts['instagram'], $days, $instagramFilter);
                
                $response['totalReach'] += $igData['reach'];
                $response['totalEngagement'] += $igData['engagement'];
                $response['platformReach'][1] = $igData['reach'];
                $response['platformEngagement'][1] = $igData['engagement'];
                $response['followerGrowth'] += $igData['followers'];
                
                for ($i = 0; $i < $days; $i++) {
                    if (isset($igData['reachData'][$i])) {
                        $response['reachData'][$i] += $igData['reachData'][$i];
                    }
                    if (isset($igData['engagementData'][$i])) {
                        $response['engagementData'][$i] += $igData['engagementData'][$i];
                    }
                }
                
                $response['recentActivity'] = array_merge($response['recentActivity'], $igData['recentActivity']);
            } catch (\Throwable $e) {
                Log::error('Instagram failed', ['error' => $e->getMessage()]);
            }
        }

        if (($platformFilter === 'all' || $platformFilter === 'youtube') && isset($accounts['youtube'])) {
            try {
                $ytData = $this->fetchYoutubeAnalytics($accounts['youtube'], $days, $youtubeFilter);
                
                $response['totalReach'] += $ytData['views'];
                $response['totalEngagement'] += $ytData['engagement'];
                $response['followerGrowth'] += $ytData['subscribers'];
                $response['platformReach'][2] = $ytData['views'];
                $response['platformEngagement'][2] = $ytData['engagement'];
                
                for ($i = 0; $i < $days; $i++) {
                    if (isset($ytData['viewsData'][$i])) {
                        $response['reachData'][$i] += $ytData['viewsData'][$i];
                    }
                    if (isset($ytData['engagementData'][$i])) {
                        $response['engagementData'][$i] += $ytData['engagementData'][$i];
                    }
                    if (isset($ytData['likesData'][$i])) {
                        $response['likesData'][$i] += $ytData['likesData'][$i];
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

        $seenIds = [];
        $uniqueActivity = [];
        foreach ($response['recentActivity'] as $activity) {
            if (!isset($activity['id'])) {
                $uniqueActivity[] = $activity;
                continue;
            }
            $key = $activity['platform'] . '_' . $activity['id'];
            if (!in_array($key, $seenIds)) {
                $seenIds[] = $key;
                $uniqueActivity[] = $activity;
            }
        }
        $response['recentActivity'] = $uniqueActivity;

        $response['recentActivity'] = array_slice($response['recentActivity'], 0, 15);

        return $response;
    }

    private function fetchFacebookAnalytics($account, string $pageFilter = 'all', int $days = 7, string $userId = ''): array    
    {
        $result = [
            'reach' => 0,
            'engagement' => 0,
            'followers' => 0,
            'pages' => $account->pages ?? [],
            'reachData' => array_fill(0, $days, 0),
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

            $followersRes = Http::timeout(10)->get("https://graph.facebook.com/{$this->graphVersion}/{$pageId}", [
                'fields' => 'followers_count,name,fan_count',
                'access_token' => $token
            ])->json();

            if (!isset($followersRes['error'])) {
                $result['followers'] += $followersRes['followers_count'] ?? $followersRes['fan_count'] ?? 0;
            }

            $viewsRes = Http::timeout(10)->get("https://graph.facebook.com/{$this->graphVersion}/{$pageId}/insights", [
                'metric' => 'page_impressions_unique',
                'period' => 'day',
                'since' => $since->toDateString(),
                'until' => $until->toDateString(),
                'access_token' => $token
            ])->json();
            Log::info('FB VIEWS RESPONSE', $viewsRes);
            $values = $viewsRes['data'][0]['values'] ?? [];
            $totalReach = 0;
            
            foreach ($values as $idx => $row) {
                $val = (int) ($row['value'] ?? 0);
                $totalReach += $val;
                if ($idx < $days) {
                    $result['reachData'][$idx] += $val;
                }
            }
            
            $result['reach'] += $totalReach;

            // $engRes = Http::timeout(10)->get(
            //     "https://graph.facebook.com/{$this->graphVersion}/{$pageId}/insights",
            //     [
            //         'metric' => 'page_engaged_users',
            //         'period' => 'day',
            //         'since' => $since->toDateString(),
            //         'until' => $until->toDateString(),
            //         'access_token' => $token
            //     ]
            // )->json();

            // Log::info('FB ENG RESPONSE', $engRes);
            $engagement = 0;
            foreach ($engRes['data'][0]['values'] ?? [] as $idx => $row) {
                $val = (int) ($row['value'] ?? 0);
                $engagement += $val;
                if ($idx < $days) {
                    $result['engagementData'][$idx] += $val;
                }
            }

            $result['engagement'] += $engagement;

            $likesRes = Http::timeout(10)->get("https://graph.facebook.com/{$this->graphVersion}/{$pageId}/insights", [
                'metric' => 'page_actions_post_reactions_total',
                'period' => 'day',
                'since' => $since->toDateString(),
                'until' => $until->toDateString(),
                'access_token' => $token
            ])->json();

            foreach ($likesRes['data'][0]['values'] ?? [] as $idx => $row) {
                if ($idx < $days) {
                    $result['likesData'][$idx] += (int) ($row['value'] ?? 0);
                }
            }

            $sharesRes = Http::timeout(10)->get("https://graph.facebook.com/{$this->graphVersion}/{$pageId}/insights", [
                'metric' => 'page_post_shares',
                'period' => 'day',
                'since' => $since->toDateString(),
                'until' => $until->toDateString(),
                'access_token' => $token
            ])->json();

            foreach ($sharesRes['data'][0]['values'] ?? [] as $idx => $row) {
                if ($idx < $days) {
                    $result['sharesData'][$idx] += (int) ($row['value'] ?? 0);
                }
            }

            $dbPosts = Post::where('user_id', $userId)
                ->where('platforms', 'facebook')
                ->where('status', 'published')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
            
            foreach ($dbPosts as $dbPost) {
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
                        $result['engagement'] += ($reactions + $comments + $shares);
                        $result['engagementData'][$days - 1] += ($reactions + $comments + $shares);
                    } catch (\Throwable $e) {
                        // Fallback
                    }
                }
                
                $result['recentActivity'][] = [
                    'platform' => 'facebook',
                    'platform_name' => 'Facebook',
                    'id' => (string) $dbPost->_id,
                    'title' => substr(strip_tags($dbPost->content ?? 'Facebook Post'), 0, 120),
                    'description' => '👍 ' . number_format($reactions) . ' reactions | 💬 ' . number_format($comments) . ' comments',
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
        }

        return $result;
    }

    private function fetchInstagramAnalytics($account, int $days = 7, string $profileFilter = 'all'): array
{
    $result = [
        'reach' => 0,
        'engagement' => 0,
        'followers' => 0,
        'reachData' => array_fill(0, $days, 0),
        'engagementData' => array_fill(0, $days, 0),
        'recentActivity' => []
    ];

    $instagramAccounts = SocialAccount::where('user_id', $account->user_id)
        ->where('platform', 'instagram')
        ->where('status', 'connected')
        ->get();

    if ($instagramAccounts->isEmpty()) {
        return $result;
    }

    $since = Carbon::now()->subDays($days - 1)->startOfDay();
    $until = Carbon::now()->endOfDay();

    foreach ($instagramAccounts as $igAccount) {
        if ($profileFilter !== 'all' && (string) $igAccount->_id !== $profileFilter) {
            continue;
        }
        
        $businessId = $igAccount->credentials['instagram_business_id'] ?? null;
        $token = $igAccount->credentials['page_access_token'] ?? $igAccount->credentials['access_token'] ?? null;

        if (!$businessId || !$token) {
            continue;
        }

        try {
            // USE v20.0 INSTEAD OF v24.0
            $profile = Http::timeout(10)->get("https://graph.facebook.com/v20.0/{$businessId}", [
                'fields' => 'followers_count,username',
                'access_token' => $token
            ])->json();

            if (!isset($profile['error'])) {
                $result['followers'] += $profile['followers_count'] ?? 0;
            }

            $insights = Http::timeout(15)->get(
                "https://graph.facebook.com/v24.0/{$businessId}/insights",
                [
                    'metric' => 'reach',
                    'period' => 'day',
                    'since' => $since->toDateString(),
                    'until' => $until->toDateString(),
                    'access_token' => $token
                ]
            )->json();
            Log::info('IG INSIGHTS', $insights);
            if (!isset($insights['error']) && !empty($insights['data'])) {
                foreach ($insights['data'] as $metric) {
                    $values = $metric['values'] ?? [];
                    $metricName = $metric['name'] ?? '';
                    
                    $valuesReversed = array_reverse($values);
                    
                    if ($metricName === 'reach') {
                        foreach ($valuesReversed as $idx => $row) {
                            $val = (int) ($row['value'] ?? 0);
                            if ($idx < $days) {
                                $result['reachData'][$idx] += $val;
                                $result['reach'] += $val;
                            }
                        }
                    }
                    
                    
                }
            }
            

        $dbPosts = Post::where('user_id', $account->user_id)
            ->where('platforms', 'instagram')
            ->where('status', 'published')
            ->whereNotNull('instagram_post_id')
            ->orderBy('created_at', 'desc')
            ->limit(15)
            ->get();

        foreach ($dbPosts as $dbPost) {
            $likes = 0;
            $comments = 0;
            $mediaType = $dbPost->ig_post_type ?? 'post';
            $typeIcon = '📷';
            if ($mediaType === 'reel') $typeIcon = '🎥';
            if ($mediaType === 'story') $typeIcon = '📸';
            
            if ($dbPost->instagram_post_id && $token) {
                try {
                    $igPost = Http::timeout(5)->get("https://graph.facebook.com/v20.0/{$dbPost->instagram_post_id}", [
                        'fields' => 'like_count,comments_count',
                        'access_token' => $token
                    ])->json();
                    Log::info('Instagram Post Analytics', [
                        'post_id' => $dbPost->instagram_post_id,
                        'response' => $igPost
                    ]);
                    
                    $likes = $igPost['like_count'] ?? 0;
                    $comments = $igPost['comments_count'] ?? 0;
                    $result['engagement'] += ($likes + $comments);
                    $result['engagementData'][$days - 1] += ($likes + $comments);
                } catch (\Throwable $e) {
                    // Silent fail
                }
            }
            
            $result['recentActivity'][] = [
                'platform' => 'instagram',
                'platform_name' => 'Instagram',
                'id' => (string) $dbPost->_id,
                'title' => substr(strip_tags($dbPost->content ?? 'Instagram Post'), 0, 120),
                'description' => $typeIcon . ' ❤️ ' . number_format($likes) . ' likes | 💬 ' . number_format($comments) . ' comments',
                'timestamp' => $dbPost->created_at->toIso8601String(),
                'created_at' => $dbPost->created_at->diffForHumans(),
                'media_url' => $dbPost->media_path ? asset('storage/' . $dbPost->media_path) : null,
                'icon' => 'fab fa-instagram',
                'icon_color' => 'pink',
                'bg_color' => 'bg-pink-100',
                'text_color' => 'text-pink-600',
                'insights' => [
                    'likes' => $likes,
                    'comments' => $comments,
                    'media_type' => $mediaType
                ]
            ];
        } 
        } catch (\Throwable $e) {
            Log::error('Instagram fetch failed: ' . $e->getMessage());
        }
    }

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

        $youtubeAccounts = SocialAccount::where('user_id', $account->user_id)
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
                Log::info('YT CHANNEL STATS', $stats);
                $result['views'] += (int) ($stats['viewCount'] ?? 0);
                $result['subscribers'] += (int) ($stats['subscriberCount'] ?? 0);
                // $result['likes'] += (int) ($stats['likeCount'] ?? 0);
                // $result['comments'] += (int) ($stats['commentCount'] ?? 0);
                // $result['engagement'] = $result['likes'] + $result['comments'];
                

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
                    
                    // ONLY SHOW POSTS FROM DATABASE (published through app)
$dbPosts = Post::where('user_id', $account->user_id)
    ->where('platforms', 'youtube')
    ->where('status', 'published')
    ->whereNotNull('youtube_video_id')
    ->orderBy('created_at', 'desc')
    ->limit(15)
    ->get();

foreach ($dbPosts as $dbPost) {
    $views = 0;
    $likes = 0;
    $comments = 0;
    
    if ($dbPost->youtube_video_id) {
        try {
            $ytVideo = Http::timeout(5)->withToken($accessToken)->get(
                'https://www.googleapis.com/youtube/v3/videos',
                [
                    'part' => 'statistics',
                    'id' => $dbPost->youtube_video_id
                ]
            )->json();
            
            $stats = $ytVideo['items'][0]['statistics'] ?? [];
            Log::info('YT VIDEO STATS', $stats);

            $views = (int) ($stats['viewCount'] ?? 0);
            $likes = (int) ($stats['likeCount'] ?? 0);
            $comments = (int) ($stats['commentCount'] ?? 0);
            $result['engagement'] += ($likes + $comments);
            $result['engagementData'][$days - 1] += ($likes + $comments);
        } catch (\Throwable $e) {
            // Silent fail
        }
    }
    
    $result['recentActivity'][] = [
        'platform' => 'youtube',
        'platform_name' => 'YouTube',
        'id' => (string) $dbPost->_id,
        'title' => substr(strip_tags($dbPost->content ?? 'YouTube Video'), 0, 100),
        'description' => '🎬 ' . number_format($views) . ' views | ❤️ ' . number_format($likes) . ' likes | 💬 ' . number_format($comments) . ' comments',
        'timestamp' => $dbPost->created_at->toIso8601String(),
        'created_at' => $dbPost->created_at->diffForHumans(),
        'media_url' => $dbPost->media_path ? asset('storage/' . $dbPost->media_path) : null,
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
            } catch (\Throwable $e) {
                Log::warning('YouTube fetch failed: ' . $e->getMessage());
            }
        }

        return $result;
    }
}