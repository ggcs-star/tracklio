<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AnalyticsService;
use App\Models\SocialAccount;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

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
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
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
            $filter = $request->get('filter');

            $days = $range === 'today' ? 1 : (int) $range;

            $analyticsService = new AnalyticsService();
            $data = $analyticsService->getAllAnalytics($userId, $platform, $pageId, $days, $instagramProfile, $youtubeChannel);

            return response()->json([
                'success' => true,
                'totalReach' => $data['totalReach'],
                'totalEngagement' => $data['totalEngagement'],
                'totalClicks' => $data['totalClicks'] ?? 0,
                'followerGrowth' => $data['followerGrowth'],
                'labels' => $data['labels'],
                'engagementData' => $data['engagementData'],
                'likesData' => $data['likesData'] ?? [],
                'sharesData' => $data['sharesData'] ?? [],
                'reachTrendData' => $data['reachTrendData'] ?? $data['engagementData'],
                'platformReach' => $data['platformReach'],
                'platformEngagement' => $data['platformEngagement'],
                'pages' => $data['pages'],
                'recentActivity' => $data['recentActivity'],
            ]);

        } catch (\Throwable $e) {
            Log::error('Dashboard live API error', [
                'user_id' => auth()->id(),
                'payload' => $request->all(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: ' . $e->getMessage(),
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