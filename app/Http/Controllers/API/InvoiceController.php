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

    public function show(Invoice $invoice)
    {
        try {
            return response()->json(['success' => true, 'data' => $invoice]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
