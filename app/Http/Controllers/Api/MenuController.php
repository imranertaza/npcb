<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    /**
     * Display a listing of menus.
     */
    public function index()
    {
        $menus = Menu::latest()->paginate(10);
        return apiResponse::success($menus, 'Menus fetched successfully');
    }

    /**
     * Store a newly created menu.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:150',
            'position' => 'nullable|string|max:150',
            'enabled'  => 'boolean'
        ]);

        $menu = Menu::create($data);

        return apiResponse::success($menu, 'Menu created successfully');
    }

    /**
     * Display the specified menu.
     */
    public function show(Menu $menu)
    {
        return apiResponse::success($menu, 'Menu fetched successfully');
    }

    /**
     * Update the specified menu.
     */
    public function update(Request $request, Menu $menu)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:150',
            'position' => 'nullable|string|max:150',
            'enabled'  => 'boolean'
        ]);

        $menu->update($data);

        return apiResponse::success($menu, 'Menu updated successfully');
    }

    /**
     * Remove the specified menu.
     */
    public function destroy(Menu $menu)
    {
        $menu->delete();
        return apiResponse::success(null, 'Menu deleted successfully');
    }
}
