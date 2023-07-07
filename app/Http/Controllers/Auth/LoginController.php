<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\RoleUser;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid login credentials'], 401);
        }

        $user = Auth::user();

        // Retrieve the role of the user from the RoleUser table
        $roleUser = RoleUser::where('user_id', $user->id)->first();

        // Check if a valid RoleUser object is retrieved
        if ($roleUser) {
            $roleId = $roleUser->role_id; // Assuming the role_id is stored in the RoleUser table

            // Generate the JWT token with the user's role as a custom claim
            $token = JWTAuth::fromUser($user, ['role_id' => $roleId]);

            return response()->json(['token' => $token], 200);
        }

        // Handle the case where the RoleUser object is not found
        return response()->json(['message' => 'Role not found for the user'], 500);
    }
}

