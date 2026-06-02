<?php

namespace App\Mail;

use App\Models\User;
use App\Models\BusinessListing;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ListingAdminCredentialsMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public ?string $plainPassword;
    public BusinessListing $listing;
    public bool $showPassword;

    public function __construct(User $user, ?string $plainPassword, BusinessListing $listing, bool $showPassword = true)
    {
        $this->user = $user;
        $this->plainPassword = $plainPassword;
        $this->listing = $listing;
        $this->showPassword = $showPassword;
    }

    public function build()
    {
        return $this->subject('Your Listing Has Been Approved')
            ->view('emails.listing_admin_credentials')
            ->with([
                'user' => $this->user,
                'plainPassword' => $this->plainPassword,
                'listing' => $this->listing,
                'name' => $this->user->name,
                'showPassword' => $this->showPassword,
            ]);
    }
}