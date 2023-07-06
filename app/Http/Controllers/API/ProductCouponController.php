<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ProductCoupon;
use Illuminate\Http\Request;

class ProductCouponController extends Controller
{
    public function index()
    {
        try {
            $productCoupons = ProductCoupon::all();

            if ($productCoupons->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'No product coupons found']);
            }

            return response()->json(['success' => true, 'data' => $productCoupons]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'product_id' => 'required|exists:products,id',
                'coupon_id' => 'required|exists:coupons,id',
            ]);

            $productCoupon = ProductCoupon::create($validatedData);
            return response()->json(['success' => true, 'data' => $productCoupon], 201);
        } catch (\Exception$e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function show(ProductCoupon $productCoupon)
    {
        try {
            return response()->json(['success' => true, 'data' => $productCoupon]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function update(Request $request, ProductCoupon $productCoupon)
    {
        try {
            $validatedData = $request->validate([
                'product_id' => 'required|exists:products,id',
                'coupon_id' => 'required|exists:coupons,id',
            ]);

            $productCoupon->update($validatedData);
            return response()->json(['success' => true, 'data' => $productCoupon]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function destroy(ProductCoupon $productCoupon)
    {
        try {
            $productCoupon->delete();
            return response()->json(['success' => true], 204);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
