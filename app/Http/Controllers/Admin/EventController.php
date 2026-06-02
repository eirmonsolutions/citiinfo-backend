<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\BusinessListing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function index()
    {
        $admin = auth()->user();

        $events = Event::with('listing')
            ->where('user_id', $admin->id) // ✅ only own events
            ->latest()
            ->get();

        return view('admin.event.index', compact('events'));
    }

    public function create()
    {
        $admin = auth()->user();

        // ✅ Sirf login admin ki listings (same as your announcement logic)
        $listings = BusinessListing::select('id', 'business_name')
            ->where('user_id', $admin->id)
            ->orderBy('business_name')
            ->get();

        return view('admin.event.create', compact('listings'));
    }

    public function store(Request $request)
    {
        $admin = auth()->user();

        $data = $request->validate([
            'listing_id'       => ['required', 'integer', 'exists:business_listings,id'],
            'listing_name'     => ['nullable', 'string', 'max:255'],

            'title'            => ['required', 'string', 'max:255'],
            'location'         => ['required', 'string', 'max:255'],

            'start_date'       => ['required', 'date'],
            'start_time'       => ['nullable', 'date_format:H:i'],

            'end_date'         => ['required', 'date', 'after_or_equal:start_date'],
            'end_time'         => ['nullable', 'date_format:H:i'],

            'description'      => ['required', 'string'],

            'ticket_platform'  => ['nullable', 'string', 'max:50'],
            'ticket_url'       => ['nullable', 'url', 'max:255'],

            'featured_image'   => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        // ✅ security: ensure listing belongs to this admin
        $ownListing = BusinessListing::where('id', $data['listing_id'])
            ->where('user_id', $admin->id)
            ->first();

        if (!$ownListing) {
            return back()->withErrors(['listing_id' => 'Invalid listing selection.'])->withInput();
        }

        // listing_name fallback
        $data['listing_name'] = $data['listing_name'] ?? $ownListing->business_name;

        // upload image
        if ($request->hasFile('featured_image')) {
            $path = $request->file('featured_image')->store('events', 'public'); // storage/app/public/events
            $data['featured_image'] = $path; // e.g. events/abc.jpg
        }

        $data['user_id'] = $admin->id;
        $data['is_active'] = 1;

        Event::create($data);

        return redirect()->route('admin.event.index')->with('success', 'Event created successfully');
    }

    public function edit(Event $event)
    {
        $admin = auth()->user();

        // ✅ only own event
        abort_if($event->user_id !== $admin->id, 403);

        // ✅ Sirf login admin ki listings
        $listings = BusinessListing::select('id', 'business_name')
            ->where('user_id', $admin->id)
            ->orderBy('business_name')
            ->get();

        return view('admin.event.edit', compact('event', 'listings'));
    }

    public function update(Request $request, Event $event)
    {
        $admin = auth()->user();

        // validate
        $data = $request->validate([
            'listing_id'       => 'required|integer',
            'listing_name'     => 'nullable|string|max:255',
            'title'            => 'required|string|max:255',
            'location'         => 'required|string|max:255',
            'start_date'       => 'required|date',
            'start_time'       => 'required',
            'end_date'         => 'required|date',
            'end_time'         => 'required',
            'description'      => 'required|string',
            'ticket_platform'  => 'required|string|in:Facebook,Eventbrite,Website,Other',
            'ticket_url'       => 'nullable|url',
            'featured_image'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // ensure listing belongs to logged in admin
        $listingOk = BusinessListing::where('id', $data['listing_id'])
            ->where('user_id', $admin->id)
            ->exists();

        if (!$listingOk) {
            return back()->withErrors(['listing_id' => 'Invalid listing selected.'])->withInput();
        }

        // image replace if new uploaded
        if ($request->hasFile('featured_image')) {
            if (!empty($event->featured_image) && Storage::disk('public')->exists($event->featured_image)) {
                Storage::disk('public')->delete($event->featured_image);
            }
            $data['featured_image'] = $request->file('featured_image')->store('events', 'public');
        }

        $event->update($data);

        return redirect()->route('admin.event.index')->with('success', 'Event updated successfully!');
    }

    public function toggle(Request $request, Event $event)
    {
        $admin = auth()->user();
        abort_if($event->user_id !== $admin->id, 403);

        $request->validate([
            'is_active' => ['required', 'boolean']
        ]);

        $event->is_active = (bool) $request->is_active;
        $event->save();

        return response()->json(['is_active' => $event->is_active ? 1 : 0]);
    }

    public function destroy(Event $event)
    {
        $admin = auth()->user();
        abort_if($event->user_id !== $admin->id, 403);

        if (!empty($event->featured_image) && Storage::disk('public')->exists($event->featured_image)) {
            Storage::disk('public')->delete($event->featured_image);
        }

        $event->delete();

        return redirect()->route('admin.event.index')->with('success', 'Event deleted successfully');
    }
}
