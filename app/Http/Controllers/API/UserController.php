<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

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
                'role' => 'required|in:admin,user,seller',
            ]);

            $user = User::create($validatedData);
            return response()->json(['success' => true, 'data' => $user], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function show(User $user)
    {
        try {
            return response()->json(['success' => true, 'data' => $user]);
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
                'role' => 'required|in:admin,user,seller',
            ]);

            $user->update($validatedData);
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
