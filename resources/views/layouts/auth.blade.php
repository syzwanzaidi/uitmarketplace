<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') · {{ config('app.name', 'UiTMarketplace') }}</title>

    <link rel="icon" href="{{ asset('assets/img/favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.min.css') }}" rel="stylesheet">
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}" defer></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="auth-shell">
        <aside class="auth-aside d-flex">
            <a href="{{ route('about') }}" class="brand position-relative" style="z-index:1">
                <span class="brand-mark"><i class="bi bi-bag-heart-fill"></i></span>
                <span>UiT<em>Marketplace</em></span>
            </a>
            <div class="position-relative" style="z-index:1">
                <h2>Buy and sell with your campus community.</h2>
                <p>Pre-loved textbooks, gadgets and hostel essentials from fellow UiTM students.</p>
                <ul>
                    <li><i class="bi bi-check-circle-fill"></i> Sellers verified by UiTM email</li>
                    <li><i class="bi bi-check-circle-fill"></i> Chat and arrange meetups</li>
                    <li><i class="bi bi-check-circle-fill"></i> Secure FPX payment via ToyyibPay</li>
                </ul>
            </div>
            <small class="position-relative" style="z-index:1;opacity:.7">&copy; {{ date('Y') }} UiTMarketplace</small>
        </aside>

        <main class="auth-main">
            <div class="auth-card">
                <a href="{{ route('about') }}" class="brand mb-4 d-lg-none">
                    <span class="brand-mark"><i class="bi bi-bag-heart-fill"></i></span>
                    <span>UiT<em>Marketplace</em></span>
                </a>
                @if (session('status') && ! in_array(session('status'), ['verification-link-sent']))
                    <div class="notice mb-4"><i class="bi bi-info-circle"></i><div>{{ session('status') }}</div></div>
                @endif
                @yield('content')
            </div>
        </main>
    </div>
    @include('partials.flash')
</body>
</html>
