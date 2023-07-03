<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\PaymentTransaction;
use Illuminate\Http\Request;

class PaymentTransactionController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'content' => 'required',
            'card_number' => 'required',
            'delivery_info_id' => 'required|exists:delivery_info,id',
        ]);

        $paymentTransaction = PaymentTransaction::create($validatedData);
        return response()->json($paymentTransaction, 201);
    }

    public function show(PaymentTransaction $paymentTransaction)
    {
        return response()->json($paymentTransaction);
    }
}
