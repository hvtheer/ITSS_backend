<?php

namespace App\Http\Controllers\API;

use App\Models\Role;
use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    public function index()
    {
        try {
            $authenticatedUser = Auth::user();
    
            // Check if the user has the required role
            if (!$authenticatedUser->roleUser ||
                !in_array($authenticatedUser->roleUser->role_id, [Role::ROLE_ADMIN, Role::ROLE_CUSTOMER, Role::ROLE_SELLER])) {
                throw new \Exception('You are not authorized to access the invoices.');
            }
    
            // Get all invoices
            $invoices = Invoice::query();
    
            // Filter orders based on the user's role
            if ($authenticatedUser->roleUser->role_id === Role::ROLE_CUSTOMER) {
                $invoices->whereHas('order', function ($query) use ($authenticatedUser) {
                    $query->where('customer_id', $authenticatedUser->customer->id);
                });
            } elseif ($authenticatedUser->roleUser->role_id === Role::ROLE_SELLER) {
                $invoices->whereHas('order', function ($query) use ($authenticatedUser) {
                    $query->where('shop_id', $authenticatedUser->shop->id);
                });
            }
    
            $invoices = $invoices->get();
    
            if ($invoices->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'No invoices found']);
            }
    
            return response()->json(['success' => true, 'data' => $invoices]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    public function update(Invoice $invoice)
    {
        try {
            $authenticatedUser = Auth::user();

            // Check if the user has the required role
            if (!$authenticatedUser->roleUser ||
                !in_array($authenticatedUser->roleUser->role_id, [Role::ROLE_ADMIN, Role::ROLE_CUSTOMER, Role::ROLE_SELLER])) {
                throw new \Exception('You are not authorized to update invoices.');
            }

            // Check if the user is authorized to update this specific invoice
            if (($authenticatedUser->roleUser->role_id === Role::ROLE_CUSTOMER && $invoice->order->customer_id !== $authenticatedUser->customer->id)) {
                throw new \Exception('You are not authorized to update this invoice.');
            }

            $invoice->update(['payment_status' => 'paid']);

            return response()->json(['success' => true, 'data' => $invoice, 'message' => 'Invoice payment status updated successfully']);
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
