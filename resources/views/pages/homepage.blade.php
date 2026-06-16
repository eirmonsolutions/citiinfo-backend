@extends('layouts.app')

@section('title', 'Australia Business Directory | Free Business Listings & Local Services')

@section('meta_description', 'Citiinfo is an Australia business directory to find local businesses, services, restaurants, salons and plumbers. Add your business free and reach customers across Australia.')

@section('meta_keywords', 'business directory australia, local business directory australia, australia business directory, free business listing sites australia, business listing website')

@section('content')


<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.css" />

<div id="toastContainer" class="toast-container position-fixed top-0 end-0 p-3" style="z-index:99999;"></div>
<meta name="wishlist-toggle-url" content="{{ Route::has('wishlist.toggle') ? route('wishlist.toggle') : '' }}">

<!-- Banner / Search Area -->
<section class="banner-area">
    <div class="container">
        <div class="banner-text">
            <h1>Australia Business Directory – Find Local Businesses & Services Near You</h1>
            <form action="{{ route('search.redirect') }}" method="POST" autocomplete="off">
                @csrf
                <input type="hidden" name="category_id" id="category_id" value="">
                <input type="hidden" name="city_id" id="city_id" value="">

                <div class="banner-form">
                    <div class="banner-wrapper">

                        <!-- SERVICE -->
                        <div class="banner-form-box position-relative">
                            <i class="fi-search"></i>

                            <input type="search"
                                id="service_input"
                                name="service"
                                class="form-control form-control-lg form-icon-start"
                                placeholder="What service do you need?"
                                required>

                            <!-- ✅ Dropdown panel -->
                            <div id="service_suggest" class="suggest-box">

                                <div class="select-search">
                                    <input type="text" placeholder="Search..." id="service_search" autocomplete="off" />
                                </div>

                                <ul class="select-options" id="serviceOptions"></ul>

                            </div>
                        </div>


                        <hr class="vr d-sm-block">

                        <!-- CITY -->
                        <div class="banner-form-box zip-form-box position-relative">
                            <i class="fi-map-pin"></i>
                            <input type="text"
                                name="city"
                                id="city_input"
                                class="form-control form-control-lg form-icon-start"
                                placeholder="City"
                                required>
                            {{-- <button type="button" id="use-my-location" class="btn btn-sm btn-outline-secondary position-absolute end-0 top-50 translate-middle-y me-2" style="font-size:0.85rem; padding:0.35rem 0.7rem; z-index:10;">
                                📍 My location
                            </button> --}}
                            <div id="city_suggest" class="suggest-box"></div>
                        </div>

                    </div>

                    <button type="submit" class="btn btn-lg btn-primary">Search</button>
                </div>
            </form>

            <div class="category-btns">
                <button class="category-btn-link" type="button" data-service="Electrician">Electrician</button>
                <button class="category-btn-link" type="button" data-service="Plumbing">Plumbing</button>
                <button class="category-btn-link" type="button" data-service="Hospitals">Hospitals</button>
                <button class="category-btn-link" type="button" data-service="Roofing">Roofing</button>
                <button class="category-btn-link" type="button" data-service="Saloon">Saloon</button>
            </div>
        </div>
    </div>
</section>

<!-- Category Area -->
<section class="category-area">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="section-title">
                    <h2>Destination Categories</h2>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="category-item-grid">
                @foreach($categories as $category)
                <div class="category-item category-item-two">
                    <!-- <div class="category-img">
                        <img
                            src="{{ $category->image ? asset('storage/'.$category->image) : asset('assets/images/saloon.jpg') }}"
                            alt="{{ $category->name }}">
                        <div class="category-overlay">
                            <div class="category-content">
                                <a href="{{ route('list.category', ['category' => Str::slug($category->name)]) }}">
                                    <i class="ti-link"></i>
                                </a>
                            </div>
                        </div>
                    </div> -->
                    <div class="info">
                        <div class="icon">
                            @if(!empty($category->categoryimage))
                            <img
                                src="{{ asset('storage/'.$category->categoryimage) }}"
                                alt="{{ $category->name }}"
                                style="width:40px;height:40px;object-fit:contain;filter: brightness(0);">
                            @else
                            <span>-</span>
                            @endif
                        </div>

                        <h3 class="title">
                            <a href="{{ route('list.category', ['category' => Str::slug($category->name)]) }}">
                                {{ $category->name }}
                            </a>
                        </h3>
                        <span class="listing">
                            {{ $category->listings_count }} Listing{{ $category->listings_count != 1 ? 's' : '' }}
                        </span>

                    </div>
                </div>
                @endforeach
            </div>

            <div class="view-all-btn">
                <a class="listing-btn" href="/categories">View All</a>
            </div>
        </div>
    </div>
