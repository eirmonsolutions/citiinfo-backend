@extends('layouts.superadmin')

@section('title', 'Home Page')

@section('content')






<section class="main-top-progress-bar sticky-progess-bar">
    <div class="top-progress-bar">
        <div class="container">
            <div class="progess-area-list ">
                <div class="progess-box active">
                    <div class="step-circle">1</div>
                    <span class="step-label">Basic Info</span>
                </div>
                <hr>
                <div class="progess-box">
                    <div class="step-circle">2</div>
                    <span class="step-label">Contact Info</span>
                </div>
                <hr>
                <div class="progess-box">
                    <div class="step-circle">3</div>
                    <span class="step-label">Hours</span>
                </div>
                <hr>
                <div class="progess-box">
                    <div class="step-circle">4</div>
                    <span class="step-label">Services</span>
                </div>
                <hr>
                <div class="progess-box">
                    <div class="step-circle">5</div>
                    <span class="step-label">Media</span>
                </div>
                <hr>
                <div class="progess-box">
                    <div class="step-circle">6</div>
                    <span class="step-label">Review</span>
                </div>
            </div>
        </div>
    </div>

    <div class="step-area">
        <div class="container">

            <form action="{{ route('superadmin.listing.update', $listing->id) }}" method="POST" enctype="multipart/form-data" class="row">
                @csrf
                @method('PUT')

                @php
                $primaryContact = $listing->contacts->first();

                // ✅ videos() relation
                $video = $listing->videos->first();

                // ✅ socialLinks() relation
                $social = $listing->socialLinks->keyBy('platform');

                // ✅ hours()
                $hoursMap = $listing->hours->keyBy('day_of_week');

                // ✅ services()
                $services = $listing->services ?? collect();

                // ✅ features() -> BusinessFeature rows
                $bizFeatures = $listing->features ?? collect();

                $featureIdsCsv = $bizFeatures->pluck('feature_id')->filter()->implode(',');
                $featureNamesCsv = $bizFeatures->pluck('feature_name')->filter()->implode(',');
                $featureIconsCsv = $bizFeatures->pluck('feature_icon')->filter()->implode(',');
                @endphp



                {{-- ===================== STEP 1 ===================== --}}
                <div class="form-step active" data-step="1">
                    <div class="row">
                        <h2>Basic Information</h2>
                        <div class="col-lg-7">
                            <div class="form-step-inner">

                                <div class="form-grid">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="business_name" class="form-label">Business Name <span class="required">*</span></label>
                                                <input type="text" id="business_name" name="business_name"
                                                    value="{{ old('business_name', $listing->business_name) }}"
                                                    placeholder="Enter your business name">
                                                <div class="error-message"></div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Category <span class="required">*</span></label>

                                                <div class="custom-select" data-select id="categorySelect">
                                                    <button type="button" class="select-trigger" data-trigger>
                                                        <span class="select-placeholder" data-label>Select a category</span>
                                                        <span class="select-icon">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                <path d="m6 9 6 6 6-6" />
                                                            </svg>
                                                        </span>
                                                    </button>

                                                    <div class="select-panel" data-panel>
                                                        <div class="select-search">
                                                            <input type="text" placeholder="Search..." data-search />
                                                        </div>

                                                        <ul class="select-options" data-options id="categoryOptions">
                                                            @foreach($categories as $cat)
                                                            <li class="select-option"
                                                                data-id="{{ $cat->id }}"
                                                                data-value="{{ $cat->id }}">
                                                                {{ $cat->name }}
                                                            </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>

                                                    {{-- ✅ Prefill hidden --}}
                                                    <input type="hidden" name="category_id" data-hidden
                                                        value="{{ old('category_id', $listing->category_id) }}" />
                                                </div>

                                                <div class="error-message"></div>
                                            </div>
                                        </div>

                                        {{-- COUNTRY --}}
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label">Country <span class="required">*</span></label>
                                                <div class="custom-select" data-select id="countrySelect">
                                                    <button type="button" class="select-trigger" data-trigger>
                                                        <span class="select-placeholder" data-label>Select your country</span>
                                                        <span class="select-icon">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                <path d="m6 9 6 6 6-6" />
                                                            </svg>
                                                        </span>
                                                    </button>

                                                    <div class="select-panel" data-panel>
                                                        <div class="select-search">
                                                            <input type="text" placeholder="Search..." data-search />
                                                        </div>

                                                        <ul class="select-options" data-options>
                                                            @foreach($countries as $country)
                                                            <li class="select-option"
                                                                data-id="{{ $country->id }}">
                                                                {{ $country->name }}
                                                            </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>

                                                    <input type="hidden" name="country_id" data-hidden
                                                        value="{{ old('country_id', $listing->country) }}" />
                                                </div>
                                            </div>
                                        </div>

                                        {{-- STATE --}}
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label">State <span class="required">*</span></label>
                                                <div class="custom-select" data-select id="stateSelect">
                                                    <button type="button" class="select-trigger" data-trigger>
                                                        <span class="select-placeholder" data-label>Select your state</span>
                                                        <span class="select-icon">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                <path d="m6 9 6 6 6-6" />
                                                            </svg>
                                                        </span>
                                                    </button>

                                                    <div class="select-panel" data-panel>
                                                        <div class="select-search">
                                                            <input type="text" placeholder="Search..." data-search />
                                                        </div>

                                                        <ul class="select-options" data-options id="stateOptions"></ul>
                                                    </div>

                                                    <input type="hidden" name="state_id" data-hidden
                                                        value="{{ old('state_id', $listing->state) }}" />
                                                </div>
                                            </div>
                                        </div>

                                        {{-- CITY --}}
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label">City <span class="required">*</span></label>
                                                <div class="custom-select" data-select id="citySelect">
                                                    <button type="button" class="select-trigger" data-trigger>
                                                        <span class="select-placeholder" data-label>Select your city</span>
                                                        <span class="select-icon">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                <path d="m6 9 6 6 6-6" />
                                                            </svg>
                                                        </span>
                                                    </button>

                                                    <div class="select-panel" data-panel>
                                                        <div class="select-search">
                                                            <input type="text" placeholder="Search..." data-search />
                                                        </div>

                                                        <ul class="select-options" data-options id="cityOptions"></ul>
                                                    </div>

                                                    <input type="hidden" name="city_id" data-hidden
                                                        value="{{ old('city_id', $listing->city) }}" />
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Address --}}
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="full_address" class="form-label">
                                                    Full Address <span class="required">*</span>
                                                </label>
                                                <textarea id="full_address" name="full_address"
                                                    class="form-control textarea-field"
                                                    placeholder="Enter full business address"
                                                    rows="3">{{ old('full_address', $listing->address) }}</textarea>
                                                <div class="error-message"></div>
                                            </div>
                                        </div>

                                        {{-- Description --}}
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="business_description" class="form-label">
                                                    Business Description <span class="required">*</span>
                                                </label>
                                                <textarea id="business_description" name="business_description"
                                                    class="form-control textarea-field"
                                                    placeholder="Describe your business, services, and specialties"
                                                    rows="4">{{ old('business_description', $listing->description) }}</textarea>
                                                <div class="error-message"></div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>

                        {{-- LOGO --}}
                        <div class="col-lg-5">
                            <div class="bussiness-logo">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="business_logo" class="form-label">Business Logo <span class="required">*</span></label>
                                            <div class="category-img-upload" id="business_logo">
                                                <div class="upload-area">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-cloud-upload-icon lucide-cloud-upload">
                                                        <path d="M12 13v8" />
                                                        <path d="M4 14.899A7 7 0 1 1 15.71 8h1.79a4.5 4.5 0 0 1 2.5 8.242" />
                                                        <path d="m8 17 4-4 4 4" />
                                                    </svg>
                                                    <p class="upload-text">Drop logo here or click</p>
                                                </div>
                                                <input type="file" id="logoFile" name="business_logo" accept="image/*" hidden="">
                                            </div>
                                            <div class="error-message"></div>
                                        </div>

                                        <div class="logo-preview" id="logoPreview" style="display:none;">
                                            <img id="logoImage" src="" alt="Logo">
                                            <button type="button" class="remove-btn" id="removeLogo">Remove</button>
                                        </div>

                                        {{-- ✅ existing logo path for JS --}}
                                        <input type="hidden" id="existingLogoPath" value="{{ $listing->logo ? asset('storage/'.$listing->logo) : '' }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>


                {{-- ===================== STEP 2 ===================== --}}
                <div class="form-step" data-step="2">
                    <div class="row">
                        <h2>Contact Information</h2>
                        <div class="col-lg-12">
                            <div class="form-step-inner">
                                <div class="form-grid">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label" for="contact_name">Your Name <span class="required">*</span></label>
                                                <input type="text" id="contact_name" name="contact_name"
                                                    value="{{ old('contact_name', $primaryContact->contact_name ?? '') }}"
                                                    placeholder="John Doe">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label" for="phone">Phone <span class="required">*</span></label>
                                                <input type="tel" id="phone" name="phone"
                                                    value="{{ old('phone', $primaryContact->phone ?? '') }}"
                                                    placeholder="(555) 123-4567">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label" for="email">Email <span class="required">*</span></label>
                                                <input type="email" id="email" name="email"
                                                    value="{{ old('email', $primaryContact->email ?? '') }}"
                                                    placeholder="business@example.com">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label" for="website">Website</label>
                                                <input type="url" id="website" name="website"
                                                    value="{{ old('website', $primaryContact->website ?? '') }}"
                                                    placeholder="https://yoursite.com">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label" for="alternate">Alternate Phone</label>
                                                <input type="tel" id="alternate" name="alternate_phone"
                                                    value="{{ old('alternate_phone', $primaryContact->alternate_phone ?? '') }}"
                                                    placeholder="(555) 987-6543">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Social --}}
                    <div class="row">
                        <h2>Social Media Links</h2>
                        <div class="col-lg-12">
                            <div class="form-step-inner">
                                <div class="form-grid">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="facebook" class="form-label">Facebook</label>
                                                <input type="url" id="facebook" name="facebook"
                                                    value="{{ old('facebook', $social['facebook']->url ?? '') }}"
                                                    placeholder="https://facebook.com/yourbusiness">
                                                <div class="error-message"></div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="instagram" class="form-label">Instagram</label>
                                                <input type="url" id="instagram" name="instagram"
                                                    value="{{ old('instagram', $social['instagram']->url ?? '') }}"
                                                    placeholder="https://instagram.com/yourbusiness">
                                                <div class="error-message"></div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="youtube" class="form-label">Youtube</label>
                                                <input type="url" id="youtube" name="youtube"
                                                    value="{{ old('youtube', $social['youtube']->url ?? '') }}"
                                                    placeholder="https://youtube.com/yourbusiness">
                                                <div class="error-message"></div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="twitter" class="form-label">Twitter</label>
                                                <input type="url" id="twitter" name="twitter"
                                                    value="{{ old('twitter', $social['twitter']->url ?? '') }}"
                                                    placeholder="https://twitter.com/yourbusiness">
                                                <div class="error-message"></div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="linkedin" class="form-label">LinkedIn</label>
                                                <input type="url" id="linkedin" name="linkedin"
                                                    value="{{ old('linkedin', $social['linkedin']->url ?? '') }}"
                                                    placeholder="https://linkedin.com/company/yourbusiness">
                                                <div class="error-message"></div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="snapchat" class="form-label">Snapchat</label>
                                                <input type="url" id="snapchat" name="snapchat"
                                                    value="{{ old('snapchat', $social['snapchat']->url ?? '') }}"
                                                    placeholder="https://snapchat.com/yourbusiness">
                                                <div class="error-message"></div>
                                            </div>
                                        </div>
                                    </div> {{-- row --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                {{-- ===================== STEP 3 (HOURS) ===================== --}}
                <div class="form-step" data-step="3">
                    <div class="row">
                        <h2>Add working hours</h2>

                        <div class="col-lg-12">
                            <div class="working-hours-card">
                                @php
                                $daysLabel = [
                                'monday' => 'Monday',
                                'tuesday' => 'Tuesday',
                                'wednesday' => 'Wednesday',
                                'thursday' => 'Thursday',
                                'friday' => 'Friday',
                                'saturday' => 'Saturday',
                                'sunday' => 'Sunday',
                                ];
                                @endphp

                                @foreach($daysLabel as $dayKey => $dayTitle)
                                @php
                                $h = $hoursMap[$dayKey] ?? null;

                                // if no record => closed by default (your existing logic)
                                $isClosed = $h ? (int) $h->is_closed : 1;

                                // lunch enabled if break times exist (or old values exist)
                                $oldLunchStart = old("hours.$dayKey.lunch_start");
                                $oldLunchEnd = old("hours.$dayKey.lunch_end");

                                $lunchStartVal = old("hours.$dayKey.lunch_start", $h->break_start ?? '');
                                $lunchEndVal = old("hours.$dayKey.lunch_end", $h->break_end ?? '');

                                $hasLunch = (!empty($lunchStartVal) || !empty($lunchEndVal));
                                @endphp

                                <div class="day-row {{ $isClosed ? 'is-closed' : '' }}" data-day="{{ $dayKey }}">
                                    <div class="day-flex">
                                        <label class="switch">
                                            {{-- Open = checked (same as create). Closed = unchecked --}}
                                            <input type="checkbox" class="day-toggle" {{ $isClosed ? '' : 'checked' }}>
                                            <span class="slider"></span>
                                        </label>
                                        <div class="day-name">{{ $dayTitle }}</div>
                                    </div>

                                    <div class="time-wrap">
                                        <div class="day-flex">
                                            <div class="time-box">
                                                <input type="time"
                                                    name="hours[{{ $dayKey }}][start]"
                                                    value="{{ old('hours.' . $dayKey . '.start', $h->open_time ?? '') }}">
                                            </div>

                                            <div class="to-text">to</div>

                                            <div class="time-box">
                                                <input type="time"
                                                    name="hours[{{ $dayKey }}][end]"
                                                    value="{{ old('hours.' . $dayKey . '.end', $h->close_time ?? '') }}">
                                            </div>
                                        </div>

                                        <div class="lunch-toggle-row">
                                            <label class="switch">
                                                {{-- Lunch checkbox checked if lunch values exist --}}
                                                <input type="checkbox" class="lunch-toggle" {{ $hasLunch ? 'checked' : '' }}>
                                                <span class="slider"></span>
                                            </label>
                                            <span class="lunch-toggle-text">Lunch</span>
                                        </div>

                                        <div class="lunch-wrap" style="{{ $hasLunch ? '' : 'display:none;' }}">
                                            <div class="day-flex">
                                                <div class="time-box">
                                                    <input type="time"
                                                        name="hours[{{ $dayKey }}][lunch_start]"
                                                        value="{{ $lunchStartVal }}">
                                                </div>

                                                <div class="to-text">to</div>

                                                <div class="time-box">
                                                    <input type="time"
                                                        name="hours[{{ $dayKey }}][lunch_end]"
                                                        value="{{ $lunchEndVal }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="closed-text {{ $isClosed ? '' : 'd-none' }}">Closed</div>
                                </div>
                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>



                {{-- ===================== STEP 4 (SERVICES + FEATURES) ===================== --}}
                <div class="form-step" data-step="4">
                    <div class="row">
                        <div class="col-lg-8">
                            <h2>Services Offered</h2>

                            <div id="servicesWrap" class="so-wrap">
                                @forelse($services as $i => $srv)
                                <div class="service-card service-row">
                                    <div class="service-grid">
                                        <div class="fg">
                                            <label>Service Name</label>
                                            <input type="text" name="services[{{ $i }}][name]"
                                                value="{{ old("services.$i.name", $srv->name) }}"
                                                placeholder="e.g., Haircut">
                                        </div>
                                        <div class="fg">
                                            <label>Price</label>
                                            <input type="text" name="services[{{ $i }}][price]"
                                                value="{{ old("services.$i.price", $srv->price) }}"
                                                placeholder="e.g., $25">
                                        </div>
                                        <div class="fg">
                                            <label>Duration (mins)</label>
                                            <input type="number" name="services[{{ $i }}][duration]"
                                                value="{{ old("services.$i.duration", $srv->duration_minutes) }}"
                                                min="0">
                                        </div>

                                        <button type="button" class="delete-service" title="Remove">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="red" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M3 6h18" />
                                                <path d="M8 6V4h8v2" />
                                                <path d="M19 6l-1 14H6L5 6" />
                                                <path d="M10 11v6" />
                                                <path d="M14 11v6" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                @empty
                                {{-- fallback 1 blank row --}}
                                <div class="service-card service-row">
                                    <div class="service-grid">
                                        <div class="fg">
                                            <label>Service Name</label>
                                            <input type="text" name="services[0][name]" placeholder="e.g., Haircut">
                                        </div>
                                        <div class="fg">
                                            <label>Price</label>
                                            <input type="text" name="services[0][price]" placeholder="e.g., $25">
                                        </div>
                                        <div class="fg">
                                            <label>Duration (mins)</label>
                                            <input type="number" name="services[0][duration]" value="30" min="0">
                                        </div>

                                        <button type="button" class="delete-service" title="Remove">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="red" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M3 6h18" />
                                                <path d="M8 6V4h8v2" />
                                                <path d="M19 6l-1 14H6L5 6" />
                                                <path d="M10 11v6" />
                                                <path d="M14 11v6" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                @endforelse
                            </div>

                            <button type="button" id="addServiceBtn" class="add-service-btn">
                                <span class="plus">＋</span> Add Service
                            </button>
                        </div>

                        <div class="col-lg-4">
                            <h2>Features</h2>

                            <div class="features-card">
                                <div class="features-grid" id="featuresGrid">
                                    @foreach($features as $f)
                                    <button type="button"
                                        class="feature-tile"
                                        data-id="{{ $f->id }}"
                                        data-name="{{ $f->name }}"
                                        data-icon-image="{{ $f->icon_image }}">
                                        <span class="ft-icon">
                                            @if(!empty($f->icon_image))
                                            <img
                                                src="{{ asset('storage/'.$f->icon_image) }}"
                                                alt="{{ $f->name }}"
                                                style="height:30px;width:40px;object-fit:contain;">
                                            @else
                                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <path d="M12 8v8M8 12h8"></path>
                                            </svg>
                                            @endif
                                        </span>

                                        <span class="ft-text">{{ $f->name }}</span>
                                    </button>
                                    @endforeach
                                </div>

                                <div class="features-divider"></div>

                                <div class="selected-head">
                                    <div class="selected-title">SELECTED (<span id="selectedCount">0</span>)</div>
                                </div>

                                <div class="selected-chips" id="selectedChips"></div>

                                <div id="featuresHiddenWrap"></div>

                                {{-- ✅ Prefill for edit (CSV) --}}
                                <input type="hidden" id="featureIDHidden" name="feature_id"
                                    value="{{ old('feature_id', $featureIdsCsv ?? '') }}">

                                <input type="hidden" id="featuresHidden" name="features"
                                    value="{{ old('features', $featureNamesCsv ?? '') }}">

                                <input type="hidden" id="featureImagesHidden" name="feature_images"
                                    value="{{ old('feature_images', $featureImagesCsv ?? '') }}">
                            </div>
                        </div>


                    </div>
                </div>


                {{-- ===================== STEP 5 (MEDIA) ===================== --}}
                <div class="form-step" data-step="5">
                    <h2 class="step-title">Media</h2>

                    <div class="row g-4">
                        <!-- Business Gallery -->
                        <div class="col-lg-6">
                            <div class="media-card">
                                <div class="media-card-head">
                                    <div class="media-icon">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <rect x="3" y="3" width="18" height="18" rx="3"></rect>
                                            <circle cx="9" cy="9" r="2"></circle>
                                            <path d="M21 15l-5-5L5 21"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="media-title">Business Gallery</div>
                                        <div class="media-subtitle">Professional Photos</div>
                                    </div>
                                </div>

                                <label class="upload-box" for="business_gallery">
                                    <div class="upload-inner">
                                        <div class="upload-circle">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M12 3v12"></path>
                                                <path d="M7 8l5-5 5 5"></path>
                                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                            </svg>
                                        </div>

                                        <div class="upload-title">Upload Your Photos</div>
                                        <div class="upload-hint">Drag and drop multiple images or click to browse</div>
                                        <div class="upload-meta">PNG, JPG up to 10MB each (max 20 images)</div>

                                        <div class="upload-btn">Choose Images</div>
                                    </div>

                                    <input
                                        id="business_gallery"
                                        name="business_gallery[]"
                                        type="file"
                                        accept="image/*"
                                        multiple
                                        hidden />
                                </label>
                            </div>
                        </div>

                        <!-- YouTube Video -->
                        <div class="col-lg-6">
                            <div class="media-card">
                                <div class="media-card-head">
                                    <div class="media-icon">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <rect x="3" y="5" width="18" height="14" rx="3"></rect>
                                            <path d="M10 9l6 3-6 3V9z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="media-title">YouTube Video</div>
                                        <div class="media-subtitle">Promotional Content</div>
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label">Video Link / Embed Code</label>
                                    <input
                                        type="text"
                                        class="form-control media-input"
                                        name="youtube_video"
                                        id="youtube_video"
                                        placeholder="https://youtu.be/xxxx or iframe embed code"
                                        value="{{ old('youtube_video', $video->video_link_url ?? '') }}" />
                                </div>

                                <div class="video-preview-wrap">
                                    <div class="video-preview-title">VIDEO PREVIEW</div>

                                    <div class="video-preview {{ old('youtube_video', $video->video_link_url ?? '') ? 'd-none' : '' }}" id="videoPreviewEmpty">
                                        <div class="video-preview-icon">
                                            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M8 5v14l11-7L8 5z"></path>
                                            </svg>
                                        </div>
                                        <div class="video-preview-text">Video preview will appear here</div>
                                    </div>

                                    <div class="ratio ratio-16x9 {{ old('youtube_video', $video->video_link_url ?? '') ? '' : 'd-none' }}" id="videoPreviewFrame"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Gallery Preview -->
                        <div class="col-12">
                            <div class="media-card">
                                <div class="gallery-head">
                                    <div>
                                        <div class="media-title">Gallery Preview</div>
                                        <div class="media-subtitle" id="galleryCountText">
                                            {{ ($listing->gallery?->count() ?? 0) }} photos ready to showcase
                                        </div>
                                    </div>
                                </div>

                                <div class="gallery-strip thumb-row" id="galleryPreview">
                                    {{-- Existing thumbnails (edit) --}}
                                    @foreach($listing->gallery as $img)
                                    <div class="gallery-item thumb" data-id="{{ $img->id }}">
                                        <img src="{{ asset('storage/'.$img->image_path) }}" class="gallery-thumb" alt="">
                                        <button type="button" class="gallery-remove-btn" title="Remove">×</button>
                                    </div>
                                    @endforeach
                                </div>
                                <input type="hidden" name="deleted_gallery_ids" id="deletedGalleryIds">
                            </div>
                        </div>
                    </div>
                </div>



                {{-- ===================== STEP 6 ===================== --}}
                @php
                $oldOpt = old('listing_option', $listing->listing_option ?? 'premium'); // default premium
                @endphp

                <div class="form-step" data-step="6">
                    <h2>Review</h2>

                    <div class="review-wrap">

                        <!-- 1) Basic Information -->
                        <div class="review-card theme-basic">
                            <div class="review-head" data-bs-toggle="collapse" data-bs-target="#revBasic" aria-expanded="true">
                                <div class="review-title">
                                    <span class="review-ico">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 1 1 18 0Z"></path>
                                            <circle cx="12" cy="10" r="3"></circle>
                                        </svg>
                                    </span>
                                    <span>Basic Information</span>
                                </div>

                                <div class="review-actions">
                                    <span class="chev">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="m6 9 6 6 6-6" />
                                        </svg>
                                    </span>
                                </div>
                            </div>

                            <div id="revBasic" class="collapse show">
                                <div class="review-body">
                                    <div class="review-grid">
                                        <div class="review-item">
                                            <div class="lbl">Business Name</div>
                                            <div class="val" id="rv_business_name">—</div>
                                        </div>

                                        <div class="review-item">
                                            <div class="lbl">Business Logo</div>
                                            <div class="val" id="rv_business_logo">—</div>
                                        </div>

                                        <div class="review-item">
                                            <div class="lbl">Category</div>
                                            <div class="val" id="rv_category">—</div>
                                        </div>

                                        <div class="review-item">
                                            <div class="lbl">Country</div>
                                            <div class="val" id="rv_country">—</div>
                                        </div>

                                        <div class="review-item">
                                            <div class="lbl">State</div>
                                            <div class="val" id="rv_state">—</div>
                                        </div>

                                        <div class="review-item">
                                            <div class="lbl">City</div>
                                            <div class="val" id="rv_city">—</div>
                                        </div>

                                        <div class="review-item full">
                                            <div class="lbl">Address</div>
                                            <div class="val" id="rv_address">—</div>
                                        </div>

                                        <div class="review-item full">
                                            <div class="lbl">Description</div>
                                            <div class="val" id="rv_description">—</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 2) Contact Information -->
                        <div class="review-card theme-contact">
                            <div class="review-head collapsed" data-bs-toggle="collapse" data-bs-target="#revContact" aria-expanded="false">
                                <div class="review-title">
                                    <span class="review-ico">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M20.59 13.41 11 3H4v7l9.59 9.59a2 2 0 0 0 2.82 0l4.18-4.18a2 2 0 0 0 0-2.82Z"></path>
                                            <circle cx="7.5" cy="7.5" r="1.5"></circle>
                                        </svg>
                                    </span>
                                    <span>Contact Information</span>
                                </div>

                                <div class="review-actions">
                                    <span class="chev">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="m6 9 6 6 6-6" />
                                        </svg>
                                    </span>
                                </div>
                            </div>

                            <div id="revContact" class="collapse">
                                <div class="review-body">
                                    <div class="review-grid">
                                        <div class="review-item">
                                            <div class="lbl">Your Name</div>
                                            <div class="val" id="rv_contact_name">—</div>
                                        </div>
                                        <div class="review-item">
                                            <div class="lbl">Phone</div>
                                            <div class="val" id="rv_phone">—</div>
                                        </div>
                                        <div class="review-item">
                                            <div class="lbl">Email</div>
                                            <div class="val" id="rv_email">—</div>
                                        </div>
                                        <div class="review-item">
                                            <div class="lbl">Website</div>
                                            <div class="val" id="rv_website">—</div>
                                        </div>
                                        <div class="review-item">
                                            <div class="lbl">Alternate Phone</div>
                                            <div class="val" id="rv_alt_phone">—</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 3) Business Hours -->
                        <div class="review-card theme-hours">
                            <div class="review-head collapsed" data-bs-toggle="collapse" data-bs-target="#revHours" aria-expanded="false">
                                <div class="review-title">
                                    <span class="review-ico">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <path d="M12 6v6l4 2"></path>
                                        </svg>
                                    </span>
                                    <span>Business Hours</span>
                                </div>

                                <div class="review-actions">
                                    <span class="chev">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="m6 9 6 6 6-6" />
                                        </svg>
                                    </span>
                                </div>
                            </div>

                            <div id="revHours" class="collapse">
                                <div class="review-body">
                                    <div class="hours-table" id="rv_hours_table">
                                        <div class="hours-row">
                                            <div class="d">Monday</div>
                                            <div class="t">—</div>
                                        </div>
                                        <div class="hours-row">
                                            <div class="d">Tuesday</div>
                                            <div class="t">—</div>
                                        </div>
                                        <div class="hours-row">
                                            <div class="d">Wednesday</div>
                                            <div class="t">—</div>
                                        </div>
                                        <div class="hours-row">
                                            <div class="d">Thursday</div>
                                            <div class="t">—</div>
                                        </div>
                                        <div class="hours-row">
                                            <div class="d">Friday</div>
                                            <div class="t">—</div>
                                        </div>
                                        <div class="hours-row">
                                            <div class="d">Saturday</div>
                                            <div class="t">—</div>
                                        </div>
                                        <div class="hours-row">
                                            <div class="d">Sunday</div>
                                            <div class="t">—</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 4) Services & Pricing -->
                        <div class="review-card theme-services">
                            <div class="review-head collapsed" data-bs-toggle="collapse" data-bs-target="#revServices" aria-expanded="false">
                                <div class="review-title">
                                    <span class="review-ico">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M13 2 3 14h9l-1 8 10-12h-9l1-8z"></path>
                                        </svg>
                                    </span>
                                    <span>Services &amp; Pricing</span>
                                </div>

                                <div class="review-actions">
                                    <span class="chev">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="m6 9 6 6 6-6" />
                                        </svg>
                                    </span>
                                </div>
                            </div>

                            <div id="revServices" class="collapse">
                                <div class="review-body">
                                    <div id="rv_services_list" class="muted-sm">No services added.</div>
                                </div>
                            </div>
                        </div>

                        <!-- 5) Features -->
                        <div class="review-card theme-features">
                            <div class="review-head collapsed" data-bs-toggle="collapse" data-bs-target="#revFeatures" aria-expanded="false">
                                <div class="review-title">
                                    <span class="review-ico">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M20.59 13.41 11 3H4v7l9.59 9.59a2 2 0 0 0 2.82 0l4.18-4.18a2 2 0 0 0 0-2.82Z"></path>
                                            <circle cx="7.5" cy="7.5" r="1.5"></circle>
                                        </svg>
                                    </span>
                                    <span>Features</span>
                                </div>

                                <div class="review-actions">
                                    <span class="chev">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="m6 9 6 6 6-6" />
                                        </svg>
                                    </span>
                                </div>
                            </div>

                            <div id="revFeatures" class="collapse">
                                <div class="review-body">
                                    <div class="chips" id="rv_features_chips">
                                        <span class="muted-sm">No features selected.</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 6) Media -->
                        <div class="review-card theme-media">
                            <div class="review-head collapsed" data-bs-toggle="collapse" data-bs-target="#revMedia" aria-expanded="false">
                                <div class="review-title">
                                    <span class="review-ico">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                            <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                            <path d="m21 15-5-5L5 21"></path>
                                        </svg>
                                    </span>
                                    <span>Media</span>
                                </div>

                                <div class="review-actions">
                                    <span class="chev">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="m6 9 6 6 6-6" />
                                        </svg>
                                    </span>
                                </div>
                            </div>

                            <div id="revMedia" class="collapse">
                                <div class="review-body">
                                    <div class="media-block">
                                        <div class="media-label">YouTube Video</div>
                                        <div class="media-video" id="rv_youtube_box">
                                            <div class="video-empty">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="m22 8-6 4 6 4V8Z"></path>
                                                    <rect x="2" y="6" width="14" height="12" rx="2"></rect>
                                                </svg>
                                            </div>
                                        </div>

                                        <div class="media-label mt-3">Gallery Images (<span id="rv_gallery_count">0</span> images)</div>
                                        <div class="media-thumbs" id="rv_gallery_thumbs"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Terms box -->
                        <div class="terms-box mt-3">
                            <label class="d-flex align-items-start gap-2 m-0">
                                <input type="checkbox" name="agree_terms" id="agree_terms" class="mt-1"
                                    {{ old('agree_terms') ? 'checked' : '' }}>
                                <span class="terms-text">
                                    I agree to the <a href="#" target="_blank">Terms of Service</a> and <a href="#" target="_blank">Privacy Policy</a>.
                                    I confirm that all information provided is accurate and up to date.
                                </span>
                            </label>
                        </div>

                        <!-- Listing Options -->
                        <!-- <div class="listing-card mt-3">
                            <div class="listing-head">Listing Options</div>

                            <label class="opt-card {{ $oldOpt === 'free' ? 'active' : '' }}" id="optFreeWrap">
                                <input type="radio" name="listing_option" value="free" {{ $oldOpt === 'free' ? 'checked' : '' }}>
                                <div class="opt-body">
                                    <div class="opt-title">Free Listing</div>
                                    <div class="opt-sub">Basic listing with standard features</div>
                                </div>
                            </label>

                            <label class="opt-card {{ $oldOpt === 'premium' ? 'active' : '' }}" id="optPremiumWrap">
                                <input type="radio" name="listing_option" value="premium" {{ $oldOpt === 'premium' ? 'checked' : '' }}>
                                <div class="opt-body">
                                    <div class="opt-title">Premium Listing - $29/month</div>
                                    <div class="opt-sub">Enhanced visibility, priority placement, and additional features</div>
                                </div>
                            </label>
                        </div> -->

                    </div>
                </div>



                {{-- FOOTER --}}
                <div class="wizard-footer">
                    <button type="button" class="btn-prev" id="btnPrev">Previous</button>

                    <div class="wizard-center">
                        <span id="stepIndicator">Step 1 of 6</span>
                    </div>

                    <button type="button" class="btn next-btn" id="nextBtn">Next</button>

                    <button type="submit" class="btn submit-btn" id="submitBtn" style="display:none;">
                        Update Listing
                    </button>
                </div>

            </form>

        </div>
    </div>

