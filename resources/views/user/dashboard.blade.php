@extends('layouts.user')

@section('title', ' User Dashboard')

@section('content')



<main class="main-dashboard">

    @php
    $hour = now()->hour;

    if ($hour < 12) {
        $greeting='Good Morning' ;
        } elseif ($hour < 17) {
        $greeting='Good Afternoon' ;
        } else {
        $greeting='Good Evening' ;
        }
        @endphp

        <h1>
        Hi {{ auth()->user()->name }}, <span class="text-color">{{ $greeting }}...!</span>
        </h1>


        @php
            $userMessageCount = \App\Models\BusinessEnquiry::query()
                ->where(function ($q) {
                    $q->where('sender_user_id', auth()->id())
                        ->orWhere('email', auth()->user()->email);
                })
                ->count();
        @endphp

        <section class="dashboard-boxes">
            <div class="row">

                <!-- card 1 -->
                <div class="col-md-3">
                    <div class="stat-card customers-card">
                        <div class="stat-header">
                            <div class="stat-icon customers-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users w-7 h-7">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="9" cy="7" r="4"></circle>
                                    <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                </svg>
                            </div>
                            <div class="stat-change customers-change">
                                <span>1010</span>
                            </div>
                        </div>
                        <div class="stat-content">
                            <!-- <h3>2,543</h3> -->
                            <div class="stat-number-row">
                                <p class="stat-number">User Views</p>
                                <button class="stat-button customers-button">
                                    <span>More info</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- card 2 -->
                <div class="col-md-3">
                    <div class="stat-card drivers-card">
                        <div class="stat-header">
                            <div class="stat-icon drivers-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-text-icon lucide-file-text">
                                    <path d="M6 22a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h8a2.4 2.4 0 0 1 1.704.706l3.588 3.588A2.4 2.4 0 0 1 20 8v12a2 2 0 0 1-2 2z" />
                                    <path d="M14 2v5a1 1 0 0 0 1 1h5" />
                                    <path d="M10 9H8" />
                                    <path d="M16 13H8" />
                                    <path d="M16 17H8" />
                                </svg>
                            </div>
                            <div class="stat-change drivers-change">
                                <span>500</span>
                            </div>
                        </div>
                        <div class="stat-content">
                            <!-- <h3>1,520</h3> -->
                            <div class="stat-number-row">
                                <p class="stat-number">Customer Leads</p>
                                <button class="stat-button drivers-button">
                                    <span>More info</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- card 3 -->
                <div class="col-md-3">
                    <div class="stat-card vehicles-card">
                        <div class="stat-header">
                            <div class="stat-icon vehicles-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-message-square-icon lucide-message-square">
                                    <path d="M22 17a2 2 0 0 1-2 2H6.828a2 2 0 0 0-1.414.586l-2.202 2.202A.71.71 0 0 1 2 21.286V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2z" />
                                </svg>
                            </div>
                            <div class="stat-change vehicles-change">
                                <span>{{ $userMessageCount }}</span>
                            </div>
                        </div>
                        <div class="stat-content">
                            <div class="stat-number-row">
                                <p class="stat-number">My Messages</p>
                                <a href="{{ route('user.messages.index') }}" class="stat-button vehicles-button">
                                    <span>More Info</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- card 4 -->
                <div class="col-md-3">
                    <div class="stat-card invoices-card">
                        <div class="stat-header">
                            <div class="stat-icon invoices-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-text-icon lucide-file-text">
                                    <path d="M6 22a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h8a2.4 2.4 0 0 1 1.704.706l3.588 3.588A2.4 2.4 0 0 1 20 8v12a2 2 0 0 1-2 2z" />
                                    <path d="M14 2v5a1 1 0 0 0 1 1h5" />
                                    <path d="M10 9H8" />
                                    <path d="M16 13H8" />
                                    <path d="M16 17H8" />
                                </svg>
                            </div>
                            <div class="stat-change invoices-change">
                                <i data-lucide="trending-up"></i>
                                <span>850</span>
                            </div>
                        </div>
                        <div class="stat-content">
                            <!-- <h3>1,200</h3> -->
                            <div class="stat-number-row">
                                <p class="stat-number">Invoices</p>
                                <button class="stat-button invoices-button">
                                    <span>More info</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </section>
</main>





@endsection