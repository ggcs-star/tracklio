<?php

namespace App\Http\Controllers;

use App\Models\WhatsAppAccount;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class WhatsAppAccountController extends Controller
{
    public function page()
    {
        try {
            return view('whatsapp_accounts.index');
        } catch (\Throwable $e) {
            Log::error('WhatsApp page load error', [
                'user_id' => Auth::id(),
                'error'   => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);
            abort(500);
        }
    }

    public function index()
    {
        try {
            $userId = (string) Auth::id();

            $accounts = WhatsAppAccount::where('user_id', $userId)
                ->latest()
                ->get();

            return response()->json([
                'success' => true,
                'data'    => $accounts,
            ]);
        } catch (\Throwable $e) {
            Log::error('WhatsApp accounts index error', [
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
                'provider'             => 'required|string',
                'phone_number_id'      => 'required|string',
                'business_account_id'  => 'required|string',
                'access_token'         => 'required|string',
                'token_expires_at'     => 'nullable|date',
            ]);

            $account = WhatsAppAccount::create([
                'user_id'             => (string) Auth::id(),
                'provider'            => $request->provider,
                'phone_number_id'     => $request->phone_number_id,
                'business_account_id' => $request->business_account_id,
                'access_token'        => encrypt($request->access_token),
                'token_expires_at'    => $request->token_expires_at,
                'status'              => 'active',
            ]);
            Notification::create([
    'user_id' => (string) Auth::id(),
    'type'    => 'whatsapp_account_connected',
    'message' => 'WhatsApp account connected successfully',
    'is_read' => false,
]);


            return response()->json([
                'success' => true,
                'message' => 'WhatsApp account connected successfully',
                'data'    => $account,
            ]);
        } catch (\Throwable $e) {
            Log::error('WhatsApp account store error', [
                'user_id' => Auth::id(),
                'payload' => $request->except('access_token'),
                'error'   => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to connect WhatsApp account',
            ], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            $account = WhatsAppAccount::where('_id', $id)
                ->where('user_id', (string) Auth::id())
                ->firstOrFail();

            $account->delete();
            Notification::create([
    'user_id' => (string) Auth::id(),
    'type'    => 'whatsapp_account_disconnected',
    'message' => 'WhatsApp account disconnected',
    'is_read' => false,
]);


            return response()->json([
                'success' => true,
                'message' => 'WhatsApp account disconnected',
            ]);
        } catch (\Throwable $e) {
            Log::error('WhatsApp account delete error', [
                'user_id'   => Auth::id(),
                'account_id'=> $id,
                'error'     => $e->getMessage(),
                'trace'     => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to disconnect WhatsApp account',
            ], 500);
        }
    }
}