</section>

<!-- Explore Cities Area -->
<section class="explore-city-area">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="section-title">
                    <h2>Explore Cities</h2>
                </div>
            </div>
        </div>

        <div class="row g-0">

            {{-- ✅ Melbourne --}}
            <div class="col-md-6 col-lg-4">
                <div class="city-grid">
                    <div class="city-img">
                        <img src="{{ asset('assets/images/cities/Melbourne.jpg') }}" alt="Melbourne">
                    </div>
                    <div class="city-title">
                        <div class="listings-count">
                            <span class="count-number">
                                {{ $cities['Melbourne']->listings_count ?? 0 }}
                            </span>
                            <p class="count-text">LISTINGS</p>
                        </div>
                        <h3>
                            <a href="{{ route('listingpage', ['location' => 'Melbourne']) }}">Melbourne</a>
                        </h3>
                    </div>
                </div>
            </div>

            {{-- ✅ Sydney + Perth (desktop second block) --}}
            <div class="col-md-6 col-lg-4">
                <div class="city-grid city-grid-normal-height">
                    <div class="city-img">
                        <img src="{{ asset('assets/images/cities/Sydney.jpg') }}" alt="Sydney">
                    </div>
                    <div class="city-title">
                        <div class="listings-count">
                            <span class="count-number">
                                {{ $cities['Sydney']->listings_count ?? 0 }}
                            </span>
                            <p class="count-text">LISTINGS</p>
                        </div>
                        <h3>
                            <a href="{{ route('listingpage', ['location' => 'Sydney']) }}">Sydney</a>
                        </h3>
                    </div>
                </div>

                <div class="city-grid city-grid-normal-height d-sm-none d-lg-block">
                    <div class="city-img">
                        <img src="{{ asset('assets/images/cities/Perth.jpg') }}" alt="Perth">
                    </div>
                    <div class="city-title">
                        <div class="listings-count">
                            <span class="count-number">
                                {{ $cities['Perth']->listings_count ?? 0 }}
                            </span>
                            <p class="count-text">LISTINGS</p>
                        </div>
                        <h3>
                            <a href="{{ route('listingpage', ['location' => 'Perth']) }}">Perth</a>
                        </h3>
                    </div>
                </div>
            </div>

            {{-- ✅ Perth (mobile only block) --}}
            <div class="col-md-6 col-lg-4 d-lg-none d-sm-block">
                <div class="city-grid city-grid-normal-height">
                    <div class="city-img">
                        <img src="{{ asset('assets/images/cities/Perth.jpg') }}" alt="Perth">
                    </div>
                    <div class="city-title">
                        <div class="listings-count">
                            <span class="count-number">
                                {{ $cities['Perth']->listings_count ?? 0 }}
                            </span>
                            <p class="count-text">LISTINGS</p>
                        </div>
                        <h3>
                            <a href="{{ route('listingpage', ['location' => 'Perth']) }}">Perth</a>
                        </h3>
                    </div>
                </div>
            </div>

            {{-- ✅ Brisbane --}}
            <div class="col-md-6 col-lg-4">
                <div class="city-grid">
                    <div class="city-img">
                        <img src="{{ asset('assets/images/cities/Brisbane.jpg') }}" alt="Brisbane">
                    </div>
                    <div class="city-title">
                        <div class="listings-count">
                            <span class="count-number">
                                {{ $cities['Brisbane']->listings_count ?? 0 }}
                            </span>
                            <p class="count-text">LISTINGS</p>
                        </div>
                        <h3>
                            <a href="{{ route('listingpage', ['location' => 'Brisbane']) }}">Brisbane</a>
                        </h3>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>



