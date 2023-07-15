<?php

namespace App\Http\Controllers\API;

use App\Models\Role;
use App\Http\Helpers;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        try {
            // Check if the authenticated user is an admin
            if (!Helpers::isAdmin()) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $search = $request->input('search');
            $page = $request->input('page', 1);
            $limit = 15;

            $customers = Customer::with(['user' => function ($query) {
                $query->select('id', 'email', 'username');
            }])
                ->select('name', 'phone_number', 'address', 'id', 'created_at')
                ->orWhere('name', 'like', "%$search%")
                ->orWhere('phone_number', 'like', "%$search%")
                ->orWhere('address', 'like', "%$search%")
                ->orderBy('created_at', 'desc')
                ->paginate($limit, ['*'], 'page', $page);

            if ($customers->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'No customers found']);
            }

            $totalItems = Customer::with(['user' => function ($query) {
                $query->select('id', 'email', 'username');
            }])
                ->select('name', 'phone_number', 'address', 'id', 'user_id', 'created_at')
                ->orWhere('name', 'like', "%$search%")
                ->orWhere('phone_number', 'like', "%$search%")
                ->orWhere('address', 'like', "%$search%")
                ->get()
                ->count();

            return response()->json(['success' => true, 'data' => $customers, 'totalItems' => $totalItems]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }


    public function store(Request $request)
    {
        try {
            // Check if the authenticated user is an admin
            if (!Helpers::isAdmin()) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $validatedData = $request->validate([
                'user_id' => 'nullable|exists:users,id|unique:customers,user_id',
                'name' => 'required',
                'phone_number' => 'required',
                'address' => 'required',
            ]);

            $customer = Customer::create($validatedData);
            return response()->json(['success' => true, 'data' => $customer], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function show(Customer $customer)
    {
        try {
            // Check if the authenticated user is an admin
            if (!Helpers::isAdmin()) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            return response()->json(['success' => true, 'data' => $customer]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function update(Request $request, Customer $customer)
    {
        try {
            // Check if the authenticated user is an admin or the owner
            if (!Helpers::isAdmin() && !Helpers::isCustomer()) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            // If the authenticated user is a shop, check if they own the shop being updated
            if (Helpers::isCustomer() && !Helpers::isOwner($customer->user_id)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $validatedData = $request->validate([
                'user_id' => 'nullable|exists:users,id|unique:customers,user_id,' . $customer->id,
                'name' => 'required|string',
                'phone_number' => 'required|string',
                'address' => 'required|string',
                'deleted' => 'required|boolean',
            ]);

            $customer->update($validatedData);
            return response()->json(['success' => true, 'data' => $customer]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function destroy(Customer $customer)
    {
        try {
            // Check if the authenticated user is an admin or the owner
            if (!Helpers::isAdmin()) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $customer->delete();
            return response()->json(['success' => true, 'data' => 'Customer deleted successfully!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
