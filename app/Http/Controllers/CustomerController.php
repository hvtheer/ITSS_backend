<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers =  Customer::all();
        if ($customers){
            // return $customers;
            return [
                'success' => 'True',
                'data' =>   $customers
            ];
        }
        else {
            return [
                'success' => 'False',
                'message' => 'Get customer failed'
            ];
        };
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' =>'required',
            'customer_name' =>'required',
            'customer_address' =>'required',
            'customer_numberPhone' =>'required',
            'is_login' =>'required',
                     
        ]);

        $customers = Customer::create($request->all());
        if ($customers){
            // return $customers;
            return [
                'success' => 'True',
                'message' => 'Create Success',
                'data' =>   $customers
            ];
        }
        else {
            return [
                'success' => 'False',
                'message' => 'Create failed',
            ];
        };
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $customers = Customer::find($id);
        if ($customers){
            // return $customers;
            return [
                'success' => 'True',
                'data' =>   $customers
            ];
        }
        else {
            return [
                'success' => 'False',
                'message' => 'Get customer failed'
            ];
        };
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $customers = Customer::find($id);
        $customers->update($request->all());
        if ($customers){
            // return $customers;
            return [
                'success' => 'True',
                'message' => 'Update Success',
                'data' =>   $customers
            ];
        }
        else {
            return [
                'success' => 'False',
                'message' => 'Update failed'
            ];
        };
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();
        return[
            'message' => 'Success Delete'
        ];
    }
}
