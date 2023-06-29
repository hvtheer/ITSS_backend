<?php

namespace App\Http\Controllers;
use App\Models\Product;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // return Product::all();
        $products =  Product::all();
        if ($products){
            // return $customers;
            return [
                'success' => 'True',
                'data' =>   $products
            ];
        }
        else {
            return [
                'success' => 'False',
                'message' => 'Get product failed'
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
            'name' =>'required',
            'description' =>'required',
            'price' =>'required',
            'quantity' =>'required',
            'category_id' =>'required',
            'shop_id' =>'required',
            'quantity_sold' =>'required',
                              
        ]);

        $product = Product::create($request->all());

        if ($product){
            // return $customers;
            return [
                'success' => 'True',
                'message' => 'Create product success',
                'data' =>   $product
            ];
        }
        else {
            return [
                'success' => 'False',
                'message' => 'Create product failed'
            ];
        };
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $products = Product::find($id);
        if ($products){
            // return $customers;
            return [
                'success' => 'True',
                'data' =>   $products
            ];
        }
        else {
            return [
                'success' => 'False',
                'message' => 'Get product failed'
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
        $product = Product::find($id);
        $product->update($request->all());
        if ($product){
            // return $customers;
            return [
                'success' => 'True',
                'message' => 'Update product success',
                'data' =>   $product
            ];
        }
        else {
            return [
                'success' => 'False',
                'message' => 'Update product failed'
            ];
        };
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::find($id);
        $product->delete();
        return[
            'message' => 'Success Delete'
        ];
    }
}
