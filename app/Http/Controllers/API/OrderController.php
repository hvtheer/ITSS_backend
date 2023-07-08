<?php

namespace App\Http\Controllers\API;

use App\Models\Order;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

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
                'order_items' => 'required|array',
                'order_items.*.id' => 'nullable|exists:order_items,id',
                'order_items.*.product_coupon_id' => 'nullable|exists:product_coupons,id',
                'order_items.*.product_id' => 'required|exists:products,id',
                'order_items.*.quantity' => 'required|integer',
            ]);

            $order = Order::create([
                'customer_id' => $request->input('customer_id'),
                'shop_id' => $request->input('shop_id'),
                'delivery_info_id' => $request->input('delivery_info_id'),
                'order_status' => 'pending',
                'note' => $request->input('note'),
            ]);

            foreach ($validatedData['order_items'] as $itemData) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_coupon_id' => $itemData['coupon_id'],
                    'product_id' => $itemData['product_id'],
                    'quantity' => $itemData['quantity'],
                ]);
            }
            // Create the invoice
            $invoice = new Invoice([
                'order_id' => $order->id,
                'payment_method' => $request->input('payment_method'),
                'payment_status' => $request->input('payment_status'),
            ]);

            // Calculate total amount payable and fill the invoice fields
            $invoice->calculateTotalAmountPayable();

            // Save the invoice
            $invoice->save();
            return response()->json(['success' => true, 'data' => $order->load('orderItems')], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function show(Order $order)
    {
        try {
            $order = $order->load('orderItems');
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
                'payment_method' => 'nullable',
                'paid' => 'nullable',
                'order_items' => 'required|array',
                'order_items.*.id' => 'nullable|exists:order_items,id',
                'order_items.*.product_coupon_id' => 'nullable|exists:product_coupons,id',
                'order_items.*.product_id' => 'required|exists:products,id',
                'order_items.*.quantity' => 'required|integer',
            ]);

            $order->update($validatedData);

            $order->orderItems()->delete();

            foreach ($validatedData['order_items'] as $itemData) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_coupon_id' => $itemData['coupon_id'],
                    'product_id' => $itemData['product_id'],
                    'quantity' => $itemData['quantity'],
                ]);
            }

            return response()->json(['success' => true, 'data' => $order]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function destroy(Order $order)
    {
        try {
            $order->orderItems()->delete();
            $order->delete();
            
            return response()->json(['success' => true], 204);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}