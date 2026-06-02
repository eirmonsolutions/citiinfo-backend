<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\BusinessListing;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index(Request $request)
    {
        $admin = auth()->user();

        // ✅ admin ki listing ids
        $listingIds = BusinessListing::where('user_id', $admin->id)->pluck('id');

        // (optional) filter by listing_id from dropdown/search
        $selectedListingId = $request->get('listing_id');

        $announcements = Announcement::query()
            ->whereIn('listing_id', $listingIds)
            ->when($selectedListingId, fn($q) => $q->where('listing_id', $selectedListingId))
            ->latest()
            ->get();

        // dropdown ke liye listings
        $listings = BusinessListing::select('id', 'business_name')
            ->where('user_id', $admin->id)
            ->orderBy('business_name')
            ->get();

        return view('admin.announcement.index', compact('announcements', 'listings', 'selectedListingId'));
    }

    public function create()
    {
        $admin = auth()->user();

        $listings = BusinessListing::select('id', 'business_name')
            ->where('user_id', $admin->id)
            ->orderBy('business_name')
            ->get();

        return view('admin.announcement.create', compact('listings'));
    }


    public function store(Request $request)
    {
        $admin = auth()->user();

        // ✅ admin ki listing ids (security)
        $allowedListingIds = BusinessListing::where('user_id', $admin->id)->pluck('id')->toArray();

        $validated = $request->validate([
            'listing_id'   => ['required', 'integer', 'in:' . implode(',', $allowedListingIds)],
            'icon'         => ['nullable', 'string', 'max:80'],
            'title'        => ['required', 'string', 'max:255'],
            'description'  => ['nullable', 'string'],
            'button_text'  => ['nullable', 'string', 'max:80'],
            'button_link'  => ['nullable', 'string', 'max:255'],
            'start_date'   => ['nullable', 'date'],
            'end_date'     => ['nullable', 'date', 'after_or_equal:start_date'],
            'is_active'    => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        Announcement::create($validated);

        return redirect()->route('admin.announcement.index')->with('success', 'Announcement created successfully.');
    }

    public function edit(Announcement $announcement)
    {
        $admin = auth()->user();

        // ✅ security: admin sirf apni listing ka announcement edit kare
        $owns = BusinessListing::where('id', $announcement->listing_id)
            ->where('user_id', $admin->id)
            ->exists();

        abort_if(!$owns, 403);

        $listings = BusinessListing::select('id', 'business_name')
            ->where('user_id', $admin->id)
            ->orderBy('business_name')
            ->get();

        return view('admin.announcement.edit', compact('announcement', 'listings'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $admin = auth()->user();

        $allowedListingIds = BusinessListing::where('user_id', $admin->id)->pluck('id')->toArray();

        // ✅ security check (announcement belongs to admin)
        abort_if(!in_array($announcement->listing_id, $allowedListingIds), 403);

        $validated = $request->validate([
            'listing_id'   => ['required', 'integer', 'in:' . implode(',', $allowedListingIds)],
            'icon'         => ['nullable', 'string', 'max:80'],
            'title'        => ['required', 'string', 'max:255'],
            'description'  => ['nullable', 'string'],
            'button_text'  => ['nullable', 'string', 'max:80'],
            'button_link'  => ['nullable', 'string', 'max:255'],
            'start_date'   => ['nullable', 'date'],
            'end_date'     => ['nullable', 'date', 'after_or_equal:start_date'],
            'is_active'    => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $announcement->update($validated);

        return redirect()->route('admin.announcement.index')->with('success', 'Announcement updated successfully.');
    }

    public function toggle(Request $request, Announcement $announcement)
    {
        $admin = auth()->user();

        // ✅ security
        $owns = BusinessListing::where('id', $announcement->listing_id)
            ->where('user_id', $admin->id)
            ->exists();

        abort_if(!$owns, 403);

        $request->validate([
            'is_active' => ['required', 'boolean'],
        ]);

        $announcement->update([
            'is_active' => $request->boolean('is_active'),
        ]);

        return response()->json([
            'success'   => true,
            'is_active' => (int) $announcement->is_active,
        ]);
    }

    public function destroy(Announcement $announcement)
    {
        $admin = auth()->user();

        $owns = BusinessListing::where('id', $announcement->listing_id)
            ->where('user_id', $admin->id)
            ->exists();

        abort_if(!$owns, 403);

        $announcement->delete();

        return back()->with('success', 'Announcement deleted successfully.');
    }
}
