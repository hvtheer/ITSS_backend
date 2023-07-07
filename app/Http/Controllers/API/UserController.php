<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\RoleUser;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        try {
            $users = User::all();
            return response()->json(['success' => true, 'data' => $users]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'username' => 'required|unique:users',
                'email' => 'required|email|unique:users',
                'password' => 'required',
                'role_id' => 'required|exists:roles,id',
                'status' => 'required|in:pending,approved,rejected',
            ]);

            $user = User::create($validatedData);
            $user->roleUser()->create([
                'role_id' => '3',
                'status' => 'pending',
            ]);

            return response()->json(['success' => true, 'data' => $user], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function show(User $user)
    {
        try {
            $userWithRole = $user->load('roleUser.role');
    
            return response()->json(['success' => true, 'data' => $userWithRole]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function update(Request $request, User $user)
    {
        try {
            $validatedData = $request->validate([
                'username' => 'required|unique:users,username,' . $user->id,
                'email' => 'required|email|unique:users,email,' . $user->id,
                'password' => 'required',
                'role_id' => 'required|exists:roles,id',
                'status' => 'required|in:pending,approved,rejected',
            ]);
    
            if ($user->roleUser->role_id === 1) {
                // Admin can update for everyone
                $user->update($validatedData);
            } else {
                // Users with other roles can only update themselves
                if ($user->id !== $request->user()->id) {
                    throw new \Exception('You can only update your own profile.');
                }
                $user->update($validatedData);
            }
            return response()->json(['success' => true, 'data' => $user]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    

    public function destroy(User $user)
    {
        try {
            $user->delete();
            return response()->json(['success' => true], 204);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
