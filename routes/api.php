<?php

use App\Http\Controllers\AdminRoleController;
use App\Http\Controllers\Api\Auth\AdminAuthController;
use App\Http\Controllers\Api\CategoryController;
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
    Route::get('admins/{admin}', 'show');
    Route::put('admins/{admin}', 'updateUser')->middleware('permission:update-users');
    Route::put('admins/{admin}/role', 'updateUserRole')->middleware('permission:update-users');
    Route::post('admins', 'store')->middleware('permission:create-users');
    Route::delete('admins/{admin}', 'destroy')->middleware('permission: delete-users');

    Route::get('roles', 'roles');

    Route::get('roles-with-permissions', 'rolesWithPermissions');
    Route::put('roles/{role}/permissions', 'updatePermissions')->middleware('permission:update-permissions');

    Route::get('permissions', 'permissions');
});
Route::middleware('auth:user')->prefix('posts')->controller(PostController::class)->group(function () {
    Route::get('/', 'index')->middleware('permission:view-posts');
    Route::post('/', 'store')->middleware('permission:create-posts');
    Route::get('{slug}', 'show')->middleware('permission:view-posts');
    Route::put('{slug}', 'update')->middleware('permission:edit-posts');
    Route::delete('{slug}', 'destroy')->middleware('permission:delete-posts');
    Route::patch('{slug}/status', 'toggleStatus')->middleware('permission:publish-posts');
});

Route::middleware('auth:user')->prefix('pages')->controller(PageController::class)->group(function () {
    Route::get('/', 'index')->middleware('permission:view-pages');
    Route::post('/', 'store')->middleware('permission:create-pages');
    Route::get('{slug}', 'show')->middleware('permission:view-pages');
    Route::put('{slug}', 'update')->middleware('permission:edit-pages');
    Route::delete('{slug}', 'destroy')->middleware('permission:delete-pages');
    Route::patch('{slug}/status', 'toggleStatus')->middleware('permission:publish pages');
});

Route::middleware('auth:user')->prefix('settings')->controller(SettingsController::class)->group(function () {
    Route::get('/', 'index');
    Route::post('/update', 'update');
});


Route::prefix('categories')->middleware('auth:user')->group(function () {
    Route::get('/', [CategoryController::class, 'index'])->middleware('permission:view-categories');
    Route::post('/', [CategoryController::class, 'store'])->middleware('permission:create-categories');
    Route::get('{category}', [CategoryController::class, 'show'])->middleware('permission:view-categories');
    Route::put('{category}', [CategoryController::class, 'update'])->middleware('permission:edit-categories');
    Route::delete('{category}', [CategoryController::class, 'destroy'])->middleware('permission:delete-categories');
});