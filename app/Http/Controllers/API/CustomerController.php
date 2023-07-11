<?php

namespace App\Http\Controllers\API;

use App\Models\Role;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
    try {
    $search = $request->input('search');
    $page = $request->input('page', 1); // get the page number or default to 1
    $limit = 15; // set the limit to 15
    
    $customers = Customer::with(['user' => function ($query) {
    $query->select('id', 'email', 'username');
    }])->select('name', 'phone_number', 'address', 'id','user_id', 'created_at')
    ->orWhere('name', 'like', "%$search%")
    ->orWhere('phone_number', 'like', "%$search%")
    ->orWhere('address', 'like', "%$search%")
    ->orderBy('created_at', 'desc') // order by created_at descending
    ->paginate($limit, ['*'], 'page', $page); // paginate the data with the limit and page number
    
    if ($customers->isEmpty()) {
    return response()->json(['success' => false, 'message' => 'No customers found']);
    }
    
    $totalItems = Customer::with(['user' => function ($query) {
    $query->select('id', 'email', 'username');
    }])->select('name', 'phone_number', 'address', 'id','user_id', 'created_at')
    ->orWhere('name', 'like', "%$search%")
    ->orWhere('phone_number', 'like', "%$search%")
    ->orWhere('address', 'like', "%$search%")
    ->get() // get all customers by query without limit
    ->count(); // count the number of customers
    
    return response()->json(['success' => true, 'data' => $customers, 'totalItems' => $totalItems]); // return the data and the totalItems
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
