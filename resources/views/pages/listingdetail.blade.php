@extends('layouts.app')

@section('title')
{{ $listing->business_name ?? 'Listing' }}
@if($listing->cityRel?->name) - {{ $listing->cityRel->name }} @endif
@if($listing->stateRel?->name), {{ $listing->stateRel->name }} @endif
@if($listing->countryRel?->name), {{ $listing->countryRel->name }} @endif
| Citiinfo
@endsection

@section('content')

<style>
    .listing-slider-wrapper {
        height: auto;
    }

    @media (min-width: 320px) and (max-width: 767px) {

        .listing-slider-wrapper {
            height: auto;
        }

        .listing-slider-single {
            height: 300px !important;
        }

        .services-list ul li {
            flex-basis: 25px;
        }

        .services-list ul {
            flex-direction: column;
        }

        .services-list {
            margin-bottom: 20px;
        }

    }
</style>

<link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.css" />


<div class="breadcrumb-area">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                @if(isset($listing->categoryRel))
                <li class="breadcrumb-item">
                    <a href="{{ route('list.category', ['category' => Str::slug($listing->categoryRel->name)]) }}">
                        {{ $listing->categoryRel->name }}
                    </a>
                </li>
                @endif
                <li class="breadcrumb-item active" aria-current="page">{{ $listing->business_name }}</li>
            </ol>
        </nav>
    </div>
</div>

<section class="profile-details">
    <div class="container">
        <div class="profile-wrapper">
            <div class="profile-detail-area">
                <div class="profile-img">
                    <img
                        src="{{ $listing->logo ? asset('storage/'.$listing->logo) : asset('assets/images/favicon.jpg') }}"
                        alt="{{ $listing->business_name ?? 'Profile Image' }}">
                </div>
                <div class="profile-content">
                    <div class="profile-name">
                        <h1>{{ $listing->business_name }}</h1>
                    </div>
                    <div class="profile-reviews-area">
                        <ul>
                            <li>
                                <i data-lucide="star"></i>
                                <span class="profile-review-number">{{ number_format($avgRating ?? 0, 1) }}</span>

                                @if(($totalReviews ?? 0) > 0)
                                <span class="profile-review-count">({{ $totalReviews }})</span>
                                @else
                                <span class="profile-review-count">(No ratings)</span>
                                @endif
                            </li>

                            <li>
                                <i data-lucide="map-pin"></i>
                                <span class="profile-review-count">
                                    {{ $listing->cityRel->name ?? '' }}
                                    @if($listing->stateRel?->name), {{ $listing->stateRel->name }} @endif
                                    @if($listing->countryRel?->name), {{ $listing->countryRel->name }} @endif
                                </span>
                            </li>
                            
                        </ul>
                    </div>
                </div>
            </div>
            <div class="profile-share-area">
                <button type="button" class="btn-2" type="button" class="btn btn-secondary"
                    data-bs-toggle="tooltip" data-bs-placement="top"
                    data-bs-custom-class="custom-tooltip"
                    data-bs-title="Share">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-share2-icon lucide-share-2">
                        <circle cx="18" cy="5" r="3" />
                        <circle cx="6" cy="12" r="3" />
                        <circle cx="18" cy="19" r="3" />
                        <line x1="8.59" x2="15.42" y1="13.51" y2="17.49" />
                        <line x1="15.41" x2="8.59" y1="6.51" y2="10.49" />
                    </svg>
                </button>

                <button type="button" class="btn-2" type="button" class="btn btn-secondary"
                    data-bs-toggle="tooltip" data-bs-placement="top"
                    data-bs-custom-class="custom-tooltip"
                    data-bs-title="Saved">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-heart-icon lucide-heart">
                        <path d="M2 9.5a5.5 5.5 0 0 1 9.591-3.676.56.56 0 0 0 .818 0A5.49 5.49 0 0 1 22 9.5c0 2.29-1.5 4-3 5.5l-5.492 5.313a2 2 0 0 1-3 .019L5 15c-1.5-1.5-3-3.2-3-5.5" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</section>



