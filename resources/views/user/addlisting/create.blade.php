@extends('layouts.user')

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

            <form action="{{ route('user.addlisting.store') }}" method="POST" enctype="multipart/form-data" class="row">
                @csrf

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
                                                <input type="text" id="business_name" name="business_name" placeholder="Enter your business name">
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
                                                            {{-- your svg icon --}}
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
                                                            {{-- if you want only active categories: add ->where('is_active',1) in controller --}}
                                                            <li class="select-option"
                                                                data-id="{{ $cat->id }}"
                                                                data-value="{{ $cat->id }}">
                                                                {{ $cat->name }}
                                                            </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>

                                                    {{-- IMPORTANT: submit this --}}
                                                    <input type="hidden" name="category_id" data-hidden />
                                                </div>

                                                <div class="error-message"></div>
                                            </div>
                                        </div>

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

                                                    <input type="hidden" name="country_id" data-hidden />
                                                </div>
                                            </div>
                                        </div>

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

                                                    <input type="hidden" name="state_id" data-hidden />
                                                </div>
                                            </div>
                                        </div>

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

                                                    <input type="hidden" name="city_id" data-hidden />
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="full_address" class="form-label">
                                                    Full Address <span class="required">*</span>
                                                </label>
                                                <textarea
                                                    id="full_address"
                                                    name="full_address"
                                                    class="form-control textarea-field"
                                                    placeholder="Enter full business address"
                                                    rows="3"></textarea>
                                                <div class="error-message"></div>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="business_description" class="form-label">
                                                    Business Description <span class="required">*</span>
                                                </label>
                                                <textarea
                                                    id="business_description"
                                                    name="business_description"
                                                    class="form-control textarea-field"
                                                    placeholder="Describe your business, services, and specialties"
                                                    rows="4"></textarea>
                                                <div class="error-message"></div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>

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
                                        <div class="logo-preview" id="logoPreview" style="display: none;">
                                            <img id="logoImage" src="" alt="Logo">
                                            <button type="button" class="remove-btn" id="removeLogo">Remove</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

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
                                                <input type="text" id="contact_name" name="contact_name" placeholder="John Doe">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label" for="phone">Phone <span class="required">*</span></label>
                                                <input type="tel" id="phone" name="phone" placeholder="(555) 123-4567">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label" for="email">Email <span class="required">*</span></label>
                                                <input type="email" name="email" class="form-control" value="{{ auth()->user()->email }}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label" for="website">Website</label>
                                                <input type="url" id="website" name="website" placeholder="https://yoursite.com">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label" for="alternate">Alternate Phone</label>
                                                <input type="tel" id="alternate" name="alternate_phone" placeholder="(555) 987-6543">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <h2>Social Media Links</h2>
                        <div class="col-lg-12">
                            <div class="form-step-inner">
                                <div class="form-grid">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="facebook" class="form-label">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#1877f2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-facebook-icon lucide-facebook">
                                                        <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z" />
                                                    </svg>
                                                    Facebook
                                                </label>
                                                <input type="url" id="facebook" name="facebook"
                                                    placeholder="https://facebook.com/yourbusiness">
                                                <div class="error-message"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="instagram" class="form-label">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#e4405f" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-instagram-icon lucide-instagram">
                                                        <rect width="20" height="20" x="2" y="2" rx="5" ry="5" />
                                                        <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z" />
                                                        <line x1="17.5" x2="17.51" y1="6.5" y2="6.5" />
                                                    </svg>
                                                    Instagram
                                                </label>
                                                <input type="url" id="instagram" name="instagram"
                                                    placeholder="https://instagram.com/yourbusiness">
                                                <div class="error-message"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="youtube" class="form-label">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#e4405f" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-youtube-icon lucide-youtube">
                                                        <path d="M2.5 17a24.12 24.12 0 0 1 0-10 2 2 0 0 1 1.4-1.4 49.56 49.56 0 0 1 16.2 0A2 2 0 0 1 21.5 7a24.12 24.12 0 0 1 0 10 2 2 0 0 1-1.4 1.4 49.55 49.55 0 0 1-16.2 0A2 2 0 0 1 2.5 17" />
                                                        <path d="m10 15 5-3-5-3z" />
                                                    </svg>
                                                    Youtube
                                                </label>
                                                <input type="url" id="youtube" name="youtube"
                                                    placeholder="https://youtube.com/yourbusiness">
                                                <div class="error-message"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="twitter" class="form-label">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#1da1f2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-twitter-icon lucide-twitter">
                                                        <path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z" />
                                                    </svg>
                                                    Twitter
                                                </label>
                                                <input type="url" id="twitter" name="twitter"
                                                    placeholder="https://twitter.com/yourbusiness">
                                                <div class="error-message"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="linkedin" class="form-label">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#0077b5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-linkedin-icon lucide-linkedin">
                                                        <path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z" />
                                                        <rect width="4" height="12" x="2" y="9" />
                                                        <circle cx="4" cy="4" r="2" />
                                                    </svg>
                                                    LinkedIn
                                                </label>
                                                <input type="url" id="linkedin" name="linkedin"
                                                    placeholder="https://linkedin.com/company/yourbusiness">
                                                <div class="error-message"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="snapchat" class="form-label">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                                        fill="none" stroke="#fffc00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="lucide lucide-snapchat-icon lucide-snapchat">
                                                        <path d="M12 2c-3.4 0-6 2.6-6 6v2.3c0 .6-.3 1.1-.8 1.4-.6.4-1.3.7-2 .9-.7.2-1.2.7-1.2 1.3 0 .7.7 1.2 1.7 1.6 1.3.5 2.2 1.2 2.8 2.1.4.6 1 .9 1.7.9h1.1c.4 0 .7.2 1 .5.5.5 1.1.8 1.7.8s1.2-.3 1.7-.8c.3-.3.6-.5 1-.5h1.1c.7 0 1.3-.3 1.7-.9.6-.9 1.5-1.6 2.8-2.1 1-.4 1.7-.9 1.7-1.6 0-.6-.5-1.1-1.2-1.3-.7-.2-1.4-.5-2-.9-.5-.3-.8-.8-.8-1.4V8c0-3.4-2.6-6-6-6z" />
                                                    </svg>

                                                    Snapchat
                                                </label>
                                                <input type="url" id="snapchat" name="snapchat"
                                                    placeholder="https://snapchat.com/yourbusiness">
                                                <div class="error-message"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-step" data-step="3">
                    <div class="row">
                        <h2>Add working hours</h2>

                        <div class="col-lg-12">
                            <div class="working-hours-card">

                                <!-- Monday -->
                                <div class="day-row" data-day="monday">
                                    <div class="day-flex">
                                        <label class="switch">
                                            <input type="checkbox" checked class="day-toggle">
                                            <span class="slider"></span>
                                        </label>
                                        <div class="day-name">Monday</div>
                                    </div>

                                    <div class="time-wrap">
                                        <div class="day-flex">
                                            <div class="time-box">
                                                <input type="time" name="hours[monday][start]" value="09:00">
                                            </div>
                                            <div class="to-text">to</div>
                                            <div class="time-box">
                                                <input type="time" name="hours[monday][end]" value="17:00">
                                            </div>
                                        </div>

                                        <div class="lunch-toggle-row">
                                            <label class="switch">
                                                <input type="checkbox" class="lunch-toggle">
                                                <span class="slider"></span>
                                            </label>
                                            <span class="lunch-toggle-text">Lunch</span>
                                        </div>

                                        <div class="lunch-wrap" style="display:none;">
                                            <div class="day-flex">
                                                <div class="time-box">
                                                    <input type="time" name="hours[monday][lunch_start]" value="13:00">
                                                </div>
                                                <div class="to-text">to</div>
                                                <div class="time-box">
                                                    <input type="time" name="hours[monday][lunch_end]" value="14:00">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="closed-text d-none">Closed</div>
                                </div>

                                <!-- Tuesday -->
                                <div class="day-row" data-day="tuesday">
                                    <div class="day-flex">
                                        <label class="switch">
                                            <input type="checkbox" checked class="day-toggle">
                                            <span class="slider"></span>
                                        </label>
                                        <div class="day-name">Tuesday</div>
                                    </div>

                                    <div class="time-wrap">
                                        <div class="day-flex">
                                            <div class="time-box">
                                                <input type="time" name="hours[tuesday][start]" value="09:00">
                                            </div>
                                            <div class="to-text">to</div>
                                            <div class="time-box">
                                                <input type="time" name="hours[tuesday][end]" value="17:00">
                                            </div>
                                        </div>

                                        <div class="lunch-toggle-row">
                                            <label class="switch">
                                                <input type="checkbox" class="lunch-toggle">
                                                <span class="slider"></span>
                                            </label>
                                            <span class="lunch-toggle-text">Lunch</span>
                                        </div>

                                        <div class="lunch-wrap" style="display:none;">
                                            <div class="day-flex">
                                                <div class="time-box">
                                                    <input type="time" name="hours[tuesday][lunch_start]" value="13:00">
                                                </div>
                                                <div class="to-text">to</div>
                                                <div class="time-box">
                                                    <input type="time" name="hours[tuesday][lunch_end]" value="14:00">
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="closed-text d-none">Closed</div>
                                </div>

                                <!-- Wednesday -->
                                <div class="day-row" data-day="wednesday">
                                    <div class="day-flex">
                                        <label class="switch">
                                            <input type="checkbox" checked class="day-toggle">
                                            <span class="slider"></span>
                                        </label>
                                        <div class="day-name">Wednesday</div>
                                    </div>

                                    <div class="time-wrap">
                                        <div class="day-flex">
                                            <div class="time-box">
                                                <input type="time" name="hours[wednesday][start]" value="09:00">
                                            </div>
                                            <div class="to-text">to</div>
                                            <div class="time-box">
                                                <input type="time" name="hours[wednesday][end]" value="17:00">
                                            </div>
                                        </div>

                                        <div class="lunch-toggle-row">
                                            <label class="switch">
                                                <input type="checkbox" class="lunch-toggle">
                                                <span class="slider"></span>
                                            </label>
                                            <span class="lunch-toggle-text">Lunch</span>
                                        </div>

                                        <div class="lunch-wrap" style="display:none;">
                                            <div class="day-flex">
                                                <div class="time-box">
                                                    <input type="time" name="hours[wednesday][lunch_start]" value="13:00">
                                                </div>
                                                <div class="to-text">to</div>
                                                <div class="time-box">
                                                    <input type="time" name="hours[wednesday][lunch_end]" value="14:00">
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="closed-text d-none">Closed</div>
                                </div>

                                <!-- Thursday -->
                                <div class="day-row" data-day="thursday">
                                    <div class="day-flex">
                                        <label class="switch">
                                            <input type="checkbox" checked class="day-toggle">
                                            <span class="slider"></span>
                                        </label>
                                        <div class="day-name">Thursday</div>
                                    </div>

                                    <div class="time-wrap">
                                        <div class="day-flex">
                                            <div class="time-box">
                                                <input type="time" name="hours[thursday][start]" value="11:00">
                                            </div>
                                            <div class="to-text">to</div>
                                            <div class="time-box">
                                                <input type="time" name="hours[thursday][end]" value="16:00">
                                            </div>
                                        </div>

                                        <div class="lunch-toggle-row">
                                            <label class="switch">
                                                <input type="checkbox" class="lunch-toggle">
                                                <span class="slider"></span>
                                            </label>
                                            <span class="lunch-toggle-text">Lunch</span>
                                        </div>

                                        <div class="lunch-wrap" style="display:none;">
                                            <div class="day-flex">
                                                <div class="time-box">
                                                    <input type="time" name="hours[thursday][lunch_start]" value="13:30">
                                                </div>
                                                <div class="to-text">to</div>
                                                <div class="time-box">
                                                    <input type="time" name="hours[thursday][lunch_end]" value="14:00">
                                                </div>
                                            </div>
                                        </div>


                                    </div>

                                    <div class="closed-text d-none">Closed</div>
                                </div>

                                <!-- Friday -->
                                <div class="day-row" data-day="friday">
                                    <div class="day-flex">
                                        <label class="switch">
                                            <input type="checkbox" checked class="day-toggle">
                                            <span class="slider"></span>
                                        </label>
                                        <div class="day-name">Friday</div>
                                    </div>

                                    <div class="time-wrap">
                                        <div class="day-flex">
                                            <div class="time-box">
                                                <input type="time" name="hours[friday][start]" value="11:00">
                                            </div>
                                            <div class="to-text">to</div>
                                            <div class="time-box">
                                                <input type="time" name="hours[friday][end]" value="16:00">
                                            </div>
                                        </div>

                                        <div class="lunch-toggle-row">
                                            <label class="switch">
                                                <input type="checkbox" class="lunch-toggle">
                                                <span class="slider"></span>
                                            </label>
                                            <span class="lunch-toggle-text">Lunch</span>
                                        </div>

                                        <div class="lunch-wrap" style="display:none;">
                                            <div class="day-flex">
                                                <div class="time-box">
                                                    <input type="time" name="hours[friday][lunch_start]" value="13:30">
                                                </div>
                                                <div class="to-text">to</div>
                                                <div class="time-box">
                                                    <input type="time" name="hours[friday][lunch_end]" value="14:00">
                                                </div>
                                            </div>
                                        </div>


                                    </div>

                                    <div class="closed-text d-none">Closed</div>
                                </div>

                                <!-- Saturday (Closed by default) -->
                                <div class="day-row is-closed" data-day="saturday">
                                    <div class="day-flex">
                                        <label class="switch">
                                            <input type="checkbox" class="day-toggle">
                                            <span class="slider"></span>
                                        </label>
                                        <div class="day-name">Saturday</div>
                                    </div>

                                    <div class="time-wrap">
                                        <div class="day-flex">
                                            <div class="time-box">
                                                <input type="time" name="hours[saturday][start]" value="09:00">
                                            </div>
                                            <div class="to-text">to</div>
                                            <div class="time-box">
                                                <input type="time" name="hours[saturday][end]" value="17:00">
                                            </div>
                                        </div>

                                        <div class="lunch-toggle-row">
                                            <label class="switch">
                                                <input type="checkbox" class="lunch-toggle">
                                                <span class="slider"></span>
                                            </label>
                                            <span class="lunch-toggle-text">Lunch</span>
                                        </div>

                                        <div class="lunch-wrap" style="display:none;">
                                            <div class="day-flex">
                                                <div class="time-box">
                                                    <input type="time" name="hours[saturday][lunch_start]" value="13:00">
                                                </div>
                                                <div class="to-text">to</div>
                                                <div class="time-box">
                                                    <input type="time" name="hours[saturday][lunch_end]" value="14:00">
                                                </div>
                                            </div>
                                        </div>


                                    </div>

                                    <div class="closed-text">Closed</div>
                                </div>

                                <!-- Sunday (Closed by default) -->
                                <div class="day-row is-closed" data-day="sunday">
                                    <div class="day-flex">
                                        <label class="switch">
                                            <input type="checkbox" class="day-toggle">
                                            <span class="slider"></span>
                                        </label>
                                        <div class="day-name">Sunday</div>
                                    </div>

                                    <div class="time-wrap">
                                        <div class="day-flex">
                                            <div class="time-box">
                                                <input type="time" name="hours[sunday][start]" value="09:00">
                                            </div>
                                            <div class="to-text">to</div>
                                            <div class="time-box">
                                                <input type="time" name="hours[sunday][end]" value="17:00">
                                            </div>
                                        </div>

                                        <div class="lunch-toggle-row">
                                            <label class="switch">
                                                <input type="checkbox" class="lunch-toggle">
                                                <span class="slider"></span>
                                            </label>
                                            <span class="lunch-toggle-text">Lunch</span>
                                        </div>

                                        <div class="lunch-wrap" style="display:none;">
                                            <div class="day-flex">
                                                <div class="time-box">
                                                    <input type="time" name="hours[sunday][lunch_start]" value="13:00">
                                                </div>
                                                <div class="to-text">to</div>
                                                <div class="time-box">
                                                    <input type="time" name="hours[sunday][lunch_end]" value="14:00">
                                                </div>
                                            </div>
                                        </div>


                                    </div>

                                    <div class="closed-text">Closed</div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-step" data-step="4">
                    <div class="row">
                        <div class="col-lg-8">
                            <h2>Services Offered</h2>

                            <div id="servicesWrap" class="so-wrap">
                                <!-- service row -->
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
                            </div>

                            <button type="button" id="addServiceBtn" class="add-service-btn">
                                <span class="plus">＋</span> Add Service
                            </button>
                        </div>

                        <div class="col-lg-4">
                            <h2>Features</h2>

                            <div class="features-card">
                                <!-- TOP: selectable boxes -->
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
                                            <!-- fallback icon -->
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

                                <!-- BOTTOM: selected chips -->
                                <div class="selected-head">
                                    <div class="selected-title">SELECTED (<span id="selectedCount">0</span>)</div>
                                </div>

                                <div class="selected-chips" id="selectedChips"></div>

                                {{-- hidden inputs --}}
                                <div id="featuresHiddenWrap"></div>
                                <input type="hidden" id="featureIDHidden" name="feature_id" value="">
                                <input type="hidden" id="featuresHidden" name="features" value="">

                                {{-- ✅ old: feature_icons (classes) removed --}}
                                {{-- <input type="hidden" id="featureIconsHidden" name="feature_icons" value=""> --}}

                                {{-- ✅ new: feature_images --}}
                                <input type="hidden" id="featureImagesHidden" name="feature_images" value="">
                            </div>
                        </div>

                    </div>
                </div>

                <div class="form-step" data-step="5">
                    <h2 class="step-title">Media</h2>

                    <div class="row g-4">
                        <!-- Business Gallery -->
                        <div class="col-lg-6">
                            <div class="media-card">
                                <div class="media-card-head">
                                    <div class="media-icon">
                                        <!-- image icon -->
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
                                        <!-- youtube/video icon -->
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
                                        placeholder="https://youtu.be/xxxx or iframe embed code" />
                                </div>

                                <div class="video-preview-wrap">
                                    <div class="video-preview-title">VIDEO PREVIEW</div>

                                    <div class="video-preview" id="videoPreviewEmpty">
                                        <div class="video-preview-icon">
                                            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M8 5v14l11-7L8 5z"></path>
                                            </svg>
                                        </div>
                                        <div class="video-preview-text">Video preview will appear here</div>
                                    </div>

                                    <!-- if you want iframe later, render it into this container -->
                                    <div class="ratio ratio-16x9 d-none" id="videoPreviewFrame"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Gallery Preview -->
                        <div class="col-12">
                            <div class="media-card">
                                <div class="gallery-head">
                                    <div>
                                        <div class="media-title">Gallery Preview</div>
                                        <div class="media-subtitle" id="galleryCountText">0 photos ready to showcase</div>
                                    </div>
                                </div>

                                <div class="gallery-strip thumb-row" id="galleryPreview">
                                    <!-- thumbnails will be injected here -->
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="form-step" data-step="6">
                    <h2>Review</h2>
                    <!-- Step 6 fields here -->

                    <div class="review-wrap">

                        <!-- 1) Basic Information -->
                        <div class="review-card theme-basic">
                            <div class="review-head" data-bs-toggle="collapse" data-bs-target="#revBasic" aria-expanded="true">
                                <div class="review-title">
                                    <span class="review-ico">
                                        <!-- location icon -->
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 1 1 18 0Z"></path>
                                            <circle cx="12" cy="10" r="3"></circle>
                                        </svg>
                                    </span>
                                    <span>Basic Information</span>
                                </div>

                                <div class="review-actions">
                                    <!-- <button type="button" class="btn-edit" onclick="goToStep(1)">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M12 20h9" />
                                            <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z" />
                                        </svg>
                                        Edit
                                    </button> -->

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
                                        <!-- tag icon -->
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M20.59 13.41 11 3H4v7l9.59 9.59a2 2 0 0 0 2.82 0l4.18-4.18a2 2 0 0 0 0-2.82Z"></path>
                                            <circle cx="7.5" cy="7.5" r="1.5"></circle>
                                        </svg>
                                    </span>
                                    <span>Contact Information</span>
                                </div>

                                <div class="review-actions">
                                    <!-- <button type="button" class="btn-edit" onclick="goToStep(2)">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M12 20h9" />
                                            <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z" />
                                        </svg>
                                        Edit
                                    </button> -->
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
                                        <!-- clock -->
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <path d="M12 6v6l4 2"></path>
                                        </svg>
                                    </span>
                                    <span>Business Hours</span>
                                </div>

                                <div class="review-actions">
                                    <!-- <button type="button" class="btn-edit" onclick="goToStep(3)">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M12 20h9" />
                                            <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z" />
                                        </svg>
                                        Edit
                                    </button> -->
                                    <span class="chev">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="m6 9 6 6 6-6" />
                                        </svg>
                                    </span>
                                </div>
                            </div>

                            <div id="revHours" class="collapse">
                                <div class="review-body">
                                    <!-- You can fill these rows dynamically -->
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
                                        <!-- bolt -->
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M13 2 3 14h9l-1 8 10-12h-9l1-8z"></path>
                                        </svg>
                                    </span>
                                    <span>Services &amp; Pricing</span>
                                </div>

                                <div class="review-actions">
                                    <!-- <button type="button" class="btn-edit" onclick="goToStep(4)">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M12 20h9" />
                                            <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z" />
                                        </svg>
                                        Edit
                                    </button> -->
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
                                        <!-- tag -->
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M20.59 13.41 11 3H4v7l9.59 9.59a2 2 0 0 0 2.82 0l4.18-4.18a2 2 0 0 0 0-2.82Z"></path>
                                            <circle cx="7.5" cy="7.5" r="1.5"></circle>
                                        </svg>
                                    </span>
                                    <span>Features</span>
                                </div>

                                <div class="review-actions">
                                    <!-- <button type="button" class="btn-edit" onclick="goToStep(5)">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M12 20h9" />
                                            <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z" />
                                        </svg>
                                        Edit
                                    </button> -->
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
                                        <!-- chips append here -->
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
                                        <!-- image -->
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                            <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                            <path d="m21 15-5-5L5 21"></path>
                                        </svg>
                                    </span>
                                    <span>Media</span>
                                </div>

                                <div class="review-actions">
                                    <!-- <button type="button" class="btn-edit" onclick="goToStep(5)">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M12 20h9" />
                                            <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z" />
                                        </svg>
                                        Edit
                                    </button> -->
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
                                        <div class="media-thumbs" id="rv_gallery_thumbs">
                                            <!-- thumbs append -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Terms box -->
                        <div class="terms-box mt-3">
                            <label class="d-flex align-items-start gap-2 m-0">
                                <input type="checkbox" name="agree_terms" id="agree_terms" class="mt-1">
                                <span class="terms-text">
                                    I agree to the <a href="#" target="_blank">Terms of Service</a> and <a href="#" target="_blank">Privacy Policy</a>.
                                    I confirm that all information provided is accurate and up to date.
                                </span>
                            </label>
                        </div>

                        <!-- Listing Options -->
                        <!-- <div class="listing-card mt-3">
                            <div class="listing-head">Listing Options</div>

                            <label class="opt-card active" id="optFreeWrap">
                                <input type="radio" name="listing_option" value="free">
                                <div class="opt-body">
                                    <div class="opt-title">Free Listing</div>
                                    <div class="opt-sub">Basic listing with standard features</div>
                                </div>
                            </label>

                            <label class="opt-card" id="optPremiumWrap">
                                <input type="radio" name="listing_option" value="premium" checked>
                                <div class="opt-body">
                                    <div class="opt-title">Premium Listing - $29/month</div>
                                    <div class="opt-sub">Enhanced visibility, priority placement, and additional features</div>
                                </div>
                            </label>
                        </div> -->


                    </div>
                </div>

                <div class="wizard-footer">
                    <button type="button" class="btn-prev" id="btnPrev">Previous</button>

                    <div class="wizard-center">
                        <span id="stepIndicator">Step 1 of 6</span>
                    </div>

                    <button type="button" class="btn next-btn" id="nextBtn">Next</button>

                    <button type="submit"
                        class="btn submit-btn"
                        id="submitBtn"
                        disabled
                        style="display:none; opacity:.6; cursor:not-allowed;">
                        Submit Listing
                    </button>
                </div>

            </form>

        </div>
    </div>

