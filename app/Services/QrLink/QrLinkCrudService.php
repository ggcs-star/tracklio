<?php

namespace App\Services\QrLink;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\QrLink;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use App\Helpers\QrLinkHelper;

class QrLinkCrudService
{
public function index(Request $request)
{
    $userId = (string) Auth::id();
    $search = trim((string) $request->input('search'));

    // 🔹 Base query (same logic, reusable)
    $baseQuery = QrLink::where('user_id', $userId)
        ->when($search !== '', function ($query) use ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('original_url', 'like', "%{$search}%")
                  ->orWhere('short_code', 'like', "%{$search}%")
                  ->orWhere('label', 'like', "%{$search}%");
            });
        });

    // 🔹 Stats (NO pagination, same logic)
    $stats = (clone $baseQuery)->get();

    // 🔹 Table data (WITH pagination)
    $qrs = (clone $baseQuery)
        ->latest()
        ->paginate(10)
        ->withQueryString();

    return view('qrcode.index', [
        // table
        'qrs'          => $qrs,

        // stats (stable on every page)
        'totalQrs'     => $stats->count(),
        'totalLinks'   => $stats->sum('visit_count'),
        'totalVisits'  => $stats->sum('visit_count'),
        'totalQrScans' => $stats->sum('qr_scan_count'),
        'activeQrs'    => $stats->count(),
    ]);
}



    public function show($id)
    {
        $qr = QrLink::where('_id', $id)
            ->where('user_id', (string) Auth::id())
            ->firstOrFail();

        return view('qrcode.show', compact('qr'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'original_url'  => 'nullable|url',
            'short_link_id' => 'nullable|string',
            'label'         => 'nullable|string|max:255',
        ]);

        if (!$request->original_url && !$request->short_link_id) {
            return back()->withErrors(['original_url' => 'URL ya Short Link required hai']);
        }

        do {
            $shortCode = Str::random(6);
        } while (QrLink::where('short_code', $shortCode)->exists());

        $qrUrl = url('/q/' . $shortCode . '?qr=1');

        $writer = new Writer(
            new ImageRenderer(new RendererStyle(300), new SvgImageBackEnd())
        );

        $svg = $writer->writeString($qrUrl);

        $path = 'qrcodes/qr_' . $shortCode . '.svg';
        Storage::disk('public')->put($path, $svg);

        QrLink::create([
            'user_id'       => (string) Auth::id(),
            'label'         => $request->label,
            'original_url'  => $request->original_url,
            'short_code'    => $shortCode,
            'qr_image_path' => $path,
            'visit_count'   => 0,
            'qr_scan_count' => 0,
        ]);

        return redirect()->route('qr-links.index')->with('success', 'QR code generated successfully');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'original_url' => 'required|url',
            'label'        => 'nullable|string|max:255',
            'foreground_type'  => 'nullable|in:single,gradient',
            'foreground_color' => 'nullable|string',
            'background_color' => 'nullable|string',
            'gradient_start'   => 'nullable|string',
            'gradient_end'     => 'nullable|string',
            'gradient_dir'     => 'nullable|in:horizontal,vertical,diagonal,radial',
            'qr_rotation'      => 'nullable|integer|in:0,90,180,270',
        ]);

        $qr = QrLink::where('_id', $id)
            ->where('user_id', (string) Auth::id())
            ->firstOrFail();

        $update = [
            'original_url' => $request->original_url,
            'label' => $request->label,
        ];

        if ($request->has('foreground_type')) {
            $svg = (new Writer(
                new ImageRenderer(new RendererStyle(300), new SvgImageBackEnd())
            ))->writeString(url('/q/' . $qr->short_code . '?qr=1'));

            $svg = QrLinkHelper::applySvgDesign($svg, $request);
            Storage::disk('public')->put($qr->qr_image_path, $svg);

            $update = array_merge($update, $request->only([
                'foreground_type','foreground_color','background_color',
                'gradient_start','gradient_end','gradient_dir'
            ]));

            $update['qr_rotation'] = $request->qr_rotation ?? 0;
        }

        $qr->update($update);

        return back()->with('success', 'QR updated successfully');
    }

    public function destroy($id)
    {
        $qr = QrLink::where('_id', $id)
            ->where('user_id', (string) Auth::id())
            ->firstOrFail();

        Storage::disk('public')->delete($qr->qr_image_path);
        $qr->delete();

        return redirect()->route('qr-links.index')->with('success', 'QR code deleted successfully');
    }
}
