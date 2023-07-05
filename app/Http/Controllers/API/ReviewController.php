<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

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
                'order_item_id' => 'required|exists:orders,id',
                'customer_id' => 'required|exists:customers,id',
                'rating' => 'required|integer|min:1|max:5',
                'review_text' => 'required',
            ]);

            $review = Review::create($validatedData);
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
                'product_id' => 'sometimes|required|exists:products,id',
                'order_item_id' => 'sometimes|required|exists:orders,id',
                'customer_id' => 'sometimes|required|exists:customers,id',
                'rating' => 'sometimes|required|integer|min:1|max:5',
                'review_text' => 'sometimes|required',
            ]);

            $review->update($validatedData);
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
