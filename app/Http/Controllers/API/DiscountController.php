<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    public function index()
    {
        $discounts = Discount::all();
        return response()->json($discounts);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'code' => 'required|unique:discounts',
            'type' => 'required|in:fixed,percent',
            'value' => 'required',
            'created_by' => 'required|exists:users,id',
            'start_at' => 'nullable|date',
            'end_at' => 'nullable|date',
        ]);

        $discount = Discount::create($validatedData);
        return response()->json($discount, 201);
    }

    public function show(Discount $discount)
    {
        return response()->json($discount);
    }

    public function update(Request $request, Discount $discount)
    {
        $validatedData = $request->validate([
            'code' => 'required|unique:discounts,code,' . $discount->id,
            'type' => 'required|in:fixed,percent',
            'value' => 'required',
            'created_by' => 'required|exists:users,id',
            'start_at' => 'nullable|date',
            'end_at' => 'nullable|date',
        ]);

        $discount->update($validatedData);
        return response()->json($discount);
    }

    public function destroy(Discount $discount)
    {
        $discount->delete();
        return response()->json(null, 204);
    }
}

