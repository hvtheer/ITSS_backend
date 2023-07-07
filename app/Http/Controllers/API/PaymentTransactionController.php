<?php

namespace App\Http\Controllers\API;

use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Models\PaymentTransaction;
use App\Http\Controllers\Controller;

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

            $invoice = Invoice::findOrFail($validatedData['invoice_id']);

            if ($invoice->payment_method === 'card') {
                $paymentTransaction = PaymentTransaction::create($validatedData);
                return response()->json(['success' => true, 'data' => $paymentTransaction], 201);
            } else {
                return response()->json(['success' => false, 'message' => 'Payment method is not "card"']);
            }
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
}
