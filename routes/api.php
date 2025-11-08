<?php

use App\Http\Controllers\AdminRoleController;
use App\Http\Controllers\Api\Auth\AdminAuthController;
use App\Http\Controllers\Api\PageController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\SettingsController;
use Illuminate\Support\Facades\Route;


Route::prefix('admin')->controller(AdminAuthController::class)->group(function () {
    // 🔹 Public route (no auth required)
    Route::post('login', 'login')->name('admin.login');

    // 🔹 Protected routes (auth:admin required)
    Route::middleware('auth:user')->group(function () {

        Route::get('me', 'me')->name('admin.me');
        Route::get('profile', 'profile')->name('admin.profile');
        Route::post('logout', 'logout')->name('admin.logout');
    });
});


// ==========================
// 🔹 Role & Permission Management (Protected)
// ==========================
Route::middleware(['auth:user'])->controller(AdminRoleController::class)->group(function () {

    Route::get('admins', 'index');
    Route::put('admins/{admin}/role', 'update');
    Route::post('admins', 'store')->middleware('role:super-admin');
    Route::delete('admins/{admin}', 'destroy')->middleware('role:super-admin');

    Route::get('roles', 'roles');

    Route::get('roles-with-permissions', 'rolesWithPermissions');
    Route::put('roles/{role}/permissions', 'updatePermissions');

    Route::get('permissions', 'permissions');
});
Route::middleware('auth:user')->prefix('posts')->controller(PostController::class)->group(function () {
    Route::get('/', 'index');
    Route::post('/', 'store');
    Route::get('{slug}', 'show');
    Route::put('{slug}', 'update');
    Route::delete('{slug}', 'destroy');
    Route::patch('{slug}/status', 'toggleStatus');
});

Route::middleware('auth:user')->prefix('pages')->controller(PageController::class)->group(function () {
    Route::get('/', 'index');
    Route::post('/', 'store');
    Route::get('{slug}', 'show');
    Route::put('{slug}', 'update');
    Route::delete('{slug}', 'destroy');
    Route::patch('{slug}/status', 'toggleStatus');
});

Route::middleware('auth:user')->prefix('settings')->controller(SettingsController::class)->group(function () {
    Route::get('/', 'index');
    Route::post('/update', 'update');
});
