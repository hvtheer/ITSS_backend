<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function index()
    {
        try {
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
            $validatedData = $request->validate([
                'product_id' => 'required|exists:products,id',
                'order_id' => 'required|exists:orders,id',
                // 'customer_id' => 'required|exists:customers,id',
                'rating' => 'required|integer|min:1|max:5',
                'review_text' => 'required',
            ]);

            // Check if the order and product have a relationship through order_items
            $orderItem = OrderItem::where('order_id', $validatedData['order_id'])
                ->where('product_id', $validatedData['product_id'])
                ->first();

            if (!$orderItem) {
                return response()->json(['message' => 'The specified order and product are not related.'], 400);
            }

            // Check if the review with the same product_id and order_id already exists
            $existingReview = Review::where('product_id', $validatedData['product_id'])
                ->where('order_id', $validatedData['order_id'])
                ->first();

            if ($existingReview) {
                return response()->json(['success' => false, 'message' => 'Review already exists for this product and order']);
            }

            $review = Review::create($validatedData);
            $review->customer_id = Auth::user()->customer->id;
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
            return response()->json(['success' => true, 'data' => $review]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function update(Request $request, Review $review)
    {
        try {
            $validatedData = $request->validate([
                'product_id' => 'required|exists:products,id',
                'order_id' => 'required|exists:orders,id',
                'customer_id' => 'required|exists:customers,id',
                'rating' => 'required|integer|min:1|max:5',
                'review_text' => 'required',
            ]);
    
            // Check if the order and product have a relationship through order_items
            $orderItem = OrderItem::where('order_id', $validatedData['order_id'])
                ->where('product_id', $validatedData['product_id'])
                ->first();
    
            if (!$orderItem) {
                return response()->json(['message' => 'The specified order and product are not related.'], 400);
            }
    
            // Check if the updated product_id and order_id combination already exists in another review
            $existingReview = Review::where('product_id', $validatedData['product_id'])
                ->where('order_id', $validatedData['order_id'])
                ->where('id', '!=', $review->id)
                ->first();
    
            if ($existingReview) {
                return response()->json(['success' => false, 'message' => 'Review already exists for this product and order']);
            }
    
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
            $review->delete();
            return response()->json(['success' => true], 204);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
