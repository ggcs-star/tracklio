<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\SocialAccount;
use App\Models\SocialHourlyStat;
use Illuminate\Support\Facades\Log;

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

            return view('dashboard.index', [
                'facebookPages' => $facebookAccount->pages ?? []
            ]);
        } catch (\Throwable $e) {
            Log::error('Dashboard index error', [
                'user_id' => auth()->id(),
                'error'   => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);
            abort(500);
        }
    }

    public function live(Request $request)
    {
        try {
            $userId = (string) auth()->user()->_id;

            $platform = $request->get('platform', 'all');
            if (!in_array($platform, ['all', 'facebook', 'instagram', 'youtube'])) {
                $platform = 'all';
            }

            $pageId = $request->get('page', 'all');

            if ($request->get('filter') === 'today') {
                $since = Carbon::today();
                $until = Carbon::today();
            } else {
                $range = (int) $request->get('range', 7);
                if (!in_array($range, [7, 30, 90])) {
                    $range = 7;
                }

                $since = Carbon::now()->subDays($range)->startOfDay();
                $until = Carbon::now()->endOfDay();
            }

            $facebook  = $this->platformFromDb('facebook', $userId, $since, $until, $pageId);
            $instagram = $this->platformFromDb('instagram', $userId, $since, $until, $pageId);
            $youtube   = $this->platformFromDb('youtube', $userId, $since, $until, $pageId);

            return response()->json([
                'totalReach' =>
                    $facebook['reach'] +
                    $instagram['reach'] +
                    $youtube['reach'],

                'totalEngagement' =>
                    $facebook['engagement'] +
                    $instagram['engagement'] +
                    $youtube['engagement'],

                'linkClicks' => 0,

                'followerGrowth' =>
                    $facebook['followers'] +
                    $instagram['followers'] +
                    $youtube['followers'],

                'labels' => $facebook['labels'],
                'engagementData' => $facebook['engagementGraph'],

                'platformReach' => [
                    $facebook['reach'],
                    $instagram['reach'],
                    $youtube['reach'],
                ],

                'platformEngagement' => [
                    $facebook['engagement'],
                    $instagram['engagement'],
                    $youtube['engagement'],
                ],

                'pages' => $facebook['pages'],
            ]);
        } catch (\Throwable $e) {
            Log::error('Dashboard live API error', [
                'user_id' => auth()->id(),
                'payload' => $request->all(),
                'error'   => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
            ], 500);
        }
    }

    private function platformFromDb(
        string $platform,
        string $userId,
        $since,
        $until,
        string $pageFilter = 'all'
    ) {
        try {
            $account = SocialAccount::forUser($userId)
                ->where('platform', $platform)
                ->where('status', 'connected')
                ->first();

            if (!$account) {
                return $this->blank();
            }

            $query = SocialHourlyStat::where('platform', $platform)
                ->where('user_id', $userId)
                ->whereBetween('stat_date', [
                    $since->toDateString(),
                    $until->toDateString()
                ]);

            if ($pageFilter !== 'all') {
                $query->where('page_id', $pageFilter);
            }

            $stats = $query->get();

            if ($stats->isEmpty()) {
                return $this->blank($account->pages);
            }

            $reach      = $stats->sum('reach');
            $engagement = $stats->sum('engagement');

            $followers = optional(
                $stats->sortByDesc('stat_date')
                      ->sortByDesc('stat_hour')
                      ->first()
            )->followers ?? 0;

            $grouped = $stats->groupBy('stat_date');

            $labels = [];
            $graph  = [];

            foreach ($grouped as $date => $rows) {
                $labels[] = Carbon::parse($date)->format('d M');
                $graph[]  = $rows->sum('engagement');
            }

            return [
                'reach'           => $reach,
                'engagement'      => $engagement,
                'followers'       => $followers,
                'labels'          => $labels,
                'engagementGraph' => $graph,
                'pages'           => $account->pages ?? [],
            ];
        } catch (\Throwable $e) {
            Log::error('Platform stats error', [
                'platform' => $platform,
                'user_id'  => $userId,
                'error'    => $e->getMessage(),
                'trace'    => $e->getTraceAsString(),
            ]);

            return $this->blank();
        }
    }

    private function blank($pages = [])
    {
        return [
            'reach' => 0,
            'engagement' => 0,
            'followers' => 0,
            'labels' => [],
            'engagementGraph' => [],
            'pages' => $pages,
        ];
    }
}
