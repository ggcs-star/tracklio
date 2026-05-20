<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Plan;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class PlanController extends Controller
{
    /**
     * Create new subscription plan (DB only)
     */
    public function create(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'amount'   => 'required|numeric|min:1',
            'interval' => 'required|in:month,year',
        ]);

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
            $plan = Plan::create([
                'plan_key'       => strtolower(str_replace(' ', '_', $request->name . '_' . $request->interval)),
                'name'           => $request->name,
                'amount'         => $request->amount,
                'currency'       => config('services.razorpay.currency', 'INR'),
                'interval'       => $request->interval,
                'interval_count' => 1,
                'status'         => 'active',
            ]);

            DB::commit();

            Notification::create([
                'user_id' => Auth::id(),
                'type'    => 'success',
                'message' => 'New plan "' . $plan->name . '" created successfully.',
                'is_read' => false,
            ]);

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
     * List active plans
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
     * Deactivate plan
     */
    public function deactivate($id)
    {
        $plan = Plan::findOrFail($id);

        $plan->update([
            'status' => 'inactive'
        ]);

        Notification::create([
            'user_id' => Auth::id(),
            'type'    => 'warning',
            'message' => '⚠️ Plan "' . $plan->name . '" has been deactivated.',
            'is_read' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Plan deactivated successfully'
        ]);
    }
}