<section class="listing-details-area">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-12 col-xl-8">

                @if($listing->coupons->count())
                @foreach($listing->coupons as $coupon)
                <div class="couponBar d-xl-none d-lg-block">

                    <div class="couponBar__content">

                        <div class="couponBar__text">
                            <strong>PROMOTION.</strong>
                            {{ \Illuminate\Support\Str::limit($coupon->details, 90) }}
                            Use Coupon
                        </div>

                        <div class="couponBar__right">
                            <div class="couponCodeText">
                                {{ $coupon->code }}
                            </div>

                            <button type="button" class="copyCouponBtn">
                                COPY
                            </button>


                        </div>
                        <div class="couponTimer"
                            data-end="{{ \Carbon\Carbon::parse($coupon->end_date)->endOfDay()->timestamp }}">
                            --
                        </div>

                    </div>

                    <button type="button" class="closeCouponBar">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x-icon lucide-x">
                            <path d="M18 6 6 18" />
                            <path d="m6 6 12 12" />
                        </svg>
                    </button>
                </div>
                @endforeach
                @endif


                <div class="listing-slider-wrapper mySwiper mb-5">
                    <div class="swiper-wrapper">
                        @foreach($listing->gallery as $img)
                        <div class="swiper-slide listing-slider-single">
                            <img
                                loading="lazy"
                                src="{{ asset('storage/'.$img->image_path) }}"
                                alt="{{ $img->alt_text ?? $listing->business_name }}">
                        </div>
                        @endforeach

                    </div>
                    <!-- scrollbar -->
                    <div class="swiper-scrollbar"></div>
                </div>

                @if($listing->announcements->count())
                @foreach($listing->announcements as $ad)
                <div class="ann-card ann-preview">
                    <div class="ann-card-head">Latest Announcements</div>
                    <div class="ann-card-body">
                        <div class="ann-preview-icon" id="pvIconWrap">
                            <i data-lucide="{{ $ad->icon }}"></i>
                        </div>
                        <div class="ann-preview-texts">
                            <div id="pvTitle" class="ann-preview-title">{{ $ad->title }}</div>
                            <div id="pvDesc" class="ann-preview-desc">{{ $ad->description }}</div>
                        </div>
                        <a href="{{ $ad->button_link }}" id="pvBtn" class="ann-chip">{{ $ad->button_text ?? 'Announcement' }}</a>
                    </div>
                </div>
                @endforeach
                @endif



                <div class="listing-details-about">
                    <h2 class="heading-title">About </h2>
                    <p>
                        {{ $listing->description }}
                    </p>
                </div>



                @if($listing->events->count())
                @foreach($listing->events as $event)

                <div class="ann-card ann-preview event-type-box">
                    <div class="ann-card-head">Latest Event</div>

                    <div class="ann-card-body">

                        {{-- Image --}}
                        @if(!empty($event->featured_image))
                        <div class="ann-preview-icon">
                            <img src="{{ asset('storage/'.$event->featured_image) }}"
                                alt="Event image"
                                style="width:100%;height:100%;object-fit:cover;border-radius:12px;">
                        </div>
                        @endif

                        {{-- Texts --}}
                        <div class="ann-preview-texts" style="flex:1;">
                            <div class="ann-preview-title">{{ $event->title }}</div>
                            <div class="ann-preview-desc">{{ $event->description }}</div>

                            {{-- Listing chip --}}
                            @if(!empty($event->listing_name))
                            <div style="margin-top:10px;">
                                <span style="display:inline-block;padding:6px 10px;border-radius:999px;font-size:12px;border:1px solid #e5e7eb;">
                                    {{ $event->listing_name }}
                                </span>
                            </div>
                            @endif

                            {{-- Location --}}
                            @if(!empty($event->location))
                            <div style="margin-top:8px; font-size:13px; opacity:.9; display:flex; flex-wrap:wrap; gap:10px;">
                                <div>
                                    📍 <span>{{ $event->location }}</span>
                                </div>
                            </div>
                            @endif

                            {{-- Date + Time --}}
                            <div style="margin-top:8px; font-size:13px; opacity:.9; display:flex; flex-wrap:wrap; gap:10px;">
                                <div style="margin-top:6px;font-size:13px;opacity:.85;">
                                    🕒
                                    <span>
                                        {{ \Carbon\Carbon::parse($event->start_date)->format('d M Y') }}
                                        @if(!empty($event->start_time))
                                        {{ \Carbon\Carbon::parse($event->start_time)->format('h:i A') }}
                                        @endif

                                        @if(!empty($event->end_date))
                                        - {{ \Carbon\Carbon::parse($event->end_date)->format('d M Y') }}
                                        @endif
                                        @if(!empty($event->end_time))
                                        {{ \Carbon\Carbon::parse($event->end_time)->format('h:i A') }}
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- CTA (Ticket URL link) --}}
                        @php $btnLink = !empty($event->ticket_url) ? $event->ticket_url : 'javascript:void(0)'; @endphp
                        <a href="{{ $btnLink }}"
                            class="ann-chip"
                            style="text-decoration:none; cursor:pointer;"
                            target="_blank" rel="noopener">
                            View Event
                        </a>

                    </div>

                    <div class="ann-card-body">
                        {{-- Map Preview --}}
                        @if(!empty($event->location))
                        @php
                        $mapQuery = urlencode($event->location);
                        $mapUrl = "https://www.google.com/maps?q={$mapQuery}&output=embed";
                        @endphp


                        <iframe style="border-radius: 10px;"
                            src="{{ $mapUrl }}"
                            width="100%"
                            height="220"

                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                        @endif
                    </div>

                </div>

                @endforeach
                @endif


                @php
                $filteredFeatures = $listing->features->filter(function ($feat) {
                return !empty(trim($feat->feature_name ?? '')) || !empty($feat->feature_image);
                });
                @endphp

                @if($filteredFeatures->count() > 0)
                <div class="listing-feature-show">
                    <h2 class="heading-title">Features</h2>

                    <div class="features-box-grid mt-4">
                        @foreach($filteredFeatures as $feat)
                        <div class="icon-box icon-box-one">
                            <div class="icon">
                                @if(!empty($feat->feature_image))
                                <img src="{{ asset('storage/'.$feat->feature_image) }}"
                                    alt="{{ $feat->feature_name }}"
                                    class="feature-icon-img"
                                    style="height:40px;width:40px;object-fit:contain;">
                                @endif
                            </div>

                            <div class="info">
                                <h6>{{ $feat->feature_name }}</h6>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif


                @php
                $filteredServices = $listing->services->filter(function ($service) {
                return !empty(trim($service->name ?? ''));
                });
                @endphp

                @if($filteredServices->count() > 0)
                <div class="listing-services-show">
                    <h2 class="heading-title">Our Services</h2>

                    <div class="row mt-4">
                        <div class="col-lg-12">
                            <div class="services-list">
                                <ul>
                                    @foreach($filteredServices as $service)
                                    <li>
                                        <div class="services-name">
                                            {{ $service->name }}
                                        </div>

                                        <div class="services-price">
                                            @if(!is_null($service->price) && $service->price !== '' && $service->price > 0)
                                            @php
                                            $symbol = $service->currency ?? '$';
                                            @endphp
                                            {{ $symbol }}{{ number_format($service->price, 2) }}
                                            @endif
                                        </div>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                @endif


                @if($listing->faqs->count())
                <div class="faq ">
                    <h2 class="heading-title">Frequently Asked Questions</h2>

                    <div class="faq-list mt-4">

                        @php
                        // multiple FAQ records ho sakte hain, items combine kar lete hain
                        $allItems = $listing->faqs->flatMap->items;
                        @endphp

                        @foreach($allItems as $item)
                        <div class="faq-item">
                            <div class="faq-question">
                                <span>{{ $item->question }}</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-down-icon lucide-chevron-down">
                                    <path d="m6 9 6 6 6-6" />
                                </svg>
                            </div>

                            <div class="faq-answer">
                                <p>{!! nl2br(e($item->answer)) !!}</p>
                            </div>
                        </div>
                        @endforeach

                    </div>
                </div>
                @endif



                <div class="listing-review-area">
                    <div class="heading-flex">
                        <h2 class="heading-title mb-0">Reviews</h2>
                        <button type="button" class="review-btn" data-bs-toggle="modal" data-bs-target="#reviewForm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M13 21h8" />
                                <path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z" />
                            </svg>
                            Add review
                        </button>
                    </div>

                    @php
                    $total = $totalReviews ?? 0;
                    $avg = $avgRating ?? 0;
                    $counts = $starCounts ?? [5=>0,4=>0,3=>0,2=>0,1=>0];

                    $pct = function($n) use ($total) {
                    if ($total <= 0) return 0;
                        return round(($n / $total) * 100, 1);
                        };
                        @endphp

                        <div class="row g-4 pb-3 mb-3">
                        <div class="col-sm-5 col-md-3 col-lg-4">
                            <div class="review-box-flex">
                                <h1>{{ number_format($avg, 1) }}</h1>

                                <div class="review-box-star-icon">
                                    @for($i=1; $i<=5; $i++)
                                        <i data-lucide="star"></i>
                                        @endfor
                                </div>

                                <div class="total-review">{{ $total }} reviews</div>
                            </div>
                        </div>

                        <div class="col-sm-7 col-md-9 col-lg-8">
                            <div class="review-progress-bar-wrapper">

                                @for($s=5; $s>=1; $s--)
                                <div class="review-progress-bar-box">
                                    <div class="review-progress-bar-1">
                                        {{ $s }} <i data-lucide="star"></i>
                                    </div>

                                    <div class="progress w-100" role="progressbar" aria-valuenow="{{ $pct($counts[$s]) }}" aria-valuemin="0" aria-valuemax="100" style="height: 6px">
                                        <div class="progress-bar bg-warning rounded-pill" style="width: {{ $pct($counts[$s]) }}%"></div>
                                    </div>

                                    <div class="fs-sm text-nowrap text-end" style="width: 40px;">
                                        {{ $counts[$s] }}
                                    </div>
                                </div>
                                @endfor

                            </div>
                        </div>
                </div>

                <div class="review-details">
                    @forelse($reviews as $r)
                    <div class="review-details-box">
                        <div class="review-info">
                            <h6>{{ $r->name }}</h6>
                            <span class="review-date">{{ $r->created_at->format('F d, Y') }}</span>
                        </div>

                        <div class="review-star">
                            @for($i=1; $i<=5; $i++)
                                <i data-lucide="star"></i>
                                @endfor
                        </div>

                        <p>{{ $r->review }}</p>
                    </div>
                    @empty
                    <p class="text-muted">No reviews yet. Be the first one to review.</p>
                    @endforelse
                </div>

                @if($reviews->hasPages())
                <div class="pt-4">
                    <nav class="pt-4 mt-1 mt-sm-0" aria-label="Reviews pagination">
                        <ul class="pagination">

                            {{-- Previous --}}
                            @if($reviews->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link" aria-hidden="true">&laquo;</span>
                            </li>
                            @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $reviews->previousPageUrl() }}" aria-label="Previous">
                                    &laquo;
                                </a>
                            </li>
                            @endif

                            {{-- Page Numbers + Dots --}}
                            @foreach ($reviews->links()->elements[0] ?? [] as $page => $url)
                            @if ($page == $reviews->currentPage())
                            <li class="page-item active" aria-current="page">
                                <span class="page-link">
                                    {{ $page }}
                                    <span class="visually-hidden">(current)</span>
                                </span>
                            </li>
                            @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                            @endif
                            @endforeach

                            {{-- If dots exist --}}
                            @if(isset($reviews->links()->elements[1]) && is_string($reviews->links()->elements[1]))
                            <li class="page-item">
                                <span class="page-link pe-none">...</span>
                            </li>
                            @endif

                            {{-- Next --}}
                            @if($reviews->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $reviews->nextPageUrl() }}" aria-label="Next">
                                    &raquo;
                                </a>
                            </li>
                            @else
                            <li class="page-item disabled">
                                <span class="page-link" aria-hidden="true">&raquo;</span>
                            </li>
                            @endif

                        </ul>
                    </nav>
                </div>
                @endif

            </div>



        </div>

        <div class="col-lg-12 col-xl-4">
            <div class="top-sticky">



                @if($listing->coupons->count())
                @foreach($listing->coupons as $coupon)
                <div class="couponBar d-none d-xl-block ">

                    <div class="couponBar__content">

                        <div class="couponBar__text">
                            <strong>PROMOTION.</strong>
                            {{ \Illuminate\Support\Str::limit($coupon->details, 90) }}
                            Use Coupon
                        </div>

                        <div class="couponBar__right">
                            <div class="couponCodeText">
                                {{ $coupon->code }}
                            </div>

                            <button type="button" class="copyCouponBtn">
                                COPY
                            </button>


                        </div>
                        <div class="couponTimer"
                            data-end="{{ \Carbon\Carbon::parse($coupon->end_date)->endOfDay()->timestamp }}">
                            --
                        </div>

                    </div>

                    <button type="button" class="closeCouponBar">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x-icon lucide-x">
                            <path d="M18 6 6 18" />
                            <path d="m6 6 12 12" />
                        </svg>
                    </button>
                </div>
                @endforeach
                @endif





                <div class="listing-details-sidebar">
                    <h3 class="heading-title">Contact Information</h3>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="contact-info">
                                <ul>
                                    <li>
                                        <span class="contact-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin-icon lucide-map-pin">
                                                <path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0" />
                                                <circle cx="12" cy="10" r="3" />
                                            </svg>
                                        </span>

                                        {{ $listing->address }}
                                    </li>

                                    <li>
                                        <span class="contact-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-phone-call-icon lucide-phone-call">
                                                <path d="M13 2a9 9 0 0 1 9 9" />
                                                <path d="M13 6a5 5 0 0 1 5 5" />
                                                <path d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.8 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384" />
                                            </svg>
                                        </span>

                                        {{ $listing->contacts->first()->phone }}
                                    </li>

                                    <li>
                                        <span class="contact-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-mail-icon lucide-mail">
                                                <path d="m22 7-8.991 5.727a2 2 0 0 1-2.009 0L2 7" />
                                                <rect x="2" y="4" width="20" height="16" rx="2" />
                                            </svg>
                                        </span>

                                        {{ $listing->contacts->first()->email }}
                                    </li>

                                    <li>
                                        <span class="contact-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-globe-icon lucide-globe">
                                                <circle cx="12" cy="12" r="10" />
                                                <path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20" />
                                                <path d="M2 12h20" />
                                            </svg>
                                        </span>

                                        <a href="{{ $listing->contacts->first()->website }}" target="_blank">
                                            {{ $listing->contacts->first()->website }}
                                        </a>
                                    </li>


                                </ul>
                            </div>

                            @php
                            // ✅ platform => url map (trim + remove empty)
                            $social = collect($listing->socialLinks)
                            ->pluck('url', 'platform')
                            ->map(fn ($url) => trim((string) $url))
                            ->filter(); // removes null/empty/""
                            @endphp

                            <div class="social-links">

                                @if($social->has('facebook'))
                                <a href="{{ $social['facebook'] }}" class="social-link facebook" target="_blank" rel="noopener">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-facebook-icon lucide-facebook">
                                        <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
                                    </svg>
                                </a>
                                @endif

                                @if($social->has('instagram'))
                                <a href="{{ $social['instagram'] }}" class="social-link instagram" target="_blank" rel="noopener">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-instagram-icon lucide-instagram">
                                        <rect width="20" height="20" x="2" y="2" rx="5" ry="5"></rect>
                                        <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                                        <line x1="17.5" x2="17.51" y1="6.5" y2="6.5"></line>
                                    </svg>
                                </a>
                                @endif

                                @if($social->has('youtube'))
                                <a href="{{ $social['youtube'] }}" class="social-link youtube" target="_blank" rel="noopener">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-youtube-icon lucide-youtube">
                                        <path d="M2.5 17a24.12 24.12 0 0 1 0-10 2 2 0 0 1 1.4-1.4 49.56 49.56 0 0 1 16.2 0A2 2 0 0 1 21.5 7a24.12 24.12 0 0 1 0 10 2 2 0 0 1-1.4 1.4 49.55 49.55 0 0 1-16.2 0A2 2 0 0 1 2.5 17"></path>
                                        <path d="m10 15 5-3-5-3z"></path>
                                    </svg>
                                </a>
                                @endif

                                @if($social->has('twitter'))
                                <a href="{{ $social['twitter'] }}" class="social-link twitter" target="_blank" rel="noopener">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-twitter-icon lucide-twitter">
                                        <path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"></path>
                                    </svg>
                                </a>
                                @endif

                                @if($social->has('linkedin'))
                                <a href="{{ $social['linkedin'] }}" class="social-link linkedin" target="_blank" rel="noopener">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-linkedin-icon lucide-linkedin">
                                        <path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path>
                                        <rect width="4" height="12" x="2" y="9"></rect>
                                        <circle cx="4" cy="4" r="2"></circle>
                                    </svg>
                                </a>
                                @endif

                                @if($social->has('snapchat'))
                                <a href="{{ $social['snapchat'] }}" class="social-link snapchat" target="_blank" rel="noopener">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-snapchat-icon lucide-snapchat">
                                        <path d="M12 2c-3.4 0-6 2.6-6 6v2.3c0 .6-.3 1.1-.8 1.4-.6.4-1.3.7-2 .9-.7.2-1.2.7-1.2 1.3 0 .7.7 1.2 1.7 1.6 1.3.5 2.2 1.2 2.8 2.1.4.6 1 .9 1.7.9h1.1c.4 0 .7.2 1 .5.5.5 1.1.8 1.7.8s1.2-.3 1.7-.8c.3-.3.6-.5 1-.5h1.1c.7 0 1.3-.3 1.7-.9.6-.9 1.5-1.6 2.8-2.1 1-.4 1.7-.9 1.7-1.6 0-.6-.5-1.1-1.2-1.3-.7-.2-1.4-.5-2-.9-.5-.3-.8-.8-.8-1.4V8c0-3.4-2.6-6-6-6z"></path>
                                    </svg>
                                </a>
                                @endif

                            </div>

                        </div>
                    </div>
                </div>

                <div class="map-sidebar">
                    {{-- Map Preview --}}
                    @if(!empty($listing->address))
                    @php
                    $mapQuery = urlencode($listing->address);
                    $mapUrl = "https://www.google.com/maps?q={$mapQuery}&output=embed";
                    @endphp

                    <iframe
                        style="border-radius: 10px;"
                        src="{{ $mapUrl }}"
                        width="100%"
                        height="220"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                    @endif

                </div>



                <div class="listing-business-hour">
                    @php
                    use Carbon\Carbon;

                    $daysOrder = ['monday','tuesday','wednesday','thursday','friday','saturday','sunday'];

                    $hoursByDay = ($listing->hours ?? collect())
                    ->keyBy(fn($h) => strtolower($h->day_of_week));

                    $fmt = fn($t) => $t ? Carbon::parse($t)->format('g:i a') : null;

                    $todayKey = strtolower(now()->format('l')); // monday...
                    $todayRow = $hoursByDay->get($todayKey);

                    $isClosedToday = !$todayRow || (int)($todayRow->is_closed ?? 0) === 1;

                    $openStr = (!$isClosedToday) ? $fmt($todayRow->open_time) : null;
                    $closeStr = (!$isClosedToday) ? $fmt($todayRow->close_time) : null;

                    // Top label (Google-style)
                    $topLabel = $isClosedToday
                    ? 'Closed'
                    : ($openStr && $closeStr ? "Open · Closes {$closeStr}" : 'Open');

                    @endphp

                    <h3 class="heading-title">Business Hour</h3>

                    <div class="bh-dropdown" id="bhDropdown">
                        <button type="button" class="bh-trigger" id="bhTrigger" aria-expanded="false">
                            <div class="bh-left">
                                <span class="bh-title">Hours:</span>
                                <span class="bh-today">{{ ucfirst($todayKey) }}</span>
                                <span class="bh-status {{ $isClosedToday ? 'is-closed' : 'is-open' }}">
                                    {{ $topLabel }}
                                </span>
                            </div>

                            <span class="bh-caret" aria-hidden="true">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="m6 9 6 6 6-6" />
                                </svg>
                            </span>
                        </button>

                        <div class="bh-menu" id="bhMenu" role="menu">
                            @foreach($daysOrder as $day)
                            @php
                            $h = $hoursByDay->get($day);
                            $closed = !$h || (int)($h->is_closed ?? 0) === 1;

                            $dayOpen = $closed ? null : $fmt($h->open_time ?? null);
                            $dayClose = $closed ? null : $fmt($h->close_time ?? null);

                            $line = $closed ? 'Closed' : ($dayOpen && $dayClose ? "{$dayOpen} – {$dayClose}" : 'Open');
                            $isToday = $day === $todayKey;
                            @endphp

                            <div class="bh-row {{ $isToday ? 'is-today' : '' }}">
                                <span class="bh-day">{{ ucfirst($day) }}</span>

                                @if($closed)
                                <span class="bh-time bh-closed">Closed</span>
                                @else
                                <span class="bh-time">{{ $line }}</span>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>




                <div class="listing-contact-form">
                    <h3 class="heading-title">Quick Enquiry</h3>

                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="contact-form-wrapper">
                                <form action="" class="row form-grid">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label" for="user_name">Enter Your Name <span class="required">*</span></label>
                                            <input type="text" id="user_name" name="user_name" placeholder="John Doe">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label" for="user_email">Enter Your Email <span class="required">*</span></label>
                                            <input type="text" id="user_email" name="user_email" placeholder="abcd@gmail.com">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label" for="user_phone">Enter Your Phone Number <span class="required">*</span></label>
                                            <input type="text" id="user_phone" name="user_phone" placeholder="800-123-4567">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="user_message" class="form-label">
                                                Message
                                            </label>
                                            <textarea id="user_message" name="user_message" class="form-control textarea-field" placeholder="Describe your business, services, and specialties" rows="4"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="submit-btn">
                                            <button type="submit" class="btn-submit">Send Message</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</section>



