<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BusinessListing;
use App\Services\BusinessEnquiryService;
use Illuminate\Http\Request;

class BusinessEnquiryApiController extends Controller
{
    public function store(Request $request, BusinessEnquiryService $enquiryService)
    {
        $user = $request->user();

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Please login to send an enquiry.',
            ], 401);
        }

        $data = $request->validate([
            'listing_id' => 'required|exists:business_listings,id',
            'phone'      => 'required|string|max:50',
            'message'    => 'required|string|min:10|max:5000',
        ]);

        $listing = BusinessListing::with('contacts')->findOrFail($data['listing_id']);

        $enquiryService->submit([
            'name'    => $user->name,
            'email'   => $user->email,
            'phone'   => $data['phone'],
            'message' => $data['message'],
        ], $listing, $user);

        $role = $user->role ?? 'user';

        $messagesUrl = match ($role) {
            'superadmin' => url('/superadmin/messages'),
            'admin' => url('/admin/inbox?tab=sent'),
            default => url('/user/messages'),
        };

        return response()->json([
            'success' => true,
            'message' => 'Your enquiry has been sent successfully. Check Messages in your dashboard for replies.',
            'messages_url' => $messagesUrl,
        ]);
    }
}
