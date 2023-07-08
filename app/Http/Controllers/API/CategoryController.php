<?php

namespace App\Http\Controllers\API;

use App\Models\Role;
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
        try {
            $authenticatedUser = Auth::user();

            // Check if the user has the required role
            if (!$authenticatedUser->roleUser || $authenticatedUser->roleUser->role_id !== Role::ROLE_ADMIN) {
                throw new \Exception('You are not authorized to create a category.');
            }

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
        try {
            $authenticatedUser = Auth::user();

            // Check if the user has the required role
            if (!$authenticatedUser->roleUser || $authenticatedUser->roleUser->role_id !== Role::ROLE_ADMIN) {
                throw new \Exception('You are not authorized to update this category.');
            }

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
        try {
            $authenticatedUser = Auth::user();

            // Check if the user has the required role
            if (!$authenticatedUser->roleUser || $authenticatedUser->roleUser->role_id !== Role::ROLE_ADMIN) {
                throw new \Exception('You are not authorized to delete this category.');
            }

            $category->delete();
            return response()->json(['success' => true], 204);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