</section>



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

        function disableSelect(select, placeholderText) {
            select.classList.add('is-disabled');
            setSelectValue(select, placeholderText, '');
            const optionsWrap = select.querySelector('[data-options]');
            if (optionsWrap) optionsWrap.innerHTML = '';
        }

        function enableSelect(select) {
            select.classList.remove('is-disabled');
        }

        // Start: state & city disabled
        disableSelect(stateSelect, 'Select your state');
        disableSelect(citySelect, 'Select your city');

        // COUNTRY option click (static list)
        countrySelect.querySelectorAll('.select-option').forEach(opt => {
            opt.addEventListener('click', async () => {
                const countryId = opt.dataset.id;
                setSelectValue(countrySelect, opt.textContent.trim(), countryId);

                // reset & disable city, enable state after load
                disableSelect(stateSelect, 'Loading states...');
                disableSelect(citySelect, 'Select your city');

                try {
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

                    let html = '';
                    states.forEach(st => {
                        html += `<li class="select-option" data-id="${st.id}" data-value="${st.id}">${st.name}</li>`;
                    });
                    stateOptions.innerHTML = html;

                    enableSelect(stateSelect);
                    setSelectValue(stateSelect, 'Select your state', '');
                    stateSelect.classList.remove('is-open');

                } catch (err) {
                    disableSelect(stateSelect, 'Select your state');
                    console.error(err);
                }
            });
        });

        // STATE option click (dynamic => event delegation)
        stateOptions.addEventListener('click', async (e) => {
            const opt = e.target.closest('.select-option');
            if (!opt) return;

            const stateId = opt.dataset.id;
            setSelectValue(stateSelect, opt.textContent.trim(), stateId);

            disableSelect(citySelect, 'Loading cities...');

            try {
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

                let html = '';
                cities.forEach(ct => {
                    html += `<li class="select-option" data-id="${ct.id}" data-value="${ct.id}">${ct.name}</li>`;
                });
                cityOptions.innerHTML = html;

                enableSelect(citySelect);
                setSelectValue(citySelect, 'Select your city', '');
                citySelect.classList.remove('is-open');

            } catch (err) {
                disableSelect(citySelect, 'Select your city');
                console.error(err);
            }
        });

        // CITY option click (dynamic)
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
    (function() {
        // ====== Services (your existing code) ======
        const servicesWrap = document.getElementById('servicesWrap');
        const addServiceBtn = document.getElementById('addServiceBtn');

        function reIndexServices() {
            const rows = servicesWrap.querySelectorAll('.service-row');
            rows.forEach((row, i) => {
                row.querySelectorAll('input').forEach(inp => {
                    inp.name = inp.name
                        .replace(/services\[\d+\]\[name\]/, `services[${i}][name]`)
                        .replace(/services\[\d+\]\[price\]/, `services[${i}][price]`)
                        .replace(/services\[\d+\]\[duration\]/, `services[${i}][duration]`);
                });
            });
        }

        servicesWrap?.addEventListener('click', function(e) {
            const btn = e.target.closest('.delete-service');
            if (!btn) return;
            const rows = servicesWrap.querySelectorAll('.service-row');
            if (rows.length === 1) return;
            btn.closest('.service-row').remove();
            reIndexServices();
        });

        addServiceBtn?.addEventListener('click', function() {
            const idx = servicesWrap.querySelectorAll('.service-row').length;
            const div = document.createElement('div');
            div.className = 'service-card service-row';
            div.innerHTML = `
      <div class="service-grid">
        <div class="fg">
          <label>Service Name</label>
          <input type="text" name="services[${idx}][name]" placeholder="e.g., Haircut, Massage">
        </div>
        <div class="fg">
          <label>Price</label>
          <input type="text" name="services[${idx}][price]" placeholder="e.g., $25">
        </div>
        <div class="fg">
          <label>Duration (mins)</label>
          <input type="number" name="services[${idx}][duration]" value="30" min="0">
        </div>
        <button type="button" class="delete-service" title="Remove">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="red" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M3 6h18"/><path d="M8 6V4h8v2"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/>
          </svg>
        </button>
      </div>
    `;
            servicesWrap.appendChild(div);
        });

        // ====== Features Select (FIXED) ======
        // ====== Features Select (UPDATED for icon_image) ======
        const featuresGrid = document.getElementById('featuresGrid');
        const selectedChips = document.getElementById('selectedChips');
        const selectedCount = document.getElementById('selectedCount');

        const featuresHidden = document.getElementById('featuresHidden'); // CSV names (optional)
        const featureImagesHidden = document.getElementById('featureImagesHidden'); // ✅ CSV image paths
        const featureIDHidden = document.getElementById('featureIDHidden'); // ✅ CSV ids

        const rvFeat = document.getElementById('rv_features_chips'); // Step6 review

        if (!featuresGrid || !selectedChips || !selectedCount || !featuresHidden || !featureImagesHidden || !featureIDHidden) {
            // features section not on this page
        } else {

            // Map key = feature_id (string), value = {name, icon_image}
            const selected = new Map();

            function setTileSelected(tile, isSel) {
                tile.classList.toggle('is-selected', isSel);
                tile.setAttribute('aria-pressed', isSel ? 'true' : 'false');
            }

            function syncHidden() {
                const names = [];
                const images = [];
                const ids = [];

                selected.forEach((v, k) => {
                    names.push(v.name);
                    images.push(v.icon_image || '');
                    ids.push(k);
                });

                featuresHidden.value = names.join(',');
                featureImagesHidden.value = images.join(',');
                featureIDHidden.value = ids.join(',');
                selectedCount.textContent = String(selected.size);
            }

            function renderReviewFeatures() {
                if (!rvFeat) return;

                rvFeat.innerHTML = '';

                if (selected.size === 0) {
                    rvFeat.innerHTML = `<span class="muted-sm">No features selected.</span>`;
                    return;
                }

                selected.forEach((v) => {
                    const chip = document.createElement('span');
                    chip.className = 'chip chip-feature-review';

                    const imgHtml = v.icon_image ?
                        `<img src="/storage/${v.icon_image}" alt="" class="chip-icon">` :
                        '';

                    chip.innerHTML = `
            ${imgHtml}
            <span class="chip-text">${v.name}</span>
        `;

                    rvFeat.appendChild(chip);
                });
            }


            function renderChips() {
                selectedChips.innerHTML = '';

                selected.forEach((v, id) => {
                    const chip = document.createElement('span');
                    chip.className = 'chip';
                    chip.setAttribute('data-id', id);

                    // ✅ optional: show small image inside chip (if you want)
                    const iconHtml = v.icon_image ?
                        `<img src="/storage/${v.icon_image}" alt="" style="height:30px;width:40px;object-fit:contain;margin-right:6px;vertical-align:middle;">` :
                        '';

                    chip.innerHTML = `
                ${iconHtml}${v.name}
                <button type="button" class="chip-remove" aria-label="Remove">×</button>
            `;

                    selectedChips.appendChild(chip);
                });

                syncHidden();
                renderReviewFeatures();
            }

            // Click on tiles (select / deselect)
            featuresGrid.addEventListener('click', function(e) {
                const tile = e.target.closest('.feature-tile');
                if (!tile) return;

                const id = String(tile.getAttribute('data-id') || '').trim();
                const name = String(tile.getAttribute('data-name') || '').trim();
                const icon_image = String(tile.getAttribute('data-icon-image') || '').trim();

                if (!id || !name) return;

                if (selected.has(id)) {
                    selected.delete(id);
                    setTileSelected(tile, false);
                } else {
                    selected.set(id, {
                        name,
                        icon_image
                    });
                    setTileSelected(tile, true);
                }

                renderChips();
            });

            // Remove from chips
            selectedChips.addEventListener('click', function(e) {
                const btn = e.target.closest('.chip-remove');
                if (!btn) return;

                const chip = btn.closest('.chip');
                const id = chip?.getAttribute('data-id');
                if (!id) return;

                selected.delete(String(id));

                const tile = featuresGrid.querySelector(`.feature-tile[data-id="${CSS.escape(String(id))}"]`);
                if (tile) setTileSelected(tile, false);

                renderChips();
            });

            // ✅ If edit page (already saved) -> auto select using IDs (BEST)
            const savedIds = (featureIDHidden.value || '').split(',').map(s => s.trim()).filter(Boolean);
            const savedNames = (featuresHidden.value || '').split(',').map(s => s.trim()).filter(Boolean);
            const savedImages = (featureImagesHidden.value || '').split(',').map(s => s.trim());

            if (savedIds.length) {
                const tiles = featuresGrid.querySelectorAll('.feature-tile');

                tiles.forEach((tile) => {
                    const id = String(tile.getAttribute('data-id') || '').trim();
                    if (!id) return;

                    const idx = savedIds.findIndex(x => x === id);
                    if (idx > -1) {
                        const name = String(tile.getAttribute('data-name') || '').trim();
                        const icon_image = savedImages[idx] || String(tile.getAttribute('data-icon-image') || '').trim();

                        selected.set(id, {
                            name,
                            icon_image
                        });
                        setTileSelected(tile, true);
                    }
                });

                renderChips();
            }
            // fallback: old data (only names saved)
            else if (savedNames.length) {
                const tiles = featuresGrid.querySelectorAll('.feature-tile');
                tiles.forEach((tile) => {
                    const id = String(tile.getAttribute('data-id') || '').trim();
                    const name = String(tile.getAttribute('data-name') || '').trim();
                    const icon_image = String(tile.getAttribute('data-icon-image') || '').trim();

                    const idx = savedNames.findIndex(n => n.toLowerCase() === name.toLowerCase());
                    if (idx > -1 && id) {
                        selected.set(id, {
                            name,
                            icon_image: savedImages[idx] || icon_image
                        });
                        setTileSelected(tile, true);
                    }
                });

                renderChips();
            } else {
                renderReviewFeatures();
            }
        }


    })();
