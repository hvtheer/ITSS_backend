<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        try {
            $roles = Role::all();

            if ($roles->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'No roles found']);
            }

            return response()->json(['success' => true, 'data' => $roles]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'role' => 'required|unique:roles,role',
            ]);

            $role = Role::create($validatedData);
            return response()->json(['success' => true, 'data' => $role], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function show(Role $role)
    {
        try {
            return response()->json(['success' => true, 'data' => $role]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function update(Request $request, Role $role)
    {
        try {
            $validatedData = $request->validate([
                'role' => 'sometimes|required|unique:roles,role,' . $role->id,
            ]);

            $role->update($validatedData);
            return response()->json(['success' => true, 'data' => $role]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function destroy(Role $role)
    {
        try {
            $role->delete();
            return response()->json(['success' => true], 204);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
