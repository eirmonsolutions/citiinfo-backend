<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/aos.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/fonts/finder-icons.woff2') }}" rel="stylesheet" type="font/woff2">
    <link href="{{ asset('assets/css/finder-icons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/flaticon.css') }}" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet">

    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/responsive.css') }}" rel="stylesheet">

    @stack('styles')
</head>

<body>

    <div class="login-34">
        <div class="container">
            <div class="row login-box">
                <div class="col-lg-6 bg-color-15 pad-0 none-992 align-self-center bg-img">
                    <div class="info clearfix">
                        <img src="{{ asset('assets/images/login-img.png') }}" alt="login-img" class="img-fluid">
                    </div>
                </div>
                <div class="col-lg-6 pad-0 form-info">
                    <div class="form-section align-self-center">
                        <h1>Welcome!</h1>
                        <h3>Sign Into Your Account</h3>
                        <div class="clearfix"></div>
                        <div class="btn-section clearfix">
                            <a href="/login" class="link-btn active btn-1 active-bg">Login</a>
                            <a href="/register" class="link-btn btn-2 default-bg">Register</a>
                        </div>
                        <div class="clearfix"></div>

                        @if ($errors->any())
                            <div class="alert alert-danger mb-3">
                                {{ $errors->first() }}
                            </div>
                        @endif

                        <form action="/login" id="loginForm" method="POST">
                            @csrf
                            <div class="form-group form-box">
                                <label for="first_field" class="form-label">Email address</label>
                                <input name="email" type="email" class="form-control" id="first_field"
                                    placeholder="Email Address" aria-label="Email Address"
                                    value="{{ old('email') }}" required>
                                @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group form-box">
                                <label for="second_field" class="form-label">Password</label>
                                <input name="password" type="password" class="form-control" autocomplete="current-password"
                                    id="second_field" placeholder="Password" aria-label="Password" required>
                                @error('password')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="checkbox form-group form-box">
                                <div class="form-check checkbox-theme">
                                    <input class="form-check-input" type="checkbox" name="remember" value="1" id="rememberMe"
                                        {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="rememberMe">
                                        Remember me
                                    </label>
                                </div>
                                <a href="forgot-password.html" class="forgot-password">Forgot Password</a>
                            </div>
                            <div class="form-group mb-0">
                                <button type="submit" class="btn-md btn-theme w-100">Login</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/aos.js') }}"></script>
    <script src="{{ asset('assets/js/gsap.js') }}"></script>
    <script src="{{ asset('assets/js/gsap-animation.js') }}"></script>
    <script src="{{ asset('assets/js/splitText.js') }}"></script>
    <script src="{{ asset('assets/js/ScrollTrigger.js') }}"></script>
    @stack('scripts')
</body>

</html>
