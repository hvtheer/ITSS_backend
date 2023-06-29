<?php

namespace App\Http\Controllers;
use App\Models\Discount;

use Illuminate\Http\Request;

class DiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // return Discount::all();
        $discounts =  Discount::all();
        if ($discounts){
            // return $customers;
            return [
                'success' => 'True',
                'data' =>   $discounts
            ];
        }
        else {
            return [
                'success' => 'False',
                'message' => 'Get discount failed'
            ];
        };
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'key' =>'required',
            'type' =>'required',
            'created_by' =>'required',
            'start_at' =>'required',
            'end_at' =>'required',
                              
        ]);

        $discount = Discount::create($request->all());

        if ($discount){
            // return $customers;
            return [
                'success' => 'True',
                'message' => 'Create discount success',
                'data' =>   $discount
            ];
        }
        else {
            return [
                'success' => 'False',
                'message' => 'Create discount failed'
            ];
        };
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $discounts = Discount::find($id);
        if ($discounts){
            // return $customers;
            return [
                'success' => 'True',
                'data' =>   $discounts
            ];
        }
        else {
            return [
                'success' => 'False',
                'message' => 'Get discount failed'
            ];
        };
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $discount = Discount::find($id);
        $discount->update($request->all());
        if ($discount){
            // return $customers;
            return [
                'success' => 'True',
                'message' => 'Update discount success',
                'data' =>   $discount
            ];
        }
        else {
            return [
                'success' => 'False',
                'message' => 'Update discount failed'
            ];
        };
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $discount = Discount::find($id);
        $discount->delete();
    }
}
