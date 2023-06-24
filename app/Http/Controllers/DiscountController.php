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
        return Discount::all();
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

        return $discount;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $discount = Discount::find($id);
        return $discount;
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
        return $discount;
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