<!-- Featured Listings -->
<section class="listing-area-front">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="section-title">
                    <h2>Listings</h2>
                </div>
            </div>
        </div>

        <div class="row">
            @forelse($listings as $listing)
            <div class="col-md-6 col-lg-6 col-xl-4">
                <div class="front-listing-box">
                    <div class="front-listing-img">
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
                            <div class="swiper-scrollbar"></div>
                        </div>
                        <div class="image-overlay"></div>



                        @php
                        $isSaved = in_array($listing->id, $wishIds);
                        @endphp

                        <div class="action-buttons">
                            <button
                                class="action-btn wishlist-btn {{ $isSaved ? 'is-saved' : '' }}"
                                type="button"
                                title="Save"
                                data-business-id="{{ $listing->id }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-heart-icon">
                                    <path d="M2 9.5a5.5 5.5 0 0 1 9.591-3.676.56.56 0 0 0 .818 0A5.49 5.49 0 0 1 22 9.5c0 2.29-1.5 4-3 5.5l-5.492 5.313a2 2 0 0 1-3 .019L5 15c-1.5-1.5-3-3.2-3-5.5" />
                                </svg>
                            </button>
                        </div>




                        {{-- ✅ DYNAMIC STATUS BADGE (Open/Closed/Lunch) --}}
                        @php
                        $day = strtolower(now()->format('l')); // monday...
                        $today = $listing->hours->firstWhere('day_of_week', $day);

                        $statusText = 'Closed Now';
                        $statusClass = 'closed';

                        // helper: normalize time to H:i:s (supports H:i or H:i:s)
                        $norm = function ($t) {
                        if (!$t) return null;
                        $t = trim($t);
                        if (preg_match('/^\d{2}:\d{2}$/', $t)) return $t . ':00';
                        if (preg_match('/^\d{2}:\d{2}:\d{2}$/', $t)) return $t;
                        return null;
                        };

                        // helper: time in range (supports overnight)
                        $inRange = function ($now, $start, $end) {
                        if (!$now || !$start || !$end) return false;

                        // normal range
                        if ($start <= $end) {
                            return ($now>= $start && $now <= $end);
                                }

                                // overnight (e.g., 22:00:00 to 02:00:00)
                                return ($now>= $start || $now <= $end);
                                    };

                                    if ($today && (int)$today->is_closed === 0) {

                                    $open = $norm($today->open_time);
                                    $close = $norm($today->close_time);
                                    $breakStart = $norm($today->break_start);
                                    $breakEnd = $norm($today->break_end);

                                    // ✅ current time (server/app timezone)
                                    $tz = $listing->cityRel->timezone ?? config('app.timezone');
                                    $nowTime = \Carbon\Carbon::now($tz)->format('H:i:s');


                                    if ($inRange($nowTime, $open, $close)) {

                                    if ($breakStart && $breakEnd && $inRange($nowTime, $breakStart, $breakEnd)) {
                                    $statusText = 'Lunch Time';
                                    $statusClass = 'lunch';
                                    } else {
                                    $statusText = 'Open Now';
                                    $statusClass = 'open';
                                    }
                                    }
                                    }
                                    @endphp

                                    <div class="status-badge {{ $statusClass }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock">
                                            <path d="M12 6v6l4 2" />
                                            <circle cx="12" cy="12" r="10" />
                                        </svg>
                                        {{ $statusText }}
                                    </div>

                    </div>

                    <div class="front-listing-content">
                        <div class="front-listing-header">
                            <div class="front-listing-title">
                                <h3>
                                    <a href="{{ route('listingdetail', $listing->slug) }}">
                                        {{ $listing->business_name }}
                                    </a>
                                </h3>
                            </div>

                            <div class="front-listing-info">
                                <div class="front-listing-meta">
                                    <div class="rating">
                                        {{-- star icon same --}}
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-star">
                                            <path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z" />
                                        </svg>

                                        @php
                                        $avg = $listing->avg_rating ? number_format($listing->avg_rating, 1) : null;
                                        @endphp

                                        <span>{{ $avg ?? '0.0' }}</span>
                                    </div>

                                    <div class="reviews">
                                        {{-- users icon same --}}
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users">
                                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                            <path d="M16 3.128a4 4 0 0 1 0 7.744" />
                                            <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                                            <circle cx="9" cy="7" r="4" />
                                        </svg>

                                        @if(($listing->ratings_count ?? 0) > 0)
                                        <span>{{ $listing->ratings_count }} rating{{ $listing->ratings_count > 1 ? 's' : '' }}</span>
                                        @else
                                        <span>No ratings</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="category-badge">
                                    {{ $listing->categoryRel->name ?? '-' }}
                                </div>
                            </div>
                        </div>

                        <div class="testimonial">
                            <div class="testimonial-content">

                                <img
                                    src="{{ $listing->logo ? asset('storage/'.$listing->logo) : 'https://citiinfo.com.au/assets/images/favicon.jpg' }}"
                                    alt="{{ $listing->business_name }}"
                                    class="testimonial-avatar">

                                <div class="testimonial-text">

                                    @if(($listing->ratings_count ?? 0) > 0 && $listing->reviews?->isNotEmpty())

                                    <p>"{{ $listing->reviews->first()->review ?? 'Great service!' }}"</p>
                                    <span class="testimonial-author">
                                        {{ $listing->reviews->first()->name ?? 'Verified Customer' }}
                                    </span>

                                    @else

                                    {{-- DEFAULT PROFESSIONAL MESSAGE --}}
                                    <p>
                                        "Be the first to share your experience with {{ $listing->business_name }}.
                                        Your review helps others make better decisions!"
                                    </p>
                                    <span class="testimonial-author">
                                        No reviews yet
                                    </span>

                                    @endif

                                </div>
                            </div>
                        </div>


                        <div class="location">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin">
                                <path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0" />
                                <circle cx="12" cy="10" r="3" />
                            </svg>
                            <span>{{ $listing->cityRel->name ?? $listing->city ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center">
                <p>No listings found.</p>
            </div>
            @endforelse
        </div>
</section>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.js"></script>

<script>
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

        // ── DOM Elements ───────────────────────────────────────────────
        const serviceInput = document.getElementById('service_input');
        const serviceBox = document.getElementById('service_suggest');
        const serviceSearch = document.getElementById('service_search');
        const serviceOptions = document.getElementById('serviceOptions');

        const cityInput = document.getElementById('city_input');
        const cityBox = document.getElementById('city_suggest');
        // const geoMsg = document.getElementById('geo_msg');

        const citySuggestUrl = "{{ route('ajax.city.suggest') }}";
        const cityIdInput = document.getElementById('city_id');


        let cityDebounce;

        cityInput.addEventListener('input', function() {
            clearTimeout(cityDebounce);
            const term = this.value.trim();

            if (term.length < 2) {
                cityBox.innerHTML = '';
                cityBox.style.display = 'none';
                if (cityIdInput) cityIdInput.value = '';
                return;
            }

            cityDebounce = setTimeout(async () => {
                try {
                    const categoryId = document.getElementById('category_id')?.value || '';
                    const res = await fetch(`${citySuggestUrl}?term=${encodeURIComponent(term)}&category_id=${encodeURIComponent(categoryId)}`);
                    const cities = await res.json();

                    if (!cities.length) {
                        cityBox.innerHTML = '';
                        cityBox.style.display = 'none';
                        if (cityIdInput) cityIdInput.value = '';
                        return;
                    }

                    cityBox.innerHTML = cities.map(c => `
                <div class="select-option city-option" data-id="${c.id}" style="padding:10px; cursor:pointer;">
                    ${c.name}
                </div>
            `).join('');

                    cityBox.style.display = 'block';
                } catch (e) {
                    console.error(e);
                }
            }, 250);
        });


        const form = serviceInput.closest('form');
        const suggestUrl = "{{ route('ajax.category.suggest') }}";

        let debounceTimer;
        let lastCategories = [];
        let lastBusinesses = [];

        function show(box) {
            box.style.display = 'block';
        }

        function hide(box) {
            box.style.display = 'none';
        }

        function showToast(message, type = 'danger') {
            const container = document.getElementById('toastContainer');
            if (!container) {
                alert(message);
                return;
            }

            const toast = document.createElement('div');
            toast.className = `toast align-items-center text-bg-${type} border-0 show`;
            toast.role = 'alert';
            toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">${message}</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto"></button>
        </div>
    `;

            container.appendChild(toast);

            toast.querySelector('button').addEventListener('click', () => toast.remove());
            setTimeout(() => toast.remove(), 3000);
        }


        // ── Auto-detect location on page load ──────────────────────────
        function autoDetectLocation() {
            if (!navigator.geolocation) {
                return; // geolocation not supported → silently ignore
            }

            navigator.geolocation.getCurrentPosition(
                async (position) => {
                        const {
                            latitude,
                            longitude
                        } = position.coords;

                        try {
                            const response = await fetch(
                                `https://nominatim.openstreetmap.org/reverse?format=json&lat=${latitude}&lon=${longitude}&zoom=10&addressdetails=1`
                            );

                            if (!response.ok) throw new Error('Reverse geocoding failed');

                            const data = await response.json();

                            let city =
                                data?.address?.city ||
                                data?.address?.town ||
                                data?.address?.city_district ||
                                data?.address?.suburb ||
                                data?.address?.county ||
                                data?.address?.state ||
                                '';

                            if (city && cityInput) {
                                cityInput.value = city;
                            }

                        } catch (err) {
                            console.error('Location detection error:', err);
                        }
                    },
                    (error) => {
                        // silently ignore errors (permission denied / timeout etc.)
                        console.warn('Geolocation error:', error);
                    }, {
                        enableHighAccuracy: false,
                        timeout: 7000,
                        maximumAge: 60000
                    }
            );
        }
        autoDetectLocation();

        // ── Render dropdown options (Categories + Businesses) ───────────
        function renderDropdown(categories = [], businesses = [], filterTerm = '') {
            lastCategories = categories;
            lastBusinesses = businesses;

            const term = (filterTerm || '').toLowerCase();

            const filteredCats = categories.filter(c => !term || (c.name || '').toLowerCase().includes(term));
            const filteredBiz = businesses.filter(b => !term || (b.business_name || '').toLowerCase().includes(term));

            let html = '';

            if (filteredCats.length) {

                html += filteredCats.map(c => `
                <li class="select-option category-option" data-slug="${c.slug}">
                    ${c.name}
                </li>
            `).join('');
            }

            if (filteredBiz.length) {
                html += filteredBiz.map(b => `
        <li class="select-option business-option"
            data-name="${b.business_name}"
            data-slug="${b.slug ?? ''}">
            ${b.business_name}
        </li>
    `).join('');
            }


            if (!html) {
                html = `<li class="select-option text-muted" style="cursor:default;">No results found</li>`;
            }

            serviceOptions.innerHTML = html;
            show(serviceBox);
        }

        // ── Service input -> API suggest ───────────────────────────────
        serviceInput.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            const term = this.value.trim();

            if (term.length < 2) {
                hide(serviceBox);
                serviceOptions.innerHTML = '';
                return;
            }

            debounceTimer = setTimeout(async () => {
                try {
                    const res = await fetch(`${suggestUrl}?term=${encodeURIComponent(term)}`);
                    const {
                        categories = [], businesses = []
                    } = await res.json();

                    // dropdown open + focus to inside search (optional)
                    serviceSearch.value = '';
                    renderDropdown(categories, businesses);
                } catch (err) {
                    console.error('Suggest error:', err);
                }
            }, 300);
        });

        // ── Inside dropdown search filter (client side) ─────────────────
        serviceSearch.addEventListener('input', function() {
            renderDropdown(lastCategories, lastBusinesses, this.value.trim());
        });

        // ── Click on dropdown option ───────────────────────────────────
        serviceOptions.addEventListener('click', (e) => {
            const cat = e.target.closest('.category-option');
            if (cat) {
                window.location.href = `/category/${cat.dataset.slug}`;
                return;
            }

            const biz = e.target.closest('.business-option');
            if (biz) {
                serviceInput.value = biz.dataset.name;
                hide(serviceBox);
                return;
            }

        });

        // ── Quick category buttons ─────────────────────────────────────
        document.querySelectorAll('.category-btn-link').forEach(btn => {
            btn.addEventListener('click', async () => {
                const term = btn.dataset.service;
                try {
                    const res = await fetch(`${suggestUrl}?term=${encodeURIComponent(term)}`);
                    const {
                        categories = []
                    } = await res.json();

                    if (categories.length > 0) {
                        window.location.href = `/category/${categories[0].slug}`;
                    } else {
                        serviceInput.value = term;
                        form.submit();
                    }
                } catch {
                    window.location.href = `/search?service=${encodeURIComponent(term)}`;
                }
            });
        });

        // ── Form submit logic ──────────────────────────────────────────
        form.addEventListener('submit', async function(e) {
            e.preventDefault();

            const service = serviceInput.value.trim();
            const city = cityInput ? cityInput.value.trim() : '';
            const city_id = cityIdInput ? cityIdInput.value.trim() : '';
            const category_id = document.getElementById('category_id')?.value || '';

            if (!service) {
                alert("Please enter a service");
                return;
            }

            try {
                const res = await fetch(`${suggestUrl}?term=${encodeURIComponent(service)}`);
                const {
                    categories = [], businesses = []
                } = await res.json();

                // ✅ category found -> category page
                if (categories.length > 0) {
                    window.location.href = `/category/${categories[0].slug}`;
                    return;
                }

                // ✅ business found OR user typed random but still search page open
                // (no 405) -> GET /search
                window.location.href = `{{ route('search.byText') }}?service=${encodeURIComponent(service)}&city=${encodeURIComponent(city)}&city_id=${encodeURIComponent(city_id)}`;
                return;

            } catch (err) {
                console.error('Search error:', err);
                window.location.href = `{{ route('search.byText') }}?service=${encodeURIComponent(service)}&city=${encodeURIComponent(city)}&city_id=${encodeURIComponent(city_id)}`;
            }
        });


        cityBox.addEventListener('click', function(e) {
            const opt = e.target.closest('.city-option');
            if (!opt) return;

            cityInput.value = opt.textContent.trim();
            if (cityIdInput) cityIdInput.value = opt.dataset.id;

            cityBox.style.display = 'none';
            cityBox.innerHTML = '';
        });



        // ── Hide on outside click ──────────────────────────────────────
        document.addEventListener('click', (e) => {
            const clickedInsideService = serviceBox.contains(e.target) || e.target === serviceInput;
            if (!clickedInsideService) hide(serviceBox);

            const clickedInsideCity = cityBox.contains(e.target) || e.target === cityInput;
            if (!clickedInsideCity) {
                cityBox.style.display = 'none';
                cityBox.innerHTML = '';
            }
        });

    });
</script>

<style>
    .select-option:hover {
        background: #f8f9fa;
    }

    .select-option {
        text-align: start;
    }

    .select-heading {
        padding: 10px 14px;
        font-size: 12px;
        font-weight: 600;
        color: #6c757d;
        text-transform: uppercase;
        cursor: default;
        border-bottom: 1px solid #f0f0f0;
    }

    .suggest-box {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border: 1px solid #ddd;
        border-radius: 10px;
        margin-top: 8px;
        max-height: 260px;
        overflow-y: auto;
        z-index: 9999;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.12);
        display: none;
    }

    .suggest-item {
        padding: 12px 16px;
        cursor: pointer;
        font-size: 15px;
        border-bottom: 1px solid #f0f0f0;
    }

    .suggest-item:hover,
    .suggest-item:focus {
        background: #f8f9fa;
    }

    .suggest-item:last-child {
        border-bottom: none;
    }

    .geo-msg {
        position: absolute;
        bottom: -26px;
        left: 0;
        font-size: 13.5px;
        color: #0069d9;
    }
</style>





@endsection