</section>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const freeWrap = document.getElementById('optFreeWrap');
        const premWrap = document.getElementById('optPremiumWrap');

        function syncOpt() {
            const val = document.querySelector('input[name="listing_option"]:checked')?.value;
            freeWrap?.classList.toggle('active', val === 'free');
            premWrap?.classList.toggle('active', val === 'premium');
        }

        document.querySelectorAll('input[name="listing_option"]').forEach(r => {
            r.addEventListener('change', syncOpt);
        });

        syncOpt();
    });
</script>


<!-- dropdown js category, , country, state, city js -->


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

        // Search filter (per dropdown)
        allSelects.forEach(select => {
            const search = select.querySelector('[data-search]');
            if (!search) return;

            search.addEventListener('click', (e) => e.stopPropagation()); // MAIN FIX
            search.addEventListener('input', () => {
                const q = search.value.toLowerCase().trim();
                select.querySelectorAll('.select-option').forEach(li => {
                    const txt = li.textContent.toLowerCase();
                    li.classList.toggle('is-hidden', q && !txt.includes(q));
                });
            });
        });

        // ========= Country -> State -> City =========
        const countrySelect = document.getElementById('countrySelect');
        const stateSelect = document.getElementById('stateSelect');
        const citySelect = document.getElementById('citySelect');

        const stateOptions = document.getElementById('stateOptions');
        const cityOptions = document.getElementById('cityOptions');

        function setSelectValue(select, text, value) {
            const label = select.querySelector('[data-label]');
            const hidden = select.querySelector('[data-hidden]');
            if (label) label.textContent = text;
            if (hidden) hidden.value = value;
            select.classList.remove('is-open');
        }

        function disableSelect(select, placeholderText, keepHidden = false) {
            select.classList.add('is-disabled');

            // ✅ keepHidden=true => hidden value wipe nahi hogi
            const hidden = select.querySelector('[data-hidden]');
            const currentVal = hidden ? hidden.value : '';

            if (keepHidden) {
                setSelectValue(select, placeholderText, currentVal);
            } else {
                setSelectValue(select, placeholderText, '');
            }

            const optionsWrap = select.querySelector('[data-options]');
            if (optionsWrap) optionsWrap.innerHTML = '';
        }

        function enableSelect(select) {
            select.classList.remove('is-disabled');
        }

        async function loadStates(countryId, selectedStateId = null) {
            disableSelect(stateSelect, 'Loading states...');
            disableSelect(citySelect, 'Select your city'); // city reset

            const res = await fetch("{{ route('get.states') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    country_id: countryId
                })
            });

            const states = await res.json();

            stateOptions.innerHTML = states.map(st =>
                `<li class="select-option" data-id="${st.id}">${st.name}</li>`
            ).join('');

            enableSelect(stateSelect);

            if (selectedStateId) {
                const st = states.find(x => String(x.id) === String(selectedStateId));
                if (st) setSelectValue(stateSelect, st.name, st.id);
                else setSelectValue(stateSelect, 'Select your state', '');
            } else {
                setSelectValue(stateSelect, 'Select your state', '');
            }

            return states;
        }

        async function loadCities(stateId, selectedCityId = null) {
            disableSelect(citySelect, 'Loading cities...');

            const res = await fetch("{{ route('get.cities') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    state_id: stateId
                })
            });

            const cities = await res.json();

            cityOptions.innerHTML = cities.map(ct =>
                `<li class="select-option" data-id="${ct.id}">${ct.name}</li>`
            ).join('');

            enableSelect(citySelect);

            if (selectedCityId) {
                const ct = cities.find(x => String(x.id) === String(selectedCityId));
                if (ct) setSelectValue(citySelect, ct.name, ct.id);
                else setSelectValue(citySelect, 'Select your city', '');
            } else {
                setSelectValue(citySelect, 'Select your city', '');
            }

            return cities;
        }

        // ✅ Initial state: by default state/city disabled
        disableSelect(stateSelect, 'Select your state', true);
        disableSelect(citySelect, 'Select your city', true);

        // ✅ Edit page prefill: DB values se auto load
        const existingCountryId = countrySelect?.querySelector('[data-hidden]')?.value || '';
        const existingStateId = stateSelect?.querySelector('[data-hidden]')?.value || '';
        const existingCityId = citySelect?.querySelector('[data-hidden]')?.value || '';

        (async () => {
            try {
                // agar country selected hai => states load + preselect state
                if (existingCountryId) {
                    await loadStates(existingCountryId, existingStateId);

                    // agar state selected hai => cities load + preselect city
                    if (existingStateId) {
                        await loadCities(existingStateId, existingCityId);
                    } else {
                        disableSelect(citySelect, 'Select your city');
                    }
                } else {
                    // country empty => keep disabled
                    disableSelect(stateSelect, 'Select your state');
                    disableSelect(citySelect, 'Select your city');
                }
            } catch (e) {
                console.error(e);
                disableSelect(stateSelect, 'Select your state');
                disableSelect(citySelect, 'Select your city');
            }
        })();

        // ✅ COUNTRY click => load states
        countrySelect.querySelectorAll('.select-option').forEach(opt => {
            opt.addEventListener('click', async () => {
                const countryId = opt.dataset.id;
                setSelectValue(countrySelect, opt.textContent.trim(), countryId);

                try {
                    await loadStates(countryId, null); // state reset
                } catch (e) {
                    console.error(e);
                    disableSelect(stateSelect, 'Select your state');
                    disableSelect(citySelect, 'Select your city');
                }
            });
        });

        // ✅ STATE click (event delegation)
        stateOptions.addEventListener('click', async (e) => {
            const opt = e.target.closest('.select-option');
            if (!opt) return;

            const stateId = opt.dataset.id;
            setSelectValue(stateSelect, opt.textContent.trim(), stateId);

            try {
                await loadCities(stateId, null); // city reset
            } catch (e) {
                console.error(e);
                disableSelect(citySelect, 'Select your city');
            }
        });

        // ✅ CITY click
        cityOptions.addEventListener('click', (e) => {
            const opt = e.target.closest('.select-option');
            if (!opt) return;

            const cityId = opt.dataset.id;
            setSelectValue(citySelect, opt.textContent.trim(), cityId);
        });

    });
