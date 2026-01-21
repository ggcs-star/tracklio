<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;
use App\Models\Subscription;

class SubscriptionController extends Controller
{
   
public function create()
{
    $user = Auth::user();

    $exists = Subscription::where('user_id', (string)$user->_id)
        ->where('status', 'active')
        ->first();

    if ($exists) {
        return response()->json([
            'message' => 'Subscription already active'
        ], 409);
    }

    $api = new Api(
        config('services.razorpay.key'),
        config('services.razorpay.secret')
    );

    $subscription = $api->subscription->create([
        'plan_id' => env('RAZORPAY_PLAN_ID'),
        'total_count' => 12, 
        'customer_notify' => 1,
    ]);

    Subscription::create([
        'user_id' => (string)$user->_id,
        'razorpay_subscription_id' => $subscription->id,
        'status' => 'pending',
    ]);

    return response()->json([
        'subscription_id' => $subscription->id,
        'key' => config('services.razorpay.key'),
    ]);
}

    
    public function verify(Request $request)
    {
        $request->validate([
            'razorpay_payment_id' => 'required|string',
            'razorpay_subscription_id' => 'required|string',
            'razorpay_signature' => 'required|string',
        ]);

        $api = new Api(
            config('services.razorpay.key'),
            config('services.razorpay.secret')
        );

        try {
            $api->utility->verifyPaymentSignature([
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_subscription_id' => $request->razorpay_subscription_id,
                'razorpay_signature' => $request->razorpay_signature,
            ]);

            Subscription::where(
                'razorpay_subscription_id',
                $request->razorpay_subscription_id
            )->update([
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'status' => 'active',
            ]);

            return response()->json([
                'status' => 'success'
            ]);

        } catch (SignatureVerificationError $e) {
            return response()->json([
                'status' => 'failed',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function cancel()
    {
        $user = Auth::user();

        $subscription = Subscription::where('user_id', (string)$user->_id)
            ->where('status', 'active')
            ->firstOrFail();

        $api = new Api(
            config('services.razorpay.key'),
            config('services.razorpay.secret')
        );

        $api->subscription->fetch(
            $subscription->razorpay_subscription_id
        )->cancel();

        $subscription->update([
            'status' => 'cancelled'
        ]);

        return response()->json([
            'message' => 'Subscription cancelled'
        ]);
    }
}
