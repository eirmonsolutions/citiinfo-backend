<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\BusinessEnquiry;
use App\Services\BusinessEnquiryService;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function __construct(
        private readonly BusinessEnquiryService $enquiryService
    ) {}

    public function index(Request $request)
    {
        $user = $request->user();
        $tab = $request->get('tab', 'sent');

        $listingIds = $this->enquiryService->listingIdsForOwner($user->id);
        $hasListings = count($listingIds) > 0;

        if ($tab === 'inbox' && $hasListings) {
            $enquiries = BusinessEnquiry::query()
                ->with(['listing', 'sender', 'replier'])
                ->whereIn('business_listing_id', $listingIds)
                ->latest()
                ->paginate(15)
                ->withQueryString();

            $unreadCount = BusinessEnquiry::whereIn('business_listing_id', $listingIds)
                ->where('is_read', false)
                ->count();
        } else {
            $tab = 'sent';
            $enquiries = BusinessEnquiry::query()
                ->with(['listing', 'replier'])
                ->where(function ($q) use ($user) {
                    $q->where('sender_user_id', $user->id)
                        ->orWhere('email', $user->email);
                })
                ->latest()
                ->paginate(15)
                ->withQueryString();

            $unreadCount = BusinessEnquiry::query()
                ->where(function ($q) use ($user) {
                    $q->where('sender_user_id', $user->id)
                        ->orWhere('email', $user->email);
                })
                ->whereNotNull('admin_reply')
                ->where('is_read', false)
                ->count();
        }

        return view('user.messages.index', compact('enquiries', 'tab', 'hasListings', 'unreadCount'));
    }

    public function show(Request $request, BusinessEnquiry $enquiry)
    {
        $user = $request->user();
        $canManage = $this->enquiryService->canManageEnquiry($enquiry, $user);
        $canViewSent = $this->enquiryService->canViewSentEnquiry($enquiry, $user);

        abort_unless($canManage || $canViewSent, 403);

        if ($canManage && ! $enquiry->is_read) {
            $enquiry->update(['is_read' => true]);
        }

        $enquiry->load(['listing', 'sender', 'replier', 'owner']);

        $mode = $canManage ? 'inbox' : 'sent';

        return view('user.messages.show', compact('enquiry', 'mode'));
    }

    public function reply(Request $request, BusinessEnquiry $enquiry)
    {
        abort_unless($this->enquiryService->canManageEnquiry($enquiry, $request->user()), 403);

        $data = $request->validate([
            'admin_reply' => 'required|string|min:3|max:5000',
        ]);

        $this->enquiryService->reply($enquiry, $request->user(), $data['admin_reply']);

        return back()->with('success', 'Reply sent successfully.');
    }
}
