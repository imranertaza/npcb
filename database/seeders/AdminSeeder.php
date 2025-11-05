<?php

namespace Database\Seeders;

use App\Models\User;
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
          $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('12345678'),
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
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'user']);
        }

        // Create super-admin role
        $role = Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'user']);

        // Assign all permissions to role
        $role->syncPermissions($permissions);

        // Assign role to admin
        $admin->assignRole($role);
    }
}
