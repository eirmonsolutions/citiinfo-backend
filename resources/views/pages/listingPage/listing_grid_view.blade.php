<link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.css" />

<section class="listing-area-front">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="section-title">
                    <h2>Listings</h2>
                </div>
            </div>
        </div>
        <div class="row listing-wrapper {{ request('view','grid')=='list' ? 'is-list' : 'is-grid' }}">
            @forelse($listings as $listing)
            <div class="{{ request('view','grid')=='list' ? 'col-12' : 'col-md-6 col-lg-6 col-xl-4' }}">
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
                            <!-- scrollbar -->
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

                        {{-- STATUS BADGE (optional) --}}
                        <div class="status-badge open close">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock-icon lucide-clock">
                                <path d="M12 6v6l4 2" />
                                <circle cx="12" cy="12" r="10" />
                            </svg>
                            Open Now
                        </div>
                    </div>

                    <div class="front-listing-content">
                        <div class="front-listing-header">
                            <div class="front-listing-title">
                                <h3>
                                    {{-- VIEW LINK --}}
                                    <a href="{{ route('listingdetail', $listing->slug) }}">
                                        {{ $listing->business_name }}
                                    </a>
                                </h3>
                            </div>

                            <div class="front-listing-info">
                                <div class="front-listing-meta">
                                    <div class="rating">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-star-icon lucide-star">
                                            <path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z" />
                                        </svg>

                                        @php
                                        $avg = $listing->avg_rating ? number_format($listing->avg_rating, 1) : '0.0';
                                        @endphp

                                        <span>{{ $avg }}</span>
                                    </div>

                                    <div class="reviews">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users-icon lucide-users">
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

                                {{-- CATEGORY --}}
                                <div class="category-badge">
                                    {{ $listing->categoryRel->name ?? '-' }}
                                </div>
                            </div>

                        </div>

                        {{-- DESCRIPTION --}}
                        @php
                        // Latest review fetch
                        $latestReview = $listing->reviews()->latest()->first();
                        @endphp

                        <div class="testimonial">
                            <div class="testimonial-content">

                                <img
                                    src="{{ $listing->logo ? asset('storage/'.$listing->logo) : 'https://citiinfo.com.au/assets/images/favicon.jpg' }}"
                                    alt="{{ $listing->business_name }}"
                                    class="testimonial-avatar">

                                <div class="testimonial-text">

                                    @if($latestReview)

                                    <p>"{{ $latestReview->review }}"</p>
                                    <span class="testimonial-author">
                                        {{ $latestReview->name ?? 'Customer' }}
                                    </span>

                                    @else

                                    {{-- Professional Default Message --}}
                                    <p>
                                        "No reviews yet — be the first to share your experience with {{ $listing->business_name }}.
                                        Your feedback helps others choose with confidence."
                                    </p>
                                    <span class="testimonial-author">
                                        No reviews yet
                                    </span>

                                    @endif

                                </div>
                            </div>
                        </div>


                        {{-- CITY --}}
                        <div class="location">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin-icon lucide-map-pin">
                                <path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0" />
                                <circle cx="12" cy="10" r="3" />
                            </svg>

                            <span>
                                {{ $listing->cityRel->name ?? $listing->city ?? '-' }}
                            </span>
                        </div>

                    </div>
                </div>
            </div> @empty <div class="col-12 text-center">
                <p>No listings found.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>


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