<div class="listing-search-area">
    <div class="container">

        <div class="row">
            <div class="col-md-12">
                <div class="listing-here">

                    @forelse($listings as $listing)
                    <div class="listing-search-area-box">

                        {{-- LEFT: slider / image --}}
                        <div class="listing-search-box-img-area">
                            <div class="listing-slider-wrapper mySwiper">
                                <div class="swiper-wrapper">

                                    @php
                                    $gallery = $listing->gallery ?? collect([]);
                                    @endphp

                                    @if($gallery->count())
                                    @foreach($gallery as $img)
                                    <div class="swiper-slide listing-slider-single">
                                        <img loading="lazy"
                                            src="{{ asset('storage/'.$img->image_path) }}"
                                            alt="{{ $img->alt_text ?? $listing->business_name }}">
                                    </div>
                                    @endforeach
                                    @else
                                    <div class="swiper-slide listing-slider-single">
                                        <img loading="lazy"
                                            src="{{ $listing->logo ? asset('storage/'.$listing->logo) : '' }}"
                                            alt="{{ $listing->business_name }}">
                                    </div>
                                    @endif

                                </div>
                            </div>
                        </div>

                        {{-- RIGHT: content --}}
                        <div class="listing-search-box-content-area">
                            <h3>
                                <a href="{{ route('listingdetail', $listing->slug) }}">
                                    {{ $listing->business_name }}
                                </a>
                            </h3>

                            <div class="profile-reviews-area">
                                <ul>
                                    <li>
                                        <i data-lucide="star"></i>

                                        @php
                                        $avg = $listing->avg_rating ? number_format($listing->avg_rating, 1) : '0.0';
                                        $count = (int) ($listing->ratings_count ?? 0);
                                        @endphp

                                        <span class="profile-review-number">{{ $avg }}</span>

                                        @if($count > 0)
                                        <span class="profile-review-count">({{ $count }})</span>
                                        @else
                                        <span class="profile-review-count">(0)</span>
                                        @endif
                                    </li>

                                    <li>
                                        <i data-lucide="map-pin" style="color:#6c727f;fill:transparent;"></i>
                                        <span class="profile-review-count">
                                            {{ $listing->cityRel->name ?? '' }}
                                            @if($listing->stateRel?->name), {{ $listing->stateRel->name }} @endif
                                            @if($listing->countryRel?->name), {{ $listing->countryRel->name }} @endif
                                        </span>
                                    </li>
                                </ul>
                            </div>

                            <div class="services-list">
                                <ul>
                                    @forelse($listing->services as $service)
                                    <li>
                                        <div class="services-name">{{ $service->name }}</div>
                                    </li>
                                    @empty
                                    <li>
                                        <div class="services-name">No services found</div>
                                    </li>
                                    @endforelse
                                </ul>
                            </div>

                            @php
                            $phone = optional($listing->contacts->first())->phone;
                            $wa = $phone ? preg_replace('/\D/', '', $phone) : null;
                            @endphp

                            <div class="listing-search-box-social-links">
                                <ul>
                                    @if($phone)
                                    <li>
                                        <a href="tel:{{ $phone }}" class="call-btn">
                                            {{ $phone }}
                                        </a>
                                    </li>

                                    <li>
                                        <a target="_blank" href="https://wa.me/{{ $wa }}" class="whatsaap-btn">
                                            WhatsApp
                                        </a>
                                    </li>
                                    @endif

                                    <li>
                                        <a href="#" class="send-enquiry-btn">Send Enquiry</a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        {{-- Wishlist Icon --}}
                        <div class="wishlist-icon">
                            @php
                            $isSaved = in_array($listing->id, $wishIds);
                            @endphp

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

                    </div>
                    @empty
                    <div class="alert alert-warning">No listings found.</div>
                    @endforelse

                </div>
            </div>
        </div>

    </div>
</div>

{{-- ✅ Swiper (only for list view) --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.css" />
<script src="https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.js"></script>

{{-- ✅ SweetAlert --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {

        // Swiper init
        document.querySelectorAll(".mySwiper").forEach(function(swiperEl) {
            const randomDelay = Math.floor(Math.random() * (15000 - 8000 + 1)) + 3000;

            new Swiper(swiperEl, {
                slidesPerView: 1,
                spaceBetween: 12,
                loop: true,
                speed: 900,
                autoplay: {
                    delay: randomDelay,
                    disableOnInteraction: false
                },
                grabCursor: true,
                simulateTouch: true,
                effect: "slide",
            });
        });

        // Wishlist AJAX
        document.querySelectorAll('.wishlist-toggle').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();

                let listingId = this.dataset.id;
                let heartIcon = this.querySelector('.wishlist-heart');

                fetch(`/wishlists/toggle/${listingId}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(async response => {
                        let data = await response.json();
                        return {
                            status: response.status,
                            body: data
                        };
                    })
                    .then(result => {
                        const data = result.body;

                        // login required
                        if (result.status === 401 || data.success === false) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Login Required',
                                text: 'Pehle login kro',
                                confirmButtonText: 'Login'
                            }).then((res) => {
                                if (res.isConfirmed) {
                                    window.location.href = '/login';
                                }
                            });
                            return;
                        }

                        // success add/remove
                        if (data.success) {
                            if (data.status === 'added') {
                                heartIcon.style.fill = 'red';
                                heartIcon.style.color = 'red';
                                heartIcon.classList.add('active');
                            } else if (data.status === 'removed') {
                                heartIcon.style.fill = 'none';
                                heartIcon.style.color = '#333';
                                heartIcon.classList.remove('active');
                            } else {
                                // fallback toggle
                                if (heartIcon.classList.contains('active')) {
                                    heartIcon.style.fill = 'none';
                                    heartIcon.style.color = '#333';
                                    heartIcon.classList.remove('active');
                                } else {
                                    heartIcon.style.fill = 'red';
                                    heartIcon.style.color = 'red';
                                    heartIcon.classList.add('active');
                                }
                            }

                            Swal.fire({
                                icon: 'success',
                                title: data.message ?? 'Wishlist updated',
                                timer: 1200,
                                showConfirmButton: false
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.message ?? 'Something went wrong'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Wishlist error:', error);

                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Something went wrong. Please try again.'
                        });
                    });
            });
        });

        // lucide render
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>

<style>
    button.action-btn.wishlist-btn {
        width: 40px;
        height: 40px;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border: none;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
        color: #374151;
    }
</style>