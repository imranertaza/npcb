<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Models\Admin;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminRoleController extends Controller
{
    public function index()
    {
        $admins = Admin::with('roles')->get()->map(function ($admin) {
            return [
                'id' => $admin->id,
                'name' => $admin->name,
                'email' => $admin->email,
                'role' => $admin->roles->pluck('name')->first() ?? 'none',
            ];
        });

        return ApiResponse::success($admins, 'Admin list retrieved');
    }

    // 📋 List all available roles
    public function roles()
    {
        $roles = Role::where('guard_name', 'admin')->pluck('name');
        return ApiResponse::success($roles, 'Available roles retrieved');
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|string|min:6',
            'role' => 'required|string|exists:roles,name',
        ]);

        $admin = Admin::create([
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
        ], 'Admin created successfully');
    }

    // 🔄 Update an admin’s role
    public function update(Request $request, Admin $admin)
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
        ], 'Admin role updated');
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
        $role = Role::where('name', $role)->where('guard_name', 'admin')->firstOrFail();

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
        $permissions = Permission::where('guard_name', 'admin')->pluck('name');
        return ApiResponse::success($permissions, 'Available permissions retrieved');
    }
    public function destroy(Admin $admin)
    {
        if ($admin->hasRole('super-admin')) {
            return ApiResponse::error('Cannot delete a super-admin', 403);
        }

        $admin->delete();

        return ApiResponse::success(['id' => $admin->id], 'Admin deleted successfully');
    }
}
