<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderItemController extends Controller
{
    public function index()
    {
        try {
            $orderItems = OrderItem::all();

            if ($orderItems->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'No order items found']);
            }

            return response()->json(['success' => true, 'data' => $orderItems]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
       public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'order_id' => 'required|exists:orders,id',
                'coupon_id' => 'nullable|exists:coupons,id',
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer',
            ]);

            $orderItem = OrderItem::create($validatedData);
            return response()->json(['success' => true, 'data' => $orderItem], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function show(OrderItem $orderItem)
    {
        try {
            return response()->json(['success' => true, 'data' => $orderItem]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function update(Request $request, OrderItem $orderItem)
    {
        try {
            $validatedData = $request->validate([
                'order_id' => 'required|exists:orders,id',
                'coupon_id' => 'nullable|exists:coupons,id',
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer',
            ]);

            $orderItem->update($validatedData);
            return response()->json(['success' => true, 'data' => $orderItem]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function destroy(OrderItem $orderItem)
    {
        try {
            $orderItem->delete();
            return response()->json(['success' => true], 204);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
