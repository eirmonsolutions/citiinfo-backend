<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\SoftDeletes;

class BusinessListing extends Model
{

    use SoftDeletes;


    protected $table = 'business_listings';

    protected $fillable = [
        'user_id',
        'business_name',
        'category_id',
        'category',
        'slug',
        'country',
        'state',
        'city',
        'address',
        'latitude',
        'longitude',
        'description',
        'logo',
        'listing_type',
        'is_featured',
        'status',
        'is_allowed',
        'submitted_at',
        'approved_at',
        'expires_at',
        'views_count',
        'show_on_homepage',
        'clicks_count',
    ];

    protected $casts = [
        'is_featured'  => 'boolean',
        'is_allowed' => 'boolean',
        'submitted_at' => 'datetime',
        'approved_at'  => 'datetime',
        'expires_at'   => 'datetime',
        'latitude'     => 'decimal:7',
        'longitude'    => 'decimal:7',
        'views_count'  => 'integer',
        'clicks_count' => 'integer',
    ];

    // Auto slug generate (if slug empty)
    protected static function booted()
    {
        static::creating(function ($listing) {
            if (empty($listing->slug) && !empty($listing->business_name)) {
                $base = Str::slug($listing->business_name);
                $slug = $base;
                $i = 1;

                while (self::where('slug', $slug)->exists()) {
                    $slug = $base . '-' . $i;
                    $i++;
                }

                $listing->slug = $slug;
            }

            if (empty($listing->submitted_at)) {
                $listing->submitted_at = now();
            }
        });
    }


    public function reviews()
    {
        return $this->hasMany(\App\Models\BusinessReview::class, 'business_id');
    }

    public function faqs()
    {
        return $this->hasMany(\App\Models\FAQ::class, 'listing_id');
    }


    // Optional relationship if you have Category model/table
    public function categoryRel()
    {
        return $this->belongsTo(\App\Models\Category::class, 'category_id');
    }

    public function contacts()
    {
        return $this->hasMany(\App\Models\BusinessContact::class, 'business_id');
    }

    public function socialLinks()
    {
        return $this->hasMany(\App\Models\BusinessSocialLink::class, 'business_id');
    }

    public function hours()
    {
        return $this->hasMany(\App\Models\BusinessHour::class, 'business_id');
    }

    public function services()
    {
        return $this->hasMany(\App\Models\BusinessService::class, 'business_id')
            ->orderBy('sort_order');
    }

    public function countryRel()
    {
        return $this->belongsTo(\App\Models\Country::class, 'country'); // country_id stored in country
    }

    public function stateRel()
    {
        return $this->belongsTo(\App\Models\State::class, 'state'); // state_id stored in state
    }

    public function cityRel()
    {
        return $this->belongsTo(\App\Models\City::class, 'city'); // city_id stored in city
    }


    public function features()
    {
        return $this->hasMany(\App\Models\BusinessFeature::class, 'business_id');
    }

    public function gallery()
    {
        return $this->hasMany(\App\Models\BusinessGallery::class, 'business_id')
            ->orderBy('sort_order');
    }

    public function videos()
    {
        return $this->hasMany(\App\Models\BusinessVideoLink::class, 'business_id');
    }

    public function announcements()
    {
        return $this->hasMany(\App\Models\Announcement::class, 'listing_id');
    }


    public function events()
    {
        return $this->hasMany(\App\Models\Event::class, 'listing_id');
    }

    public function coupons()
    {
        return $this->hasMany(\App\Models\Coupon::class, 'listing_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public function enquiries()
    {
        return $this->hasMany(\App\Models\BusinessEnquiry::class, 'business_listing_id');
    }
}
