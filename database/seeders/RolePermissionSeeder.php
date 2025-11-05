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

        // 🧩 Define permissions
        $permissions = [
            'view-dashboard',
            'manage-users',
            'view-content',
            'publish-content',
            'edit-content',
            'delete-content',
            'view-pages',
            'publish-pages',
            'edit-pages',
            'delete-pages',
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
                    $role->syncPermissions(Permission::where('guard_name', $guard)->pluck('name'));
                    break;
                case 'admin':
                    $role->syncPermissions(['view-dashboard', 'manage-users','delete-content','view-content','delete-pages','view-pages']);
                    break;
                case 'editor':
                    $role->syncPermissions(['view-dashboard','view-content','edit-content', 'publish-content']);
                    break;
                case 'viewer':
                    $role->syncPermissions(['view-dashboard','view-content','view-pages']);
                    break;
            }
        }
    }
}
