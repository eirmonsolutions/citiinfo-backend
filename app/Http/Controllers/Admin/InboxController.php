<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BusinessEnquiry;
use App\Services\BusinessEnquiryService;
use Illuminate\Http\Request;

class InboxController extends Controller
{
    public function __construct(
        private readonly BusinessEnquiryService $enquiryService
    ) {}

    public function index(Request $request)
    {
        $user = $request->user();
        $tab = $request->get('tab', 'inbox');
        $listingIds = $this->enquiryService->listingIdsForOwner($user->id);

        if ($tab === 'sent') {
            $enquiries = BusinessEnquiry::query()
                ->with(['listing', 'replier'])
                ->where(function ($q) use ($user) {
                    $q->where('sender_user_id', $user->id)
                        ->orWhere('email', $user->email);
                })
                ->latest()
                ->paginate(15)
                ->withQueryString();

            $unreadCount = 0;
            $filter = 'all';

            return view('admin.inbox.index', compact('enquiries', 'unreadCount', 'filter', 'tab'));
        }

        $tab = 'inbox';
        $filter = $request->get('filter', 'all');

        $enquiries = BusinessEnquiry::query()
            ->with(['listing', 'sender', 'replier'])
            ->whereIn('business_listing_id', $listingIds)
            ->when($filter === 'unread', fn ($q) => $q->where('is_read', false))
            ->when($filter === 'read', fn ($q) => $q->where('is_read', true))
            ->when($filter === 'replied', fn ($q) => $q->whereNotNull('admin_reply'))
            ->when($filter === 'pending', fn ($q) => $q->whereNull('admin_reply'))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $unreadCount = BusinessEnquiry::whereIn('business_listing_id', $listingIds)
            ->where('is_read', false)
            ->count();

        return view('admin.inbox.index', compact('enquiries', 'unreadCount', 'filter', 'tab'));
    }

    public function show(BusinessEnquiry $enquiry)
    {
        $user = auth()->user();
        $canManage = $this->enquiryService->canManageEnquiry($enquiry, $user);
        $canViewSent = $this->enquiryService->canViewSentEnquiry($enquiry, $user);

        abort_unless($canManage || $canViewSent, 403);

        if ($canManage && ! $enquiry->is_read) {
            $enquiry->update(['is_read' => true]);
        }

        $enquiry->load(['listing', 'sender', 'replier', 'owner']);

        $mode = $canManage ? 'inbox' : 'sent';

        return view('admin.inbox.show', compact('enquiry', 'mode'));
    }

    public function reply(Request $request, BusinessEnquiry $enquiry)
    {
        abort_unless($this->enquiryService->canManageEnquiry($enquiry, $request->user()), 403);

        $data = $request->validate([
            'admin_reply' => 'required|string|min:3|max:5000',
        ]);

        $this->enquiryService->reply($enquiry, $request->user(), $data['admin_reply']);

        return back()->with('success', 'Reply sent to customer successfully.');
    }

    public function markRead(BusinessEnquiry $enquiry)
    {
        abort_unless($this->enquiryService->canManageEnquiry($enquiry, auth()->user()), 403);

        $enquiry->update(['is_read' => true]);

        return back()->with('success', 'Message marked as read.');
    }

    public function destroy(BusinessEnquiry $enquiry)
    {
        abort_unless($this->enquiryService->canManageEnquiry($enquiry, auth()->user()), 403);

        $enquiry->delete();

        return redirect()->route('admin.inbox.index', ['tab' => 'inbox'])->with('success', 'Message deleted.');
    }
}
