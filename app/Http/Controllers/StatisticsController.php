<?php

namespace App\Http\Controllers;

use App\Models\QrCode;
use App\Models\QrLink;
use App\Models\QrClickLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class StatisticsController extends Controller
{
    public function index(Request $request)
    {
        try {
            $days = (int) $request->get('range', 30);
            $from = Carbon::now()->subDays($days);

            $userId = (string) auth()->id();

            $qrLinks = QrLink::where('user_id', $userId)->get();
            $qrCodes = QrCode::where('user_id', $userId)->get();

            $totalLinksCount =
                $qrLinks->count() +
                $qrCodes->count();

            $qrIds = collect()
                ->merge($qrLinks->pluck('_id'))
                ->merge($qrCodes->pluck('_id'))
                ->map(fn ($id) => (string) $id);

            $logs = QrClickLog::whereIn('qr_id', $qrIds)
                ->where('created_at', '>=', $from)
                ->get();

            $totalQrScans      = $logs->where('type', 'qr')->count();
            $totalLinkClicks   = $logs->where('type', 'link')->count();
            $totalInteractions = $totalQrScans + $totalLinkClicks;

            $clicksByDate = $logs
                ->groupBy(fn ($log) => $log->created_at->format('Y-m-d'))
                ->map->count();

            $countries = $logs
                ->groupBy(fn ($l) => $l->country ?: 'Unknown')
                ->map->count()
                ->sortDesc();

            $devices = $logs
                ->groupBy(fn ($l) => $l->device_type ?: 'Unknown')
                ->map->count();

            $browsers = $logs
                ->groupBy(fn ($l) => $l->browser ?: 'Other')
                ->map->count();

            $typeSplit = [
                'qr'   => $totalQrScans,
                'link' => $totalLinkClicks,
            ];

            return view('statistics.index', [
                'totalLinksCount'   => $totalLinksCount,
                'totalQrScans'      => $totalQrScans,
                'totalLinkClicks'   => $totalLinkClicks,
                'totalInteractions' => $totalInteractions,
                'days'              => $days,
                'clicksByDate'      => $clicksByDate,
                'countries'         => $countries,
                'devices'           => $devices,
                'browsers'          => $browsers,
                'typeSplit'         => $typeSplit,
            ]);
        } catch (\Throwable $e) {
            Log::error('Statistics index error', [
                'user_id' => auth()->id(),
                'error'   => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            abort(500);
        }
    }

    public function scanQrCode($id, Request $request)
    {
        try {
            $qr = QrCode::where('_id', $id)->firstOrFail();

            QrClickLog::create([
                'qr_id'       => (string) $qr->_id,
                'short_code'  => null,
                'type'        => 'qr',
                'ip_address'  => $request->ip(),
                'city'        => null,
                'country'     => $request->header('CF-IPCountry') ?? null,
                'device_type' => $request->header('User-Agent')
                    ? (str_contains($request->header('User-Agent'), 'Mobile') ? 'Mobile' : 'Desktop')
                    : null,
                'browser'     => $request->header('User-Agent'),
            ]);

            $qr->increment('scans');

            return redirect($qr->original_url ?? '/');
        } catch (\Throwable $e) {
            Log::error('QR scan log error', [
                'qr_id'  => $id,
                'ip'     => $request->ip(),
                'error'  => $e->getMessage(),
                'trace'  => $e->getTraceAsString(),
            ]);

            abort(404);
        }
    }
}
