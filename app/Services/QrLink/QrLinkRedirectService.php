<?php

namespace App\Services\QrLink;

use Illuminate\Http\Request;
use App\Models\QrLink;
use App\Models\QrClickLog;
use App\Helpers\QrLinkHelper;

class QrLinkRedirectService
{
    public function handle(Request $request, string $code)
    {
        $qr = QrLink::where('short_code', $code)->firstOrFail();

        $type = $request->has('qr') ? 'qr' : 'link';
        $qr->increment($type === 'qr' ? 'qr_scan_count' : 'visit_count');

        $location = QrLinkHelper::getLocationFromIp($request->ip());
        $device   = QrLinkHelper::detectDevice($request->userAgent() ?? '');

        QrClickLog::create([
            'qr_id'       => (string) $qr->_id,
            'short_code'  => $qr->short_code,
            'type'        => $type,
            'ip_address'  => $request->ip(),
            'city'        => $location['city'],
            'country'     => $location['country'],
            'device_type' => $device['device'],
            'browser'     => $device['browser'],
        ]);
        // 🔔 NOTIFICATION – QR / LINK SCANNED
\App\Models\Notification::create([
    'user_id' => (string) $qr->user_id,   // QR owner
    'type'    => $type === 'qr' ? 'qr_scan' : 'link_visit',
    'message' => $type === 'qr'
        ? 'Your QR code was scanned'
        : 'Your link was visited',
    'is_read' => false,
]);


        return redirect()->away($qr->original_url);
    }
}
