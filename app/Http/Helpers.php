<?php

namespace App\Http;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class Helpers
{
    public static function isAdmin()
    {
        $user = Auth::user();
        if ($user) {
            return $user->roleUser->role_id === Role::ROLE_ADMIN;
        }
        return false;
    }
    
    public static function isCustomer()
    {
        $user = Auth::user();
        if ($user && $user->roleUser) {
            return $user->roleUser->role_id === Role::ROLE_CUSTOMER;
        }
        return false;
    }
    
    public static function isShop()
    {
        $user = Auth::user();
        if ($user && $user->roleUser) {
            return $user->roleUser->role_id === Role::ROLE_SELLER;
        }
        return false;
    }
    

    public static function isOwner($userId)
    {
        return $userId === Auth::id();
    }

    public static function isDeletedUser($userId)
    {
        $user = User::findOrFail($userId);
        return $user->deleted;
    }
}
