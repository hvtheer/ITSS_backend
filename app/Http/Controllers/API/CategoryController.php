<?php

namespace App\Http\Controllers\API;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function index()
    {
        try {
            $categories = Category::all();

            if ($categories->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'No categories found']);
            }

            return response()->json(['success' => true, 'data' => $categories]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'slug' => 'required|unique:categories',
                'name' => 'required',
                'status' => 'boolean',
            ]);

            $category = Category::create($validatedData);
            return response()->json(['success' => true, 'data' => $category], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function show($categorySlug)
    {
        try {
            $category = Category::where('slug', $categorySlug)->with('products')->first();
            if (!$category) {
                return response()->json(['success' => false, 'message' => 'Category not found.']);
            }
            
            return response()->json(['success' => true, 'data' => $category]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    

    public function update(Request $request, Category $category)
    {
        try {
            $validatedData = $request->validate([
                'slug' => 'required|unique:categories,slug,' . $category->id,
                'name' => 'required',
                'status' => 'boolean',
            ]);

            $category->update($validatedData);
            return response()->json(['success' => true, 'data' => $category]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function destroy(Category $category)
    {
        try {
            $category->delete();
            return response()->json(['success' => true], 204);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
