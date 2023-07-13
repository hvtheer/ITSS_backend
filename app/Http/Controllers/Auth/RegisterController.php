<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Role;
use App\Models\RoleUser;
use Symfony\Component\Console\Input\Input;

class RegisterController extends Controller
{
    public function registerCustomer(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'username' => 'required|string|max:255',
                'email' => 'required|email|unique:users',
                'password' => 'required|string|min:6',
                'name' => 'required|string',
                'phone_number' => 'required|string',
                'address' => 'required|string',
            ]);
    
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }
    
            $user = User::create([
                'username' => $request->input('username'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
                'deleted' => false
            ]);
    
            // Assign the role to the newly registered user
            $roleUser = $user->roleUser()->create([
                'role_id' => Role::ROLE_CUSTOMER,
                'deleted' => false
            ]);
    
            // Assign the customer to the newly registered user
            $customer = $user->customer()->create([
                'name' => $request->input('name'),
                'phone_number' => $request->input('phone_number'),
                'address' => $request->input('address'),
                'deleted' => false
            ]);

            return response()->json(['success' => true, 'data' => $user, 'message' => 'User successfully registered']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function registerShop(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'username' => 'required|string|max:255',
                'email' => 'required|email|unique:users',
                'password' => 'required|string|min:6',
                'shop_name' => 'required',
                'description' => 'required',
                'address' => 'required',
                'phone_number' => 'required',
                'shop_logo' => 'nullable',
            ]);
    
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }
    
            $user = User::create([
                'username' => $request->input('username'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
                'deleted' => false
            ]);
    
            // Assign the role to the newly registered user
            $roleUser = $user->roleUser()->create([
                'role_id' => Role::ROLE_SELLER,
                'deleted' => false
            ]);
    
            // Assign the customer to the newly registered user
            $shop = $user->shop()->create([
                'shop_name' => $request->input('shop_name'),
                'description' => $request->input('description'),
                'address' => $request->input('address'),
                'phone_number' => $request->input('phone_number'),
                'verified' => false,
                'shop_logo' => $request->input('shop_logo'),
                'deleted' => false
            ]);

            return response()->json(['success' => true, 'data' => $user, 'message' => 'User successfully registered']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
