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
        $data = $request->validate([
            'listing_id' => 'required|exists:business_listings,id',
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|max:255',
            'phone'      => 'required|string|max:50',
            'message'    => 'nullable|string',
        ]);

        $listing = BusinessListing::with('contacts')->findOrFail($data['listing_id']);

        $enquiryService->submit($data, $listing);

        return response()->json([
            'success' => true,
            'message' => 'Your enquiry has been sent successfully.',
        ]);
    }
}
