<?php

namespace App\Services\Bio;

use Illuminate\Http\Request;
use App\Models\BioPage;
use App\Models\BioPageView;
use App\Models\Notification; // ✅ ONLY ADDITION
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BioViewService
{
    public function handle(string $slug, Request $request)
    {
        try {
            $bio = BioPage::where('slug', $slug)
                ->where('is_active', true)
                ->firstOrFail();

            $rawLinks = $bio->links ?? [];
            $links = is_string($rawLinks)
                ? json_decode($rawLinks, true) ?? []
                : $rawLinks;

            try {
                $ip = $request->ip();
                $userAgent = strtolower($request->userAgent() ?? '');

                $device = str_contains($userAgent, 'mobile') ? 'Mobile' : 'Desktop';

                if (str_contains($userAgent, 'chrome')) {
                    $browser = 'Chrome';
                } elseif (str_contains($userAgent, 'firefox')) {
                    $browser = 'Firefox';
                } elseif (str_contains($userAgent, 'safari')) {
                    $browser = 'Safari';
                } else {
                    $browser = 'Other';
                }

                if (str_contains($userAgent, 'windows')) {
                    $platform = 'Windows';
                } elseif (str_contains($userAgent, 'android')) {
                    $platform = 'Android';
                } elseif (str_contains($userAgent, 'iphone') || str_contains($userAgent, 'ios')) {
                    $platform = 'iOS';
                } elseif (str_contains($userAgent, 'mac')) {
                    $platform = 'Mac';
                } else {
                    $platform = 'Other';
                }

                $location = [];
                try {
                    $location = Http::timeout(2)
                        ->get("http://ip-api.com/json/{$ip}")
                        ->json();
                } catch (\Throwable $e) {
                    $location = [];
                }

                BioPageView::create([
                    'bio_page_id' => (string) $bio->_id,
                    'ip' => $ip,
                    'country' => $location['country'] ?? 'Unknown',
                    'city' => $location['city'] ?? 'Unknown',
                    'device' => $device,
                    'platform' => $platform,
                    'browser' => $browser,
                    'referrer' => $request->headers->get('referer'),
                ]);

                // 🔔 NOTIFICATION (ADDED – NO LOGIC TOUCHED)
                Notification::create([
                    'user_id' => $bio->user_id,
                    'type'    => 'profile_view',
                    'message' => 'Someone viewed your bio page',
                    'is_read' => false,
                ]);

            } catch (\Throwable $e) {
                Log::warning('Bio view tracking failed', [
                    'slug' => $slug,
                    'error' => $e->getMessage(),
                ]);
            }

            return view('bio.public', compact('bio', 'links'));

        } catch (\Throwable $e) {
            Log::error('Bio public view error', [
                'slug' => $slug,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            abort(404);
        }
    }
}
