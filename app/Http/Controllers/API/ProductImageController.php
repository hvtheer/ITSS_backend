<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ProductImage;
use Illuminate\Http\Request;

class ProductImageController extends Controller
{
    public function index()
    {
        try {
            $productImages = ProductImage::all();

            if ($productImages->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'No product images found']);
            }

            return response()->json(['success' => true, 'data' => $productImages]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'product_id' => 'required|exists:products,id',
                'image_url' => 'required|url',
                'is_primary_image' => 'boolean',
            ]);

            $productImage = ProductImage::create($validatedData);
            return response()->json(['success' => true, 'data' => $productImage], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function show(ProductImage $productImage)
    {
        try {
            return response()->json(['success' => true, 'data' => $productImage]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function update(Request $request, ProductImage $productImage)
    {
        try {
            $validatedData = $request->validate([
                'product_id' => 'required|exists:products,id',
                'image_url' => 'required|url',
                'is_primary_image' => 'boolean',
            ]);

            $productImage->update($validatedData);
            return response()->json(['success' => true, 'data' => $productImage]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function destroy(ProductImage $productImage)
    {
        try {
            $productImage->delete();
            return response()->json(['success' => true], 204);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
