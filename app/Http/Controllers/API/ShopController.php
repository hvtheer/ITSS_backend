<?php

namespace App\Http\Controllers\API;

use App\Models\Role;
use App\Models\Shop;
use App\Http\Helpers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        try {
            // Get the current page from the request, default to 1
            $page = $request->input('page', 1);
            // Get the limit value from the request, default to 15
            $limit = $request->input('limit', 15);
            
            // Check if the authenticated user is an admin
            if (!Helpers::isAdmin()) {
                // Retrieve only non-deleted shops
                $totalShops = Shop::where('deleted', false)->count();
                $shops = Shop::where('deleted', false)->paginate($limit, ['*'], 'page', $page);
            } else {
                // Retrieve all shops (including deleted)
                $totalShops = Shop::count();
                $shops = Shop::paginate($limit, ['*'], 'page', $page);
            }
    
            if ($shops->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'No shops found']);
            }
    
            return response()->json([
                'success' => true,
                'data' => $shops->makeHidden(['created_at', 'updated_at']),
                'totalShops' => $totalShops,
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    

    public function store(Request $request)
    {
        try {
            // Check if the authenticated user is an admin
            if (!Helpers::isAdmin()) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $validatedData = $request->validate([
                'user_id' => 'required|exists:users,id|unique:shops,user_id',
                'shop_name' => 'required',
                'description' => 'required',
                'address' => 'required',
                'phone_number' => 'required',
                'verified' => 'required|boolean',
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
            // If the authenticated user is a customer or shop, check if the record is deleted
            if (Helpers::isCustomer() || Helpers::isShop()) {
                if ($shop->deleted || !$shop->verified) {
                    return response()->json(['success' => false, 'message' => 'Shop not found'], 404);
                }
            }
            
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
