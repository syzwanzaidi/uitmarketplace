<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>UiTMarketplace</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Laravel Vite -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <!-- Vendor CSS Files -->
  <link href="{{ asset('assets/niceshop/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/niceshop/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/niceshop/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/niceshop/vendor/aos/aos.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/niceshop/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/niceshop/vendor/drift-zoom/drift-basic.css') }}" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="{{ asset('assets/niceshop/css/main.css') }}" rel="stylesheet">

  <!-- Vendor JS Files -->
  <script src="{{ asset('assets/niceshop/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('assets/niceshop/vendor/swiper/swiper-bundle.min.js') }}"></script>
  <script src="{{ asset('assets/niceshop/vendor/aos/aos.js') }}"></script>
  <script src="{{ asset('assets/niceshop/vendor/glightbox/js/glightbox.min.js') }}"></script>
  <script src="{{ asset('assets/niceshop/vendor/drift-zoom/Drift.min.js') }}"></script>
  <script src="{{ asset('assets/niceshop/vendor/purecounter/purecounter_vanilla.js') }}"></script>

  

</head>

<body class="index-page">


  <x-header />

  <main class="main">

    @yield('content')

  </main>

  <x-footer />

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>



  <!-- Main JS File -->
  <script src="{{ asset('assets/niceshop/js/main.js') }}"></script>

</body>

</html>