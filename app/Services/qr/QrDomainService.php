<?php

namespace App\Services\Qr;

use Illuminate\Support\Facades\Log;

class QrDomainService
{
    public function getDomain()
    {
        try {
            $domain = config('app.url');

            $hostname = request()->getHost();
            $isLocal =
                $hostname === 'localhost' ||
                $hostname === '127.0.0.1' ||
                str_contains($hostname, '.test') ||
                str_contains($hostname, '.local') ||
                app()->environment('local');

            if ($isLocal) {
                $domain = 'https://qrul.co';
            }

            return response()->json([
                'success' => true,
                'domain' => $domain,
                'qr_base_url' => $domain . '/qr/',
                'is_local' => $isLocal,
                'detected_host' => $hostname
            ]);
        } catch (\Exception $e) {
            Log::error('Get domain error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'domain' => config('app.url'),
                'qr_base_url' => config('app.url') . '/qr/',
                'is_local' => false,
                'detected_host' => request()->getHost()
            ]);
        }
    }
}
