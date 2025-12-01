<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class EventController extends Controller
{
    // List events with optional search
    public function index(Request $request)
    {
        $query = Event::latest();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        $perPage = $request->input('per_page', 10);
        $events = $query->paginate($perPage);

        return ApiResponse::success($events, 'Events retrieved successfully');
    }

    // Show single event by slug
    public function show($slug)
    {
        $event = Event::with('category')->where('slug', $slug)->firstOrFail();
        return ApiResponse::success($event, 'Event retrieved successfully');
    }

    // Store new event
    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_category_id' => 'required|exists:event_categories,id',
            'title'             => 'required|string|max:255',
            'slug'              => 'required|string|unique:events,slug',
            'description'       => 'nullable|string',
            'file'              => 'nullable|file|mimes:jpg,jpeg,png,gif,pdf|max:4096',
            'categories' => 'required|array',
            'categories.*' => 'exists:event_categories,id',
        ]);

        $validated['createdBy'] = Auth::id();
        $validated['updatedBy'] = Auth::id();

        if ($request->hasFile('file')) {
            $validated['file'] = $request->file('file')->store('events', 'public');
        }

        $event = Event::create($validated);

        if (isset($validated['categories'])) {
            $event->syncCategories($validated['categories']);
        }

        return ApiResponse::success($event, 'Event created successfully');
    }

    // Update existing event
    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $validated = $request->validate([
            'event_category_id' => 'required|exists:event_categories,id',
            'title'             => 'required|string|max:255',
            'slug'              => [
                'required',
                'string',
                Rule::unique('events', 'slug')->ignore($event->id),
            ],
            'description'       => 'nullable|string',
            'file'              => 'nullable|file|mimes:jpg,jpeg,png,gif,pdf|max:4096',
            'categories' => 'required|array',
            'categories.*' => 'exists:event_categories,id',
        ]);

        $validated['updatedBy'] = Auth::id();

        if ($request->hasFile('file')) {
            if ($event->file && Storage::disk('public')->exists($event->file)) {
                Storage::disk('public')->delete($event->file);
            }
            $validated['file'] = $request->file('file')->store('events', 'public');
        }

        $event->update($validated);

        if (isset($validated['categories'])) {
            $event->syncCategories($validated['categories']);
        }

        return ApiResponse::success($event, 'Event updated successfully');
    }

    // Toggle active/inactive status
    public function toggleStatus($slug)
    {
        $event = Event::where('slug', $slug)->firstOrFail();
        $event->status = $event->status === '1' ? '0' : '1';
        $event->save();

        return response()->json([
            'message' => $event->status === '1' ? 'Event active' : 'Event inactive',
            'status'  => $event->status,
        ]);
    }

    // Delete event
    public function destroy($slug)
    {
        $event = Event::where('slug', $slug)->firstOrFail();

        if ($event->file && Storage::disk('public')->exists($event->file)) {
            Storage::disk('public')->delete($event->file);
        }

        $event->delete();

        return ApiResponse::success(null, 'Event deleted successfully');
    }
}
