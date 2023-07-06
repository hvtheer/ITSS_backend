<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index()
    {
        try {
            $shops = Shop::all();

            if ($shops->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'No shops found']);
            }

            return response()->json(['success' => true, 'data' => $shops]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'user_id' => 'required|exists:users,id|unique:shops,user_id',
                'shop_name' => 'required',
                'description' => 'required',
                'address' => 'required',
                'phone_number' => 'required',
                'is_verified' => 'required|in:PENDING,ACCEPTED,NOT_ACCEPTED',
                'shop_logo' => 'nullable',
            ]);

            $shop = Shop::create($validatedData);
            return response()->json(['success' => true, 'data' => $shop], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function show(Shop $shop)
    {
        try {
            return response()->json(['success' => true, 'data' => $shop]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function update(Request $request, Shop $shop)
    {
        try {
            $validatedData = $request->validate([
                'user_id' => 'nullable|exists:users,id|unique:shops,user_id,' . $shop->id,
                'shop_name' => 'required',
                'description' => 'required',
                'address' => 'required',
                'phone_number' => 'required',
                'is_verified' => 'required|in:PENDING,ACCEPTED,NOT_ACCEPTED',
                'shop_logo' => 'nullable',
            ]);

            $shop->update($validatedData);
            return response()->json(['success' => true, 'data' => $shop]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function destroy(Shop $shop)
    {
        try {
            $shop->delete();
            return response()->json(['success' => true], 204);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
