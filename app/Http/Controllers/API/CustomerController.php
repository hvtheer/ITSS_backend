<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::all();
        return response()->json($customers);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'name' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'is_login' => 'required|boolean',
        ]);

        $customer = Customer::create($validatedData);
        return response()->json($customer, 201);
    }

    public function show(Customer $customer)
    {
        return response()->json($customer);
    }

    public function update(Request $request, Customer $customer)
    {
        $validatedData = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'name' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'is_login' => 'required|boolean',
        ]);

        $customer->update($validatedData);
        return response()->json($customer);
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return response()->json(null, 204);
    }
}
