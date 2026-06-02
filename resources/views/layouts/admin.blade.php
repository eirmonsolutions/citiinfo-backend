<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', config('app.name'))</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- <meta name="wishlist-toggle-url" content="{{ Route::has('wishlist.toggle') ? route('wishlist.toggle') : '' }}"> -->

    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/aos.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/fonts/finder-icons.woff2') }}" rel="stylesheet" type="font/woff2">
    <link href="{{ asset('assets/css/finder-icons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/flaticon.css') }}" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/responsive.css') }}" rel="stylesheet">
    @stack('styles')
</head>

<body>
    @php
    $user = auth()->user();

    $name = $user->name ?? 'Admin';

    $words = explode(' ', trim($name));

    if (count($words) >= 2) {
    $initials = strtoupper(
    substr($words[0], 0, 1) . substr($words[1], 0, 1)
    );
    } else {
    $initials = strtoupper(substr($words[0], 0, 1));
    }

    // avatar path (storage/public)
    $avatar = $user->avatar
    ? asset('storage/' . $user->avatar)
    : null;
    @endphp



    <div class="position-fixed top-0 end-0 p-3" style="z-index: 9999;">
        <div id="appToast"
            class="toast align-items-center text-bg-success border-0"
            role="alert"
            aria-live="assertive"
            aria-atomic="true">

            <div class="d-flex">
                <div class="toast-body" id="appToastMsg">
                    {{-- message from session --}}
                </div>

                <button type="button"
                    class="btn-close btn-close-white me-2 m-auto"
                    data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>
    </div>

    <div class="dashboard-wrapper">
        @include('admin.sidebar')

        <div class="dashboard-area">

            <header class="dashboard-header">
                <button class="menu-icon-btn" id="menu-toggle-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-menu-icon lucide-menu">
                        <path d="M4 5h16" />
                        <path d="M4 12h16" />
                        <path d="M4 19h16" />
                    </svg>
                </button>

                <div class="dashboard-right-header">

                    @if($avatar)
                    <div class="profile-img">
                        <img src="{{ $avatar }}" alt="{{ $name }}">
                    </div>
                    @else
                    <div class="profile-box">
                        {{ $initials }}
                    </div>
                    @endif

                    <div class="dropdown-area">
                        <span>{{ $name }}</span>

                        <ul class="d-none">
                            <li>
                                <form method="POST" action="{{ route('logout') }}" id="logout-form">
                                    @csrf
                                    <button type="submit" class="btn btn-link p-0 text-danger">
                                        Logout
                                    </button>
                                </form>

                            </li>
                        </ul>
                    </div>
                </div>


            </header>
            @yield('content')
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // ✅ Success Toast
            @if(session('success'))
            const toastEl = document.getElementById('appToast');
            const toastMsg = document.getElementById('appToastMsg');

            toastEl.classList.remove('text-bg-danger');
            toastEl.classList.add('text-bg-success');

            toastMsg.innerText = @json(session('success'));

            const toast = new bootstrap.Toast(toastEl, {
                delay: 2500
            });
            toast.show();
            @endif

            // ✅ Error Toast (optional)
            @if(session('error'))
            const toastEl = document.getElementById('appToast');
            const toastMsg = document.getElementById('appToastMsg');

            toastEl.classList.remove('text-bg-success');
            toastEl.classList.add('text-bg-danger');

            toastMsg.innerText = @json(session('error'));

            const toast = new bootstrap.Toast(toastEl, {
                delay: 3000
            });
            toast.show();
            @endif

        });
    </script>




    <!-- Bootstrap JS -->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/aos.js') }}"></script>
    <script src="{{ asset('assets/js/gsap.js') }}"></script>
    <script src="{{ asset('assets/js/gsap-animation.js') }}"></script>
    <script src="{{ asset('assets/js/splitText.js') }}"></script>
    <!-- Custom JS -->
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <!-- Development version -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        lucide.createIcons();
    </script>

    @if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success',
                title: 'Submitted!',
                text: @json(session('success')),
                confirmButtonText: 'OK',
            });
        });
    </script>
    @endif

    @if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: @json(session('error')),
                confirmButtonText: 'OK',
            });
        });
    </script>
    @endif

    @stack('scripts')
</body>

</html>