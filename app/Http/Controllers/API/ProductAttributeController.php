<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ProductAttribute;
use Illuminate\Http\Request;

class ProductAttributeController extends Controller
{
    public function index()
    {
        try {
            $productAttributes = ProductAttribute::all();

            if ($productAttributes->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'No product attributes found']);
            }

            return response()->json(['success' => true, 'data' => $productAttributes]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'product_id' => 'required|exists:products,id',
                'attribute_name' => 'required',
                'attribute_value' => 'required',
            ]);

            $productAttribute = ProductAttribute::create($validatedData);
            return response()->json(['success' => true, 'data' => $productAttribute], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function show(ProductAttribute $productAttribute)
    {
        try {
            return response()->json(['success' => true, 'data' => $productAttribute]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function update(Request $request, ProductAttribute $productAttribute)
    {
        try {
            $validatedData = $request->validate([
                'product_id' => 'required|exists:products,id',
                'attribute_name' => 'required',
                'attribute_value' => 'required',
            ]);

            $productAttribute->update($validatedData);
            return response()->json(['success' => true, 'data' => $productAttribute]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function destroy(ProductAttribute $productAttribute)
    {
        try {
            $productAttribute->delete();
            return response()->json(['success' => true], 204);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}