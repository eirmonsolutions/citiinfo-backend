@php
$role = auth()->user()->role ?? 'user';
@endphp

<aside class="sidebar-main">
    <div class="sidebar-heading">
        <h1 class="hidden">Citiinfo</h1>
        <svg width="32" height="32" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="lucide lucide-layout-dashboard-icon lucide-layout-dashboard">
            <rect width="7" height="9" x="3" y="3" rx="1" />
            <rect width="7" height="5" x="14" y="3" rx="1" />
            <rect width="7" height="9" x="14" y="12" rx="1" />
            <rect width="7" height="5" x="3" y="16" rx="1" />
        </svg>
    </div>

    <nav class="sidebar-nav">

        {{-- ===========================
            USER SIDEBAR (role=user)
        ============================ --}}
        @if($role === 'user')

        <a href="{{ route('user.dashboard') }}" class="sidebar-list {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
            <svg width="22" height="22" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-layout-dashboard-icon lucide-layout-dashboard">
                <rect width="7" height="9" x="3" y="3" rx="1" />
                <rect width="7" height="5" x="14" y="3" rx="1" />
                <rect width="7" height="9" x="14" y="12" rx="1" />
                <rect width="7" height="5" x="3" y="16" rx="1" />
            </svg>
            <span class="sidebar-link">Dashboard</span>
        </a>

        {{-- Wishlist (abhi aapka wishlist route admin prefix me hai, ok for now) --}}
        <a href="{{ route('admin.wishlist.index') }}" class="sidebar-list {{ request()->routeIs('admin.wishlist.*') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-heart-icon lucide-heart">
                <path d="M2 9.5a5.5 5.5 0 0 1 9.591-3.676.56.56 0 0 0 .818 0A5.49 5.49 0 0 1 22 9.5c0 2.29-1.5 4-3 5.5l-5.492 5.313a2 2 0 0 1-3 .019L5 15c-1.5-1.5-3-3.2-3-5.5" />
            </svg>
            <span class="sidebar-link">Wishlist</span>
        </a>

        <a href="#" class="sidebar-list">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-star-icon lucide-star">
                <path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z" />
            </svg>
            <span class="sidebar-link">My Reviews</span>
        </a>

        @endif


        {{-- ===========================
            ADMIN SIDEBAR (role=admin)
        ============================ --}}
        @if($role === 'admin')

        <a href="{{ route('admin.dashboard') }}" class="sidebar-list {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <svg width="22" height="22" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-layout-dashboard-icon lucide-layout-dashboard">
                <rect width="7" height="9" x="3" y="3" rx="1" />
                <rect width="7" height="5" x="14" y="3" rx="1" />
                <rect width="7" height="9" x="14" y="12" rx="1" />
                <rect width="7" height="5" x="3" y="16" rx="1" />
            </svg>
            <span class="sidebar-link">Dashboard</span>
        </a>

        <a href="{{ route('admin.announcement.index') }}" class="sidebar-list {{ request()->routeIs('admin.announcement.*') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-megaphone-icon lucide-megaphone">
                <path d="M11 6a13 13 0 0 0 8.4-2.8A1 1 0 0 1 21 4v12a1 1 0 0 1-1.6.8A13 13 0 0 0 11 14H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2z" />
                <path d="M6 14a12 12 0 0 0 2.4 7.2 2 2 0 0 0 3.2-2.4A8 8 0 0 1 10 14" />
                <path d="M8 6v8" />
            </svg>
            <span class="sidebar-link">Announcements</span>
        </a>

        <a href="{{ route('admin.event.index') }}" class="sidebar-list {{ request()->routeIs('admin.event.*') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-calendar-icon lucide-calendar">
                <path d="M8 2v4" />
                <path d="M16 2v4" />
                <rect width="18" height="18" x="3" y="4" rx="2" />
                <path d="M3 10h18" />
            </svg>
            <span class="sidebar-link">Events</span>
        </a>

        <a href="{{ route('admin.coupon.index') }}" class="sidebar-list {{ request()->routeIs('admin.coupon.*') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-tag-icon lucide-tag">
                <path d="M12.586 2.586A2 2 0 0 0 11.172 2H4a2 2 0 0 0-2 2v7.172a2 2 0 0 0 .586 1.414l8.704 8.704a2.426 2.426 0 0 0 3.42 0l6.58-6.58a2.426 2.426 0 0 0 0-3.42z" />
                <circle cx="7.5" cy="7.5" r=".5" fill="currentColor" />
            </svg>
            <span class="sidebar-link">Coupons</span>
        </a>

        <a href="{{ route('admin.faq.index') }}" class="sidebar-list {{ request()->routeIs('admin.faq.*') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-file-text-icon lucide-file-text">
                <path d="M6 22a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h8a2.4 2.4 0 0 1 1.704.706l3.588 3.588A2.4 2.4 0 0 1 20 8v12a2 2 0 0 1-2 2z" />
                <path d="M14 2v5a1 1 0 0 0 1 1h5" />
                <path d="M10 9H8" />
                <path d="M16 13H8" />
                <path d="M16 17H8" />
            </svg>
            <span class="sidebar-link">FAQ's</span>
        </a>

        <a href="{{ route('admin.listing.index') }}" class="sidebar-list {{ request()->routeIs('admin.listing.*') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-file-text-icon lucide-file-text">
                <path d="M6 22a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h8a2.4 2.4 0 0 1 1.704.706l3.588 3.588A2.4 2.4 0 0 1 20 8v12a2 2 0 0 1-2 2z" />
                <path d="M14 2v5a1 1 0 0 0 1 1h5" />
                <path d="M10 9H8" />
                <path d="M16 13H8" />
                <path d="M16 17H8" />
            </svg>
            <span class="sidebar-link">Listings</span>
        </a>

        <a href="{{ route('admin.wishlist.index') }}" class="sidebar-list {{ request()->routeIs('admin.wishlist.*') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-heart-icon lucide-heart">
                <path d="M2 9.5a5.5 5.5 0 0 1 9.591-3.676.56.56 0 0 0 .818 0A5.49 5.49 0 0 1 22 9.5c0 2.29-1.5 4-3 5.5l-5.492 5.313a2 2 0 0 1-3 .019L5 15c-1.5-1.5-3-3.2-3-5.5" />
            </svg>
            <span class="sidebar-link">Wishlist</span>
        </a>

        <a href="#" class="sidebar-list">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-star-icon lucide-star">
                <path d="M11.525 2.295a.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z" />
            </svg>
            <span class="sidebar-link">Reviews</span>
        </a>

        <a href="#" class="sidebar-list">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-file-text-icon lucide-file-text">
                <path d="M6 22a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h8a2.4 2.4 0 0 1 1.704.706l3.588 3.588A2.4 2.4 0 0 1 20 8v12a2 2 0 0 1-2 2z" />
                <path d="M14 2v5a1 1 0 0 0 1 1h5" />
                <path d="M10 9H8" />
                <path d="M16 13H8" />
                <path d="M16 17H8" />
            </svg>
            <span class="sidebar-link">Subscribe plans</span>
        </a>

        <a href="#" class="sidebar-list">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-radio-icon lucide-radio">
                <path d="M16.247 7.761a6 6 0 0 1 0 8.478" />
                <path d="M19.075 4.933a10 10 0 0 1 0 14.134" />
                <path d="M4.925 19.067a10 10 0 0 1 0-14.134" />
                <path d="M7.753 16.239a6 6 0 0 1 0-8.478" />
                <circle cx="12" cy="12" r="2" />
            </svg>
            <span class="sidebar-link">Ads Campaigns</span>
        </a>

        @endif

    </nav>

    {{-- Logout (sab roles) --}}
    <div class="logout-btn">
        <form method="POST" action="{{ route('logout') }}" id="logout-form">
            @csrf
            <button type="submit">
                <svg class="flex-shrink-0" width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                    </path>
                </svg>
                <span class="sidebar-link">Logout</span>
            </button>
        </form>
    </div>

</aside>