<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\RoleUser;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle($request, Closure $next, ...$roles)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $roleUser = RoleUser::where('user_id', $user->id)->first();
        
        if (!$roleUser || !in_array($roleUser->role_id, $roles)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return $next($request);
    }
}
