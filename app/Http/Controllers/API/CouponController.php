<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\ProductCoupon;
use App\Models\UserCoupon;
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
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
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
                'product_coupons.*.product_id' => 'required|exists:products,id',
                'user_coupons.*.user_id' => 'required|exists:users,id',
                'user_coupons.*.is_used' => 'required|boolean',
                'user_coupons.*.used_date' => 'nullable|date',
            ]);

            $coupon = Coupon::create($validatedData);

            // Create product coupons
            if (isset($validatedData['product_coupons'])) {
                foreach ($validatedData['product_coupons'] as $productCouponData) {
                    $productCoupon = new ProductCoupon($productCouponData);
                    $coupon->productCoupons()->save($productCoupon);
                }
            }

            // Create user coupons
            if (isset($validatedData['user_coupons'])) {
                foreach ($validatedData['user_coupons'] as $userCouponData) {
                    $userCoupon = new UserCoupon($userCouponData);
                    $coupon->userCoupons()->save($userCoupon);
                }
            }

            return response()->json(['success' => true, 'data' => $coupon], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function show(Coupon $coupon)
    {
        try {
            $coupon = $coupon->with('productCoupons', 'userCoupons')->get();
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
                'product_coupons.*.product_id' => 'required|exists:products,id',
                'user_coupons.*.user_id' => 'required|exists:users,id',
                'user_coupons.*.is_used' => 'required|boolean',
                'user_coupons.*.used_date' => 'nullable|date',
            ]);

            $coupon->update($validatedData);

            // Update product coupons
            if (isset($validatedData['product_coupons'])) {
                $coupon->productCoupons()->delete();
                foreach ($validatedData['product_coupons'] as $productCouponData) {
                    $productCoupon = new ProductCoupon($productCouponData);
                    $coupon->productCoupons()->save($productCoupon);
                }
            }

            // Update user coupons
            if (isset($validatedData['user_coupons'])) {
                $coupon->userCoupons()->delete();
                foreach ($validatedData['user_coupons'] as $userCouponData) {
                    $userCoupon = new UserCoupon($userCouponData);
                    $coupon->userCoupons()->save($userCoupon);
                }
            }

            return response()->json(['success' => true, 'data' => $coupon]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function destroy(Coupon $coupon)
    {
        try {
            $coupon->productCoupons()->delete();
            $coupon->userCoupons()->delete();
            $coupon->delete();

            return response()->json(['success' => true], 204);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
