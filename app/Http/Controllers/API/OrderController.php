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
                'delivery_info_id' => 'nullable|exists:delivery_infos,id',
                'order_status' => 'required|in:pending,accepted,not accepted',
                'note' => 'nullable',
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
                'customer_id' => 'required|exists:customers,id',
                'shop_id' => 'required|exists:shops,id',
                'delivery_info_id' => 'nullable|exists:delivery_infos,id',
                'order_status' => 'required|in:pending,accepted,not accepted',
                'note' => 'nullable',
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
