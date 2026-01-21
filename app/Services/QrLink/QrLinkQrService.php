<?php

namespace App\Services\QrLink;

use Illuminate\Support\Facades\Storage;
use App\Models\QrLink;

class QrLinkQrService
{
    public function downloadSvg($id)
    {
        $qr = QrLink::where('_id', $id)->firstOrFail();

        $path = storage_path('app/public/' . $qr->qr_image_path);

        if (!file_exists($path)) {
            abort(404);
        }

        return response(
            file_get_contents($path),
            200,
            ['Content-Type' => 'image/svg+xml']
        );
    }
}
