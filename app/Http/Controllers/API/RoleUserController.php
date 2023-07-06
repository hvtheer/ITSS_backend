<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\RoleUser;
use Illuminate\Http\Request;

class RoleUserController extends Controller
{
    public function index()
    {
        try {
            $roleUsers = RoleUser::all();

            if ($roleUsers->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'No role users found']);
            }

            return response()->json(['success' => true, 'data' => $roleUsers]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'user_id' => 'required|exists:users,id',
                'role_id' => 'required|exists:roles,id',
            ]);

            $roleUser = RoleUser::create($validatedData);
            return response()->json(['

success' => true, 'data' => $roleUser], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function show(RoleUser $roleUser)
    {
        try {
            return response()->json(['success' => true, 'data' => $roleUser]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function update(Request $request, RoleUser $roleUser)
    {
        try {
            $validatedData = $request->validate([
                'user_id' => 'required|exists:users,id',
                'role_id' => 'required|exists:roles,id',
            ]);

            $roleUser->update($validatedData);
            return response()->json(['success' => true, 'data' => $roleUser]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function destroy(RoleUser $roleUser)
    {
        try {
            $roleUser->delete();
            return response()->json(['success' => true], 204);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}