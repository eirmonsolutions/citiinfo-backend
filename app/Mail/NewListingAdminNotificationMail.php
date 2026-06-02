<?php

namespace App\Mail;

use App\Models\BusinessListing;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use Illuminate\Contracts\Queue\ShouldQueue;

class NewListingAdminNotificationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public BusinessListing $listing;

    public function __construct(BusinessListing $listing)
    {
        $this->listing = $listing;
    }

    public function build()
    {
        return $this->subject('New Listing Submitted - Review Required')
            ->view('emails.new_listing_admin_notification')
            ->with([
                'listing' => $this->listing,
            ]);
    }
}