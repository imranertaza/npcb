<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * API Controller for managing events.
 *
 * Handles CRUD operations, status toggling, category assignment, and file management
 * for events (banner and featured images).
 */
class EventController extends Controller
{
    /**
     * Retrieve a paginated list of events with optional search.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * Retrieve a single event by its slug along with its category.
     *
     * @param string $slug The unique slug of the event
     * @return \Illuminate\Http\JsonResponse
     * @throws ModelNotFoundException
     */
    public function show($id)
    {
        $event = Event::with('category')->findOrFail($id);
        return ApiResponse::success($event, 'Event retrieved successfully');
    }

    /**
     * Store a new event.
     *
     * Handles banner and featured image uploads and assigns the authenticated user
     * as creator/updater.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_category_id' => 'required|exists:event_categories,id',
            'title'             => 'required|string|max:255',
            'slug'              => 'required|string|unique:events,slug',
            'description'       => 'nullable|string',
            'type'              => 'required|integer|in:0,1',
            'banner_image'      => 'nullable|file|mimes:jpg,jpeg,png,gif|max:4096',
            'featured_image'    => 'nullable|file|mimes:jpg,jpeg,png,gif|max:4096',
        ]);

        $validated['createdBy'] = Auth::id();
        $validated['updatedBy'] = Auth::id();

        // Create event first (without images)
        $event = Event::create($validated);

        // Handle banner image
        if ($request->hasFile('banner_image')) {
            $filename = uniqid('banner_') . '.' . $request->file('banner_image')->getClientOriginalExtension();
            $path = $request->file('banner_image')
                ->storeAs("events/{$event->id}/images/banner", $filename, 'public');
            $event->update(['banner_image' => $path]);
        }

        // Handle featured image
        if ($request->hasFile('featured_image')) {
            $filename = uniqid('featured_') . '.' . $request->file('featured_image')->getClientOriginalExtension();
            $path = $request->file('featured_image')
                ->storeAs("events/{$event->id}/images/featured", $filename, 'public');
            $event->update(['featured_image' => $path]);
        }

        return ApiResponse::success($event, 'Event created successfully');
    }


    /**
     * Update an existing event.
     *
     * Replaces banner/featured images only if new files are uploaded (deletes old files).
     *
     * @param Request $request
     * @param int $id The ID of the event to update
     * @return \Illuminate\Http\JsonResponse
     */
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
            'type'              => 'required|integer|in:0,1,2',
            'description'       => 'nullable|string',
            'banner_image'      => 'nullable|file|mimes:jpg,jpeg,png,gif|max:4096',
            'featured_image'    => 'nullable|file|mimes:jpg,jpeg,png,gif|max:4096',
        ]);

        $validated['updatedBy'] = Auth::id();

        // Handle banner image replacement
        if ($request->hasFile('banner_image')) {
            if ($event->banner_image && Storage::disk('public')->exists($event->banner_image)) {
                Storage::disk('public')->delete($event->banner_image);
            }

            $filename = uniqid('banner_') . '.' . $request->file('banner_image')->getClientOriginalExtension();
            $validated['banner_image'] = $request->file('banner_image')
                ->storeAs("events/{$event->id}/images/banner", $filename, 'public');
        }

        // Handle featured image replacement
        if ($request->hasFile('featured_image')) {
            if ($event->featured_image && Storage::disk('public')->exists($event->featured_image)) {
                Storage::disk('public')->delete($event->featured_image);
            }

            $filename = uniqid('featured_') . '.' . $request->file('featured_image')->getClientOriginalExtension();
            $validated['featured_image'] = $request->file('featured_image')
                ->storeAs("events/{$event->id}/images/featured", $filename, 'public');
        }

        $event->update($validated);

        return ApiResponse::success($event, 'Event updated successfully');
    }


    /**
     * Toggle the publication status (active/inactive) of an event.
     *
     * @param string $slug The slug of the event
     * @return \Illuminate\Http\JsonResponse
     * @throws ModelNotFoundException
     */
    public function toggleStatus($id)
    {
        $event = Event::findOrFail($id);
        $event->status = $event->status === '1' ? '0' : '1';
        $event->save();

        return ApiResponse::success([
            'status' => $event->status,
        ], $event->status === '1' ? 'Event active' : 'Event inactive');
    }

    /**
     * Permanently delete an event along with its associated banner and featured images.
     *
     * @param string $slug The slug of the event to delete
     * @return \Illuminate\Http\JsonResponse
     * @throws ModelNotFoundException
     */
    public function destroy($id)
    {
        $event = Event::findOrFail($id);

        if ($event->banner_image && Storage::disk('public')->exists($event->banner_image)) {
            Storage::disk('public')->delete($event->banner_image);
        }

        if ($event->featured_image && Storage::disk('public')->exists($event->featured_image)) {
            Storage::disk('public')->delete($event->featured_image);
        }

        $event->delete();

        return ApiResponse::success(null, 'Event deleted successfully');
    }
}
