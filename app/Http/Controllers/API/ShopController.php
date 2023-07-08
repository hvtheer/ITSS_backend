<?php

namespace App\Http\Controllers\API;

use App\Models\Role;
use App\Models\Shop;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

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
            $authenticatedUser = Auth::user();

            if ($authenticatedUser->roleUser->role_id !== Role::ROLE_ADMIN) {
                throw new \Exception('You are not authorized to create a shop.');
            }

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
            $shop = $shop->load('products');
            return response()->json(['success' => true, 'data' => $shop]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function update(Request $request, Shop $shop)
    {
        try {
            $authenticatedUser = Auth::user();

            if (
                $authenticatedUser->roleUser->role_id !== Role::ROLE_ADMIN &&
                $authenticatedUser->id !== $shop->user_id
            ) {
                throw new \Exception('You are not authorized to update this shop.');
            }

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
            $authenticatedUser = Auth::user();

            if (
                $authenticatedUser->roleUser->role_id !== Role::ROLE_ADMIN &&
                $authenticatedUser->id !== $shop->user_id
            ) {
                throw new \Exception('You are not authorized to delete this shop.');
            }

            $shop->delete();
            return response()->json(['success' => true], 204);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function topShopsBySoldQuantity()
    {
        try {
            $topShops = Shop::select('shops.id', 'shops.shop_name', 'shops.address', 'shops.shop_logo', 'shops.phone_number')
                ->join('products', 'shops.id', '=', 'products.shop_id')
                ->groupBy('shops.id', 'shops.shop_name', 'shops.address', 'shops.shop_logo', 'shops.phone_number')
                ->orderByRaw('SUM(products.sold_quantity) DESC')
                ->limit(6)
                ->get();

            return response()->json(['success' => true, 'data' => $topShops]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
