<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessSocialLink extends Model
{
    protected $table = 'business_social_links';

    protected $fillable = ['business_id', 'platform', 'url'];

    public function business()
    {
        return $this->belongsTo(BusinessListing::class, 'business_id');
    }
}
