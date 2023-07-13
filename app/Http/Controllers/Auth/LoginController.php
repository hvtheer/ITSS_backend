<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\User;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        try {
            $credentials = $request->only('email', 'password');

            $validator = Validator::make($credentials, [
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => 'Invalid email or password'], 400);
            }

            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Invalid email or password'], 401);
            }

            $user = Auth::user()->makeHidden(['email_verified_at', 'deleted', 'created_at', 'updated_at']);
            
            // Check if the user and related models exist before accessing their properties
            if ($user->deleted) {
                return response()->json(['error' => 'User account has been deleted'], 401);
            }

            $roleUser = $user->roleUser->makeHidden(['user_id', 'deleted', 'created_at', 'updated_at']);

            if ($roleUser) {
                if (Helpers::isShop()) {
                    $userShop = $user->shop->makeHidden(['user_id', 'deleted', 'created_at', 'updated_at']);
                } elseif (Helpers::isCustomer()) {
                    $userCustomer = $user->customer->makeHidden(['user_id', 'deleted', 'created_at', 'updated_at']);
                }
            }

            return response()->json([
                'success' => true,
                'data' => $token,
                'user' => $user,
                'message' => 'Login successful!'
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
