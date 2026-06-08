<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AnalyticsService;
use App\Models\SocialAccount;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Models\DashboardAnalytics;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            $userId = (string) auth()->user()->_id;

            $facebookAccount = SocialAccount::forUser($userId)
                ->where('platform', 'facebook')
                ->where('status', 'connected')
                ->first();

            $instagramAccounts = SocialAccount::forUser($userId)
                ->where('platform', 'instagram')
                ->where('status', 'connected')
                ->get();

            $youtubeAccounts = SocialAccount::forUser($userId)
                ->where('platform', 'youtube')
                ->where('status', 'connected')
                ->get();

            return view('dashboard.index', [
                'facebookPages' => $facebookAccount->pages ?? [],
                'instagramAccounts' => $instagramAccounts,
                'youtubeAccounts' => $youtubeAccounts,
                'hasFacebook' => !is_null($facebookAccount),
                'hasInstagram' => $instagramAccounts->count() > 0,
                'hasYoutube' => $youtubeAccounts->count() > 0,
            ]);

        } catch (\Throwable $e) {
            Log::error('Dashboard index error', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);
            
            return view('dashboard.index', [
                'facebookPages' => [],
                'instagramAccounts' => collect(),
                'youtubeAccounts' => collect(),
                'hasFacebook' => false,
                'hasInstagram' => false,
                'hasYoutube' => false,
            ]);
        }
    }

    public function live(Request $request)
{
    try {
        $userId = (string) auth()->user()->_id;
        $platform = $request->get('platform', 'all');
        $pageId = $request->get('page', 'all');
        $instagramProfile = $request->get('instagram_profile', 'all');
        $youtubeChannel = $request->get('youtube_channel', 'all');
        $range = $request->get('range', '7');
        $metric = $request->get('metric', 'reach');

        $days = $range === 'today' ? 1 : (int) $range;

        $analyticsService = new AnalyticsService();
        $data = $analyticsService->getAllAnalytics($userId, $platform, $pageId, $days, $instagramProfile, $youtubeChannel);

        // ✅ SAVE TO DATABASE (optional - sirf agar chahiye to)
        DashboardAnalytics::updateOrCreate(
            [
                'user_id' => $userId,
                'created_at' => now()->toDateString()
            ],
            [
                'stats' => [
                    'reach' => $data['totalReach'],
                    'engagement' => $data['totalEngagement'],
                    'followers' => $data['followerGrowth']
                ],
                'weekly' => [
                    'labels' => $data['labels'],
                    'engagementData' => $data['engagementData'],
                    'reachData' => $data['reachData']
                ],
                'performance' => [
                    'platformReach' => $data['platformReach'],
                    'platformEngagement' => $data['platformEngagement']
                ],
                'updated_at' => now()
            ]
        );

        $labelsCount = count($data['labels']);
        
        $engagementData = array_pad($data['engagementData'] ?? [], $labelsCount, 0);
        $likesData = array_pad($data['likesData'] ?? [], $labelsCount, 0);
        $sharesData = array_pad($data['sharesData'] ?? [], $labelsCount, 0);
        $reachData = array_pad($data['reachData'] ?? [], $labelsCount, 0);

        $chartData = $reachData;
        if ($metric === 'likes') $chartData = $likesData;
        if ($metric === 'shares') $chartData = $sharesData;

        return response()->json([
            'success' => true,
            'totalReach' => $data['totalReach'],
            'totalEngagement' => $data['totalEngagement'],
            'totalClicks' => $data['totalClicks'] ?? 0,
            'followerGrowth' => $data['followerGrowth'],
            'labels' => $data['labels'],
            'engagementData' => $engagementData,
            'likesData' => $likesData,
            'sharesData' => $sharesData,
            'reachData' => $reachData,
            'chartData' => $chartData,
            'platformReach' => $data['platformReach'],
            'platformEngagement' => $data['platformEngagement'],
            'pages' => $data['pages'],
            'recentActivity' => $data['recentActivity'],
        ]);

    } catch (\Throwable $e) {
        Log::error('Dashboard live API error', [
            'user_id' => auth()->id(),
            'error' => $e->getMessage()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Something went wrong',
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
            'recentActivity' => [],
        ], 200);
    }
}

    public function refreshTokens(Request $request)
    {
        try {
            $userId = (string) auth()->user()->_id;
            $platform = $request->get('platform');

            $account = SocialAccount::forUser($userId)
                ->where('platform', $platform)
                ->where('status', 'connected')
                ->first();

            if (!$account) {
                return response()->json([
                    'success' => false,
                    'message' => 'Account not found'
                ], 404);
            }

            if ($platform === 'youtube') {
                $creds = $account->credentials;
                
                $tokenRes = Http::asForm()->post(
                    'https://oauth2.googleapis.com/token',
                    [
                        'client_id' => env('YOUTUBE_CLIENT_ID'),
                        'client_secret' => env('YOUTUBE_CLIENT_SECRET'),
                        'refresh_token' => $creds['refresh_token'],
                        'grant_type' => 'refresh_token',
                    ]
                );

                if ($tokenRes->successful()) {
                    $newToken = $tokenRes->json('access_token');
                    $creds['access_token'] = $newToken;
                    $account->credentials = $creds;
                    $account->save();
                    
                    return response()->json([
                        'success' => true,
                        'message' => 'Token refreshed successfully'
                    ]);
                }
            }

            return response()->json([
                'success' => false,
                'message' => 'Could not refresh token'
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}