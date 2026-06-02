<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessVideoLink extends Model
{
    protected $table = 'business_video_link';

    protected $fillable = ['business_id', 'video_link_url', 'embed_code', 'provider'];

    public function business()
    {
        return $this->belongsTo(BusinessListing::class, 'business_id');
    }
}
