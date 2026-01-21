<?php

namespace App\Helpers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class QrLinkHelper
{
    public static function detectDevice(string $agent): array
    {
        $agent = strtolower($agent);
        $device = preg_match('/mobile|android|iphone|ipad|ipod/', $agent) ? 'Mobile' : 'Desktop';

        if (str_contains($agent, 'chrome')) $browser = 'Chrome';
        elseif (str_contains($agent, 'firefox')) $browser = 'Firefox';
        elseif (str_contains($agent, 'safari')) $browser = 'Safari';
        elseif (str_contains($agent, 'edge')) $browser = 'Edge';
        else $browser = 'Other';

        return compact('device', 'browser');
    }

    public static function getLocationFromIp(string $ip): array
    {
        try {
            $data = json_decode(
                file_get_contents("http://ip-api.com/json/{$ip}?fields=status,country,city"),
                true
            );

            if (($data['status'] ?? '') !== 'success') {
                return ['city' => 'Unknown', 'country' => 'Unknown'];
            }

            return [
                'city' => $data['city'] ?? 'Unknown',
                'country' => $data['country'] ?? 'Unknown',
            ];
        } catch (\Throwable $e) {
            Log::warning('IP location failed', ['ip' => $ip]);
            return ['city' => 'Unknown', 'country' => 'Unknown'];
        }
    }

    public static function applySvgDesign(string $svg, Request $request): string
    {
        $fg = $request->foreground_color ?? '#000000';
        $bg = $request->background_color ?? '#ffffff';

        $svg = preg_replace('/<\?xml.*?\?>/i', '', $svg);

        $svg = preg_replace(
            '/<svg([^>]*)>/i',
            '<svg$1><rect width="100%" height="100%" fill="'.$bg.'" />',
            $svg,
            1
        );

        return preg_replace(
            '/<path([^>]*)fill="[^"]*"([^>]*)>/i',
            '<path$1fill="'.$fg.'"$2>',
            $svg
        );
    }
}
