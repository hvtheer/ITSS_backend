<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // return Category::all();
        $categorys =  Category::all();
        if ($categorys){
            // return $customers;
            return [
                'success' => 'True',
                'data' =>   $categorys
            ];
        }
        else {
            return [
                'success' => 'False',
                'message' => 'Get category failed'
            ];
        };
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' =>'required',
                              
        ]);

        $category = Category::create($request->all());

        if ($category){
            // return $customers;
            return [
                'success' => 'True',
                'message' => 'Create category success',
                'data' =>   $category
            ];
        }
        else {
            return [
                'success' => 'False',
                'message' => 'Create category failed'
            ];
        };
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $categorys = Category::find($id);
        if ($categorys){
            // return $customers;
            return [
                'success' => 'True',
                'data' =>   $categorys
            ];
        }
        else {
            return [
                'success' => 'False',
                'message' => 'Get category failed'
            ];
        };
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $category = Category::find($id);
        $category->update($request->all());
        if ($category){
            // return $customers;
            return [
                'success' => 'True',
                'message' => 'Update category success',
                'data' =>   $category
            ];
        }
        else {
            return [
                'success' => 'False',
                'message' => 'Update category failed'
            ];
        };
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::find($id);
        $category->delete();
        return[
            'message' => 'Success Delete'
        ];
    }
}