<!-- Modal -->
<div class="modal fade" id="reviewForm" tabindex="-1" aria-labelledby="reviewFormModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fs-5" id="reviewFormModalLabel">Write a review</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body pb-3">
                <form action="{{ route('listing.reviews.store', $listing->slug) }}"
                    method="POST"
                    class="row form-grid"
                    id="reviewSubmitForm">
                    @csrf

                    {{-- If guest: allow name/email --}}
                    @guest
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label">Enter Your Name <span class="required">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" placeholder="John Doe">
                            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label">Enter Your Email <span class="required">*</span></label>
                            <input type="text" name="email" value="{{ old('email') }}" placeholder="abcd@gmail.com">
                            @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                    </div>
                    @endguest

                    {{-- If auth: show readonly --}}
                    @auth
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label">Your Name</label>
                            <input type="text" value="{{ auth()->user()->name }}" readonly>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label">Your Email</label>
                            <input type="text" value="{{ auth()->user()->email }}" readonly>
                        </div>
                    </div>
                    @endauth

                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label">Rating <span class="required">*</span></label>

                            <div class="custom-select" data-select id="ratingSelect">
                                <button type="button" class="select-trigger" data-trigger>
                                    <span class="select-placeholder" data-label>Select rating</span>
                                    <span class="select-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="m6 9 6 6 6-6" />
                                        </svg>
                                    </span>
                                </button>

                                <div class="select-panel" data-panel>
                                    <ul class="select-options" data-options>
                                        @for($i=1; $i<=5; $i++)
                                            <li class="select-option" data-value="{{ $i }}">
                                            @for($s=1; $s<=$i; $s++)
                                                <i data-lucide="star"></i>
                                                @endfor
                                                </li>
                                                @endfor
                                    </ul>
                                </div>

                                {{-- ✅ MUST submit --}}
                                <input type="hidden" name="rating" id="ratingHidden" value="{{ old('rating') }}">
                            </div>

                            @error('rating') <small class="text-danger d-block">{{ $message }}</small> @enderror
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label">Review <span class="required">*</span></label>
                            <textarea name="review"
                                class="form-control textarea-field"
                                placeholder="Your review must be at least 50 characters."
                                rows="4">{{ old('review') }}</textarea>
                            @error('review') <small class="text-danger d-block">{{ $message }}</small> @enderror
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Submit review</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const wrap = document.getElementById('bhDropdown');
        if (!wrap) return;

        const btn = document.getElementById('bhTrigger');
        const menu = document.getElementById('bhMenu');

        const open = () => {
            wrap.classList.add('open');
            btn.setAttribute('aria-expanded', 'true');
        };

        const close = () => {
            wrap.classList.remove('open');
            btn.setAttribute('aria-expanded', 'false');
        };

        btn.addEventListener('click', function(e) {
            e.preventDefault();
            wrap.classList.contains('open') ? close() : open();
        });

        document.addEventListener('click', function(e) {
            if (!wrap.contains(e.target)) close();
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') close();
        });
    });
