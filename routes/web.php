<?php

use App\Models\Admin;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

// Serve the Vue SPA (frontend)
Route::get('/{any}', function () {
//     $admin= Admin::first(); // Assuming an admin user already exists
//     $adminRole = Role::find(1);

//     // Create permissions if they don't exist
//     $editArticlesPermission = Permission::firstOrCreate(['name' => 'edit articles','guard_name'=>'admin']);
//     $manageUsersPermission = Permission::firstOrCreate(['name' => 'manage users','guard_name'=>'admin']);

//     // Assign permissions to the admin role
//     $adminRole->givePermissionTo($editArticlesPermission);
//     $adminRole->givePermissionTo($manageUsersPermission);
//   dd($admin->getAllPermissions());
//     dd($admin->hasRole('super-admin'));
    // You can also assign multiple permissions at once:
    // $adminRole->givePermissionTo(['edit articles', 'manage users']);
    // dd(ModelsRole::get(), Permission::get());
    return view('welcome');
})->where('any', '.*'); // Catch-all route for Vue Router

require __DIR__ . '/auth.php';
