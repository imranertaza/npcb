<?php

use App\Http\Controllers\AdminRoleController;
use App\Http\Controllers\Api\Auth\AdminAuthController;
use App\Http\Controllers\Api\Auth\UserAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('user')->group(function () {
    Route::post('login', [UserAuthController::class, 'login']);
    Route::post('register', [UserAuthController::class, 'register']); // optional
    Route::middleware('auth:user')->group(function () {
        Route::get('profile', [UserAuthController::class, 'profile']);
        Route::post('logout', [UserAuthController::class, 'logout']);
    });
});


Route::prefix('admin')->group(function () {
    Route::post('login', [AdminAuthController::class, 'login']);
    Route::get('me', [AdminAuthController::class, 'me'])->middleware('auth:admin');
    Route::middleware(['auth:admin', 'role:super-admin'])->group(function () {
        Route::get('profile', [AdminAuthController::class, 'profile']);
        Route::post('logout', [AdminAuthController::class, 'logout']);
    });
});

Route::middleware(['auth:admin'])->get('/admin/check-access', function (Request $request) {
    $admin = $request->user();
    $permission = $request->query('permission');

    if (!$permission || !$admin->can($permission)) {
        return response()->json(['allowed' => false], 403);
    }

    return response()->json(['allowed' => true]);
});


Route::middleware(['auth:admin'])->group(function () {

    // 🔍 Admin Role Management
    Route::get('/admins', [AdminRoleController::class, 'index']); // List all admins with roles
    Route::put('/admins/{admin}/role', [AdminRoleController::class, 'update']); // Update admin role
    Route::post('/admins', [AdminRoleController::class, 'store'])->middleware(['auth:admin', 'role:super-admin']);
    Route::delete('/admins/{admin}', [AdminRoleController::class, 'destroy'])->middleware(['auth:admin', 'role:super-admin']);
    // 📋 Role Listing
    Route::get('/roles', [AdminRoleController::class, 'roles']); // List all roles

    // 🔍 Role Permission Management
    Route::get('/roles-with-permissions', [AdminRoleController::class, 'rolesWithPermissions']); // List roles + permissions
    Route::put('/roles/{role}/permissions', [AdminRoleController::class, 'updatePermissions']); // Update permissions for role

    // 📋 Permission Listing (optional for frontend checkbox rendering)
    Route::get('/permissions', [AdminRoleController::class, 'permissions']); // List all permissions
});