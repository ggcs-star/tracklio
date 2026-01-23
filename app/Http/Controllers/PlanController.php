<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Razorpay\Api\Api;
use App\Models\Plan;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;


class PlanController extends Controller
{
   
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

            
            $api = new Api(
                config('services.razorpay.key'),
                config('services.razorpay.secret')
            );

           
            $razorpayPlan = $api->plan->create([
                'period'   => $request->interval,
                'interval' => 1,
                'item' => [
                    'name'     => $request->name,
                    'amount'   => (int) ($request->amount * 100), // paise
                    'currency' => config('services.razorpay.currency', 'INR'),
                ],
            ]);

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
