<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class BusinessFeature extends Model
{
    protected $table = 'business_features';

    // ✅ icon class removed, image added
    protected $fillable = [
        'business_id',
        'feature_id',
        'feature_name',
        'feature_image',
    ];

    protected $appends = [
        'feature_icon',
        'feature_image_url',
    ];

    /**
     * Make sure API/frontends always get an usable icon path.
     * If business_features.feature_image is empty, fallback to features.icon_image.
     */
    public function getFeatureImageAttribute($value): ?string
    {
        if (!empty($value)) {
            return $value;
        }

        return $this->feature?->icon_image;
    }

    public function business()
    {
        return $this->belongsTo(BusinessListing::class, 'business_id');
    }

    public function feature()
    {
        return $this->belongsTo(\App\Models\Feature::class, 'feature_id');
    }

    public function getFeatureIconAttribute(): ?string
    {
        if (!empty($this->feature_image)) {
            return $this->feature_image;
        }

        return $this->feature?->icon_image;
    }

    public function getFeatureImageUrlAttribute(): ?string
    {
        $icon = $this->feature_icon;
        if (empty($icon)) {
            return null;
        }

        return url(Storage::url($icon));
    }
}