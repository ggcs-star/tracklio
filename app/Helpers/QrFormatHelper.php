<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;

class QrFormatHelper
{
    public function formatQrData($type, $data)
    {
        try {
            if (!is_array($data)) {
                $data = ['value' => $data];
            }

            switch ($type) {
                case 'text':
                    return $data['value'] ?? '';

                case 'link':
                    $url = $data['value'] ?? '';
                    return preg_match('~^https?://~', $url) ? $url : 'https://' . $url;

                case 'email':
                    return "mailto:{$data['to']}?subject=" .
                        urlencode($data['subject'] ?? '') .
                        "&body=" . urlencode($data['message'] ?? '');

                case 'phone':
                    return 'tel:' . preg_replace('/\D/', '', $data['value'] ?? '');

                case 'sms':
                    return "sms:{$data['phone']}?body=" .
                        urlencode($data['message'] ?? '');

                case 'whatsapp':
                    return "https://wa.me/" .
                        preg_replace('/\D/', '', $data['phone'] ?? '') .
                        "?text=" . urlencode($data['message'] ?? '');

                case 'cryptocurrency':
                    return ($data['type'] ?? 'bitcoin') . ':' . ($data['address'] ?? '');

                default:
                    return $data['value'] ?? '';
            }
        } catch (\Exception $e) {
            Log::error('Format QR data error', [
                'type' => $type,
                'data' => $data,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return '';
        }
    }

    public function generateVCard($data)
    {
        try {
            return "BEGIN:VCARD
VERSION:3.0
FN:" . ($data['firstName'] ?? '') . " " . ($data['lastName'] ?? '') . "
ORG:" . ($data['organization'] ?? '') . "
TEL;TYPE=WORK:" . ($data['phone'] ?? '') . "
TEL;TYPE=CELL:" . ($data['cell'] ?? '') . "
TEL;TYPE=FAX:" . ($data['fax'] ?? '') . "
EMAIL:" . ($data['email'] ?? '') . "
URL:" . ($data['website'] ?? '') . "
END:VCARD";
        } catch (\Exception $e) {
            Log::error('Generate vcard error', [
                'data' => $data,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return "BEGIN:VCARD\nVERSION:3.0\nFN:Contact\nEND:VCARD";
        }
    }

    public function generateICal($data)
    {
        try {
            $start = date('Ymd\THis\Z', strtotime($data['start'] ?? now()));
            $end = date('Ymd\THis\Z', strtotime($data['end'] ?? now()->addHour()));

            return "BEGIN:VCALENDAR
VERSION:2.0
BEGIN:VEVENT
SUMMARY:" . ($data['title'] ?? 'Event') . "
DESCRIPTION:" . ($data['description'] ?? '') . "
LOCATION:" . ($data['location'] ?? '') . "
URL:" . ($data['url'] ?? '') . "
DTSTART:" . $start . "
DTEND:" . $end . "
END:VEVENT
END:VCALENDAR";
        } catch (\Exception $e) {
            Log::error('Generate ical error', [
                'data' => $data,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return "BEGIN:VCALENDAR\nVERSION:2.0\nEND:VCALENDAR";
        }
    }
}
