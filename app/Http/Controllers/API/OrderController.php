<?php

namespace App\Http\Controllers\API;

use App\Models\Role;
use App\Models\Order;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        try {
            $authenticatedUser = Auth::user();
    
            // Check if the user has the required role
            if (!$authenticatedUser->roleUser ||
                !in_array($authenticatedUser->roleUser->role_id, [Role::ROLE_ADMIN, Role::ROLE_CUSTOMER, Role::ROLE_SELLER])) {
                throw new \Exception('You are not authorized to access the orders.');
            }
    
            $orders = Order::all();
    
            // Filter orders based on the user's role
            if ($authenticatedUser->roleUser->role_id === Role::ROLE_CUSTOMER) {
                $orders = $orders->where('customer_id', $authenticatedUser->customer->id);
            } elseif ($authenticatedUser->roleUser->role_id === Role::ROLE_SELLER) {
                $orders = $orders->where('shop_id', $authenticatedUser->shop->id);
            } else {
                $orders = Order::all();
            }
    
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
            $authenticatedUser = Auth::user();
    
            // Check if the user has the required role or is the customer who created the order
            if (!$authenticatedUser->roleUser ||
                !in_array($authenticatedUser->roleUser->role_id, [Role::ROLE_ADMIN, Role::ROLE_CUSTOMER])) {
                throw new \Exception('You are not authorized to create an order.');
            }
    
            $validatedData = $request->validate([
                // 'customer_id' => 'required|exists:customers,id',
                'shop_id' => 'required|exists:shops,id',
                'delivery_info_id' => 'nullable|exists:delivery_infos,id',
                'note' => 'nullable',
                'order_items' => 'required|array',
                'order_items.*.id' => 'nullable|exists:order_items,id',
                'order_items.*.product_coupon_id' => 'nullable|exists:product_coupons,id',
                'order_items.*.product_id' => [
                    'required',
                    Rule::exists('products', 'id')->where(function ($query) use ($request) {
                        $query->where('shop_id', $request->input('shop_id'));
                    }),
                ],
                'order_items.*.quantity' => 'required|integer',
                'customer_coupon_id' => 'nullable',
                'payment_method' => 'required|in:cod,card',
                // 'payment_status' => 'required:paid,unpaid',
            ]);
    
            // Set the customer_id to the authenticated user's customer ID if not provided
            if (!isset($validatedData['customer_id'])) {
                $validatedData['customer_id'] = $authenticatedUser->customer->id;
            }
    
            $validatedData['order_status'] = 'pending';
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
    
            // Set the values for the desired fields
            $invoice = new Invoice([
                'order_id' => $order->id,
                'total_amount' => 0,
                'total_amount_decreased' => 0,
                'total_amount_payable' => 0,
                'customer_coupon_id' => $validatedData['customer_coupon_id'],
                'payment_method' => $validatedData['payment_method'],
                'payment_status' => 'unpaid',
            ]);
    
            // Calculate and set the total amount payable
            $invoice->calculateTotalAmountPayable();
    
            // Save the invoice to persist it in the database
            $invoice->save();
            return response()->json(['success' => true, 'data' => $invoice], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    public function show(Order $order)
    {
        try {
            $authenticatedUser = Auth::user();
    
            // Check if the user has the required role or is the customer/shop related to the order
            if (!$authenticatedUser->roleUser ||
                !in_array($authenticatedUser->roleUser->role_id, [Role::ROLE_ADMIN, Role::ROLE_CUSTOMER, Role::ROLE_SELLER]) ||
                ($authenticatedUser->roleUser->role_id === Role::ROLE_CUSTOMER && $order->customer_id !== $authenticatedUser->customer->id) ||
                ($authenticatedUser->roleUser->role_id === Role::ROLE_SELLER && $order->shop_id !== $authenticatedUser->shop->id)) {
                throw new \Exception('You are not authorized to access this order.');
            }
    
            $order = $order->load('orderItems');
            return response()->json(['success' => true, 'data' => $order]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    

    public function update(Request $request, $order)
    {
        try {
            $authenticatedUser = Auth::user();
    
            // Check if the user has the required role or is the customer who created the order
            if (!$authenticatedUser->roleUser ||
                !in_array($authenticatedUser->roleUser->role_id, [Role::ROLE_ADMIN, Role::ROLE_CUSTOMER]) ||
                ($authenticatedUser->roleUser->role_id === Role::ROLE_CUSTOMER && $order->customer_id !== $authenticatedUser->customer->id)) {
                throw new \Exception('You are not authorized to update this order.');
            }
    
            $validatedData = $request->validate([
                // 'customer_id' => 'required|exists:customers,id',
                'shop_id' => 'required|exists:shops,id',
                'delivery_info_id' => 'nullable|exists:delivery_infos,id',
                'note' => 'nullable',
                'order_items' => 'required|array',
                'order_items.*.id' => 'nullable|exists:order_items,id',
                'order_items.*.product_coupon_id' => 'nullable|exists:product_coupons,id',
                'order_items.*.product_id' => [
                    'required',
                    Rule::exists('products', 'id')->where(function ($query) use ($request) {
                        $query->where('shop_id', $request->input('shop_id'));
                    }),
                ],
                'order_items.*.quantity' => 'required|integer',
                'customer_coupon_id' => 'nullable',
                'payment_method' => 'required|in:cod,card',
                'payment_status' => 'required:paid,unpaid',
            ]);
    
            // Set the customer_id to the authenticated user's customer ID if not provided
            if (!isset($validatedData['customer_id'])) {
                $validatedData['customer_id'] = $authenticatedUser->customer->id;
            }
    
            $order = Order::findOrFail($order);
    
            // Update the order with the validated data
            $order->update($validatedData);
    
            // Delete existing order items and create new ones
            $order->orderItems()->delete();
            foreach ($validatedData['order_items'] as $itemData) {
                $order->orderItems()->create([
                    'product_coupon_id' => $itemData['product_coupon_id'],
                    'product_id' => $itemData['product_id'],
                    'quantity' => $itemData['quantity'],
                ]);
                $product = Product::findOrFail($itemData['product_id']);
                $product->updateStockQuantity($itemData['quantity']);
            }
    
            // Update the invoice related fields
            $invoice = $order->invoice;
            $invoice->customer_coupon_id = $validatedData['customer_coupon_id'];
            $invoice->payment_method = $validatedData['payment_method'];
            $invoice->payment_status = $validatedData['payment_status'];
            $invoice->calculateTotalAmountPayable();
            $invoice->save();
    
            return response()->json(['success' => true, 'data' => $invoice]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    
    public function destroy(Order $order)
    {
        try {
            $authenticatedUser = Auth::user();
    
            // Check if the user has the required role or is the customer who created the order
            if (!$authenticatedUser->roleUser ||
                !in_array($authenticatedUser->roleUser->role_id, [Role::ROLE_ADMIN, Role::ROLE_CUSTOMER]) ||
                ($authenticatedUser->roleUser->role_id === Role::ROLE_CUSTOMER && $order->customer_id !== $authenticatedUser->customer->id)) {
                throw new \Exception('You are not authorized to delete this order.');
            }
    
            $order->orderItems()->delete();
            $order->delete();
            
            return response()->json(['success' => true], 204);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
}