<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ContactsController extends Controller
{
    public function index()
    {
        try {
            $userId = (string) Auth::id();

            $contacts = Contact::where('user_id', $userId)
                ->latest()
                ->get()
                ->map(fn ($c) => [
                    '_id'          => (string) $c->_id,
                    'name'         => $c->name,
                    'phone_number' => $c->phone_number,
                    'opt_in'       => (bool) $c->opt_in,
                    'source'       => $c->source ?? 'manual',
                ])
                ->values();

            return response()->json([
                'success' => true,
                'data'    => $contacts,
            ]);
        } catch (\Throwable $e) {
            Log::error('Contacts index error', [
                'user_id' => Auth::id(),
                'error'   => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name'         => 'nullable|string|max:255',
                'phone_number' => 'required|string',
                'opt_in'       => 'required|boolean',
            ]);

            $phone = $this->normalizePhone($request->phone_number);

            $contact = Contact::create([
                'user_id'      => (string) Auth::id(),
                'name'         => $request->name,
                'phone_number' => $phone,
                'opt_in'       => $request->opt_in,
                'source'       => 'manual',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Contact added successfully',
                'data'    => [
                    '_id'          => (string) $contact->_id,
                    'name'         => $contact->name,
                    'phone_number' => $contact->phone_number,
                    'opt_in'       => (bool) $contact->opt_in,
                    'source'       => $contact->source,
                ],
            ]);
        } catch (\Throwable $e) {
            Log::error('Contact store error', [
                'user_id' => Auth::id(),
                'payload' => $request->all(),
                'error'   => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to add contact',
            ], 500);
        }
    }
public function uploadContacts(Request $request)
{
    $request->validate([
        'file' => 'required|file|max:8192',
    ]);

    $file = $request->file('file');
    $ext  = strtolower($file->getClientOriginalExtension());

    if ($ext === 'csv') {
        return $this->uploadCsv($file);
    }

    if ($ext === 'vcf') {
        return $this->uploadVcf($file);
    }

    return back()->with('error', 'Only CSV or VCF files supported');
}

private function uploadVcf($file)
{
    set_time_limit(0);

    $content = file_get_contents($file->getRealPath());

    // unfold folded lines
    $content = preg_replace("/\r\n[ \t]/", '', $content);

    $cards = preg_split('/END:VCARD/i', $content);

    $userId = (string) auth()->id();

    $batch = [];
    $inserted = 0;

    foreach ($cards as $card) {
        if (!trim($card)) continue;

        // ---- NAME ----
        preg_match('/^FN:(.+)$/mi', $card, $fn);

        if (empty($fn)) {
            preg_match('/^N:(.+)$/mi', $card, $n);
            if (!empty($n[1])) {
                $p = explode(';', $n[1]);
                $fn[1] = trim(($p[1] ?? '') . ' ' . ($p[0] ?? ''));
            }
        }

        $name = isset($fn[1]) ? trim($fn[1]) : null;

        // ---- ALL TEL ----
        preg_match_all('/TEL[^:]*:(.+)/i', $card, $tels);

        if (empty($tels[1])) continue;

        foreach ($tels[1] as $rawPhone) {

            $rawPhone = trim($rawPhone);
            if ($rawPhone === '') continue;

            // KEEP RAW VALUE (NO COLLISION)
            $phone = $rawPhone;

            $batch[] = [
                'user_id'      => $userId,
                'name'         => $name,
                'phone_number' => $phone,
                'opt_in'       => true,
                'source'       => 'vcf',
                'created_at'   => now(),
                'updated_at'   => now(),
            ];

            $inserted++;

            if (count($batch) === 500) {
                Contact::insert($batch);
                $batch = [];
            }
        }
    }

    if (!empty($batch)) {
        Contact::insert($batch);
    }

    return back()->with(
        'success',
        "{$inserted} numbers imported (no filtering, no dedupe)"
    );
}


public function listForBroadcast()
{
    return response()->json([
        'success' => true,
        'data' => Contact::where('user_id', (string) auth()->id())
            ->get([
                '_id',
                'name',
                'phone_number'
            ])
            ->map(fn ($c) => [
                'id' => (string) $c->_id,
                'name' => $c->name ?? 'Unknown',
                'phone' => $c->phone_number,
            ])
    ]);
}


private function normalizePhone(string $phone): string
{
    $phone = preg_replace('/\D/', '', $phone);

    // already country code
    if (strlen($phone) === 12 && str_starts_with($phone, '91')) {
        return $phone;
    }

    // Indian local number
    if (strlen($phone) === 10) {
        return '91' . $phone;
    }

    return $phone;
}

}