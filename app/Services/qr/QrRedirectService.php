<?php

namespace App\Services\Qr;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\Notification; // ✅ ONLY ADDITION

class QrRedirectService
{
    public function redirectToTarget($qr)
    {
        try {
            if (empty($qr->original_url)) {
                return redirect('/')->with('error', 'QR target not configured');
            }

            // 🔔 NOTIFICATION (ADDED – NO LOGIC TOUCHED)
            Notification::create([
                'user_id' => $qr->user_id,
                'type'    => 'qr_open',
                'message' => 'Your QR link was opened',
                'is_read' => false,
            ]);

            switch ($qr->qr_type) {
                case 'sms':
                    return $this->redirectToSMS($qr);
                case 'phone':
                    return $this->redirectToPhone($qr);
                case 'email':
                    return $this->redirectToEmail($qr);
                case 'whatsapp':
                    return $this->redirectToWhatsApp($qr);
                case 'application':
                    return $this->redirectToApplication($qr);
                case 'file':
                    return $this->redirectToFile($qr);
                case 'vcard':
                    return $this->redirectToVCard($qr);
                case 'wifi':
                    return $this->redirectToWifi($qr);
                case 'event':
                    return $this->redirectToEvent($qr);
                case 'cryptocurrency':
                    return $this->redirectToCrypto($qr);
                case 'link':
                    $url = $qr->original_url;
                    if (!preg_match('/^https?:\/\//', $url)) {
                        $url = 'https://' . $url;
                    }
                    return redirect()->away($url);
                default:
                    return redirect()->away($qr->original_url);
            }
        } catch (\Exception $e) {
            Log::error('Redirect to target error', [
                'qr_id' => $qr->_id,
                'qr_type' => $qr->qr_type,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->away($qr->original_url);
        }
    }


    private function redirectToApplication($qr)
    {
        try {
            $original = $qr->original_url;

            if (str_starts_with($original, '{')) {
                try {
                    $data = json_decode($original, true);

                    if (!empty($data['appStore'])) {
                        return redirect()->away($data['appStore']);
                    } elseif (!empty($data['googlePlay'])) {
                        return redirect()->away($data['googlePlay']);
                    } elseif (!empty($data['other'])) {
                        return redirect()->away($data['other']);
                    } elseif (!empty($data['value'])) {
                        return redirect()->away($data['value']);
                    }
                } catch (\Exception $e) {
                }
            }

            if (filter_var($original, FILTER_VALIDATE_URL)) {
                return redirect()->away($original);
            }

            return redirect()->away("https://play.google.com/store/search?q=" . urlencode($original));
        } catch (\Exception $e) {
            Log::error('Redirect to application error', [
                'qr_id' => $qr->_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->away($qr->original_url);
        }
    }

    private function redirectToFile($qr)
    {
        try {
            $original = $qr->original_url;

            if (filter_var($original, FILTER_VALIDATE_URL)) {
                return redirect()->away($original);
            }

            if (Storage::disk('public')->exists($original)) {
                return Storage::disk('public')->response($original);
            }

            if (str_starts_with($original, 'data:')) {
                return response(
                    base64_decode(
                        preg_replace('#^data:[\w/]+;base64,#i', '', $original)
                    )
                )
                    ->header('Content-Type', 'application/octet-stream')
                    ->header('Content-Disposition', 'inline');
            }

            abort(404, 'File not found');
        } catch (\Exception $e) {
            Log::error('Redirect to file error', [
                'qr_id' => $qr->_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            abort(404, 'File not found');
        }
    }

    private function redirectToVCard($qr)
    {
        try {
            $original = $qr->original_url;

            if (str_contains($original, 'BEGIN:VCARD')) {
                return response($original)
                    ->header('Content-Type', 'text/vcard')
                    ->header('Content-Disposition', 'attachment; filename="contact.vcf"');
            }

            if (str_starts_with($original, '{')) {
                try {
                    $data = json_decode($original, true);
                    $vcard = app('App\\Helpers\\QrFormatHelper')->generateVCard($data);

                    return response($vcard)
                        ->header('Content-Type', 'text/vcard')
                        ->header('Content-Disposition', 'attachment; filename="contact.vcf"');
                } catch (\Exception $e) {
                }
            }

            return redirect()->away($original);
        } catch (\Exception $e) {
            Log::error('Redirect to vcard error', [
                'qr_id' => $qr->_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->away($qr->original_url);
        }
    }

    private function redirectToWifi($qr)
    {
        try {
            $original = $qr->original_url;

            if (str_starts_with($original, 'WIFI:')) {
                return view('qr.wifi', [
                    'qr' => $qr,
                    'wifiConfig' => $original
                ]);
            }

            if (str_starts_with($original, '{')) {
                try {
                    $data = json_decode($original, true);

                    $encryption = $data['encryption'] ?? 'WPA';
                    $ssid = $data['ssid'] ?? '';
                    $password = $data['password'] ?? '';

                    $wifiString = "WIFI:T:{$encryption};S:{$ssid};P:{$password};;";

                    return view('qr.wifi', [
                        'qr' => $qr,
                        'wifiConfig' => $wifiString
                    ]);
                } catch (\Exception $e) {
                }
            }

            return redirect()->away($original);
        } catch (\Exception $e) {
            Log::error('Redirect to wifi error', [
                'qr_id' => $qr->_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->away($qr->original_url);
        }
    }

    private function redirectToEvent($qr)
    {
        try {
            $original = $qr->original_url;

            if (str_contains($original, 'BEGIN:VEVENT')) {
                return response($original)
                    ->header('Content-Type', 'text/calendar')
                    ->header('Content-Disposition', 'attachment; filename="event.ics"');
            }

            if (str_starts_with($original, '{')) {
                try {
                    $data = json_decode($original, true);
                    $ical = app('App\\Helpers\\QrFormatHelper')->generateICal($data);

                    return response($ical)
                        ->header('Content-Type', 'text/calendar')
                        ->header('Content-Disposition', 'attachment; filename="event.ics"');
                } catch (\Exception $e) {
                }
            }

            return redirect()->away($original);
        } catch (\Exception $e) {
            Log::error('Redirect to event error', [
                'qr_id' => $qr->_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->away($qr->original_url);
        }
    }

    private function redirectToCrypto($qr)
    {
        try {
            $original = $qr->original_url;

            if (str_contains($original, ':')) {
                [$type, $address] = explode(':', $original, 2);

                return view('qr.crypto', [
                    'qr' => $qr,
                    'cryptoType' => $type,
                    'address' => $address
                ]);
            }

            return redirect()->away($original);
        } catch (\Exception $e) {
            Log::error('Redirect to crypto error', [
                'qr_id' => $qr->_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->away($qr->original_url);
        }
    }

    private function redirectToSMS($qr)
    {
        try {
            $original = $qr->original_url;

            if (strpos($original, ':') !== false) {
                [$phone, $message] = explode(':', $original, 2);
                $smsUrl = "sms:" . trim($phone) . "?body=" . urlencode(trim($message));
            } else {
                $smsUrl = "sms:" . trim($original);
            }

            return redirect()->away($smsUrl);
        } catch (\Exception $e) {
            Log::error('Redirect to SMS error', [
                'qr_id' => $qr->_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->away($qr->original_url);
        }
    }

    private function redirectToPhone($qr)
    {
        try {
            $phone = preg_replace('/\D/', '', $qr->original_url);

            if ($phone && !str_starts_with($phone, '+')) {
                $phone = '+' . $phone;
            }

            return redirect()->away("tel:{$phone}");
        } catch (\Exception $e) {
            Log::error('Redirect to phone error', [
                'qr_id' => $qr->_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->away($qr->original_url);
        }
    }

    private function redirectToEmail($qr)
    {
        try {
            $original = $qr->original_url;

            if (str_starts_with($original, 'mailto:')) {
                return redirect()->away($original);
            }

            if (filter_var($original, FILTER_VALIDATE_EMAIL)) {
                return redirect()->away("mailto:{$original}");
            }

            return redirect()->away($original);
        } catch (\Exception $e) {
            Log::error('Redirect to email error', [
                'qr_id' => $qr->_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->away($qr->original_url);
        }
    }

    private function redirectToWhatsApp($qr)
    {
        try {
            $original = $qr->original_url;

            if (str_starts_with($original, 'https://wa.me/')) {
                return redirect()->away($original);
            }

            $phone = preg_replace('/\D/', '', $original);

            if (strlen($phone) > 10 && $phone[0] === '0') {
                $phone = substr($phone, 1);
            }

            return redirect()->away("https://wa.me/{$phone}");
        } catch (\Exception $e) {
            Log::error('Redirect to whatsapp error', [
                'qr_id' => $qr->_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->away($qr->original_url);
        }
    }
}
