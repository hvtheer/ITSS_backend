<?php

namespace App\Http\Controllers\API;

use App\Models\Role;
use App\Models\Shop;
use App\Models\User;
use App\Http\Helpers;
use App\Models\Customer;
use App\Models\RoleUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // Check if the authenticated user is an admin
        if (!Helpers::isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    
        $page = $request->input('page', 1);
        $limit = $request->input('limit', 15);
    
        try {
            $totalUsers = User::count();
    
            $users = User::paginate($limit, ['*'], 'page', $page);
    
            if ($users->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'No users found']);
            }
    
            return response()->json([
                'success' => true,
                'data' => $users->makeHidden(['deleted', 'created_at', 'updated_at', 'email_verified_at']),
                'totalUsers' => $totalUsers
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    
    public function show(User $user)
    {
        try {
            // Check if the authenticated user is an admin
            if (!Helpers::isAdmin()) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            
            $data = $user->makeHidden(['email_verified_at', 'created_at', 'updated_at']);
    
            $roleUser = $user->roleUser->makeHidden(['user_id', 'deleted', 'created_at', 'updated_at']);
            if ($roleUser) {
                if ($roleUser->role_id === Role::ROLE_SELLER) {
                    $userShop = $user->shop->makeHidden(['user_id', 'deleted', 'created_at', 'updated_at']);
                    $data['shop'] = $userShop;
                } elseif ($roleUser->role_id === Role::ROLE_CUSTOMER) {
                    $userCustomer = $user->customer->makeHidden(['user_id', 'deleted', 'created_at', 'updated_at']);
                    $data['customer'] = $userCustomer;
                }
            }
    
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    public function update(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
    
            // If neither an admin nor the owner, return unauthorized error
            if (!Helpers::isAdmin() && !Helpers::isOwner($id)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $validatedData = $request->validate([
                'username' => 'required|unique:users,username,' . $user->id,
                'email' => 'required|email|unique:users,email,' . $user->id,
                'password' => 'required|string',
                'deleted' => 'required|integer'
            ]);
            
            $user->username = $validatedData['username'];
            $user->email = $validatedData['email'];
            
            if ($validatedData['deleted']) {
                if ($user->customer) {
                    $user->customer->deleted = true;
                    $user->customer->save();
                } elseif ($user->shop) {
                    $user->shop->deleted = true;
                    $user->shop->save();
                }
                $user->deleted = $validatedData['deleted'];
            }
            
            if (!empty($validatedData['password'])) {
                $user->password = bcrypt($validatedData['password']);
            }
            
            $user->save();
                      
    
            return response()->json(['success' => true, 'data' => $user, 'message' => 'User details successfully updated']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    public function destroy(User $user)
    {
        try {
            // Check if the authenticated user is an admin
            if (!Helpers::isAdmin()) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $user->delete();

            return response()->json(['success' => true], 204);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function getCurrent()
    {
        try {
            $user = Auth::user();

            if ($user) {
                $data = $user->makeHidden(['email_verified_at','deleted','created_at','updated_at']);
                $roleUser = $user->roleUser->makeHidden(['user_id','deleted','created_at','updated_at']);

                if ($roleUser) {
                    if (Helpers::isShop()) {
                        $userShop = $user->shop->makeHidden(['user_id','deleted','created_at','updated_at']);
                    } elseif (Helpers::isCustomer()) {
                        $userCustomer = $user->customer->makeHidden(['user_id','deleted','created_at','updated_at']);
                    }
                }

                return response()->json(['success' => true, 'data' => $data]);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
