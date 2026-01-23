<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Razorpay\Api\Api;
use App\Models\Plan;

class PlanController extends Controller
{
    /**
     * Create Razorpay Plan + Save to DB
     * WITHOUT WEBHOOK
     */
    public function create(Request $request)
    {
        // ✅ Validate request
        $request->validate([
            'name'     => 'required|string|max:255',
            'amount'   => 'required|numeric|min:1',
            'interval' => 'required|in:month,year',
        ]);

        // ✅ Prevent duplicate plans (important)
        $existingPlan = Plan::where('name', $request->name)
            ->where('interval', $request->interval)
            ->where('status', 'active')
            ->first();

        if ($existingPlan) {
            return response()->json([
                'success' => false,
                'message' => 'Plan already exists',
                'plan'    => $existingPlan
            ], 409);
        }

        DB::beginTransaction();

        try {

            // ✅ Razorpay init
            $api = new Api(
                config('services.razorpay.key'),
                config('services.razorpay.secret')
            );

            // ✅ Create Razorpay Plan
            $razorpayPlan = $api->plan->create([
                'period'   => $request->interval,
                'interval' => 1,
                'item' => [
                    'name'     => $request->name,
                    'amount'   => (int) ($request->amount * 100), // paise
                    'currency' => config('services.razorpay.currency', 'INR'),
                ],
            ]);

            // ✅ Save Plan in DB (single source of truth)
            $plan = Plan::create([
                'plan_key'          => strtolower(
                    str_replace(' ', '_', $request->name . '_' . $request->interval)
                ),
                'razorpay_plan_id'  => $razorpayPlan->id,
                'name'              => $request->name,
                'amount'            => $request->amount,
                'currency'          => config('services.razorpay.currency', 'INR'),
                'interval'          => $request->interval,
                'interval_count'    => 1,
                'status'            => 'active',
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Plan created successfully',
                'plan'    => $plan
            ], 201);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Plan creation failed',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all active plans (for UI)
     */
    public function index()
    {
        $plans = Plan::where('status', 'active')
            ->orderBy('amount')
            ->get();

        return response()->json([
            'success' => true,
            'plans'   => $plans
        ]);
    }

    /**
     * Disable a plan (soft delete)
     */
    public function deactivate($id)
    {
        $plan = Plan::findOrFail($id);

        $plan->update([
            'status' => 'inactive'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Plan deactivated successfully'
        ]);
    }
}
