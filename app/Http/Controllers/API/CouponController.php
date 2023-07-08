<?php

namespace App\Http\Controllers\API;

use App\Models\Coupon;
use App\Models\CustomerCoupon;
use App\Models\ProductCoupon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Role;

class CouponController extends Controller
{
    public function index()
    {
        try {
            $authenticatedUser = Auth::user();
            
            // Check if the user has the required role
            if (!$authenticatedUser->roleUser ||
                !in_array($authenticatedUser->roleUser->role_id, [Role::ROLE_ADMIN, Role::ROLE_SELLER, Role::ROLE_CUSTOMER])) {
                throw new \Exception('You are not authorized to access the coupons.');
            }

            if ($authenticatedUser->roleUser->role_id === Role::ROLE_ADMIN) {
                $coupons = Coupon::all();
            } elseif ($authenticatedUser->roleUser->role_id === Role::ROLE_SELLER) {
                $coupons = Coupon::where('created_by', $authenticatedUser->id)->get();
            } else {
                $coupons = Coupon::whereHas('customerCoupons', function ($query) use ($authenticatedUser) {
                    $query->where('customer_id', $authenticatedUser->customer->id);
                })->get();
            }

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
            $authenticatedUser = Auth::user();

            // Check if the user has the required role
            if (!$authenticatedUser->roleUser ||
                !in_array($authenticatedUser->roleUser->role_id, [Role::ROLE_ADMIN, Role::ROLE_SELLER])) {
                throw new \Exception('You are not authorized to create a coupon.');
            }

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
                    $userCoupon = new CustomerCoupon($userCouponData);
                    $coupon->customerCoupons()->save($userCoupon);
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
            $authenticatedUser = Auth::user();

            // Check if the user has the required role
            if (!$authenticatedUser->roleUser ||
                ($authenticatedUser->roleUser->role_id !== Role::ROLE_ADMIN &&
                $coupon->created_by !== $authenticatedUser->id &&
                !$coupon->customerCoupons()->where('customer_id', $authenticatedUser->customer->id)->exists())) {
                throw new \Exception('You are not authorized to view this coupon.');
            }

            $coupon = $coupon->load('productCoupons', 'customerCoupons');
            return response()->json(['success' => true, 'data' => $coupon]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function update(Request $request, Coupon $coupon)
    {
        try {
            $authenticatedUser = Auth::user();

            // Check if the user has the required role
            if (!$authenticatedUser->roleUser ||
                !in_array($authenticatedUser->roleUser->role_id, [Role::ROLE_ADMIN, Role::ROLE_SELLER]) ||
                ($authenticatedUser->roleUser->role_id === Role::ROLE_SELLER &&
                $coupon->created_by !== $authenticatedUser->id)) {
                throw new \Exception('You are not authorized to update this coupon.');
            }

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
                $coupon->customerCoupons()->delete();
                foreach ($validatedData['user_coupons'] as $userCouponData) {
                    $userCoupon = new CustomerCoupon($userCouponData);
                    $coupon->customerCoupons()->save($userCoupon);
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
            $authenticatedUser = Auth::user();

            // Check if the user has the required role
            if (!$authenticatedUser->roleUser ||
                !in_array($authenticatedUser->roleUser->role_id, [Role::ROLE_ADMIN, Role::ROLE_SELLER]) ||
                ($authenticatedUser->roleUser->role_id === Role::ROLE_SELLER &&
                $coupon->created_by !== $authenticatedUser->id)) {
                throw new \Exception('You are not authorized to delete this coupon.');
            }

            $coupon->productCoupons()->delete();
            $coupon->customerCoupons()->delete();
            $coupon->delete();

            return response()->json(['success' => true], 204);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
