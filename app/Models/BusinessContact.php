<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessContact extends Model
{
    protected $table = 'business_contacts';

    protected $fillable = [
        'business_id',
        'contact_name',
        'phone',
        'email',
        'alternate_phone',
        'website',
        'is_primary'
    ];

    public function business()
    {
        return $this->belongsTo(BusinessListing::class, 'business_id');
    }
}