</script>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const select = document.querySelector('#ratingSelect');
        if (!select) return;

        const trigger = select.querySelector('[data-trigger]');
        const panel = select.querySelector('[data-panel]');
        const label = select.querySelector('[data-label]');
        const hidden = document.getElementById('ratingHidden');

        function closePanel() {
            panel.style.display = 'none';
        }

        function openPanel() {
            panel.style.display = 'block';
        }

        closePanel();

        trigger.addEventListener('click', function(e) {
            e.preventDefault();
            const isOpen = panel.style.display === 'block';
            isOpen ? closePanel() : openPanel();
        });

        select.querySelectorAll('.select-option').forEach(opt => {
            opt.addEventListener('click', function() {
                const val = this.dataset.value;
                hidden.value = val;

                // show "Selected rating: X"
                label.textContent = `Rating: ${val}`;

                closePanel();
            });
        });

        document.addEventListener('click', function(e) {
            if (!select.contains(e.target)) closePanel();
        });
    });
</script>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('swal_success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Done!',
        text: @json(session('swal_success')),
        confirmButtonText: 'OK'
    });
</script>
@endif



<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.faq-question').forEach(q => {
            q.addEventListener('click', () => {
                const item = q.closest('.faq-item');
                const ans = item.querySelector('.faq-answer');

                // close others (optional)
                document.querySelectorAll('.faq-item').forEach(i => {
                    if (i !== item) {
                        i.classList.remove('active');
                        const a = i.querySelector('.faq-answer');
                        if (a) a.style.display = 'none';
                    }
                });

                // toggle current
                const isOpen = item.classList.contains('active');
                item.classList.toggle('active', !isOpen);
                ans.style.display = isOpen ? 'none' : 'block';
            });
        });

        // start hidden
        document.querySelectorAll('.faq-answer').forEach(a => a.style.display = 'none');
    });
