<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        try {
            $orders = Order::all();

            if ($orders->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'No orders found']);
            }

            return response()->json(['success' => true, 'data' => $orders]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'customer_id' => 'required|exists:customers,id',
                'shop_id' => 'required|exists:shops,id',
                'coupon_id' => 'nullable|exists:coupons,id',
                'subtotal' => 'required|numeric',
                'shipping_fee' => 'required|numeric',
                'total' => 'required|numeric',
                'payment_method' => 'required|in:cod,card',
                'delivery_address' => 'nullable|string',
                'order_status' => 'required|in:pending,accepted,not accepted',
                'shipping_method' => 'required|in:slow,normal,fast',
                'tracking_number' => 'nullable|string',
            ]);

            $order = Order::create($validatedData);
            return response()->json(['success' => true, 'data' => $order], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function show(Order $order)
    {
        try {
            return response()->json(['success' => true, 'data' => $order]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function update(Request $request, Order $order)
    {
        try {
            $validatedData = $request->validate([
                'customer_id' => 'sometimes|required|exists:customers,id',
                'shop_id' => 'sometimes|required|exists:shops,id',
                'coupon_id' => 'nullable|exists:coupons,id',
                'subtotal' => 'sometimes|required|numeric',
                'shipping_fee' => 'sometimes|required|numeric',
                'total' => 'sometimes|required|numeric',
                'payment_method' => 'sometimes|required|in:cod,card',
                'delivery_address' => 'nullable|string',
                'order_status' => 'sometimes|required|in:pending,accepted,not accepted',
                'shipping_method' => 'sometimes|required|in:slow,normal,fast',
                'tracking_number' => 'nullable|string',
            ]);

            $order->update($validatedData);
            return response()->json(['success' => true, 'data' => $order]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function destroy(Order $order)
    {
        try {
            $order->delete();
            return response()->json(['success' => true], 204);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
