<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BusinessEnquiry;
use App\Models\BusinessListing;
use Illuminate\Http\Request;

class InboxController extends Controller
{
    public function index(Request $request)
    {
        $admin = auth()->user();
        $listingIds = BusinessListing::where('user_id', $admin->id)->pluck('id');

        $filter = $request->get('filter', 'all');

        $enquiries = BusinessEnquiry::query()
            ->with('listing')
            ->whereIn('business_listing_id', $listingIds)
            ->when($filter === 'unread', fn($q) => $q->where('is_read', false))
            ->when($filter === 'read', fn($q) => $q->where('is_read', true))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $unreadCount = BusinessEnquiry::whereIn('business_listing_id', $listingIds)
            ->where('is_read', false)
            ->count();

        return view('admin.inbox.index', compact('enquiries', 'unreadCount', 'filter'));
    }

    public function show(BusinessEnquiry $enquiry)
    {
        $this->authorizeEnquiry($enquiry);

        if (!$enquiry->is_read) {
            $enquiry->update(['is_read' => true]);
        }

        $enquiry->load('listing');

        return view('admin.inbox.show', compact('enquiry'));
    }

    public function markRead(BusinessEnquiry $enquiry)
    {
        $this->authorizeEnquiry($enquiry);

        $enquiry->update(['is_read' => true]);

        return back()->with('success', 'Message marked as read.');
    }

    public function destroy(BusinessEnquiry $enquiry)
    {
        $this->authorizeEnquiry($enquiry);

        $enquiry->delete();

        return redirect()->route('admin.inbox.index')->with('success', 'Message deleted.');
    }

    private function authorizeEnquiry(BusinessEnquiry $enquiry): void
    {
        $listingIds = BusinessListing::where('user_id', auth()->id())->pluck('id');

        abort_unless($listingIds->contains($enquiry->business_listing_id), 403);
    }
}
