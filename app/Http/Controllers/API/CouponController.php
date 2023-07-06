<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        try {
            $coupons = Coupon::all();

            if ($coupons->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'No coupons found']);
            }

            return response()->json(['success' => true, 'data' => $coupons]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' =>$e->getMessage()]);
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'code' => 'required',
                'type' => 'required|in:fixed,percent',
                'discounted_amount' => 'nullable|numeric',
                'quantity' => 'required|integer',
                'created_by' => 'required|exists:users,id',
                'start_date' => 'required|date',
                'end_date' => 'required|date',
            ]);

            $coupon = Coupon::create($validatedData);
            return response()->json(['success' => true, 'data' => $coupon], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function show(Coupon $coupon)
    {
        try {
            return response()->json(['success' => true, 'data' => $coupon]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function update(Request $request, Coupon $coupon)
    {
        try {
            $validatedData = $request->validate([
                'code' => 'required',
                'type' => 'required|in:fixed,percent',
                'discounted_amount' => 'nullable|numeric',
                'quantity' => 'required|integer',
                'created_by' => 'required|exists:users,id',
                'start_date' => 'required|date',
                'end_date' => 'required|date',
            ]);

            $coupon->update($validatedData);
            return response()->json(['success' => true, 'data' => $coupon]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function destroy(Coupon $coupon)
    {
        try {
            $coupon->delete();
            return response()->json(['success' => true], 204);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
