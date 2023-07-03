<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\OrderDetail;
use Illuminate\Http\Request;

class OrderDetailController extends Controller
{
    public function index()
    {
        $orderDetails = OrderDetail::all();
        return response()->json($orderDetails);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:0',
        ]);

        $orderDetail = new OrderDetail();
        $orderDetail->order_id = $validatedData['order_id'];
        $orderDetail->product_id = $validatedData['product_id'];
        $orderDetail->quantity = $validatedData['quantity'];
        $orderDetail->save();

        return response()->json($orderDetail, 201);
    }

    public function show(OrderDetail $orderDetail)
    {
        return response()->json($orderDetail);
    }

    public function update(Request $request, OrderDetail $orderDetail)
    {
        $validatedData = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:0',
        ]);

        $orderDetail->order_id = $validatedData['order_id'];
        $orderDetail->product_id = $validatedData['product_id'];
        $orderDetail->quantity = $validatedData['quantity'];
        $orderDetail->save();

        return response()->json($orderDetail);
    }

    public function destroy(OrderDetail $orderDetail)
    {
        $orderDetail->delete();
        return response()->json(null, 204);
    }
}
