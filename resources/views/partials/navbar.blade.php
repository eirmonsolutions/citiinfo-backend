<header class="navbar-main">
    <nav class="navbar navbar-expand-lg ">
        <div class="container">
            <a class="navbar-brand" href="/">
                <img src="{{ asset('assets/images/logo.png') }}" alt="Logo">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarTogglerDemo01">

                <ul class="navbar-nav m-auto">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="/">Home</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="/listing">Business Listings</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="/categories">Categories</a>
                    </li>


                </ul>
                <div class="right-header">

                    {{-- Add Listing button (always show) --}}
                    <a class="listing-btn" href="/add-listing">
                        <i class="fi-plus fs-lg"></i>
                        Add Listing
                    </a>

                    {{-- Auth dropdown --}}
                    @auth
                    @php
                    $user = auth()->user();
                    $role = $user->role ?? 'user';

                    $name = $user->name ?? 'User';
                    $avatar = $user->avatar ?? null;

                    // Default display name
                    $displayName = $name;

                    // ✅ Default dashboard route for NORMAL USER
                    $dashboardRoute = route('user.dashboard');

                    // SUPERADMIN
                    if ($role === 'superadmin') {
                    $displayName = 'Super Admin';
                    $dashboardRoute = route('superadmin.dashboard');
                    }

                    // ADMIN / LISTER
                    if ($role === 'admin') {

                    $businessUserId = $user->business_user_id ?? $user->id;

                    $businessName = \App\Models\BusinessListing::where('user_id', $businessUserId)
                    ->latest('id')
                    ->value('business_name');

                    if (!empty($businessName)) {
                    $displayName = $businessName;
                    }

                    $dashboardRoute = route('admin.dashboard');
                    }

                    // Initials
                    $parts = preg_split('/\s+/', trim($displayName));
                    $initials = strtoupper(
                    substr($parts[0] ?? 'U', 0, 1) .
                    substr($parts[1] ?? '', 0, 1)
                    );
                    @endphp





                    <div class="dashboard-right-header user-dd" id="userDropdown">
                        @if($avatar)
                        <div class="profile-img">
                            <img src="{{ $avatar }}" alt="{{ $name }}">
                        </div>
                        @else
                        <div class="profile-box">{{ $initials }}</div>
                        @endif


                        <button type="button" class="dropdown-trigger" id="userDropdownBtn" aria-expanded="false">
                            <span class="user-name">{{ $displayName }}</span>

                            <span class="chev">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="m6 9 6 6 6-6" />
                                </svg>
                            </span>
                        </button>

                        <div class="dropdown-menu-user" id="userDropdownMenu">
                            <a href="{{ $dashboardRoute }}" class="dd-item">
                                Dashboard
                            </a>
                            <a href="" class="dd-item">
                                My Profile
                            </a>


                            <a href="" class="dd-item">
                                Wishlist (<span id="wishlistCount">{{ $wishlistCount ?? 0 }}</span>)
                            </a>


                            <a href="" class="dd-item">
                                Notifications
                            </a>
                            <a href="" class="dd-item">
                                Settings
                            </a>

                            <div class="dd-divider"></div>

                            <form method="POST" action="{{ route('logout') }}" class="m-0">
                                @csrf
                                <button type="submit" class="dd-item dd-danger">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                    @else
                    <a class="login-btn" href="/login">Login</a>
                    @endauth

                </div>

            </div>

        </div>
    </nav>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const wrap = document.getElementById('userDropdown');
        const btn = document.getElementById('userDropdownBtn');
        const menu = document.getElementById('userDropdownMenu');

        if (!wrap || !btn || !menu) return;

        function openMenu() {
            wrap.classList.add('open');
            btn.setAttribute('aria-expanded', 'true');
        }

        function closeMenu() {
            wrap.classList.remove('open');
            btn.setAttribute('aria-expanded', 'false');
        }

        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            wrap.classList.contains('open') ? closeMenu() : openMenu();
        });

        // close when click outside
        document.addEventListener('click', function(e) {
            if (!wrap.contains(e.target)) closeMenu();
        });

        // close on ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeMenu();
        });
    });
</script>