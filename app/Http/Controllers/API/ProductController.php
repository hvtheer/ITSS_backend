<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        try {
            $products = Product::all();

            if ($products->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'No products found']);
            }

            return response()->json(['success' => true, 'data' => $products]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'slug' => 'required|unique:products',
                'shop_id' => 'required|exists:shops,id',
                'name' => 'required',
                'description' => 'required',
                'price' => 'required|numeric|min:0',
                'stock_quantity' => 'required|integer|min:0',
                'category_id' => 'required|exists:categories,id',
            ]);

            $product = Product::create($validatedData);
            return response()->json(['success' => true, 'data' => $product], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function show(Product $product)
    {
        try {
            return response()->json(['success' => true, 'data' => $product]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function update(Request $request, Product $product)
    {
        try {
            $validatedData = $request->validate([
                'slug' => 'required|unique:products,slug,' . $product->id,
                'shop_id' => 'required|exists:shops,id',
                'name' => 'required',
                'description' => 'required',
                'price' => 'required|numeric|min:0',
                'stock_quantity' => 'required|integer|min:0',
                'category_id' => 'required|exists:categories,id',
            ]);

            $product->update($validatedData);
            return response()->json(['success' => true, 'data' => $product]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function destroy(Product $product)
    {
        try {
            $product->delete();
            return response()->json(['success' => true], 204);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
