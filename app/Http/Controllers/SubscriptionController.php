<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;



class SubscriptionController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|string'
        ]);

        $user = Auth::user();

        // 🔹 Already active?
        $exists = Subscription::where('user_id', (string) $user->_id)
            ->where('status', 'active')
            ->first();

        if ($exists) {
            return response()->json([
                'message' => 'Subscription already active'
            ], 409);
        }

        // 🔹 Fetch plan from DB (SINGLE SOURCE OF TRUTH)
        $plan = Plan::where('_id', $request->plan_id)
            ->where('status', 'active')
            ->first();

        if (!$plan) {
            return response()->json(['message' => 'Invalid plan'], 400);
        }

        $api = new Api(
            config('services.razorpay.key'),
            config('services.razorpay.secret')
        );

  $rzpSubscription = $api->subscription->create([
    'plan_id' => $plan->razorpay_plan_id,
    'customer_notify' => 1,

    // 🔥 REQUIRED BY RAZORPAY
    'total_count' => 120, // 120 months = 10 years
]);


        // 🔹 Save subscription in DB
        Subscription::create([
            'user_id' => (string) $user->_id,

            'plan_id'   => (string) $plan->_id,
            'plan_name' => $plan->name,
            'price'     => $plan->amount,
            'currency'  => $plan->currency,
            'interval'  => $plan->interval,

            'razorpay_plan_id' => $plan->razorpay_plan_id,
            'razorpay_subscription_id' => $rzpSubscription->id,

            'status' => 'pending',
        ]);

        return response()->json([
            'subscription_id' => $rzpSubscription->id,
            'payment_link'    => $rzpSubscription->short_url,
            'key'             => config('services.razorpay.key'),
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

        // 1️⃣ Signature verify
        $api->utility->verifyPaymentSignature([
            'razorpay_payment_id' => $request->razorpay_payment_id,
            'razorpay_subscription_id' => $request->razorpay_subscription_id,
            'razorpay_signature' => $request->razorpay_signature,
        ]);

        // 2️⃣ Fetch payment
        $payment = $api->payment->fetch($request->razorpay_payment_id);

        // 3️⃣ Fetch subscription
        $subscription = Subscription::where(
            'razorpay_subscription_id',
            $request->razorpay_subscription_id
        )->firstOrFail();

        // 4️⃣ Activate subscription
        $subscription->update([
            'razorpay_payment_id' => $payment->id,
            'status' => 'active',
        ]);

        // 5️⃣ Save transaction
        Transaction::create([
            'user_id' => $subscription->user_id,
            'subscription_id' => (string) $subscription->_id,
            'plan_id' => $subscription->plan_id,

            'razorpay_payment_id' => $payment->id,
            'razorpay_subscription_id' => $subscription->razorpay_subscription_id,
            'razorpay_order_id' => $payment->order_id ?? null,

            'amount' => $payment->amount / 100,
            'currency' => $payment->currency,
            'status' => $payment->status,
            'method' => $payment->method,

            'upi_vpa' => $payment->method === 'upi'
                ? ($payment->vpa ?? null)
                : null,

            'card_last4' => $payment->method === 'card'
                ? ($payment->card->last4 ?? null)
                : null,

            'bank' => $payment->bank ?? null,
            'raw' => json_encode($payment->toArray()),
            'paid_at' => Carbon::createFromTimestamp($payment->created_at),
        ]);

        // ✅ 🔥 THIS WAS MISSING
        return response()->json([
            'status' => 'success'
        ]);

    } catch (SignatureVerificationError $e) {
        return response()->json([
            'status' => 'failed',
            'error' => 'Signature verification failed',
            'message' => $e->getMessage()
        ], 400);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'failed',
            'error' => 'Verification error',
            'message' => $e->getMessage()
        ], 500);
    }
}



    public function cancel()
    {
        $user = Auth::user();

        $subscription = Subscription::where('user_id', (string) $user->_id)
            ->where('status', 'active')
            ->firstOrFail();

        $api = new Api(
            config('services.razorpay.key'),
            config('services.razorpay.secret')
        );

        $api->subscription
            ->fetch($subscription->razorpay_subscription_id)
            ->cancel();

        $subscription->update([
            'status' => 'cancelled'
        ]);

        return response()->json([
            'message' => 'Subscription cancelled'
        ]);
    }

    // 🔹 Manual sync (WITHOUT WEBHOOK safety)
    public function syncStatus()
    {
        $user = Auth::user();

        $subscription = Subscription::where('user_id', (string) $user->_id)
            ->where('status', 'pending')
            ->first();

        if (!$subscription) {
            return response()->json(['status' => 'no_pending']);
        }

        $api = new Api(
            config('services.razorpay.key'),
            config('services.razorpay.secret')
        );

        $rzpSub = $api->subscription->fetch(
            $subscription->razorpay_subscription_id
        );

        if ($rzpSub->status === 'active') {
            $subscription->update([
                'status' => 'active'
            ]);
        }

        return response()->json([
            'razorpay_status' => $rzpSub->status,
            'db_status' => $subscription->status
        ]);
    }
}
