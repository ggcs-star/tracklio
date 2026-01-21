<?php

namespace App\Services\Qr;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\QrCode;
use MongoDB\BSON\ObjectId;
use Illuminate\Support\Facades\Log;

class QrCrudService
{
    public function index()
    {
        try {
            $qrCodes = QrCode::where('user_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->get();

            return view('qr.list', compact('qrCodes'));
        } catch (\Exception $e) {
            Log::error('QR index error', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString()
            ]);

            return view('qr.list', ['qrCodes' => []]);
        }
    }

    public function create()
    {
        try {
            return view('qr.index');
        } catch (\Exception $e) {
            Log::error('QR create error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()
                ->route('qr.builder')
                ->with('error', 'Error loading QR builder');
        }
    }

    public function edit($id)
    {
        try {
            Log::info('Edit called', ['id' => $id, 'user' => Auth::id()]);

            $qr = QrCode::where('_id', new ObjectId($id))
                ->where('user_id', (string) Auth::id())
                ->firstOrFail();

            return view('qr.index', [
                'qr' => $qr,
                'qrSettings' => is_string($qr->settings)
                    ? json_decode($qr->settings, true)
                    : $qr->settings
            ]);
        } catch (\Exception $e) {
            Log::error('QR edit error', [
                'id' => $id,
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()
                ->route('qr.builder')
                ->with('error', 'QR not found');
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name'          => 'required|string|max:255',
                'mode'          => 'required|in:static,dynamic',
                'payload_type'  => 'required|string',
                'payload_value' => 'required',
                'design'        => 'nullable|array',
                'qr_png'        => 'required|string',
                'short_url'     => 'nullable|string',
                'qr_data'       => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors'  => $validator->errors()
                ], 422);
            }

            $payload = $request->payload_value;
            if (is_string($payload)) {
                $decoded = json_decode($payload, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $payload = $decoded;
                }
            }

            $shortUrl    = null;
            $originalUrl = null;
            $shortCode   = null;
            $qrData      = $request->qr_data;

            if ($request->mode === 'dynamic') {
                $shortCode = $request->short_url;

                if (empty($shortCode)) {
                    do {
                        $shortCode = Str::random(8);
                    } while (QrCode::where('short_url', $shortCode)->exists());
                } else {
                    if (QrCode::where('short_url', $shortCode)->exists()) {
                        do {
                            $shortCode = Str::random(8);
                        } while (QrCode::where('short_url', $shortCode)->exists());
                    }
                }

                $shortUrl = $shortCode;

                if (is_array($payload)) {
                    if (isset($payload['value'])) {
                        $originalUrl = $payload['value'];
                    } elseif (isset($payload['to']) && $request->payload_type === 'email') {
                        $originalUrl = "mailto:{$payload['to']}";
                    } elseif (isset($payload['phone']) && $request->payload_type === 'sms') {
                        $originalUrl = "smsto:{$payload['phone']}";
                    } elseif (isset($payload['phone']) && $request->payload_type === 'whatsapp') {
                        $originalUrl = "https://wa.me/{$payload['phone']}";
                    } else {
                        $originalUrl = '';
                    }
                } else {
                    if ($request->payload_type === 'phone') {
                        $phone = preg_replace('/\D/', '', $payload);
                        if ($phone && !str_starts_with($phone, '+')) {
                            $phone = '+' . $phone;
                        }
                        $originalUrl = 'tel:' . $phone;
                    } else {
                        $originalUrl = $payload;
                    }
                }

                $qrData = $request->qr_data;
            } else {
                $qrData = app('App\\Helpers\\QrFormatHelper')
                    ->formatQrData($request->payload_type, $payload);
            }

            $pngPath = null;

            if ($request->qr_png) {
                $png = base64_decode(
                    preg_replace('#^data:image/\w+;base64,#i', '', $request->qr_png)
                );

                $pngPath = 'qr-codes/' . Auth::id() . '/' . time() . '.png';
                Storage::disk('public')->makeDirectory('qr-codes/' . Auth::id());
                Storage::disk('public')->put($pngPath, $png);
            }

            $qr = QrCode::create([
                'user_id'       => (string) Auth::id(),
                'title'         => $request->name,
                'qr_type'       => $request->payload_type,
                'qr_mode'       => $request->mode,
                'qr_data'       => $qrData,
                'short_url'     => $shortUrl,
                'original_url'  => $originalUrl,
                'qr_image_url'  => $pngPath,
                'scans'         => 0,
                'visits'        => 0,
                'qr_scan_count' => 0,
                'visit_count'   => 0,
                'settings'      => $request->design ?? [],
                'is_active'     => true,
                'expiry_date'   => null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'QR created successfully',
                'data'    => [
                    'id'           => (string) $qr->_id,
                    'short_url'    => $qr->short_url,
                    'qr_image_url' => $qr->qr_image_url,
                    'qr_data'      => $qr->qr_data,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('QR store error', [
                'error' => $e->getMessage(),
                'request' => $request->all(),
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error creating QR code'
            ], 500);
        }
    }

  public function update(Request $request, $id)
{
    try {
        Log::info('UPDATE ID CHECK', ['id' => $id]);

        $validator = Validator::make($request->all(), [
            'name'          => 'required|string|max:255',
            'mode'          => 'required|in:static,dynamic',
            'payload_type'  => 'required|string',
            'payload_value' => 'required',
            'design'        => 'nullable|array',
            'qr_png'        => 'nullable|string',
            'qr_data'       => 'required|string',
            'short_url'     => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors()
            ], 422);
        }

        $qr = QrCode::where('_id', new ObjectId($id))
            ->where('user_id', (string) Auth::id())
            ->firstOrFail();

        $payloadValue = $request->payload_value;
        if (is_string($payloadValue)) {
            $decoded = json_decode($payloadValue, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $payloadValue = $decoded;
            }
        }

        $qrData = $request->qr_data;
        $originalUrl = $qr->original_url;

        if ($request->mode === 'dynamic') {
            $qrData = $request->qr_data;

            if (is_array($payloadValue)) {
                if (isset($payloadValue['value'])) {
                    $originalUrl = $payloadValue['value'];
                } elseif (isset($payloadValue['to']) && $request->payload_type === 'email') {
                    $originalUrl = "mailto:{$payloadValue['to']}";
                } elseif (isset($payloadValue['phone']) && $request->payload_type === 'sms') {
                    $originalUrl = "smsto:{$payloadValue['phone']}";
                } elseif (isset($payloadValue['phone']) && $request->payload_type === 'whatsapp') {
                    $originalUrl = "https://wa.me/{$payloadValue['phone']}";
                }
            } else {
                $originalUrl = $payloadValue;
            }
        } else {
            $qrData = app('App\\Helpers\\QrFormatHelper')
                ->formatQrData($request->payload_type, $payloadValue);
        }

        $design = $request->design ?? [];

        if (!empty($request->qr_png)) {
            $imageData = base64_decode(
                preg_replace('#^data:image/\w+;base64,#i', '', $request->qr_png)
            );

            Storage::disk('public')->makeDirectory('qr-codes/' . Auth::id());

            $qrImagePath = 'qr-codes/' . Auth::id() . '/' . time() . '.png';
            Storage::disk('public')->put($qrImagePath, $imageData);

            if ($qr->qr_image_url && Storage::disk('public')->exists($qr->qr_image_url)) {
                Storage::disk('public')->delete($qr->qr_image_url);
            }

            $qr->qr_image_url = $qrImagePath;
        }

        $qr->update([
            'title'        => $request->name,
            'qr_mode'      => $request->mode,
            'qr_type'      => $request->payload_type,
            'qr_data'      => $qrData,
            'original_url' => $originalUrl,
            'settings'     => $design,
            'qr_image_url' => $qr->qr_image_url,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'QR code updated successfully',
            'data'    => [
                'id'           => (string) $qr->_id,
                'name'         => $qr->title,
                'short_url'    => $qr->short_url,
                'qr_image_url' => $qr->qr_image_url,
                'qr_data'      => $qr->qr_data,
                'updated_at'   => $qr->updated_at,
            ]
        ]);
    } catch (\Exception $e) {
        Log::error('QR update error', [
            'id' => $id,
            'error' => $e->getMessage(),
            'request' => $request->all(),
            'user_id' => Auth::id(),
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Error updating QR code'
        ], 500);
    }
}
public function destroy($id)
{
    try {
        $qr = QrCode::where('_id', new ObjectId($id))
            ->where('user_id', (string) Auth::id())
            ->firstOrFail();

        if ($qr->qr_image_url && Storage::disk('public')->exists($qr->qr_image_url)) {
            Storage::disk('public')->delete($qr->qr_image_url);
        }

        $qr->delete();

        return redirect()
            ->route('qr.builder')
            ->with('success', 'QR deleted successfully');
    } catch (\Exception $e) {
        Log::error('QR destroy error', [
            'id' => $id,
            'error' => $e->getMessage(),
            'user_id' => Auth::id(),
            'trace' => $e->getTraceAsString()
        ]);

        return redirect()
            ->route('qr.builder')
            ->with('error', 'Error deleting QR');
    }
}

}
