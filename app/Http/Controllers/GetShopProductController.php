<?php

namespace App\Http\Controllers;
use App\Models\Shop;
use App\Http\Resources\GetShopResource;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GetShopProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = DB::table('products')
            ->join('shops', 'products.shop_id', '=', 'shops.id')
            ->join('attributes', 'products.id', '=', 'attributes.product_id')
            ->select('shops.id','shops.shop_name as Shop_name' ,'products.name as product_name','attributes.attribute')
            ->get();

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
                    'message' => 'Get products failed'
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {   

        $products = DB::table('products')
            ->join('shops', 'products.shop_id', '=', 'shops.id')
            ->join('attributes', 'products.id', '=', 'attributes.product_id')
            ->select('shops.id','shops.shop_name as Shop_name' ,'products.name as product_name','attributes.attribute')
            ->where('shops.id','=',$id)
            ->get();
        
        
        
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
                    'message' => 'Get products failed'
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
