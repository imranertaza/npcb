<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
          // Create admin user
          $admin = Admin::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password123'),
            ]
        );

        // Create permissions
        $permissions = [
            'manage users',
            'manage roles',
            'view reports',
            'edit settings',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'admin']);
        }

        // Create super-admin role
        $role = Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'admin']);

        // Assign all permissions to role
        $role->syncPermissions($permissions);

        // Assign role to admin
        $admin->assignRole($role);
    }
}
