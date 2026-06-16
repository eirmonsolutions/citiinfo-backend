@extends('layouts.admin')

@section('title', 'Listing')

@section('content')

<style>
    .whistlist-grid {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
    }
</style>

<main class="main-dashboard">


    <div class="top-heading d-flex align-items-center justify-content-between">
        <h1 class="mb-0">Saved Listings</h1>
    </div>



    <section class="whistlist-boxes">
        <div class="row whistlist-grid">
            @forelse($listings as $listing)

            @php
            $isSaved = isset($wishIds)
            ? in_array($listing->id, $wishIds)
            : true; // wishlist page pe already saved
            @endphp

            <div class="whishlist-wrapper" >
                <div class="front-listing-box" id="wishlist-item-{{ $listing->id }}">
                    <div class="front-listing-img">
                        <div class="listing-slider-wrapper mySwiper mb-5">
                            <div class="swiper-wrapper">
                                @forelse($listing->gallery as $img)
                                <div class="swiper-slide listing-slider-single">
                                    <img
                                        loading="lazy"
                                        src="{{ asset('storage/'.$img->image_path) }}"
                                        alt="{{ $img->alt_text ?? $listing->business_name }}">
                                </div>
                                @empty
                                <div class="swiper-slide listing-slider-single">
                                    <img
                                        loading="lazy"
                                        src="{{ $listing->logo ? asset('storage/'.$listing->logo) : asset('assets/images/favicon.jpg') }}"
                                        alt="{{ $listing->business_name }}">
                                </div>
                                @endforelse
                            </div>
                            <div class="swiper-scrollbar"></div>
                        </div>
                        <div class="image-overlay"></div>


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
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-star">
                                            <path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z" />
                                        </svg>
                                        <span>4.5</span>
                                    </div>

                                    <div class="reviews">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users">
                                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                            <path d="M16 3.128a4 4 0 0 1 0 7.744" />
                                            <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                                            <circle cx="9" cy="7" r="4" />
                                        </svg>
                                        <span>No ratings</span>
                                    </div>
                                </div>

                                <div class="category-badge">
                                    {{ $listing->categoryRel->name ?? '-' }}
                                </div>
                            </div>
                        </div>

                        <div class="testimonial">
                            <div class="testimonial-content">
                                <img src="{{ $listing->logo ? asset('storage/'.$listing->logo) : 'https://images.unsplash.com/photo-1496442226666-8d4d0e62e6e9?auto=format&fit=crop&w=800&h=500' }}" alt="{{ $listing->business_name }}" class="testimonial-avatar">
                                <div class="testimonial-text">
                                    <p>"I haven't had pizza in like 5 minutes!! talking with my colleague after our office lunch"</p>
                                    <span class="testimonial-author">Mike Johnson</span>
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
            <div class="col-12">
                <div class="announcement-inner text-center p-5">
                    <h2>No saved listings yet</h2>
                    <p class="mb-0">Browse listings and tap the heart icon to save them here.</p>
                </div>
            </div>
            @endforelse

        </div>
    </section>




</main>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.wishlist-btn').forEach(button => {
            button.addEventListener('click', function() {
                const businessId = this.dataset.businessId;
                const card = document.getElementById('wishlist-item-' + businessId);
                const btn = this;

                fetch("{{ route('wishlist.toggle') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Accept": "application/json"
                        },
                        body: JSON.stringify({
                            business_id: businessId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success && data.action === 'removed') {
                            const wrapper = card?.closest('.whishlist-wrapper');
                            card.style.transition = 'all 0.35s ease';
                            card.style.opacity = '0';
                            card.style.transform = 'translateY(-10px)';

                            setTimeout(() => {
                                wrapper?.remove();

                                const remaining = document.querySelectorAll('.whishlist-wrapper');
                                if (remaining.length === 0) {
                                    const grid = document.querySelector('.whistlist-grid');
                                    if (grid) {
                                        grid.innerHTML = '<div class="col-12"><div class="announcement-inner text-center p-5"><h2>No saved listings yet</h2><p class="mb-0">Browse listings and tap the heart icon to save them here.</p></div></div>';
                                    }
                                }
                            }, 350);
                        } else if (data.success && data.action === 'added') {
                            btn.classList.add('is-saved');
                        } else {
                            alert(data.message || 'Something went wrong.');
                        }
                    })
                    .catch(error => {
                        console.error(error);
                        alert('Unable to update wishlist right now.');
                    });
            });
        });
    });
</script>

@endsection