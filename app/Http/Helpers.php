<?php

namespace App\Http;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class Helpers
{
    public static function isAdmin()
    {
        return Auth::user()->roleUser->role_id === Role::ROLE_ADMIN;
    }

    public static function isCustomer()
    {
        return Auth::user()->roleUser->role_id === Role::ROLE_CUSTOMER;
    }

    public static function isShop()
    {
        return Auth::user()->roleUser->role_id === Role::ROLE_SELLER;
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
