<?php

namespace App\Http\Controllers\API;

use App\Models\Role;
use App\Models\Review;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function index()
    {
        try {
            $authenticatedUser = Auth::user();

            // Check if the user has the required role
            if (!$authenticatedUser->roleUser ||
                !in_array($authenticatedUser->roleUser->role_id, [Role::ROLE_ADMIN, Role::ROLE_SELLER, Role::ROLE_CUSTOMER])) {
                throw new \Exception('You are not authorized to access the reviews.');
            }

            $reviews = Review::all();

            if ($reviews->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'No reviews found']);
            }

            return response()->json(['success' => true, 'data' => $reviews]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function store(Request $request)
    {
        try {
            $authenticatedUser = Auth::user();

            // Check if the user has the required role or is the customer who created the review
            if (!$authenticatedUser->roleUser ||
                !in_array($authenticatedUser->roleUser->role_id, [Role::ROLE_ADMIN, Role::ROLE_CUSTOMER])) {
                throw new \Exception('You are not authorized to create a review.');
            }

            $validatedData = $request->validate([
                'product_id' => 'required|exists:products,id',
                'order_id' => 'required|exists:orders,id',
                // 'customer_id' => 'required|exists:customers,id',
                'rating' => 'required|integer|min:1|max:5',
                'review_text' => 'required',
            ]);

            // Retrieve the authenticated user's customer ID
            $customerID = Auth::user()->customer->id;

            // Check if the order and product have a relationship through order_items
            $orderItem = OrderItem::where('order_id', $validatedData['order_id'])
                ->where('product_id', $validatedData['product_id'])
                ->first();

            if (!$orderItem) {
                return response()->json(['message' => 'The specified order and product are not related.'], 400);
            }

            // Check if the review with the same product_id, order_id, and customer_id already exists
            $existingReview = Review::where('product_id', $validatedData['product_id'])
                ->where('order_id', $validatedData['order_id'])
                ->where('customer_id', $customerID)
                ->first();

            if ($existingReview) {
                return response()->json(['success' => false, 'message' => 'Review already exists for this product and order']);
            }

            $validatedData['customer_id'] = $customerID;

            $review = Review::create($validatedData);
            $product = Product::findOrFail($review->product_id);
            $product->calculateAverageRating();
            return response()->json(['success' => true, 'data' => $review], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function show(Review $review)
    {
        try {
            $authenticatedUser = Auth::user();

            // Check if the user has the required role or is the customer/seller related to the review
            if (!$authenticatedUser->roleUser ||
                !in_array($authenticatedUser->roleUser->role_id, [Role::ROLE_ADMIN, Role::ROLE_CUSTOMER, Role::ROLE_SELLER]) ||
                ($authenticatedUser->roleUser->role_id === Role::ROLE_CUSTOMER && $review->customer_id !== $authenticatedUser->customer->id) ||
                ($authenticatedUser->roleUser->role_id === Role::ROLE_SELLER && $review->product->shop_id !== $authenticatedUser->shop->id)) {
                throw new \Exception('You are not authorized to access this review.');
            }

            return response()->json(['success' => true, 'data' => $review]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function update(Request $request, Review $review)
    {
        try {
            $authenticatedUser = Auth::user();

            // Check if the user has the required role or is the customer who created the review
            if (!$authenticatedUser->roleUser ||
                !in_array($authenticatedUser->roleUser->role_id, [Role::ROLE_ADMIN, Role::ROLE_CUSTOMER]) ||
                ($authenticatedUser->roleUser->role_id === Role::ROLE_CUSTOMER && $review->customer_id !== $authenticatedUser->customer->id)) {
                throw new \Exception('You are not authorized to update this review.');
            }

            $validatedData = $request->validate([
                'product_id' => 'required|exists:products,id',
                'order_id' => 'required|exists:orders,id',
                // 'customer_id' => 'required|exists:customers,id',
                'rating' => 'required|integer|min:1|max:5',
                'review_text' => 'required',
            ]);

            // Retrieve the authenticated user's customer ID
            $customerID = Auth::user()->customer->id;

            // Check if the order and product have a relationship through order_items
            $orderItem = OrderItem::where('order_id', $validatedData['order_id'])
                ->where('product_id', $validatedData['product_id'])
                ->first();

            if (!$orderItem) {
                return response()->json(['message' => 'The specified order and product are not related.'], 400);
            }

            // Check if the updated product_id, order_id, and customer_id combination already exists in another review
            $existingReview = Review::where('product_id', $validatedData['product_id'])
                ->where('order_id', $validatedData['order_id'])
                ->where('customer_id', $customerID)
                ->where('id', '!=', $review->id)
                ->first();

            if ($existingReview) {
                return response()->json(['success' => false, 'message' => 'Review already exists for this product and order']);
            }

            $validatedData['customer_id'] = $customerID;

            $review->update($validatedData);
            $product = Product::findOrFail($review->product_id);
            $product->calculateAverageRating();
            return response()->json(['success' => true, 'data' => $review]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function destroy(Review $review)
    {
        try {
            $authenticatedUser = Auth::user();

            // Check if the user has the required role or is the customer who created the review
            if (!$authenticatedUser->roleUser ||
                !in_array($authenticatedUser->roleUser->role_id, [Role::ROLE_ADMIN, Role::ROLE_CUSTOMER]) ||
                ($authenticatedUser->roleUser->role_id === Role::ROLE_CUSTOMER && $review->customer_id !== $authenticatedUser->customer->id)) {
                throw new \Exception('You are not authorized to delete this review.');
            }

            $review->delete();
            return response()->json(['success' => true], 204);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
