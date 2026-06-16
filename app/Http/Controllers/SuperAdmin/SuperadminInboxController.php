<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\BusinessEnquiry;
use App\Services\BusinessEnquiryService;
use Illuminate\Http\Request;

class SuperadminInboxController extends Controller
{
    public function __construct(
        private readonly BusinessEnquiryService $enquiryService
    ) {}

    public function index(Request $request)
    {
        $filter = $request->get('filter', 'all');

        $enquiries = BusinessEnquiry::query()
            ->with(['listing', 'owner', 'sender', 'replier'])
            ->when($filter === 'unread', fn ($q) => $q->where('is_read', false))
            ->when($filter === 'read', fn ($q) => $q->where('is_read', true))
            ->when($filter === 'replied', fn ($q) => $q->whereNotNull('admin_reply'))
            ->when($filter === 'pending', fn ($q) => $q->whereNull('admin_reply'))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $unreadCount = BusinessEnquiry::where('is_read', false)->count();

        return view('superadmin.inbox.index', compact('enquiries', 'unreadCount', 'filter'));
    }

    public function show(BusinessEnquiry $enquiry)
    {
        if (! $enquiry->is_read) {
            $enquiry->update(['is_read' => true]);
        }

        $enquiry->load(['listing', 'owner', 'sender', 'replier']);

        return view('superadmin.inbox.show', compact('enquiry'));
    }

    public function reply(Request $request, BusinessEnquiry $enquiry)
    {
        $data = $request->validate([
            'admin_reply' => 'required|string|min:3|max:5000',
        ]);

        $this->enquiryService->reply($enquiry, $request->user(), $data['admin_reply']);

        return back()->with('success', 'Reply sent to customer successfully.');
    }

    public function destroy(BusinessEnquiry $enquiry)
    {
        $enquiry->delete();

        return redirect()->route('superadmin.messages.index')->with('success', 'Message deleted.');
    }
}