</script>



<!-- logo select box for  -->

<script>
    document.addEventListener('DOMContentLoaded', () => {

        const uploadBox = document.getElementById('business_logo');
        const logoFile = document.getElementById('logoFile');
        const logoPreview = document.getElementById('logoPreview');
        const logoImage = document.getElementById('logoImage');
        const removeLogo = document.getElementById('removeLogo');

        if (uploadBox && logoFile) {

            // Click on upload area => open file picker
            uploadBox.addEventListener('click', () => {
                logoFile.click();
            });

            // When file selected => show preview
            logoFile.addEventListener('change', () => {
                const file = logoFile.files && logoFile.files[0];
                if (!file) return;

                // only image check (optional)
                if (!file.type.startsWith('image/')) {
                    logoFile.value = '';
                    return;
                }

                const url = URL.createObjectURL(file);
                logoImage.src = url;
                logoPreview.style.display = 'block';
            });

            // Remove
            if (removeLogo) {
                removeLogo.addEventListener('click', () => {
                    logoFile.value = '';
                    logoImage.src = '';
                    logoPreview.style.display = 'none';
                });
            }
        }

    });
</script>


<!-- form previous btn, next btn, and top form progress bar js -->

<script>
    document.addEventListener('DOMContentLoaded', () => {

        const formSteps = Array.from(document.querySelectorAll('.form-step'));
        const progressBoxes = Array.from(document.querySelectorAll('.progess-area-list .progess-box'));
        const totalSteps = Math.max(formSteps.length, progressBoxes.length) || 6;

        // ✅ IDs matched with your HTML
        const prevBtn = document.getElementById('btnPrev'); // was prevBtn
        const nextBtn = document.getElementById('nextBtn');
        const submitBtn = document.getElementById('submitBtn');

        // ✅ Your indicator: "Step 1 of 6"
        const stepIndicator = document.getElementById('stepIndicator');

        const tickSVG = `
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
              viewBox="0 0 24 24" fill="none" stroke="currentColor"
              stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
              class="lucide lucide-check-icon lucide-check">
              <path d="M20 6 9 17l-5-5"/>
            </svg>
        `;

        // number labels store
        progressBoxes.forEach((box, idx) => {
            const circle = box.querySelector('.step-circle');
            if (circle) circle.dataset.stepNumber = String(idx + 1);
        });

        let currentStep = 1;

        function clearStepErrors(stepEl) {
            stepEl.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            stepEl.querySelectorAll('.error-message').forEach(em => em.textContent = '');
        }

        function setFieldError(field, message) {
            field.classList.add('is-invalid');
            const fg = field.closest('.form-group');
            if (fg) {
                const em = fg.querySelector('.error-message');
                if (em) em.textContent = message || 'This field is required.';
            }
        }

        function validateCurrentStep() {
            const stepEl = formSteps[currentStep - 1];
            if (!stepEl) return true;

            clearStepErrors(stepEl);

            const requiredFields = Array.from(stepEl.querySelectorAll('[required]'));
            let firstInvalid = null;

            requiredFields.forEach(field => {
                // hidden inputs (custom selects)
                if (field.type === 'hidden') {
                    if (!field.value || String(field.value).trim() === '') {
                        if (!firstInvalid) firstInvalid = field;
                        setFieldError(field, 'Please select a value.');
                    }
                    return;
                }

                if (!field.checkValidity()) {
                    if (!firstInvalid) firstInvalid = field;
                    setFieldError(field, field.validationMessage || 'This field is required.');
                }
            });

            if (firstInvalid) {
                if (firstInvalid.type !== 'hidden') {
                    firstInvalid.focus({
                        preventScroll: true
                    });
                    firstInvalid.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                } else {
                    const fg = firstInvalid.closest('.form-group');
                    if (fg) fg.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }
                return false;
            }

            return true;
        }

        function renderSteps() {
            // show active step
            formSteps.forEach((stepEl, idx) => {
                stepEl.classList.toggle('active', (idx + 1) === currentStep);
            });

            // progress UI
            progressBoxes.forEach((box, idx) => {
                const stepNo = idx + 1;
                const circle = box.querySelector('.step-circle');

                box.classList.remove('active', 'completed');

                if (stepNo < currentStep) {
                    box.classList.add('completed');
                    if (circle) circle.innerHTML = tickSVG;
                } else if (stepNo === currentStep) {
                    box.classList.add('active');
                    if (circle) circle.textContent = circle.dataset.stepNumber || stepNo;
                } else {
                    if (circle) circle.textContent = circle.dataset.stepNumber || stepNo;
                }
            });

            // prev disabled on step 1
            if (prevBtn) prevBtn.disabled = currentStep === 1;

            // last step => hide next, show submit
            const isLast = currentStep === totalSteps;
            if (nextBtn) nextBtn.style.display = isLast ? 'none' : 'inline-flex';
            if (submitBtn) submitBtn.style.display = isLast ? 'inline-flex' : 'none';

            // Step indicator text: "Step X of Y"
            if (stepIndicator) stepIndicator.textContent = `Step ${currentStep} of ${totalSteps}`;
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', () => {
                if (!validateCurrentStep()) return;

                if (currentStep < totalSteps) {
                    currentStep++;
                    renderSteps();
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                }
            });
        }

        if (prevBtn) {
            prevBtn.addEventListener('click', () => {
                if (currentStep > 1) {
                    currentStep--;
                    renderSteps();
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                }
            });
        }

        renderSteps();
    });