</script>


<!-- media step js -->

<script>
    // Gallery preview (shows thumbnails like your screenshot)
    const galleryInput = document.getElementById("business_gallery");
    const galleryPreview = document.getElementById("galleryPreview");
    const galleryCountText = document.getElementById("galleryCountText");

    // IMPORTANT: FileList read-only hoti hai, so we keep our own array
    let selectedFiles = [];

    function syncInputFiles() {
        // Update the actual input.files using DataTransfer
        const dt = new DataTransfer();
        selectedFiles.forEach(f => dt.items.add(f));
        galleryInput.files = dt.files;
    }

    function renderGallery() {
        galleryPreview.innerHTML = "";

        // show max 20 like your code
        selectedFiles.slice(0, 20).forEach((file, index) => {
            const url = URL.createObjectURL(file);

            // wrapper
            const wrap = document.createElement("div");
            wrap.className = "gallery-item";

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
                // remove that file from array
                selectedFiles.splice(index, 1);

                // update input files + re-render
                syncInputFiles();
                renderGallery();

                // cleanup object url
                URL.revokeObjectURL(url);
            });

            wrap.appendChild(img);
            wrap.appendChild(btn);
            galleryPreview.appendChild(wrap);
        });

        galleryCountText.textContent = `${selectedFiles.length} photos ready to showcase`;
    }

    galleryInput?.addEventListener("change", () => {
        const files = Array.from(galleryInput.files || []);

        // replace selection (same behaviour as your old code)
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