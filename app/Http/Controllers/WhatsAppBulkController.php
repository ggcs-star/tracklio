<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Contact;
use App\Models\Notification;
use App\Models\WhatsAppAccount;
use App\Models\WhatsAppMessageLog;
use App\Services\WhatsAppService;

class WhatsAppBulkController extends Controller
{
    public function prepare(Request $request)
    {
        try {
            $request->validate([
                'whatsapp_account_id' => 'required|string',
                'contact_ids'         => 'required|array',
                'template_name'       => 'required|string',
                'payload'             => 'required|array',
            ]);

            $userId = (string) Auth::id();

            $account = WhatsAppAccount::where('_id', $request->whatsapp_account_id)
                ->where('user_id', $userId)
                ->firstOrFail();

            $contacts = Contact::whereIn('_id', $request->contact_ids)
                ->where('user_id', $userId)
                ->where('opt_in', true)
                ->get();

            if ($contacts->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No valid opt-in contacts found',
                ], 422);
            }

            $logs = [];

            foreach ($contacts as $contact) {
                $log = WhatsAppMessageLog::createPending([
                    'user_id'             => $userId,
                    'whatsapp_account_id' => (string) $account->_id,
                    'contact_id'          => (string) $contact->_id,
                    'phone_number'        => $contact->phone_number,
                    'template_name'       => $request->template_name,
                    'payload'             => $request->payload,
                ]);

                $logs[] = $log->_id;
            }

            return response()->json([
                'success'        => true,
                'message'        => 'Bulk messages prepared',
                'total_contacts' => count($logs),
                'log_ids'        => $logs,
            ]);
        } catch (\Throwable $e) {
            Log::error('WhatsApp bulk prepare error', [
                'user_id' => Auth::id(),
                'payload' => $request->all(),
                'error'   => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to prepare bulk messages',
            ], 500);
        }
    }

    public function execute(Request $request)
    {
        try {
            $request->validate([
                'log_ids' => 'required|array',
            ]);

            $userId = (string) auth()->id();

            $logs = WhatsAppMessageLog::whereIn('_id', $request->log_ids)
                ->where('user_id', $userId)
                ->where('status', 'pending')
                ->get();

            if ($logs->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No pending messages to send',
                ], 422);
            }

            $accountId = (string) $logs->first()->whatsapp_account_id;

            $account = WhatsAppAccount::where('_id', $accountId)
                ->where('user_id', $userId)
                ->firstOrFail();

            $service = new WhatsAppService(
                decrypt($account->access_token),
                $account->phone_number_id
            );

            $sent = 0;
            $failed = 0;

            foreach ($logs as $log) {
                try {
                    $service->sendTemplate(
                        $log->phone_number,
                        $log->template_name,
                        $log->payload['body'] ?? []
                    );

                    $log->markAsSent();
                    $sent++;
                } catch (\Throwable $e) {
                    $log->markAsFailed($e->getMessage());
                    $failed++;
                }

                usleep(200000);
            }
            Notification::create([
    'user_id' => (string) auth()->id(),
    'type'    => 'whatsapp_bulk_sent',
    'message' => "Bulk WhatsApp send completed: {$sent} sent, {$failed} failed",
    'is_read' => false,
]);


            return response()->json([
                'success' => true,
                'message' => 'Bulk send completed',
                'sent'    => $sent,
                'failed'  => $failed,
            ]);
        } catch (\Throwable $e) {
            Log::error('WhatsApp bulk execute error', [
                'user_id' => auth()->id(),
                'log_ids' => $request->log_ids ?? [],
                'error'   => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Bulk send failed',
            ], 500);
        }
    }
}
