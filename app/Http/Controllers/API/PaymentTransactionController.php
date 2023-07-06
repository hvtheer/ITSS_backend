<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\PaymentTransaction;
use Illuminate\Http\Request;

class PaymentTransactionController extends Controller
{
    public function index()
    {
        try {
            $paymentTransactions = PaymentTransaction::all();

            if ($paymentTransactions->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'No payment transactions found']);
            }

            return response()->json(['success' => true, 'data' => $paymentTransactions]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'content' => 'required',
                'card_password' => 'required',
                'card_number' => 'required|integer',
                'invoice_id' => 'required|exists:invoices,id',
            ]);

            $paymentTransaction = PaymentTransaction::create($validatedData);
            return response()->json(['success' => true,'data' => $paymentTransaction], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function show(PaymentTransaction $paymentTransaction)
    {
        try {
            return response()->json(['success' => true, 'data' => $paymentTransaction]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function update(Request $request, PaymentTransaction $paymentTransaction)
    {
        try {
            $validatedData = $request->validate([
                'content' => 'required',
                'card_password' => 'required',
                'card_number' => 'required|integer',
                'invoice_id' => 'required|exists:invoices,id',
            ]);

            $paymentTransaction->update($validatedData);
            return response()->json(['success' => true, 'data' => $paymentTransaction]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function destroy(PaymentTransaction $paymentTransaction)
    {
        try {
            $paymentTransaction->delete();
            return response()->json(['success' => true], 204);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
