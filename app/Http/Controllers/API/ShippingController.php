<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Shipping;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    public function index()
    {
        try {
            $shippings = Shipping::all();

            if ($shippings->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'No shippings found']);
            }

            return response()->json(['success' => true, 'data' => $shippings]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'order_id' => 'required|exists:orders,id',
                'shipping_date' => 'required|date',
                'shipping_status' => 'required|in:shipping soon,shipped,out of delivery,delivered',
                'shipping_carrier' => 'required|string',
                'tracking_number' => 'required|string',
                'payment_amount' => 'required|numeric',
            ]);

            $shipping = Shipping::create($validatedData);
            return response()->json(['success' => true, 'data' => $shipping], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function show(Shipping $shipping)
    {
        try {
            return response()->json(['success' => true, 'data' => $shipping]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function update(Request $request, Shipping $shipping)
    {
        try {
            $validatedData = $request->validate([
                'order_id' => 'sometimes|required|exists:orders,id',
                'shipping_date' => 'sometimes|required|date',
                'shipping_status' => 'sometimes|required|in:shipping soon,shipped,out of delivery,delivered',
                'shipping_carrier' => 'sometimes|required|string',
                'tracking_number' => 'sometimes|required|string',
                'payment_amount' => 'sometimes|required|numeric',
            ]);

            $shipping->update($validatedData);
            return response()->json(['success' => true, 'data' => $shipping]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function destroy(Shipping $shipping)
    {
        try {
            $shipping->delete();
            return response()->json(['success' => true], 204);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
