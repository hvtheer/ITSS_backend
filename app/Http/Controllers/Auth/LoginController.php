<?php
namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


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
        $role = $user->role; // Assuming you have a 'role' field in the users table

        // Generate the JWT token with the user's role as a custom claim
        $token = JWTAuth::fromUser($user, ['role' => $role]);

        return response()->json(['token' => $token], 200);
    }
}

