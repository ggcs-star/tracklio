<?php

namespace App\Services\Qr;

use App\Models\QrCode;
use App\Models\QrClickLog;
use App\Models\Notification; // ✅ ONLY ADDITION
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class QrScanService
{
    public function scan($code)
    {
        try {
            $qr = QrCode::where('short_url', $code)->firstOrFail();

            if (!$qr->is_active) {
                abort(403);
            }

            $ip = request()->ip();
            $cacheKey = "qr_scan_{$qr->_id}_{$ip}";

            $qr->increment('scans');
            $qr->increment('qr_scan_count');

            if (!cache()->has($cacheKey)) {
                $ua = request()->userAgent();
                $deviceType = str_contains(strtolower($ua), 'mobile') ? 'Mobile' : 'Desktop';

                if (str_contains($ua, 'Chrome')) {
                    $browser = 'Chrome';
                } elseif (str_contains($ua, 'Firefox')) {
                    $browser = 'Firefox';
                } elseif (str_contains($ua, 'Safari')) {
                    $browser = 'Safari';
                } elseif (str_contains($ua, 'Edge')) {
                    $browser = 'Edge';
                } else {
                    $browser = 'Other';
                }

                $city = 'Unknown';
                $country = 'Unknown';

                try {
                    $response = Http::timeout(3)->get("http://ip-api.com/json/{$ip}");
                    if ($response->ok()) {
                        $data = $response->json();
                        $city = $data['city'] ?? 'Unknown';
                        $country = $data['country'] ?? 'Unknown';
                    }
                } catch (\Exception $e) {
                }

                QrClickLog::create([
                    'qr_id'       => (string) $qr->_id,
                    'short_code'  => $code,
                    'type'        => 'qr',
                    'ip_address'  => $ip,
                    'city'        => $city,
                    'country'     => $country,
                    'device_type' => $deviceType,
                    'browser'     => $browser,
                ]);

                cache()->put($cacheKey, true, now()->addMinutes(5));

                // 🔔 NOTIFICATION (ADDED – NO LOGIC TOUCHED)
                Notification::create([
                    'user_id' => $qr->user_id,
                    'type'    => 'qr_scan',
                    'message' => 'Your QR code was scanned',
                    'is_read' => false,
                ]);
            }

            return app(QrRedirectService::class)->redirectToTarget($qr);
        } catch (\Exception $e) {
            Log::error('QR scan error', [
                'code' => $code,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            abort(404, 'QR not found');
        }
    }

    public function visit($code)
    {
        try {
            $qr = QrCode::where('short_url', $code)->firstOrFail();

            if ($qr->is_active) {
                $qr->increment('visits');
                $qr->increment('visit_count');
            }

            return redirect("/qr/{$code}/scan");
        } catch (\Exception $e) {
            Log::error('QR visit error', [
                'code' => $code,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            abort(404, 'QR not found');
        }
    }
}
