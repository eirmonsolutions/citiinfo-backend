@extends('layouts.app')

@section('title', $categoryRow->title ?? $categoryRow->name ?? 'Listings')

@section('meta_description', $categoryRow->description ?? '')

@section('meta_keywords', $categoryRow->keyword ?? '')

@section('content')



<div class="search-bar">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <form action="{{ route('search.redirect') }}" method="POST" autocomplete="off">
          @csrf

          <input type="hidden" name="category_id" id="category_id">

          <div class="banner-form">
            <div class="banner-wrapper">

              <!-- CITY -->
              <div class="banner-form-box zip-form-box position-relative">
                <i class="fi-map-pin"></i>
                <input type="text"
                  name="city"
                  id="city_input"
                  class="form-control form-control-lg form-icon-start"
                  placeholder="City"
                  required>

                <div id="city_suggest" class="suggest-box d-none"></div>
              </div>

              <!-- SERVICE -->
              <div class="banner-form-box position-relative">
                <i class="fi-search"></i>
                <input type="search"
                  id="service_input"
                  class="form-control form-control-lg form-icon-start"
                  placeholder="What service do you need?"
                  required>

                <div id="service_suggest" class="suggest-box d-none"></div>
              </div>

            </div>

            <button type="submit" class="btn btn-lg btn-primary">Search</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>


<div class="breadcrumb-area pb-0">
  <div class="container">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item"><a href="/categories">Category</a></li>
        <li class="breadcrumb-item active" aria-current="page">
          {{ $categoryRow->name ?? $catName ?? 'Listings' }}
        </li>
      </ol>
    </nav>
  </div>
</div>


<div class="listing-search-area">
  <div class="container">
    <h1>
      Popular {{ $categoryRow->name ?? ($catName ?? 'Listings') }} @if(!empty($cityName)) in {{ $cityName }} @endif

    </h1>

    <div class="row">

    </div>
    <div class="filter-area"></div>


    <div class="row">
      <div class="col-md-12 col-lg-12 col-xl-8">
        <div class="listing-here">
          @forelse($listings as $listing)
          <div class="listing-search-area-box">
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

              <!-- <div class="profile-reviews-area">
                <ul>

                </ul>
              </div> -->

              <div class="services-list">
                <ul>
                  @forelse($listing->services as $service)
                  <li>
                    <div class="services-name">{{ $service->name }}</div>

                  </li>
                  @empty
                  <li>
                    <div class="services-name">No services found</div>
                    <div class="services-price"></div>
                  </li>
                  @endforelse
                </ul>
              </div>

              <div class="listing-search-box-social-links">
                <ul>
                  @if(!empty($listing->contacts->first()->phone))
                  <li><a href="tel:{{ $listing->contacts->first()->phone }}" class="call-btn">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-phone-call-icon lucide-phone-call">
                        <path d="M13 2a9 9 0 0 1 9 9" />
                        <path d="M13 6a5 5 0 0 1 5 5" />
                        <path d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.8 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384" />
                      </svg>
                      {{ $listing->contacts->first()->phone }}</a></li>
                  @endif

                  @if(!empty($listing->contacts->first()->phone))
                  <li>
                    <a target="_blank" href="https://wa.me/{{ preg_replace('/\D/', '', $listing->contacts->first()->phone) }}" class="whatsaap-btn">
                      <!-- <span class="icon-img">
                        <img src="/assets/images/whatsapp-img.svg" alt="">
                      </span> -->
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-message-square-icon lucide-message-square">
                        <path d="M22 17a2 2 0 0 1-2 2H6.828a2 2 0 0 0-1.414.586l-2.202 2.202A.71.71 0 0 1 2 21.286V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2z" />
                      </svg>
                      WhatsApp
                    </a>
                  </li>
                  @endif

                  <li>
                    <a href="" class="send-enquiry-btn">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-send-icon lucide-send">
                        <path d="M14.536 21.686a.5.5 0 0 0 .937-.024l6.5-19a.496.496 0 0 0-.635-.635l-19 6.5a.5.5 0 0 0-.024.937l7.93 3.18a2 2 0 0 1 1.112 1.11z" />
                        <path d="m21.854 2.147-10.94 10.939" />
                      </svg>
                      Send Enquiry
                    </a>
                  </li>
                </ul>
              </div>
            </div>

            <div class="wishlist-icon">
              <a href="#">
                <i data-lucide="heart"></i>
              </a>
            </div>
          </div>

          @empty
          <div class="alert alert-warning">
            No listings found for <b>{{ $cityName }}</b>.
          </div>
          @endforelse

          {{-- Pagination --}}
          <div class="mt-3">
            {{ $listings->links() }}
          </div>

        </div>
      </div>
      <div class="col-md-12 col-lg-12 col-xl-4"></div>
    </div>


  </div>



</div>



{{-- ✅ Swiper (only for list view) --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.css" />
<script src="https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.js"></script>

<script>
  document.addEventListener("DOMContentLoaded", function() {
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
  });
</script>



@endsection