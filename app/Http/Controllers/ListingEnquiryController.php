<?php

namespace App\Http\Controllers;

use App\Models\BusinessListing;
use App\Services\BusinessEnquiryService;
use Illuminate\Http\Request;

class ListingEnquiryController extends Controller
{
    public function store(Request $request, string $slug, BusinessEnquiryService $enquiryService)
    {
        $listing = BusinessListing::where('slug', $slug)->firstOrFail();

        $data = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'phone'   => 'required|string|max:50',
            'message' => 'nullable|string',
        ]);

        $enquiryService->submit($data, $listing);

        return back()->with('success', 'Your enquiry has been sent successfully.');
    }
}
