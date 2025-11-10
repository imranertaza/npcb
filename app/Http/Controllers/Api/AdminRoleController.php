<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminRoleController extends Controller
{
    public function index()
    {
        $admins = User::with('roles')->get()->map(function ($admin) {
            return [
                'id' => $admin->id,
                'name' => $admin->name,
                'email' => $admin->email,
                'role' => $admin->roles->pluck('name')->first() ?? 'none',
            ];
        });

        return ApiResponse::success($admins, 'User list retrieved');
    }

    public function show(User $admin)
    {
        $admin->load('roles');

        return ApiResponse::success([
            'id' => $admin->id,
            'name' => $admin->name,
            'email' => $admin->email,
            'role' => $admin->roles->pluck('name')->first() ?? 'none',
        ], 'User fetched successfully');
    }

    // 📋 List all available roles
    public function roles()
    {
        $roles = Role::where('guard_name', 'user')->pluck('name');
        return ApiResponse::success($roles, 'Available roles retrieved');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|string|exists:roles,name',
        ]);

        $admin = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $admin->assignRole($request->role);

        return ApiResponse::success([
            'id' => $admin->id,
            'name' => $admin->name,
            'email' => $admin->email,
            'role' => $request->role,
        ], 'User created successfully');
    }


    public function updateUser(Request $request, User $admin)
    {
        // ✅ Validate input
        $validated = $request->validate([
            'name'     => 'required|string|max:155',
            'email'    => 'required|email|max:155|unique:users,email,' . $admin->id,
            'password' => 'nullable|string|min:6',
            'role'     => 'required|string|exists:roles,name',
        ]);

        // ✅ Update basic fields
        $admin->name  = $validated['name'];
        $admin->email = $validated['email'];

        // ✅ Update password only if provided
        if (!empty($validated['password'])) {
            $admin->password = Hash::make($validated['password']);
            $admin->tokens()->delete();
        }

        $admin->save();

        // ✅ Sync role (Spatie Permission)
        if (!empty($validated['role'])) {
            $admin->syncRoles([$validated['role']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully',
            'data'    => $admin->load('roles'),
        ]);
        return ApiResponse::success([
            $admin->load('roles')
        ], 'User updated successfully');
    }
    // 🔄 Update an admin’s role
    public function updateUserRole(Request $request, User $admin)
    {
        $request->validate([
            'role' => 'required|string|exists:roles,name',
        ]);
        if ($admin->hasRole('super-admin')) {
            return ApiResponse::error('Cannot modify role of a super-admin', 403);
        }
        $admin->syncRoles([$request->role]);

        return ApiResponse::success([
            'id' => $admin->id,
            'role' => $request->role,
        ], 'User role updated');
    }

    // 🔍 List all roles with their permissions
    public function rolesWithPermissions()
    {
        $roles = Role::with('permissions')->get()->map(function ($role) {
            return [
                'name' => $role->name,
                'permissions' => $role->permissions->pluck('name'),
            ];
        });

        return ApiResponse::success($roles, 'Roles with permissions retrieved');
    }

    public function updatePermissions(Request $request, $role)
    {
        $role = Role::where('name', $role)->where('guard_name', 'user')->firstOrFail();

        if ($role->name === 'super-admin') {
            return ApiResponse::error('Cannot update permissions for super-admin', 403);
        }

        $request->validate([
            'permissions' => 'array',
            'permissions.*' => 'string|exists:permissions,name',
        ]);

        $role->syncPermissions($request->permissions);

        return ApiResponse::success([
            'role' => $role->name,
            'permissions' => $role->permissions->pluck('name'),
        ], 'Permissions updated');
    }


    // 📋 List all available permissions
    public function permissions()
    {
        $permissions = Permission::where('guard_name', 'user')->pluck('name');
        return ApiResponse::success($permissions, 'Available permissions retrieved');
    }
    public function destroy(User $admin)
    {
        if ($admin->hasRole('super-admin')) {
            return ApiResponse::error('Cannot delete a super-admin', 403);
        }

        $admin->delete();

        return ApiResponse::success(['id' => $admin->id], 'User deleted successfully');
    }
}
