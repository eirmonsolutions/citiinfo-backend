<?php

namespace App\Services;

use App\Mail\BusinessEnquiryMail;
use App\Models\BusinessEnquiry;
use App\Models\BusinessListing;
use Illuminate\Support\Facades\Mail;

class BusinessEnquiryService
{
    public function submit(array $data, BusinessListing $listing): BusinessEnquiry
    {
        $enquiry = BusinessEnquiry::create([
            'business_listing_id' => $listing->id,
            'user_id'             => $listing->user_id,
            'name'                => $data['name'],
            'email'               => $data['email'],
            'phone'               => $data['phone'],
            'message'             => $data['message'] ?? null,
        ]);

        $listing->loadMissing('contacts');

        $businessEmail = $listing->contacts->first()->email ?? null;

        $emails = array_filter([
            $businessEmail,
            'vishaleirmon15896@gmail.com',
        ]);

        if (!empty($emails)) {
            Mail::to($emails)->send(new BusinessEnquiryMail($data, $listing));
        }

        return $enquiry;
    }
}
