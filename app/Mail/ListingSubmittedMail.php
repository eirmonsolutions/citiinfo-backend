<?php

namespace App\Mail;

use App\Models\BusinessListing;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ListingSubmittedMail extends Mailable
{
    use Queueable, SerializesModels;

    public BusinessListing $listing;

    public function __construct(BusinessListing $listing)
    {
        $this->listing = $listing;
    }

    public function build()
    {
        return $this->subject('Your Listing Has Been Submitted')
            ->view('emails.listing_submitted')
            ->with([
                'listing' => $this->listing,
            ]);
    }
}