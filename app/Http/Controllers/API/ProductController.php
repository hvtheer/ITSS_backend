<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductAttribute;
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
                'price' => 'required|numeric',
                'thumbnail' => 'required',
                'sold_quantity' => 'required|integer',
                'stock_quantity' => 'required|integer',
                'category_id' => 'required|exists:categories,id',
                'images' => 'required|array',
                'images.*.image_url' => 'required',
                'attributes' => 'required|array',
                'attributes.*.name' => 'required',
                'attributes.*.value' => 'required',
            ]);

            $product = Product::create($validatedData);

            $images = [];
            foreach ($validatedData['images'] as $imageData) {
                $imageData['product_id'] = $product->id;
                $images[] = $imageData;
            }
            ProductImage::insert($images);

            $attributes = [];
            foreach ($validatedData['attributes'] as $attributeData) {
                $attributeData['product_id'] = $product->id;
                $attributes[] = $attributeData;
            }
            ProductAttribute::insert($attributes);

            return response()->json(['success' => true, 'data' => $product], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function show(Product $product)
    {
        try {
            $product = $product->load('productImages', 'productAttributes');

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
                'price' => 'required|numeric',
                'thumbnail' => 'required|mimes:jpg,jpeg,png',
                'sold_quantity' => 'required|integer',
                'stock_quantity' => 'required|integer',
                'category_id' => 'required|exists:categories,id',
                'images' => 'nullable|array',
                'images.*.image_url' => 'required_with:images|array',
                'attributes' => 'nullable|array',
                'attributes.*.name' => 'required_with:attributes|array',
                'attributes.*.value' => 'required_with:attributes|array',
            ]);

            $product->update($validatedData);

            if ($request->has('images')) {
                $images = [];
                foreach ($validatedData['images'] as $imageData) {
                    $imageData['product_id'] = $product->id;
                    $images[] = $imageData;
                }
                ProductImage::where('product_id', $product->id)->delete();
                ProductImage::insert($images);
            }

            if ($request->has('attributes')) {
                $attributes = [];
                foreach ($validatedData['attributes'] as $attributeData) {
                    $attributeData['product_id'] = $product->id;
                    $attributes[] = $attributeData;
                }
                ProductAttribute::where('product_id', $product->id)->delete();
                ProductAttribute::insert($attributes);
            }

            return response()->json(['success' => true, 'data' => $product]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function destroy(Product $product)
    {
        try {
            ProductImage::where('product_id', $product->id)->delete();
            ProductAttribute::where('product_id', $product->id)->delete();
            $product->delete();
            return response()->json(['success' => true], 204);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function getProductsByPriceRange(Request $request)
    {
        $minPrice = $request->input('min');
        $maxPrice = $request->input('max');

        try {
            $products = Product::where('price', '>=', $minPrice)
                ->where('price', '<=', $maxPrice)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $products,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function getProductsByRatingRange(Request $request)
    {
        $minRating = $request->input('min');
        $maxRating = $request->input('max');

        try {
            $products = Product::whereHas('reviews', function ($query) use ($minRating, $maxRating) {
                $query->whereBetween('rating', [$minRating, $maxRating]);
            })->get();

            return response()->json([
                'success' => true,
                'data' => $products,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function getBestSellingProducts(Request $request)
    {
        try {
            $products = Product::orderBy('sold_quantity', 'desc')
                ->limit(10)
                ->get();

            return response()->json(['success' => true, 'data' => $products]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function getLatestProducts(Request $request)
    {
        try {
            $products = Product::latest()->take(10)->get();

            return response()->json(['success' => true, 'data' => $products]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
