<?php

namespace App\Http\Controllers\Auth;

use App\Models\Role;
use App\Models\User;
use App\Models\Customer;
use App\Models\RoleUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Shop;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:4',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $user = User::create([
            'username' => $request->input('username'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        $roleUser = RoleUser::create([
            'user_id' => $user->id,
            'role_id' => Role::ROLE_CUSTOMER,
            'status' => 'approved',
        ]);

        if ($roleUser->role_id === Role::ROLE_CUSTOMER) {
            $customer = Customer::create([
                'user_id' => $user->id,
            ]);
        }

        if ($roleUser->role_id === Role::ROLE_SELLER) {
            $shop = Shop::create([
                'user_id' => $user->id,
            ]);
        }

        return response()->json(['success' => true, 'message' => 'User registered successfully']);
    }
}
