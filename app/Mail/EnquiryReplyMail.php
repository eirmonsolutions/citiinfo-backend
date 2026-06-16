<?php

namespace App\Mail;

use App\Models\BusinessEnquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EnquiryReplyMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public BusinessEnquiry $enquiry) {}

    public function build()
    {
        $listingName = $this->enquiry->listing->business_name ?? 'Business Listing';

        return $this->subject('Reply to your message - ' . $listingName)
            ->view('emails.enquiry-reply');
    }
}