</script>

<!-- // ✅ Toggle open/close day rows + disable inputs when closed -->

<script>
    document.querySelectorAll(".day-row").forEach((row) => {
        const dayToggle = row.querySelector(".day-toggle");
        const timeWrap = row.querySelector(".time-wrap");
        const closedText = row.querySelector(".closed-text");

        // Lunch toggle + wrapper
        const lunchToggle = row.querySelector(".lunch-toggle");
        const lunchWrap = row.querySelector(".lunch-wrap");

        // Inputs
        const allTimeInputs = row.querySelectorAll('input[type="time"]');
        const lunchInputs = row.querySelectorAll('input[name*="[lunch_start]"], input[name*="[lunch_end]"]');

        function applyLunchState(dayOpen) {
            if (!lunchToggle || !lunchWrap) return;

            const lunchOn = lunchToggle.checked;

            // show lunch only if day open AND lunch toggle on
            const showLunch = dayOpen && lunchOn;
            lunchWrap.style.display = showLunch ? "flex" : "none";

            // lunch inputs enable only if showLunch
            lunchInputs.forEach(i => i.readOnly = !showLunch);

            // if day closed -> also force hide lunch (optional)
            // (toggle state keep rahega, but UI hide rahegi)
        }

        function applyDayState() {
            const open = dayToggle ? dayToggle.checked : true;

            row.classList.toggle("is-closed", !open);

            if (timeWrap) timeWrap.style.display = open ? "flex" : "none";
            if (closedText) closedText.classList.toggle("d-none", open);

            // all inputs disable when day closed
            allTimeInputs.forEach(i => i.disabled = !open);

            // lunch state (will re-disable lunch if needed)
            applyLunchState(open);
        }

        if (dayToggle) dayToggle.addEventListener("change", applyDayState);

        if (lunchToggle) {
            // default OFF (safe even if HTML me checked na ho)
            lunchToggle.checked = false;

            lunchToggle.addEventListener("change", () => {
                const dayOpen = dayToggle ? dayToggle.checked : true;
                applyLunchState(dayOpen);
            });
        }

        // init
        applyDayState();
    });
