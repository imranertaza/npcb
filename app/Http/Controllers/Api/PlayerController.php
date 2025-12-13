<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PlayerController extends Controller
{
    /**
     * List players with optional search + pagination
     */
    public function index(Request $request)
    {
        $query = Player::latest();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sport', 'like', "%{$search}%")
                  ->orWhere('position', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        $perPage = $request->input('per_page', 10);
        $players = $query->paginate($perPage);

        return ApiResponse::success($players, 'Players retrieved successfully');
    }

    /**
     * Show single player by slug
     */
    public function show($slug)
    {
        $player = Player::where('slug', $slug)->firstOrFail();
        return ApiResponse::success($player, 'Player retrieved successfully');
    }

    /**
     * Store new player
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'sport'     => 'required|string|max:255',
            'position'  => 'nullable|string|max:255',
            'team'      => 'nullable|string|max:255',
            'age'       => 'nullable|integer',
            'image'     => 'nullable|file|mimes:jpg,jpeg,png,gif|max:4096',
            'status'    => ['required', Rule::in(['0', '1'])],
        ]);

        // Generate slug from name + position + sport + age
        $validated['slug'] = Str::slug(
            $validated['name'].'-'.$validated['position'].'-'.$validated['sport'].'-'.$validated['age']
        );

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('players', 'public');
        }

        $player = Player::create($validated);

        return ApiResponse::success($player, 'Player created successfully');
    }

    /**
     * Update existing player
     */
    public function update(Request $request, $id)
    {
        $player = Player::findOrFail($id);

        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'sport'     => 'required|string|max:255',
            'position'  => 'nullable|string|max:255',
            'team'      => 'nullable|string|max:255',
            'age'       => 'nullable|integer',
            'image'     => 'nullable|file|mimes:jpg,jpeg,png,gif|max:4096',
            'status'    => ['required', Rule::in(['0', '1'])],
        ]);

        // Regenerate slug
        $validated['slug'] = Str::slug(
            $validated['name'].'-'.$validated['position'].'-'.$validated['sport'].'-'.$validated['age']
        );

        if ($request->hasFile('image')) {
            if ($player->image && Storage::disk('public')->exists($player->image)) {
                Storage::disk('public')->delete($player->image);
            }
            $validated['image'] = $request->file('image')->store('players', 'public');
        }

        $player->update($validated);

        return ApiResponse::success($player, 'Player updated successfully');
    }

    /**
     * Toggle active/inactive status
     */
    public function toggleStatus($slug)
    {
        $player = Player::where('slug', $slug)->firstOrFail();
        $player->status = $player->status == 1 ? 0 : 1;
        $player->save();

        return ApiResponse::success([
            'status' => $player->status,
        ], $player->status == 1 ? 'Player active' : 'Player inactive');
    }

    /**
     * Delete player
     */
    public function destroy($slug)
    {
        $player = Player::where('slug', $slug)->firstOrFail();

        if ($player->image && Storage::disk('public')->exists($player->image)) {
            Storage::disk('public')->delete($player->image);
        }

        $player->delete();

        return ApiResponse::success(null, 'Player deleted successfully');
    }
}