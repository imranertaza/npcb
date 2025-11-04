<?php

use App\Http\Controllers\AdminRoleController;
use App\Http\Controllers\Api\Auth\AdminAuthController;
use Illuminate\Support\Facades\Route;


Route::prefix('admin')->controller(AdminAuthController::class)->group(function () {
    // 🔹 Public route (no auth required)
    Route::post('login', 'login')->name('admin.login');

    // 🔹 Protected routes (auth:admin required)
    Route::middleware('auth:admin')->group(function () {

        Route::get('me', 'me')->name('admin.me');
        Route::get('profile', 'profile')->name('admin.profile');
        Route::post('logout', 'logout')->name('admin.logout');

        Route::get('admins', 'index');                       // List all admins with roles
        Route::put('admins/{admin}/role', 'update');         // Update admin role
        Route::post('admins', 'store')->middleware('role:super-admin'); // Add new admin (super-admin only)
        Route::delete('admins/{admin}', 'destroy')->middleware('role:super-admin'); // Delete admin (super-admin only)

        // 📋 Role Listing
        Route::get('roles', 'roles');                        // List all roles

        // 🔐 Role Permission Management
        Route::get('roles-with-permissions', 'rolesWithPermissions'); // List roles + permissions
        Route::put('roles/{role}/permissions', 'updatePermissions');  // Update role permissions

        // ✅ Permission Listing (for frontend rendering)
        Route::get('permissions', 'permissions');             // List all permissions
    });
});


// ==========================
// 🔹 Role & Permission Management (Protected)
// ==========================
Route::middleware(['auth:admin'])->controller(AdminRoleController::class)->group(function () {

    // 👤 Admin Role Management
    Route::get('admins', 'index');                       // List all admins with roles
    Route::put('admins/{admin}/role', 'update');         // Update admin role
    Route::post('admins', 'store')->middleware('role:super-admin'); // Add new admin (super-admin only)
    Route::delete('admins/{admin}', 'destroy')->middleware('role:super-admin'); // Delete admin (super-admin only)

    // 📋 Role Listing
    Route::get('roles', 'roles');                        // List all roles

    // 🔐 Role Permission Management
    Route::get('roles-with-permissions', 'rolesWithPermissions'); // List roles + permissions
    Route::put('roles/{role}/permissions', 'updatePermissions');  // Update role permissions

    // ✅ Permission Listing (for frontend rendering)
    Route::get('permissions', 'permissions');             // List all permissions
});