</script>


<!-- services step -->



<script>
    // Gallery preview (shows thumbnails like your screenshot)
    const galleryInput = document.getElementById("business_gallery");
    const galleryPreview = document.getElementById("galleryPreview");
    const galleryCountText = document.getElementById("galleryCountText");

    // IMPORTANT: FileList read-only hoti hai, so we keep our own array
    let selectedFiles = [];

    let deletedIds = [];

    function syncInputFiles() {
        // Update the actual input.files using DataTransfer
        const dt = new DataTransfer();
        selectedFiles.forEach(f => dt.items.add(f));
        galleryInput.files = dt.files;
    }

    function renderGallery() {

        // ✅ ONLY remove new preview items, existing DB items ko touch nahi karega
        galleryPreview.querySelectorAll('.gallery-item[data-new="1"]').forEach(el => el.remove());

        // show max 20 like your code
        selectedFiles.slice(0, 20).forEach((file, index) => {
            const url = URL.createObjectURL(file);

            // wrapper
            const wrap = document.createElement("div");
            wrap.className = "gallery-item thumb";
            wrap.setAttribute("data-new", "1"); // ✅ mark as new

            // image
            const img = document.createElement("img");
            img.className = "gallery-thumb";
            img.src = url;
            img.alt = "Gallery Image";

            // remove button
            const btn = document.createElement("button");
            btn.type = "button";
            btn.className = "gallery-remove-btn";
            btn.innerHTML = "&times;"; // ×

            btn.addEventListener("click", () => {
                selectedFiles.splice(index, 1);
                syncInputFiles();
                renderGallery();
                URL.revokeObjectURL(url);
            });

            wrap.appendChild(img);
            wrap.appendChild(btn);
            galleryPreview.appendChild(wrap);
        });

        // ✅ existing + new count show
        const existingCount = galleryPreview.querySelectorAll('.gallery-item:not([data-new="1"])').length;
        galleryCountText.textContent = `${existingCount + selectedFiles.length} photos ready to showcase`;
    }


    // Existing DB images ko remove karne ke liye delegated click
    // (jQuery dependency avoid kiya, taaki handler consistently chale)
    galleryPreview?.addEventListener('click', (e) => {
        const btn = e.target.closest('.gallery-remove-btn');
        if (!btn) return;

        const item = btn.closest('.gallery-item');
        if (!item) return;

        // New upload previews are handled by their own button listener in renderGallery()
        if (item.getAttribute('data-new') === '1') return;

        const id = item.getAttribute('data-id');
        if (id && !deletedIds.includes(id)) {
            deletedIds.push(id);
            const deletedInput = document.getElementById('deletedGalleryIds');
            if (deletedInput) {
                deletedInput.value = deletedIds.join(',');
            }
        }

        item.remove();

        const existingCount = galleryPreview.querySelectorAll('.gallery-item:not([data-new="1"])').length;
        galleryCountText.textContent = `${existingCount + selectedFiles.length} photos ready to showcase`;
    });





    galleryInput?.addEventListener("change", () => {
        const files = Array.from(galleryInput.files || []);
        selectedFiles = files.slice(0, 20);
        syncInputFiles();
        renderGallery();
    });


    // Optional: YouTube preview (basic)
    const ytInput = document.getElementById("youtube_video");
    const emptyBox = document.getElementById("videoPreviewEmpty");
    const frameBox = document.getElementById("videoPreviewFrame");

    function extractYouTubeId(value) {
        if (!value) return null;

        // iframe embed
        const iframeMatch = value.match(/src=["']([^"']+)["']/i);
        if (iframeMatch) value = iframeMatch[1];

        // youtu.be / watch?v=
        const idMatch =
            value.match(/youtu\.be\/([A-Za-z0-9_-]{6,})/) ||
            value.match(/v=([A-Za-z0-9_-]{6,})/) ||
            value.match(/embed\/([A-Za-z0-9_-]{6,})/);

        return idMatch ? idMatch[1] : null;
    }

    ytInput?.addEventListener("input", () => {
        const id = extractYouTubeId(ytInput.value.trim());
        if (!id) {
            frameBox.classList.add("d-none");
            emptyBox.classList.remove("d-none");
            frameBox.innerHTML = "";
            return;
        }

        emptyBox.classList.add("d-none");
        frameBox.classList.remove("d-none");
        frameBox.innerHTML = `<iframe src="https://www.youtube.com/embed/${id}" title="YouTube video"
      allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
      allowfullscreen></iframe>`;
    });
