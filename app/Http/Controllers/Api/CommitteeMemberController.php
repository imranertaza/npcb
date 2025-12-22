<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Helpers\ApiResponse;
use App\Models\CommitteeMember;

class CommitteeMemberController extends Controller
{
    public function index(Request $request)
    {
        $query = CommitteeMember::orderBy('order');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('designation', 'like', "%{$search}%");
            });
        }

        $perPage = $request->input('per_page', 10);
        $members = $query->paginate($perPage);

        return ApiResponse::success($members, 'Committee members retrieved successfully');
    }

    /**
     * Show single committee member by id
     */
    public function show($id)
    {
        $member = CommitteeMember::findOrFail($id);
        return ApiResponse::success($member, 'Committee member retrieved successfully');
    }

    /**
     * Store new committee member
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'image'       => 'nullable|file|mimes:jpg,jpeg,png,gif|max:4096',
            'order'       => 'required|integer',
            'status'      => ['required', Rule::in(['0', '1'])],
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('committee_members', 'public');
        }

        $member = CommitteeMember::create($validated);

        return ApiResponse::success($member, 'Committee member created successfully');
    }

    /**
     * Update existing committee member
     */
    public function update(Request $request, $id)
    {
        $member = CommitteeMember::findOrFail($id);

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'image'       => 'nullable|file|mimes:jpg,jpeg,png,gif|max:4096',
            'order'       => 'required|integer',
            'status'      => ['required', Rule::in(['0', '1'])],
        ]);

        if ($request->hasFile('image')) {
            if ($member->image && Storage::disk('public')->exists($member->image)) {
                Storage::disk('public')->delete($member->image);
            }
            $validated['image'] = $request->file('image')->store('committee_members', 'public');
        }

        $member->update($validated);

        return ApiResponse::success($member, 'Committee member updated successfully');
    }

    /**
     * Toggle active/inactive status
     */
    public function toggleStatus($id)
    {
        $member = CommitteeMember::findOrFail($id);
        $member->status = $member->status == 1 ? 0 : 1;
        $member->save();

        return ApiResponse::success([
            'status' => $member->status,
        ], $member->status == 1 ? 'Member active' : 'Member inactive');
    }

    /**
     * Delete committee member
     */
    public function destroy($id)
    {
        $member = CommitteeMember::findOrFail($id);

        if ($member->image && Storage::disk('public')->exists($member->image)) {
            Storage::disk('public')->delete($member->image);
        }

        $member->delete();

        return ApiResponse::success(null, 'Committee member deleted successfully');
    }
}
