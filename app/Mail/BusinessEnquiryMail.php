<?php

namespace App\Mail;

use App\Models\BusinessListing;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BusinessEnquiryMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $listing;

    public function __construct($data, BusinessListing $listing)
    {
        $this->data = $data;
        $this->listing = $listing;
    }

    public function build()
    {
        return $this->subject('New Business Enquiry - ' . $this->listing->business_name)
            ->view('emails.business-enquiry');
    }
}