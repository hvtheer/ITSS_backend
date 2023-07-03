<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::all();
        return response()->json($orders);
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required',
            'seller_id' => 'required',
            'total' => 'required',
            'subtotal' => 'required',
            'payment_method' => 'required',
            'payment_status' => 'required',
            'status' => 'required',
            'total_qty' => 'required',
        ]);

        $order = Order::create($request->all());

        return response()->json($order, 201);
    }

    public function show(Order $order)
    {
        return response()->json($order);
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'customer_id' => 'required',
            'seller_id' => 'required',
            'total' => 'required',
            'subtotal' => 'required',
            'payment_method' => 'required',
            'payment_status' => 'required',
            'status' => 'required',
            'total_qty' => 'required',
        ]);

        $order->update($request->all());

        return response()->json($order);
    }

    public function destroy(Order $order)
    {
        $order->delete();

        return response()->json(null, 204);
    }
}
