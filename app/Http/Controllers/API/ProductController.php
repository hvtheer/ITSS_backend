<?php

namespace App\Http\Controllers\API;

use App\Models\Role;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use App\Models\ProductAttribute;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $minPrice = $request->input('minPrice');
        $maxPrice = $request->input('maxPrice');
        $minRating = $request->input('minRating');
        $page = $request->input('page', 1);
        $limit = 15;

        try {
            $query = Product::with(['shop:id,shop_name,shop_logo', 'category:id,name,slug'])
                ->when($minPrice, function ($query, $minPrice) {
                    return $query->where('price', '>=', $minPrice);
                })
                ->when($maxPrice, function ($query, $maxPrice) {
                    return $query->where('price', '<=', $maxPrice);
                })
                ->when($minRating, function ($query, $minRating) {
                    return $query->where(function ($subQuery) use ($minRating) {
                        $subQuery->where('avg_rating', '>=', $minRating)
                            ->orWhereNull('avg_rating');
                    });
                });

            if (!Helpers::isAdmin()) {
                $query->where('deleted', false)->whereHas('shop', function ($query) {
                    $query->where('deleted', false)->where('verified', true);
                });
            }

            $totalItems = $query->count();
            $products = $query->paginate($limit, ['*'], 'page', $page);

            if ($products->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'No products found']);
            }

            return response()->json([
                'success' => true,
                'data' => $products->makeHidden(['category_id', 'shop_id']),
                'totalItems' => $totalItems
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function store(Request $request)
    {
        try {
            $authenticatedUser = Auth::user();

            if (!$authenticatedUser->roleUser ||
                !in_array($authenticatedUser->roleUser->role_id, [Role::ROLE_ADMIN, Role::ROLE_SELLER])) {
                throw new \Exception('You are not authorized to create a product.');
            }

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
            $authenticatedUser = Auth::user();

            if (!$authenticatedUser->roleUser ||
                !in_array($authenticatedUser->roleUser->role_id, [Role::ROLE_ADMIN, Role::ROLE_SELLER]) ||
                ($authenticatedUser->roleUser->role_id === Role::ROLE_SELLER &&
                    $product->shop_id !== $authenticatedUser->id)) {
                throw new \Exception('You are not authorized to update this product.');
            }

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
            $authenticatedUser = Auth::user();

            if (!$authenticatedUser->roleUser ||
                !in_array($authenticatedUser->roleUser->role_id, [Role::ROLE_ADMIN, Role::ROLE_SELLER]) ||
                ($authenticatedUser->roleUser->role_id === Role::ROLE_SELLER &&
                    $product->shop_id !== $authenticatedUser->id)) {
                throw new \Exception('You are not authorized to delete this product.');
            }

            ProductImage::where('product_id', $product->id)->delete();
            ProductAttribute::where('product_id', $product->id)->delete();
            $product->delete();
            return response()->json(['success' => true], 204);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function getBestSellingProducts(Request $request)
    {
        try {
            $query = Product::with(['shop:id,shop_name,shop_logo', 'category:id,name,slug'])
                ->when(!Helpers::isAdmin(), function ($query) {
                    $query->whereHas('shop', function ($subQuery) {
                        $subQuery->where('deleted', false)->where('verified', true);
                    });
                })
                ->orderBy('sold_quantity', 'desc')
                ->limit(6);

            $products = $query->get();

            return response()->json(['success' => true, 'data' => $products->makeHidden('category_id', 'shop_id')]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function getLatestProducts(Request $request)
    {
        try {
            $query = Product::with(['shop:id,shop_name,shop_logo', 'category:id,name,slug'])
                ->when(!Helpers::isAdmin(), function ($query) {
                    $query->whereHas('shop', function ($subQuery) {
                        $subQuery->where('deleted', false)->where('verified', true);
                    });
                })
                ->latest()
                ->take(10);

            $products = $query->get();

            return response()->json(['success' => true, 'data' => $products->makeHidden('category_id', 'shop_id')]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
