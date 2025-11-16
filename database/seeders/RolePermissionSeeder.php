<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $guard = 'user';

        // 🧩 Define roles
        $roles = ['super-admin', 'admin', 'editor', 'viewer'];

        // 🧩 Define permissions (expanded)
        $permissions = [
            // Dashboard
            'view-dashboard',

            // User management
            'view-users',
            'create-users',
            'update-users',
            'delete-users',
            'update-user-role',

            // Posts CRUD
            'view-posts',
            'create-posts',
            'edit-posts',
            'delete-posts',
            'publish-posts',

            // Pages CRUD
            'view-pages',
            'create-pages',
            'edit-pages',
            'delete-pages',
            'publish-pages',

            // Categories CRUD
            'view-categories',
            'create-categories',
            'edit-categories',
            'delete-categories',

            // Settings
            'view-settings',
            'update-settings',

            'manage-menus',

            // Permissions management
            'update-permissions',
        ];

        // ✅ Create permissions
        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => $guard]);
        }

        // ✅ Create roles and assign permissions
        foreach ($roles as $roleName) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => $guard]);

            switch ($roleName) {
                case 'super-admin':
                    // Full access
                    $role->syncPermissions(Permission::where('guard_name', $guard)->pluck('name'));
                    break;

                case 'admin':
                    $role->syncPermissions([
                        'view-dashboard',

                        // User management
                        'view-users',
                        'create-users',
                        'update-users',
                        'delete-users',
                        'update-user-role',

                        // Posts
                        'view-posts',
                        'create-posts',
                        'edit-posts',
                        'delete-posts',
                        'publish-posts',

                        // Pages
                        'view-pages',
                        'create-pages',
                        'edit-pages',
                        'delete-pages',
                        'publish-pages',

                        // Categories
                        'view-categories',
                        'create-categories',
                        'edit-categories',
                        'delete-categories',

                        // Settings
                        'view-settings',
                        'update-settings',
                        'update-permissions',
                        'manage-menus',
                    ]);
                    break;

                case 'editor':
                    $role->syncPermissions([
                        'view-dashboard',

                        // Posts
                        'view-posts',
                        'create-posts',
                        'edit-posts',
                        'publish-posts',

                        // Pages
                        'view-pages',
                        'create-pages',
                        'edit-pages',
                        'publish-pages',

                        // Categories
                        'view-categories',
                        'create-categories',
                        'edit-categories',
                    ]);
                    break;

                case 'viewer':
                    $role->syncPermissions([
                        'view-dashboard',

                        // Read-only access
                        'view-users',
                        'view-posts',
                        'view-pages',
                        'view-categories',
                        'view-settings',
                    ]);
                    break;
            }
        }
    }
}
