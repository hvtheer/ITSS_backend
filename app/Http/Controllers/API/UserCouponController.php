<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\UserCoupon;
use Illuminate\Http\Request;

class UserCouponController extends Controller
{
    public function index()
    {
        try {
            $userCoupons = UserCoupon::all();

            if ($userCoupons->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'No user coupons found']);
            }

            return response()->json(['success' => true, 'data' => $userCoupons]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'user_id' => 'required|exists:users,id',
                'coupon_id' => 'required|exists:coupons,id',
                'is_used' => 'required|boolean',
                'used_date' => 'nullable|date',
            ]);

            $userCoupon = UserCoupon::create($validatedData);
            return response()->json(['success' => true, 'data' => $userCoupon], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function show(UserCoupon $userCoupon)
    {
        try {
            return response()->json(['success' => true, 'data' => $userCoupon]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function update(Request $request, UserCoupon $userCoupon)
    {
        try {
            $validatedData = $request->validate([
                'user_id' => 'required|exists:users,id',
                'coupon_id' => 'required|exists:coupons,id',
                'is_used' => 'required|boolean',
                'used_date' => 'nullable|date',
            ]);

            $userCoupon->update($validatedData);
            return response()->json(['success' => true, 'data' => $userCoupon]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function destroy(UserCoupon $userCoupon)
    {
        try {
            $userCoupon->delete();
            return response()->json(['success' => true], 204);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
