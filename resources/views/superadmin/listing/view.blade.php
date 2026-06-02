@extends('layouts.superadmin')

@section('title', 'Listing')

@section('content')

<link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.css" />

<style>
    .swiper-wrapper {
        width: 0px !important;
    }

    aside.sidebar-main {
        height: auto;
    }

    .listing-slider-single {
        height: 360px !important;
    }

    .listing-slider-wrapper {
        height: auto;
    }
</style>

<main class="main-dashboard">

    <div class="top-heading">
        <h1>Listing View</h1>
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
            </div>
        </div>
    </section>

    <section class="listing-details-area mb-5">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-8">

                    <div class="listing-slider-wrapper mySwiper mb-5" style="overflow: hidden;">
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


                    <div class="listing-details-about">
                        <h2 class="heading-title">About </h2>
                        <p>
                            {{ $listing->description }}
                        </p>
                    </div>

                    @php
                    $filteredFeatures = $listing->features->filter(function ($feat) {
                    return !empty(trim($feat->feature_name ?? $feat->feature?->name ?? ''))
                        || !empty($feat->feature_image)
                        || !empty($feat->feature?->icon_image);
                    });
                    @endphp

                    @if($filteredFeatures->count() > 0)
                    <div class="listing-feature-show">
                        <h2 class="heading-title">Features</h2>

                        <div class="features-box-grid mt-4">
                            @foreach($filteredFeatures as $feat)
                            @php
                            $featureName = $feat->feature_name ?: ($feat->feature->name ?? 'Feature');
                            $iconPath = $feat->feature_image ?: ($feat->feature->icon_image ?? null);
                            $iconSrc = null;
                            if (!empty($iconPath)) {
                                $iconPathStr = (string) $iconPath;
                                if (\Illuminate\Support\Str::startsWith($iconPathStr, ['http://', 'https://'])) {
                                    $iconSrc = $iconPathStr;
                                } elseif (\Illuminate\Support\Str::startsWith($iconPathStr, ['storage/', '/storage/'])) {
                                    $iconSrc = asset(ltrim($iconPathStr, '/'));
                                } else {
                                    $iconSrc = asset('storage/' . ltrim($iconPathStr, '/'));
                                }
                            }
                            @endphp
                            <div class="icon-box icon-box-one">
                                <div class="icon">
                                    @if(!empty($iconSrc))
                                    <img src="{{ $iconSrc }}"
                                        alt="{{ $featureName }}"
                                        class="feature-icon-img"
                                        style="height:40px;width:40px;object-fit:contain;">
                                    @endif
                                </div>

                                <div class="info">
                                    <h6>{{ $featureName }}</h6>
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


                </div>


                <div class="col-lg-4">
                    <div class="top-sticky">
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
                                    $social = $listing->socialLinks->pluck('url', 'platform');

                                    $facebook = $social['facebook'] ?? null;
                                    $instagram = $social['instagram'] ?? null;
                                    $youtube = $social['youtube'] ?? null;
                                    $twitter = $social['twitter'] ?? null;
                                    $linkedin = $social['linkedin'] ?? null;
                                    $snapchat = $social['snapchat'] ?? null;
                                    @endphp



                                    <div class="social-links">
                                        <a href="{{ $social['facebook'] ?? '#' }}" class="social-link facebook" target="_blank" rel="noopener">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-facebook-icon lucide-facebook">
                                                <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
                                            </svg>
                                        </a>

                                        <a href="{{ $social['instagram'] ?? '#' }}" class="social-link instagram" target="_blank" rel="noopener">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-instagram-icon lucide-instagram">
                                                <rect width="20" height="20" x="2" y="2" rx="5" ry="5"></rect>
                                                <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                                                <line x1="17.5" x2="17.51" y1="6.5" y2="6.5"></line>
                                            </svg>
                                        </a>

                                        <a href="{{ $social['youtube'] ?? '#' }}" class="social-link youtube" target="_blank" rel="noopener">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-youtube-icon lucide-youtube">
                                                <path d="M2.5 17a24.12 24.12 0 0 1 0-10 2 2 0 0 1 1.4-1.4 49.56 49.56 0 0 1 16.2 0A2 2 0 0 1 21.5 7a24.12 24.12 0 0 1 0 10 2 2 0 0 1-1.4 1.4 49.55 49.55 0 0 1-16.2 0A2 2 0 0 1 2.5 17"></path>
                                                <path d="m10 15 5-3-5-3z"></path>
                                            </svg>
                                        </a>

                                        <a href="{{ $social['twitter'] ?? '#' }}" class="social-link twitter" target="_blank" rel="noopener">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-twitter-icon lucide-twitter">
                                                <path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"></path>
                                            </svg>
                                        </a>

                                        <a href="{{ $social['linkedin'] ?? '#' }}" class="social-link linkedin" target="_blank" rel="noopener">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-linkedin-icon lucide-linkedin">
                                                <path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path>
                                                <rect width="4" height="12" x="2" y="9"></rect>
                                                <circle cx="4" cy="4" r="2"></circle>
                                            </svg>
                                        </a>

                                        <a href="{{ $social['snapchat'] ?? '#' }}" class="social-link snapchat" target="_blank" rel="noopener">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-snapchat-icon lucide-snapchat">
                                                <path d="M12 2c-3.4 0-6 2.6-6 6v2.3c0 .6-.3 1.1-.8 1.4-.6.4-1.3.7-2 .9-.7.2-1.2.7-1.2 1.3 0 .7.7 1.2 1.7 1.6 1.3.5 2.2 1.2 2.8 2.1.4.6 1 .9 1.7.9h1.1c.4 0 .7.2 1 .5.5.5 1.1.8 1.7.8s1.2-.3 1.7-.8c.3-.3.6-.5 1-.5h1.1c.7 0 1.3-.3 1.7-.9.6-.9 1.5-1.6 2.8-2.1 1-.4 1.7-.9 1.7-1.6 0-.6-.5-1.1-1.2-1.3-.7-.2-1.4-.5-2-.9-.5-.3-.8-.8-.8-1.4V8c0-3.4-2.6-6-6-6z"></path>
                                            </svg>
                                        </a>


                                    </div>
                                </div>
                            </div>
                        </div>



                        <div class="listing-business-hour">
                            @php
                            $daysOrder = ['monday','tuesday','wednesday','thursday','friday','saturday','sunday'];

                            // safe: agar rows nahi hain to empty collection
                            $hoursByDay = ($listing->hours ?? collect())
                            ->keyBy(fn($h) => strtolower($h->day_of_week));

                            $fmt = function ($t) {
                            return $t ? \Carbon\Carbon::parse($t)->format('g:i A') : null;
                            };
                            @endphp

                            <h3 class="heading-title">Business Hour</h3>

                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <div class="business-hour-list">
                                        <ul>
                                            @foreach($daysOrder as $day)
                                            @php $h = $hoursByDay->get($day); @endphp
                                            <li>
                                                <span class="day-title">{{ ucfirst($day) }}</span>

                                                @if(!$h || (int)$h->is_closed === 1)
                                                <span class="day-close">Closed</span>
                                                @else
                                                <span class="day-time">
                                                    {{ $fmt($h->open_time) }} - {{ $fmt($h->close_time) }}
                                                </span>
                                                @endif
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>




                    </div>
                </div>
            </div>
        </div>
    </section>


</main>




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

@endsection