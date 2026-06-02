<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\BusinessEnquiryMail;
use App\Models\BusinessListing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class BusinessEnquiryApiController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'listing_id' => 'required|exists:business_listings,id',
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|max:255',
            'phone'     => 'required|string|max:50',
            'message'   => 'nullable|string',
        ]);

        $listing = BusinessListing::with('contacts')->findOrFail($data['listing_id']);

        $businessEmail = $listing->contacts->first()->email ?? null;

        $emails = array_filter([
            $businessEmail,
            'vishaleirmon15896@gmail.com',
        ]);

        if (empty($emails)) {
            return response()->json([
                'success' => false,
                'message' => 'Business email not found.',
            ], 422);
        }

        Mail::to($emails)->send(new BusinessEnquiryMail($data, $listing));

        return response()->json([
            'success' => true,
            'message' => 'Your enquiry has been sent successfully.',
        ]);
    }
}