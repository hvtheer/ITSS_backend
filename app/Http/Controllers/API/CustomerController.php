<?php

namespace App\Http\Controllers\API;

use App\Models\Role;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function index()
    {
        try {
            $customers = Customer::all();

            if ($customers->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'No customers found']);
            }

            return response()->json(['success' => true, 'data' => $customers]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function store(Request $request)
    {
        try {
            $authenticatedUser = Auth::user();

            if ($authenticatedUser->roleUser->role_id !== Role::ROLE_ADMIN) {
                throw new \Exception('You are not authorized to create a customer.');
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
            return response()->json(['success' => true, 'data' => $customer]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function update(Request $request, Customer $customer)
    {
        try {
            $authenticatedUser = Auth::user();

            if (
                $authenticatedUser->roleUser->role_id !== Role::ROLE_ADMIN &&
                $authenticatedUser->id !== $customer->user_id
            ) {
                throw new \Exception('You are not authorized to update this customer.');
            }

            $validatedData = $request->validate([
                'user_id' => 'nullable|exists:users,id|unique:customers,user_id,' . $customer->id,
                'name' => 'required',
                'phone_number' => 'required',
                'address' => 'required',
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
            $authenticatedUser = Auth::user();

            if (
                $authenticatedUser->roleUser->role_id !== Role::ROLE_ADMIN &&
                $authenticatedUser->id !== $customer->user_id
            ) {
                throw new \Exception('You are not authorized to delete this customer.');
            }

            $customer->delete();
            return response()->json(['success' => true], 204);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
