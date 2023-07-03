<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ProductAttribute;
use Illuminate\Http\Request;

class ProductAttributeController extends Controller
{
    public function index()
    {
        $productAttributes = ProductAttribute::all();
        return response()->json($productAttributes);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'product_id' => 'required|exists:products,id',
            'attribute' => 'required',
            'value' => 'required',
        ]);

        $productAttribute = ProductAttribute::create($validatedData);
        return response()->json($productAttribute, 201);
    }

    public function show(ProductAttribute $productAttribute)
    {
        return response()->json($productAttribute);
    }

    public function update(Request $request, ProductAttribute $productAttribute)
    {
        $validatedData = $request->validate([
            'product_id' => 'required|exists:products,id',
            'attribute' => 'required',
            'value' => 'required',
        ]);

        $productAttribute->update($validatedData);
        return response()->json($productAttribute);
    }

    public function destroy(ProductAttribute $productAttribute)
    {
        $productAttribute->delete();
        return response()->json(null, 204);
    }
}
