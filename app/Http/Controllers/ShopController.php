<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // return Shop::all();
        $shops =  Shop::all();
        if ($shops){
            // return $customers;
            return [
                'success' => 'True',
                'data' =>   $shops
            ];
        }
        else {
            return [
                'success' => 'False',
                'message' => 'Get shop failed'
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
            'shop_id' =>'required',
            'shop_name' =>'required',
            'description' =>'required',
            'shop_address' =>'required',
            'shop_numberPhone' =>'required',
            'shop_logo' =>'required',
            'status' =>'required',
                              
        ]);

        $shop = Shop::create($request->all());

        if ($shop){
            // return $customers;
            return [
                'success' => 'True',
                'message' => 'Success create',
                'data' =>   $shop
            ];
        }
        else {
            return [
                'success' => 'False',
                'message' => 'Create failed'
            ];
        };
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $shops = Shop::find($id);
        if ($shops){
            // return $customers;
            return [
                'success' => 'True',
                'data' =>   $shops
            ];
        }
        else {
            return [
                'success' => 'False',
                'message' => 'Get shop failed'
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
        $shop = Shop::find($id);
        $shop->update($request->all());
        if ($shop){
            // return $customers;
            return [
                'success' => 'True',
                'message' => 'Success update',
                'data' =>   $shop
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
    public function destroy(string $id)
    {
        $shop = Shop::find($id);
        $shop->delete();
        return[
            'message' => 'Success Delete'
        ];
    }
}