</script>



<script>
    document.addEventListener('DOMContentLoaded', () => {

        // COPY BUTTON
        document.querySelectorAll('.copyCouponBtn').forEach(btn => {
            btn.addEventListener('click', () => {
                const code = btn.closest('.couponBar')
                    .querySelector('.couponCodeText')
                    .innerText.trim();

                navigator.clipboard.writeText(code).then(() => {
                    btn.innerText = 'COPIED';
                    setTimeout(() => btn.innerText = 'COPY', 1200);
                });
            });
        });

        // CLOSE BUTTON
        document.querySelectorAll('.closeCouponBar').forEach(btn => {
            btn.addEventListener('click', () => {
                btn.closest('.couponBar').style.display = 'none';
            });
        });

        // COUNTDOWN TIMER
        document.querySelectorAll('.couponTimer').forEach(timer => {
            const endTs = parseInt(timer.dataset.end, 10);

            function updateTimer() {
                const now = Math.floor(Date.now() / 1000);
                let diff = endTs - now;

                if (diff <= 0) {
                    timer.innerText = 'Expired';
                    return;
                }

                const d = Math.floor(diff / 86400);
                diff %= 86400;
                const h = Math.floor(diff / 3600);
                diff %= 3600;
                const m = Math.floor(diff / 60);
                const s = diff % 60;

                timer.innerText = `${d}d ${h}h ${m}m ${s}s`;
            }

            updateTimer();
            setInterval(updateTimer, 1000);
        });

    });
