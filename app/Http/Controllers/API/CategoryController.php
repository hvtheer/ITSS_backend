<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return response()->json($categories);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'slug' => 'required|unique:categories',
            'name' => 'required',
            'status' => 'boolean',
        ]);

        $category = Category::create($validatedData);
        return response()->json($category, 201);
    }

    public function show(Category $category)
    {
        return response()->json($category);
    }

    public function update(Request $request, Category $category)
    {
        $validatedData = $request->validate([
            'slug' => 'required|unique:categories,slug,' . $category->id,
            'name' => 'required',
            'status' => 'boolean',
        ]);

        $category->update($validatedData);
        return response()->json($category);
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return response()->json(null, 204);
    }
}
