<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessService extends Model
{
    protected $table = 'business_services';

    protected $fillable = [
        'business_id',
        'name',
        'description',
        'price',
        'currency',
        'duration_minutes',
        'is_popular',
        'sort_order'
    ];

    public function business()
    {
        return $this->belongsTo(BusinessListing::class, 'business_id');
    }
}
