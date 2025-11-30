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

  <!-- Custom JS -->
  <script>
    document.addEventListener('click', function (e) {
      const modal = document.querySelector('#locationModal');
      if (e.target === modal) {
        const modalInstance = bootstrap.Modal.getInstance(modal);
        modalInstance.hide();
      }
    });
  </script>

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

<!-- Location Filter Modal -->
<div class="modal fade" id="locationModal" tabindex="-1" aria-labelledby="locationModalLabel" aria-hidden="true" data-bs-backdrop="true" data-bs-keyboard="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content location-modal-content">
        <div class="modal-body">
            <div class="location-popup">
            <h4>Select Your Location</h4>

            <div id="map" class="map-placeholder mb-3">
                <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3984.0020565179075!2d101.49506447497367!3d3.0726252530260403!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31cc52d7df6b18cf%3A0xa33ee1b9b6a28c76!2sUniversiti%20Teknologi%20MARA%20(UiTM)%20Shah%20Alam!5e0!3m2!1sen!2smy!4v1730390199474!5m2!1sen!2smy" 
                allowfullscreen=""
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>

            <label for="manualLocation" class="form-label fw-semibold">Enter Location</label>
            <input type="text" id="manualLocation" class="form-control mb-4" placeholder="Type your location manually...">

            <label class="form-label fw-semibold">Slide the Radius</label>
            <input type="range" class="form-range radius-slider mb-3" min="1" max="50" value="10">

            <div class="text-center">
                <button type="button" class="btn-confirm" data-bs-dismiss="modal">Confirm <i class="bi bi-arrow-right"></i></button>
            </div>
            </div>
        </div>
      </div>
  </div>
</div>

</html>