</script>

<script>
    // ---------- Listing option active border ----------
    const optFree = document.getElementById('optFreeWrap');
    const optPremium = document.getElementById('optPremiumWrap');

    function refreshListingUI() {
        const freeChecked = optFree.querySelector('input').checked;
        optFree.classList.toggle('active', freeChecked);
        optPremium.classList.toggle('active', !freeChecked);
    }
    optFree?.addEventListener('click', refreshListingUI);
    optPremium?.addEventListener('click', refreshListingUI);
    refreshListingUI();

    // ---------- Edit button jump (you already have steps) ----------
    function goToStep(stepNumber) {
        // yahan aap apna existing step show wala function call karo
        // Example: showStep(stepNumber)
        // Abhi demo:
        const allSteps = document.querySelectorAll('.form-step');
        allSteps.forEach(s => s.classList.add('d-none'));
        const target = document.querySelector('.form-step[data-step="' + stepNumber + '"]');
        if (target) {
            target.classList.remove('d-none');
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }
    }
</script>

<script>
    function fillReviewFromForm() {
        const setText = (id, val) => {
            const el = document.getElementById(id);
            if (el) el.textContent = (val && String(val).trim()) ? val : '—';
        };

        // Step 1
        const businessName = document.querySelector('[name="business_name"]')?.value || '—';

        // ✅ Custom selects: label text (shown text) yaha se milega
        const categoryLabel = document.querySelector('#categorySelect [data-label]')?.textContent?.trim() || '—';
        const countryLabel = document.querySelector('#countrySelect [data-label]')?.textContent?.trim() || '—';
        const stateLabel = document.querySelector('#stateSelect [data-label]')?.textContent?.trim() || '—';
        const cityLabel = document.querySelector('#citySelect [data-label]')?.textContent?.trim() || '—';

        // ✅ Hidden selected ids
        const categoryId = document.querySelector('[name="category_id"]')?.value || '';
        const countryId = document.querySelector('[name="country_id"]')?.value || '';
        const stateId = document.querySelector('[name="state_id"]')?.value || '';
        const cityId = document.querySelector('[name="city_id"]')?.value || '';



        const address = document.querySelector('[name="full_address"]')?.value || '—';
        const description = document.querySelector('[name="business_description"]')?.value || '—';



        setText('rv_business_name', businessName);
        setText('rv_category', categoryId ? categoryLabel : '—');
        setText('rv_country', countryId ? countryLabel : '—');
        setText('rv_state', stateId ? stateLabel : '—');
        setText('rv_city', cityId ? cityLabel : '—');
        setText('rv_address', address);
        setText('rv_description', description);

        // Step 2: Contact
        setText('rv_contact_name', document.querySelector('[name="contact_name"]')?.value || '—');
        setText('rv_phone', document.querySelector('[name="phone"]')?.value || '—');
        setText('rv_email', document.querySelector('[name="email"]')?.value || '—');
        setText('rv_alt_phone', document.querySelector('[name="alternate_phone"]')?.value || '—');
        setText('rv_website', document.querySelector('[name="website"]')?.value || '—');

        // ✅ Logo in review (aapke preview img se)
        const logoImg = document.getElementById('logoImage');
        const rvLogo = document.getElementById('rv_business_logo');
        if (rvLogo) {
            if (logoImg && logoImg.src && logoImg.src.startsWith('blob:')) {
                rvLogo.innerHTML = `<img src="${logoImg.src}" style="max-width:120px; height:auto; border-radius:8px;" alt="Logo" />`;
            } else {
                rvLogo.textContent = '—';
            }
        }

        // Step 4: Services list
        const rvServices = document.getElementById('rv_services_list');
        if (rvServices) {
            const serviceRows = document.querySelectorAll('#servicesWrap .service-row');
            const items = [];
            serviceRows.forEach((row) => {
                const name = row.querySelector('input[name*="[name]"]')?.value?.trim();
                const price = row.querySelector('input[name*="[price]"]')?.value?.trim();
                const dur = row.querySelector('input[name*="[duration]"]')?.value?.trim();
                if (name || price || dur) items.push({
                    name,
                    price,
                    dur
                });
            });

            if (!items.length) {
                rvServices.textContent = 'No services added.';
            } else {
                rvServices.innerHTML = items.map(s =>
                    `<div class="hours-row">
            <div class="d">${s.name || '—'}</div>
            <div class="t">${s.price || '—'} ${s.dur ? `• ${s.dur} mins` : ''}</div>
          </div>`
                ).join('');
            }
        }

        // ✅ Features Review
        const rvFeatures = document.getElementById('rv_features_chips');
        if (rvFeatures) {
            const activeFeatures = Array.from(document.querySelectorAll('#featuresGrid .feature-tile.active'));

            if (!activeFeatures.length) {
                rvFeatures.innerHTML = `<span class="muted-sm">No features selected.</span>`;
            } else {
                rvFeatures.innerHTML = activeFeatures.map(feature => {
                    const name = feature.dataset.name || '';
                    const icon = feature.dataset.iconImage || '';

                    return `
                        <span class="chip">
                            ${icon ? `<img src="/storage/${icon}" alt="${name}" style="height:18px;width:22px;object-fit:contain;margin-right:6px;vertical-align:middle;">` : ''}
                            <span>${name}</span>
                        </span>
                    `;
                }).join('');
            }
        }

        // Step 5: Gallery thumbs
        const rvThumbs = document.getElementById('rv_gallery_thumbs');
        const galleryPreview = document.getElementById('galleryPreview');
        const rvCount = document.getElementById('rv_gallery_count');
        if (rvThumbs && galleryPreview) {
            rvThumbs.innerHTML = galleryPreview.innerHTML || '';
            const count = galleryPreview.querySelectorAll('img').length;
            if (rvCount) rvCount.textContent = count;
            if (!rvThumbs.innerHTML.trim()) rvThumbs.innerHTML = `<span class="muted-sm">—</span>`;
        }

        // Step 5: Youtube (just show text or embed later)
        const yt = document.getElementById('youtube_video')?.value?.trim();
        const rvYt = document.getElementById('rv_youtube_box');
        if (rvYt) {
            if (!yt) {
                rvYt.innerHTML = `<div class="video-empty">—</div>`;
            } else {
                rvYt.innerHTML = `<div style="font-size:14px; word-break:break-all;">${yt}</div>`;
            }
        }

        fillHoursInReview();
    }

    function fillHoursInReview() {
        const rvTable = document.getElementById('rv_hours_table');
        if (!rvTable) return;

        // review me already Monday..Sunday rows hain (d=day, t=time). :contentReference[oaicite:2]{index=2}
        const rows = rvTable.querySelectorAll('.hours-row');

        rows.forEach((row) => {
            const dayName = (row.querySelector('.d')?.textContent || '').trim().toLowerCase();
            const timeCell = row.querySelector('.t');
            if (!timeCell || !dayName) return;

            // step 3 day-row data-day="monday" etc
            const dayRow = document.querySelector(`.day-row[data-day="${dayName}"]`);
            if (!dayRow) {
                timeCell.textContent = '—';
                return;
            }

            const toggle = dayRow.querySelector('.day-toggle');
            const isOpen = toggle ? toggle.checked : true;

            if (!isOpen) {
                timeCell.textContent = 'Closed';
                return;
            }

            const start = document.querySelector(`input[name="hours[${dayName}][start]"]`)?.value || '';
            const end = document.querySelector(`input[name="hours[${dayName}][end]"]`)?.value || '';

            const lunchStart = document.querySelector(`input[name="hours[${dayName}][lunch_start]"]`)?.value || '';
            const lunchEnd = document.querySelector(`input[name="hours[${dayName}][lunch_end]"]`)?.value || '';
            const lunchOn = dayRow.querySelector('.lunch-toggle')?.checked;

            let txt = '';
            if (start && end) txt += `${start} - ${end}`;
            if (lunchOn && lunchStart && lunchEnd) txt += `  |  Lunch: ${lunchStart} - ${lunchEnd}`;
            timeCell.textContent = txt || '—';

        });
    }
