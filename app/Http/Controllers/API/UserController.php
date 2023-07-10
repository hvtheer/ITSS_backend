<?php

namespace App\Http\Controllers\API;

use App\Models\Role;
use App\Models\Shop;
use App\Models\User;
use App\Models\Customer;
use App\Models\RoleUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // Get the current page from the request, default to 1
        $page = $request->input('page', 1);
        // Get the perPage value from the request, default to 15
        $perPage = $request->input('perPage', 15);
    
        try {
            $totalUsers = User::count();
    
            $users = User::paginate($perPage, ['*'], 'page', $page);
    
            if ($users->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'No users found']);
            }
    
            return response()->json([
                'success' => true,
                'data' => $users->makeHidden(['created_at', 'updated_at']),
                'totalUsers' => $totalUsers,
                'perPage' => $perPage, // Include perPage value in the response
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function store(Request $request)
    {
        try {
            $authenticatedUser = Auth::user();

            if ($authenticatedUser->roleUser->role_id !== Role::ROLE_ADMIN) {
                throw new \Exception('You are not authorized to create a user.');
            }

            $validatedData = $request->validate([
                'username' => 'required|unique:users',
                'email' => 'required|email|unique:users',
                'password' => 'required',
                'status' => 'required|in:pending,approved,rejected',
                'role_id' => 'required|exists:roles,id',
            ]);

            $user = User::create($validatedData);
            $roleUser = new RoleUser([
                'role_id' => $validatedData['role_id'],
                'status' => $validatedData['status'],
            ]);
            $user->roleUser()->save($roleUser);
            
            if ($user->roleUser->role_id === Role::ROLE_CUSTOMER) {
                $customer = Customer::create([
                    'user_id' => $user->id,
                ]);
            }

            if ($user->roleUser->role_id === Role::ROLE_SELLER) {
                $shop = Shop::create([
                    'user_id' => $user->id,
                ]);
            }

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
            $authenticatedUser = Auth::user();

            if ($authenticatedUser->roleUser->role_id !== Role::ROLE_ADMIN && $authenticatedUser->id !== $user->id) {
                throw new \Exception('You are not authorized to update this user.');
            }

            $validatedData = $request->validate([
                'username' => 'required|unique:users,username,' . $user->id,
                'email' => 'required|email|unique:users,email,' . $user->id,
                'password' => 'required',
                'status' => 'sometimes|required|in:pending,approved,rejected',
                'role_id' => 'sometimes|required|exists:roles,id',
            ]);

            $user->update($validatedData);

            if (isset($validatedData['role_id']) || isset($validatedData['status'])) {
                $roleUser = $user->roleUser;

                if (!$roleUser) {
                    $roleUser = new RoleUser();
                    $user->roleUser()->save($roleUser);
                }

                if (isset($validatedData['role_id'])) {
                    $roleUser->role_id = $validatedData['role_id'];
                }

                if (isset($validatedData['status'])) {
                    $roleUser->status = $validatedData['status'];
                }

                $roleUser->save();
            }

            return response()->json(['success' => true, 'data' => $user]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function destroy(User $user)
    {
        try {
            $authenticatedUser = Auth::user();

            if ($authenticatedUser->roleUser->role_id !== Role::ROLE_ADMIN && $authenticatedUser->id !== $user->id) {
                throw new \Exception('You are not authorized to delete this user.');
            }

            $user->delete();

            return response()->json(['success' => true], 204);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function getCurrent()
    {
        try {
            $user = Auth::user();

            if($user) {
                if($user->roleUser->role_id === Role::ROLE_SELLER) {
                    $data = $user->load('shop');
                } elseif($user->roleUser->role_id === Role::ROLE_CUSTOMER) {
                    $data = $user->load('customer');
                } else {
                    $data = $user;
                }
                return response()->json(['success' => true, 'data' => $data]);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
