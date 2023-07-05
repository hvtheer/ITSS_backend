<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        try {
            $payments = Payment::all();

            if ($payments->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'No payments found']);
            }

            return response()->json(['success' => true, 'data' => $payments]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'order_id' => 'required|exists:orders,id',
                'payment_date' => 'required|date',
                'payment_status' => 'required|string',
                'payment_amount' => 'required|numeric',
            ]);

            $payment = Payment::create($validatedData);
            return response()->json(['success' => true, 'data' => $payment], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function show(Payment $payment)
    {
        try {
            return response()->json(['success' => true, 'data' => $payment]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function update(Request $request, Payment $payment)
    {
        try {
            $validatedData = $request->validate([
                'order_id' => 'sometimes|required|exists:orders,id',
                'payment_date' => 'sometimes|required|date',
                'payment_status' => 'sometimes|required|string',
                'payment_amount' => 'sometimes|required|numeric',
            ]);

            $payment->update($validatedData);
            return response()->json(['success' => true, 'data' => $payment]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function destroy(Payment $payment)
    {
        try {
            $payment->delete();
            return response()->json(['success' => true], 204);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
