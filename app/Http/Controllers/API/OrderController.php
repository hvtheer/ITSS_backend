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
                'note' => 'nullable',
                'payment_method' => 'nullable',
                'paid' => 'nullable',
                'order_items' => 'required|array',
                'order_items.*.product_coupon_id' => 'nullable|exists:product_coupons,id',
                'order_items.*.product_id' => [
                    'required',
                    Rule::exists('products', 'id')->where(function ($query) use ($request) {
                        $query->where('shop_id', $request->input('shop_id'));
                    }),
                ],
                'order_items.*.quantity' => 'required|integer',
            ]);
            $validatedData['status'] = 'pending';
            $order = Order::create($validatedData);

            foreach ($validatedData['order_items'] as $itemData) {
                $order->orderItems()->create([
                    'product_coupon_id' => $itemData['product_coupon_id'],
                    'product_id' => $itemData['product_id'],
                    'quantity' => $itemData['quantity'],
                ]);
                $product = Product::findOrFail($itemData['product_id']);
                $product->updateStockQuantity($itemData['quantity']);
            }            

            $invoice = new Invoice();
            $invoice->order_id = $order->id;
            $invoice->save();
    
            // Update the invoice's fields
            $invoice->calculateTotalAmountPayable();
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