</script>


<script>
    // goToStep already hai aapke project me (Edit buttons me call ho raha hai)
    // बस isme end me yeh add kardo:

    function goToStep(step) {
        document.querySelectorAll('.form-step').forEach(s => s.classList.remove('active'));
        document.querySelector('.form-step[data-step="' + step + '"]')?.classList.add('active');

        // ✅ Step 6 par आते hi review fill
        if (Number(step) === 6) {
            fillReviewFromForm();
        }
    }

    // ✅ Next button se jab step 6 aayega tab bhi fill
    document.getElementById('nextBtn')?.addEventListener('click', () => {
        const active = document.querySelector('.form-step.active');
        const step = Number(active?.getAttribute('data-step') || 1);
        const next = step + 1;

        // (aapka existing step change logic rahe)
        // बस before/after step activate, yeh call ensure:
        if (next === 6) fillReviewFromForm();
    });
</script>



{{-- ===================== EDIT PREFILL + HELPERS JS ===================== --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {

        // ----------------- Custom select helper -----------------
        function setSelectValue(selectId, value, label) {
            const wrap = document.getElementById(selectId);
            if (!wrap) return;
            const hidden = wrap.querySelector('input[data-hidden]');
            const lbl = wrap.querySelector('[data-label]');
            if (hidden) hidden.value = value ?? '';
            if (lbl) lbl.textContent = (label && label.trim()) ? label : lbl.textContent;
        }

        // ✅ Prefill Category label from selected option
        const catHidden = document.querySelector('#categorySelect input[data-hidden]');
        if (catHidden && catHidden.value) {
            const opt = document.querySelector(`#categoryOptions .select-option[data-id="${catHidden.value}"]`);
            if (opt) setSelectValue('categorySelect', catHidden.value, opt.textContent.trim());
        }

        // ✅ Prefill Country label (from list)
        const countryHidden = document.querySelector('#countrySelect input[data-hidden]');
        if (countryHidden && countryHidden.value) {
            const opt = document.querySelector(`#countrySelect .select-option[data-id="${countryHidden.value}"]`);
            if (opt) setSelectValue('countrySelect', countryHidden.value, opt.textContent.trim());
        }

        // ⚠️ State/City options are loaded dynamically in your JS (AJAX),
        // so we keep ids ready. After your stateOptions/cityOptions load,
        // call these lines again (or call `prefillStateCity()`).
        function prefillStateCity() {
            const stateId = document.querySelector('#stateSelect input[data-hidden]')?.value;
            const cityId = document.querySelector('#citySelect input[data-hidden]')?.value;

            if (stateId) {
                const st = document.querySelector(`#stateOptions .select-option[data-id="${stateId}"]`);
                if (st) setSelectValue('stateSelect', stateId, st.textContent.trim());
            }
            if (cityId) {
                const ct = document.querySelector(`#cityOptions .select-option[data-id="${cityId}"]`);
                if (ct) setSelectValue('citySelect', cityId, ct.textContent.trim());
            }
        }

        // Run once (if options already there)
        prefillStateCity();

        // ----------------- Hours toggle UI -----------------
        document.querySelectorAll('.day-row').forEach(row => {
            const toggle = row.querySelector('.day-toggle');
            const timeWrap = row.querySelector('.time-wrap');
            const closedText = row.querySelector('.closed-text');

            function sync() {
                const isOn = toggle.checked;
                row.classList.toggle('is-closed', !isOn);

                if (timeWrap) timeWrap.style.display = isOn ? '' : 'none';
                if (closedText) closedText.classList.toggle('d-none', isOn);

                // Disable inputs when closed (so request doesn't send)
                row.querySelectorAll('input[type="time"]').forEach(inp => {
                    inp.disabled = !isOn;
                });
            }

            if (toggle) {
                toggle.addEventListener('change', sync);
                sync();
            }
        });

        // ----------------- Logo preview -----------------
        const existingLogoPath = document.getElementById('existingLogoPath')?.value;
        const logoPreview = document.getElementById('logoPreview');
        const logoImage = document.getElementById('logoImage');
        const logoFile = document.getElementById('logoFile');
        const removeLogo = document.getElementById('removeLogo');

        if (existingLogoPath) {
            logoPreview.style.display = 'block';
            logoImage.src = existingLogoPath;
        }

        if (logoFile) {
            logoFile.addEventListener('change', (e) => {
                const f = e.target.files?.[0];
                if (!f) return;
                const url = URL.createObjectURL(f);
                logoPreview.style.display = 'block';
                logoImage.src = url;
            });
        }

        if (removeLogo) {
            removeLogo.addEventListener('click', () => {
                if (logoFile) logoFile.value = '';
                if (logoPreview) logoPreview.style.display = 'none';
                if (logoImage) logoImage.src = '';
            });
        }

        // ----------------- Services add/remove -----------------
        const servicesWrap = document.getElementById('servicesWrap');
        const addServiceBtn = document.getElementById('addServiceBtn');

        function currentServiceIndex() {
            return servicesWrap ? servicesWrap.querySelectorAll('.service-row').length : 0;
        }

        if (addServiceBtn && servicesWrap) {
            addServiceBtn.addEventListener('click', () => {
                const i = currentServiceIndex();
                const html = `
                <div class="service-card service-row">
                    <div class="service-grid">
                        <div class="fg">
                            <label>Service Name</label>
                            <input type="text" name="services[${i}][name]" placeholder="e.g., Haircut">
                        </div>
                        <div class="fg">
                            <label>Price</label>
                            <input type="text" name="services[${i}][price]" placeholder="e.g., $25">
                        </div>
                        <div class="fg">
                            <label>Duration (mins)</label>
                            <input type="number" name="services[${i}][duration]" value="30" min="0">
                        </div>
                        <button type="button" class="delete-service" title="Remove">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="red" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 6h18" />
                                <path d="M8 6V4h8v2" />
                                <path d="M19 6l-1 14H6L5 6" />
                                <path d="M10 11v6" />
                                <path d="M14 11v6" />
                            </svg>
                        </button>
                    </div>
                </div>
            `;
                servicesWrap.insertAdjacentHTML('beforeend', html);
            });

            servicesWrap.addEventListener('click', (e) => {
                const btn = e.target.closest('.delete-service');
                if (!btn) return;
                const row = btn.closest('.service-row');
                if (row) row.remove();
            });
        }

        // ----------------- Features preselect + CSV hidden sync (UPDATED for icon_image) -----------------
        const featureIDHidden = document.getElementById('featureIDHidden');
        const featuresHidden = document.getElementById('featuresHidden');
        const featureImagesHidden = document.getElementById('featureImagesHidden');

        function setTileSelected(tile, isSel) {
            tile.classList.toggle('is-selected', isSel);
            tile.setAttribute('aria-pressed', isSel ? 'true' : 'false');
        }

        function updateFeatureCsvFromActive() {
            // ✅ First: sync tiles classes properly
            document.querySelectorAll('.feature-tile').forEach(tile => {
                const isActive = tile.classList.contains('active');
                setTileSelected(tile, isActive); // adds/removes is-selected + aria-pressed
            });

            // ✅ Now pick active list
            const active = Array.from(document.querySelectorAll('.feature-tile.active'));

            const ids = active.map(b => (b.dataset.id || '').trim());
            const names = active.map(b => (b.dataset.name || '').trim());
            const images = active.map(b => (b.dataset.iconImage || '').trim());

            if (featureIDHidden) featureIDHidden.value = ids.join(',');
            if (featuresHidden) featuresHidden.value = names.join(',');
            if (featureImagesHidden) featureImagesHidden.value = images.join(',');

            const count = document.getElementById('selectedCount');
            if (count) count.textContent = String(active.length);

            // Chips (image + name)
            const chipsWrap = document.getElementById('selectedChips');
            if (chipsWrap) {
                chipsWrap.innerHTML = '';
                active.forEach(b => {
                    const chip = document.createElement('div');
                    chip.className = 'chip';

                    const imgPath = (b.dataset.iconImage || '').trim();
                    const name = (b.dataset.name || '').trim();

                    chip.innerHTML = `
                ${imgPath ? `<img src="/storage/${imgPath}" alt="${name}" style="height:18px;width:22px;object-fit:contain;margin-right:6px;vertical-align:middle;">` : ''}
                <span>${name}</span>
            `;
                    chipsWrap.appendChild(chip);
                });
            }
        }

        // ✅ Pre-activate based on saved IDs (edit prefill)
        const existingIds = (featureIDHidden?.value || '')
            .split(',')
            .map(v => v.trim())
            .filter(Boolean);

        if (existingIds.length) {
            document.querySelectorAll('.feature-tile').forEach(btn => {
                if (existingIds.includes((btn.dataset.id || '').trim())) {
                    btn.classList.add('active');
                    setTileSelected(btn, true); // ✅ add is-selected on prefill
                }
            });
        }

        updateFeatureCsvFromActive();

        // Toggle active on click
        document.getElementById('featuresGrid')?.addEventListener('click', (e) => {
            const tile = e.target.closest('.feature-tile');
            if (!tile) return;

            const willSelect = !tile.classList.contains('active');

            tile.classList.toggle('active', willSelect);
            setTileSelected(tile, willSelect); // ✅ keep is-selected in sync

            updateFeatureCsvFromActive();
        });

    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const terms = document.getElementById('agree_terms');
        const submitBtn = document.getElementById('submitBtn');

        if (!terms || !submitBtn) return;

        // helper
        function toggleSubmit() {
            const ok = terms.checked;

            // aapka button hidden hai -> check hoga to show
            submitBtn.style.display = ok ? 'inline-block' : 'none';
            submitBtn.disabled = !ok;

            // optional styling
            submitBtn.style.opacity = ok ? '1' : '.6';
            submitBtn.style.cursor = ok ? 'pointer' : 'not-allowed';
        }

        // initial state
        toggleSubmit();

        // on change
        terms.addEventListener('change', toggleSubmit);

        // extra safety: form submit pe bhi check
        const form = submitBtn.closest('form');
        if (form) {
            form.addEventListener('submit', (e) => {
                if (!terms.checked) {
                    e.preventDefault();
                    alert('Please accept Terms of Service & Privacy Policy to submit.');
                }
            });
        }
    });
</script>


@endsection