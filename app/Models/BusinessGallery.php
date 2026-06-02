<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessGallery extends Model
{
    protected $table = 'business_gallery';
    public $timestamps = false; // because your insert has uploaded_at only, but we can keep manual

    protected $fillable = [
        'business_id',
        'image_path',
        'caption',
        'alt_text',
        'is_cover',
        'sort_order',
        'uploaded_at'
    ];

    protected $casts = [
        'uploaded_at' => 'datetime',
        'is_cover' => 'boolean',
    ];

    public function business()
    {
        return $this->belongsTo(BusinessListing::class, 'business_id');
    }
}
