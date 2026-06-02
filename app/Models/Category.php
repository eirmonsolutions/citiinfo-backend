<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
        'title',
        'description',
        'keyword',
        'icon',
        'image',
        'categoryimage',
        'is_active',
        'is_home',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_home'   => 'boolean',
    ];

    public function businessListings()
    {
        return $this->hasMany(\App\Models\BusinessListing::class, 'category_id');
    }
}
