<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FAQ extends Model
{
    protected $table = 'faqs';
    protected $fillable = ['listing_id', 'listing_name'];

    public function listing()
    {
        return $this->belongsTo(\App\Models\BusinessListing::class, 'listing_id');
    }

    public function items()
    {
        return $this->hasMany(FAQItem::class, 'faq_id')->orderBy('sort_order');
    }
}
