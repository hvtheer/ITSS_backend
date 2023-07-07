<?php

namespace App\Http\Controllers\API;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

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
        // Check if the user has the required role
        if (!Auth::user()->roleUser || !Auth::user()->roleUser->role_id = 1 ){
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }
    
        try {
            $validatedData = $request->validate([
                'name' => 'required',
                'slug' => 'required|unique:categories',
            ]);
    
            $category = Category::create($validatedData);
            return response()->json(['success' => true, 'data' => $category], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function show(Category $category)
    {
        try {
            $category = $category->load('products');
            return response()->json(['success' => true, 'data' => $category]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function update(Request $request, Category $category)
    {
        // Check if the user has the required role
        if (!Auth::user()->roleUser || !Auth::user()->roleUser->role_id = 1) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }
    
        try {
            $validatedData = $request->validate([
                'name' => 'required',
                'slug' => 'required|unique:categories,slug,' . $category->id,
            ]);
    
            $category->update($validatedData);
            return response()->json(['success' => true, 'data' => $category]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    public function destroy(Category $category)
    {
        // Check if the user has the required role
        if (!Auth::user()->roleUser || !in_array(Auth::user()->roleUser->role_id, [2, 3])) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }
    
        try {
            $category->delete();
            return response()->json(['success' => true], 204);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
