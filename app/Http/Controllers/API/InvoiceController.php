<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index()
    {
        try {
            $invoices = Invoice::all();

            if ($invoices->isEmpty()){
                return response()->json(['success' => false, 'message' => 'No invoices found']);
            }

            return response()->json(['success' => true, 'data' => $invoices]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'order_id' => 'required|exists:orders,id',
                'user_coupon_id' => 'nullable|exists:user_coupons,id',
                'total_amount' => 'required|numeric',
                'total_amount_decreased' => 'required|numeric',
                'total_amount_payable' => 'required|numeric',
                'payment_method' => 'required|in:cod,card',
                'paid' => 'required|boolean',
            ]);

            $invoice = Invoice::create($validatedData);
            return response()->json(['success' => true, 'data' => $invoice], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function show(Invoice $invoice)
    {
        try {
            return response()->json(['success' => true, 'data' => $invoice]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function update(Request $request, Invoice $invoice)
    {
        try {
            $validatedData = $request->validate([
                'order_id' => 'required|exists:orders,id',
                'user_coupon_id' => 'nullable|exists:user_coupons,id',
                'total_amount' => 'required|numeric',
                'total_amount_decreased' => 'required|numeric',
                'total_amount_payable' => 'required|numeric',
                'payment_method' => 'required|in:cod,card',
                'paid' => 'required|boolean',
            ]);

            $invoice->update($validatedData);
            return response()->json(['success' => true, 'data' => $invoice]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function destroy(Invoice $invoice)
    {
        try {
            $invoice->delete();
            return response()->json(['success' => true], 204);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
