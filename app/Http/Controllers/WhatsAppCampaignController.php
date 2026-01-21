<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use App\Models\WhatsAppAccount;
use App\Models\WhatsAppCampaign;
use App\Models\WhatsAppCampaignSend;
use App\Models\BroadcastGroup;
use App\Models\BroadcastGroupContact;
use App\Models\Contact;
use App\Models\Notification;


use App\Services\WhatsAppService;

class WhatsAppCampaignController extends Controller
{
   
    public function index()
    {
        try {
            $userId = (string) Auth::id();

            // Campaigns
            $campaigns = WhatsAppCampaign::where('user_id', $userId)
                ->latest()
                ->get()
                ->map(fn ($c) => [
                    '_id'    => (string) $c->_id,
                    'name'   => $c->name,
                    'status' => $c->status ?? 'active',
                ])
                ->values();

            // Broadcast Groups
            $groups = BroadcastGroup::where('user_id', $userId)
                ->get()
                ->map(function ($g) {
                    return [
                        '_id' => (string) $g->_id,
                        'name' => $g->name,
                        'contacts_count' => BroadcastGroupContact::where(
                            'group_id',
                            (string) $g->_id
                        )->count(),
                    ];
                })
                ->values();

            return view('whatsapp_accounts.campaigns', [
                'campaigns' => $campaigns,
                'groups'    => $groups, // ✅ IMPORTANT
                'account'   => WhatsAppAccount::where('user_id', $userId)->first(),
            ]);

        } catch (\Throwable $e) {
            Log::error('WhatsApp campaign index error', [
                'user_id' => Auth::id(),
                'error'   => $e->getMessage(),
            ]);

            abort(500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
            ]);

            $campaign = WhatsAppCampaign::create([
                'user_id' => (string) Auth::id(),
                'name'    => $request->name,
                'status'  => 'active', 
            ]);
            Notification::create([
    'user_id' => (string) Auth::id(),
    'type'    => 'whatsapp_campaign_created',
    'message' => 'WhatsApp campaign created successfully',
    'is_read' => false,
]);


            return response()->json([
                'success' => true,
                'data'    => [
                    '_id'    => (string) $campaign->_id,
                    'name'   => $campaign->name,
                    'status' => $campaign->status,
                ],
            ]);

        } catch (\Throwable $e) {
            Log::error('WhatsApp campaign store error', [
                'user_id' => Auth::id(),
                'payload' => $request->all(),
                'error'   => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create campaign',
            ], 500);
        }
    }

    public function send(Request $request)
    {Log::info('Send payload', $request->all());

        try {
            $request->validate([
                'campaign_id' => 'required|string',
                'group_id'    => 'required|string',
                'template'    => 'nullable|string',
            ]);

            $userId = (string) Auth::id();

            // Campaign validation
            $campaign = WhatsAppCampaign::where('_id', $request->campaign_id)
                ->where('user_id', $userId)
                ->firstOrFail();

            // WhatsApp account
            $account = WhatsAppAccount::where('user_id', $userId)
                ->firstOrFail();

            // Resolve group → contacts
            $contactIds = BroadcastGroupContact::where(
                    'group_id',
                    (string) $request->group_id
                )
                ->pluck('contact_id')
                ->toArray();

            $numbers = Contact::whereIn('_id', $contactIds)
                ->pluck('phone_number')
                ->toArray();

            $sent = 0;
            $failed = 0;
            $sentContacts = [];

            foreach ($numbers as $mobile) {
                try {
                    $mobile = preg_replace('/\D/', '', $mobile);

                    if (strlen($mobile) === 10) {
                        $mobile = '91' . $mobile;
                    }

                    if (strlen($mobile) !== 12) {
                        throw new \Exception('Invalid mobile');
                    }

                    WhatsAppService::sendTemplate(
                        $mobile,
                        $request->template ?? 'hello_world'
                    );

                    $sent++;
                    $sentContacts[] = $mobile;

                } catch (\Throwable $e) {
                    $failed++;

                    Log::error('Campaign send failed', [
                        'campaign_id' => (string) $campaign->_id,
                        'to' => $mobile,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            /**
             * 🔥 SAVE SEND HISTORY
             */
            WhatsAppCampaignSend::create([
                'user_id'             => $userId,
                'campaign_id'         => (string) $campaign->_id,
                'whatsapp_account_id' => (string) $account->_id,
                'template_name'       => $request->template ?? 'hello_world',
                'payload'             => [],
                'group_ids'           => [(string) $request->group_id], // ✅ group saved
                'contact_ids'         => $sentContacts,
                'sent'                => $sent,
                'failed'              => $failed,
                'created_at'          => now(),
            ]);
            Notification::create([
            'user_id' => (string) Auth::id(),
            'type'    => 'whatsapp_campaign_sent',
            'message' => "Campaign sent: {$sent} sent, {$failed} failed",
            'is_read' => false,
        ]);


            return response()->json([
                'success'  => true,
                'sent'     => $sent,
                'failed'   => $failed,
                'campaign' => [
                    '_id'    => (string) $campaign->_id,
                    'name'   => $campaign->name,
                    'status' => $campaign->status,
                ],
            ]);

        } catch (\Throwable $e) {
            Log::error('WhatsApp campaign send error', [
                'user_id' => Auth::id(),
                'error'   => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Campaign send failed',
            ], 500);
        }
    }
}