</script>


<script src="https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.js"></script>

<script>
    // var swiperThumbs = new Swiper(".mySwiperThumbs", {
    //     spaceBetween: 10,
    //     slidesPerView: 5,
    //     freeMode: true,
    //     watchSlidesProgress: true,
    //     grabCursor: true,

    //     breakpoints: {
    //         0: {
    //             slidesPerView: 3
    //         },
    //         576: {
    //             slidesPerView: 4
    //         },
    //         992: {
    //             slidesPerView: 5
    //         },
    //     },
    // });

    var swiper = new Swiper(".mySwiper", {
        slidesPerView: 1,
        spaceBetween: 12,
        loop: true,

        speed: 900,

        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },

        grabCursor: true,
        simulateTouch: true,

        scrollbar: {
            el: ".swiper-scrollbar",
            draggable: true,
            hide: false,
        },

        effect: "slide",

        // thumbs: {
        //     swiper: swiperThumbs,
        // },

        breakpoints: {
            0: {
                slidesPerView: 1
            },
            768: {
                slidesPerView: 1
            },
            1024: {
                slidesPerView: 1
            },
        },
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function(el) {
            return new bootstrap.Tooltip(el);
        });
    });
</script>


<script>
    document.addEventListener('DOMContentLoaded', () => {

        const allSelects = document.querySelectorAll('[data-select]');

        // ========= Category (static) =========
        const categorySelect = document.getElementById('categorySelect');
        const categoryOptions = document.getElementById('categoryOptions');

        if (categorySelect && categoryOptions) {
            categoryOptions.addEventListener('click', (e) => {
                const opt = e.target.closest('.select-option');
                if (!opt) return;

                const categoryId = opt.dataset.id || opt.dataset.value;
                setSelectValue(categorySelect, opt.textContent.trim(), categoryId);
            });
        }


        function closeAll(except = null) {
            allSelects.forEach(s => {
                if (s !== except) s.classList.remove('is-open');
            });
        }

        // ONLY ONE outside click => close
        document.addEventListener('click', () => closeAll());

        // stop close when clicking inside dropdown (panel/search/options)
        allSelects.forEach(sel => {
            sel.addEventListener('click', (e) => e.stopPropagation());
        });

        function openSelect(select) {
            const isOpen = select.classList.contains('is-open');
            closeAll(select);
            select.classList.toggle('is-open', !isOpen);

            // focus search on open
            if (!isOpen) {
                const search = select.querySelector('[data-search]');
                const options = select.querySelectorAll('.select-option');
                if (search) {
                    search.value = '';
                    options.forEach(li => li.classList.remove('is-hidden'));
                    setTimeout(() => search.focus(), 50);
                }
            }
        }

        function setSelectValue(select, text, value) {
            const label = select.querySelector('[data-label]');
            const hidden = select.querySelector('[data-hidden]');
            if (label) {
                label.textContent = text;
            }
            if (hidden) hidden.value = value;
            select.classList.remove('is-open');
        }

        // Trigger click => open/close
        document.querySelectorAll('[data-trigger]').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const select = btn.closest('[data-select]');
                if (!select || select.classList.contains('is-disabled')) return;
                openSelect(select);
            });
        });


    });
</script>

@endsection