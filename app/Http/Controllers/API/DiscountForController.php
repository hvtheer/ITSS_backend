<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\DiscountFor;
use Illuminate\Http\Request;

class DiscountForController extends Controller
{
    public function index()
    {
        $discountFors = DiscountFor::all();
        return response()->json($discountFors);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'product_id' => 'nullable|exists:products,id',
            'discount_id' => 'required|exists:discounts,id',
            'user_id' => 'nullable|exists:users,id',
        ]);

        $discountFor = DiscountFor::create($validatedData);
        return response()->json($discountFor, 201);
    }

    public function show(DiscountFor $discountFor)
    {
        return response()->json($discountFor);
    }

    public function update(Request $request, DiscountFor $discountFor)
    {
        $validatedData = $request->validate([
            'product_id' => 'nullable|exists:products,id',
            'discount_id' => 'required|exists:discounts,id',
            'user_id' => 'nullable|exists:users,id',
        ]);

        $discountFor->update($validatedData);
        return response()->json($discountFor);
    }

    public function destroy(DiscountFor $discountFor)
    {
        $discountFor->delete();
        return response()->json(null, 204);
    }
}
