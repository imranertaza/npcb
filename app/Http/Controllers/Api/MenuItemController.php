<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MenuItemController extends Controller
{
    /**
     * List menu items (optionally filter by menu_id).
     */
    public function index(Request $request)
    {
        $query = MenuItem::with('children');

        if ($request->has('menu_id')) {
            $query->where('menu_id', $request->menu_id);
        }

        // ✅ Only fetch top-level items
        $query->whereNull('parent_id');

        $items = $query->orderBy('order')->get();

        // Transform children -> elements recursively
        $transform = function ($item) use (&$transform) {
            return [
                'id'            => $item->id,
                'name'          => $item->name,
                'icon'          => $item->icon,
                'link'          => $item->url,
                'link_type'   => $item->link_type,
                'category_id'   => $item->category_id,
                'page_id'       => $item->page_id,
                'order'         => $item->order,
                'enabled'       => (bool) $item->enabled,
                '_newChildName' => '',
                'elements'      => $item->children->map(fn($child) => $transform($child))->toArray(),
            ];
        };

        $data = $items->map(fn($item) => $transform($item));

        return apiResponse::success($data, 'Menu items fetched successfully');
    }


    /**
     * Store a new menu item.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'menu_id'     => 'required|exists:menus,id',
            'name'        => 'required|string|max:255',
            'icon'        => 'nullable|string|max:255',
            'link_type'   => 'required|in:page,category,url',
            'page_id'     => 'nullable|exists:pages,id',
            'category_id' => 'nullable|exists:categories,id',
            'url'         => 'nullable|string',
            'enabled'     => 'boolean',
            'parent_id'   => 'nullable|exists:menu_items,id',
            'order'       => 'integer'
        ]);

        if ($validator->fails()) {
            return apiResponse::error($validator->errors(), 'Validation failed', 422);
        }

        $item = MenuItem::create($validator->validated());

        return apiResponse::success($item, 'Menu item created successfully', 201);
    }

    /**
     * Show a single menu item.
     */
    public function show(MenuItem $menuItem)
    {
        return apiResponse::success(
            $menuItem->load('children'),
            'Menu item fetched successfully'
        );
    }

    /**
     * Update a menu item.
     */
    public function update(Request $request, MenuItem $menuItem)
    {
        $validator = Validator::make($request->all(), [
            'name'        => 'sometimes|required|string|max:255',
            'icon'        => 'nullable|string|max:255',
            'link_type'   => 'sometimes|required|in:page,category,url',
            'page_id'     => 'nullable|exists:pages,id',
            'category_id' => 'nullable|exists:categories,id',
            'url'         => 'nullable|string',
            'enabled'     => 'boolean',
            'parent_id'   => 'nullable|exists:menu_items,id',
            'order'       => 'integer'
        ]);

        if ($validator->fails()) {
            return apiResponse::error($validator->errors(), 'Validation failed', 422);
        }

        $menuItem->update($validator->validated());

        return apiResponse::success($menuItem, 'Menu item updated successfully');
    }
    public function reorder(Request $request)
    {
        $validated = $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'items' => 'required|array',
            'items.*.id' => 'required|exists:menu_items,id',
            'items.*.order' => 'required|integer|min:1',
            'items.*.parent_id' => 'nullable|integer', // ✅ allow null
        ]);
    
        DB::transaction(function () use ($validated) {
            foreach ($validated['items'] as $item) {
                MenuItem::where('id', $item['id'])
                    ->where('menu_id', $validated['menu_id'])
                    ->update([
                        'order' => $item['order'],
                        'parent_id' => $item['parent_id'],
                    ]);
            }
        });
    
        return apiResponse::success(null, 'Menu items reordered successfully');
    }
    

    /**
     * Delete a menu item.
     */
    public function destroy(MenuItem $menuItem)
    {
        $menuItem->delete();

        return apiResponse::success(null, 'Menu item deleted successfully');
    }
}
