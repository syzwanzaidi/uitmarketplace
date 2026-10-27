<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('code') · {{ config('app.name', 'UiTMarketplace') }}</title>
    <link rel="icon" href="{{ asset('assets/img/favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.min.css') }}" rel="stylesheet">
    <style>
        body { font-family: "Plus Jakarta Sans", system-ui, sans-serif; background: #f7f6fa; color: #1c1726; min-height: 100vh; display: grid; place-items: center; padding: 1.5rem; }
        .box { max-width: 460px; text-align: center; }
        .code { font-size: 5rem; font-weight: 800; line-height: 1; letter-spacing: -.04em; background: linear-gradient(135deg, #4b2a85, #7a4fc7); -webkit-background-clip: text; background-clip: text; color: transparent; }
        .btn-brand { background: #4b2a85; color: #fff; border-radius: 10px; padding: .65rem 1.2rem; font-weight: 600; }
        .btn-brand:hover { background: #3c2170; color: #fff; }
    </style>
</head>
<body>
    <div class="box">
        <div class="code mb-3">@yield('code')</div>
        <h1 class="h4 fw-bold mb-2">@yield('title')</h1>
        <p class="text-secondary mb-4">@yield('message')</p>
        <a href="{{ url('/') }}" class="btn btn-brand"><i class="bi bi-house"></i> Back to home</a>
    </div>
</body>
</html>
