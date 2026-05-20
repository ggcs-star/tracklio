<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;
use Illuminate\Support\Facades\DB;


class SubscriptionController extends Controller
{
    /**
     * STEP 1 — Create Razorpay ORDER
     */
public function create(Request $request)
{
    $request->validate(['plan_id' => 'required|string']);
    $user = Auth::user();

    // ❌ Block if active subscription exists
    $active = Subscription::where('user_id', (string)$user->_id)
        ->where('status', 'active')
        ->where('expires_at', '>', now())
        ->exists();

    if ($active) {
        return response()->json(['message' => 'Subscription already active'], 409);
    }

    // 🧹 Lazy expiry (NO CRON NEEDED)
    Transaction::where('user_id', (string)$user->_id)
        ->where('status', 'pending')
        ->where('created_at', '<', now()->subMinutes(15))
        ->update(['status' => 'expired']);

    $plan = Plan::where('_id', $request->plan_id)
        ->where('status', 'active')
        ->firstOrFail();

    // ♻️ Reuse valid pending order
    $existingTxn = Transaction::where('user_id', (string)$user->_id)
        ->where('plan_id', (string)$plan->_id)
        ->where('status', 'pending')
        ->first();

    if ($existingTxn) {
        return response()->json([
            'order_id' => $existingTxn->razorpay_order_id,
            'key'      => config('services.razorpay.key'),
            'amount'   => $existingTxn->amount,
            'plan_id'  => (string)$existingTxn->plan_id
        ]);
    }

    // 🆕 Create new Razorpay order
    $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));

    $order = $api->order->create([
        'amount'   => $plan->amount * 100,
        'currency' => $plan->currency,
        'receipt'  => 'rcpt_' . uniqid(),
    ]);

    Transaction::create([
        'user_id' => (string)$user->_id,
        'plan_id' => (string)$plan->_id,
        'razorpay_order_id' => $order->id,
        'amount' => $plan->amount,
        'currency' => $plan->currency,
        'status' => 'pending',
    ]);

    return response()->json([
        'order_id' => $order->id,
        'key'      => config('services.razorpay.key'),
        'amount'   => $plan->amount,
        'plan_id'  => (string)$plan->_id
    ]);
}



public function verify(Request $request)
{
    $request->validate([
        'razorpay_payment_id' => 'required|string',
        'razorpay_order_id'   => 'required|string',
        'razorpay_signature'  => 'required|string',
    ]);

    $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));

    try {
        DB::transaction(function () use ($request, $api) {

            // 🔒 Lock transaction row
            $txn = Transaction::where('razorpay_order_id', $request->razorpay_order_id)
                ->lockForUpdate()
                ->firstOrFail();

            // Already processed
            if ($txn->status === 'paid') {
                return;
            }

            // 🔐 Signature verify
            $api->utility->verifyPaymentSignature([
                'razorpay_order_id'   => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature'  => $request->razorpay_signature,
            ]);

            $payment = $api->payment->fetch($request->razorpay_payment_id);

            // 💰 Ensure payment captured
            if ($payment->status !== 'captured') {
                throw new \Exception('Payment not captured');
            }

            // 🎯 Plan comes from DB, not request
            $plan = Plan::findOrFail($txn->plan_id);

            // 💸 Amount validation
            if (($payment->amount / 100) != $plan->amount) {
                throw new \Exception('Amount mismatch');
            }

            // 🧹 Expire old subscription
            Subscription::where('user_id', $txn->user_id)
                ->where('status', 'active')
                ->update(['status' => 'expired']);

            $expires = $plan->interval === 'month'
                ? now()->addMonth()
                : now()->addYear();

            $subscription = Subscription::create([
                'user_id'    => $txn->user_id,
                'plan_id'    => (string)$plan->_id,
                'plan_name'  => $plan->name,
                'price'      => $plan->amount,
                'currency'   => $plan->currency,
                'interval'   => $plan->interval,
                'status'     => 'active',
                'started_at' => now(),
                'expires_at' => $expires,
            ]);

            // 💾 Update transaction
            $txn->update([
                'subscription_id'     => (string)$subscription->_id,
                'razorpay_payment_id' => $payment->id,
                'status'              => 'paid',
                'method'              => $payment->method,
                'paid_at'             => Carbon::createFromTimestamp($payment->created_at),
                'raw'                 => $payment->toArray(),
            ]);

            Notification::create([
                'user_id' => $txn->user_id,
                'type'    => 'success',
                'message' => '🎉 Subscription activated successfully!',
                'is_read' => false,
            ]);
        });

        return response()->json(['status' => 'success']);

    } catch (SignatureVerificationError $e) {
        return response()->json(['status' => 'failed', 'message' => 'Signature failed'], 400);

    } catch (\Exception $e) {
        return response()->json(['status' => 'failed', 'message' => $e->getMessage()], 400);
    }
}

}
