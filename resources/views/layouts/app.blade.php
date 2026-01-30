<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>UiTMarketplace</title>

  <meta name="csrf-token" content="{{ csrf_token() }}">

  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <!-- Vendor CSS -->
  <link href="{{ asset('assets/niceshop/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/niceshop/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/niceshop/vendor/aos/aos.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/niceshop/css/main.css') }}" rel="stylesheet">

  <!-- Leaflet -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
</head>

<body class="index-page">

<x-header />

<main class="main">
  @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show mt-3">
      {{ session('success') }}
      <button class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  @yield('content')
</main>

<x-footer />

<a href="#" id="scroll-top" class="scroll-top">
  <i class="bi bi-arrow-up-short"></i>
</a>

<script src="{{ asset('assets/niceshop/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/niceshop/vendor/aos/aos.js') }}"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="{{ asset('assets/niceshop/js/main.js') }}"></script>

<!-- ================= LOCATION MODAL ================= -->
<div class="modal fade" id="locationModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-body">

        <h4 class="mb-3">Select Your Location</h4>

        <!-- MAP -->
        <div id="buyerMap"
             style="height:300px;border-radius:8px;"
             class="mb-3"></div>

        <!-- LOCATION NAME -->
        <input type="text"
               id="buyer_location_name"
               class="form-control mb-3"
               placeholder="Click on map to detect location"
               readonly>

        <!-- RADIUS -->
        <div class="mb-2 fw-semibold">
          Radius: <span id="radiusValue">10</span> km
        </div>

        <input type="range"
               id="buyer_radius"
               class="form-range mb-4"
               min="1" max="50" value="10">

        <!-- ACTION BUTTONS -->
        <div class="d-flex justify-content-between">
          <button type="button"
                  class="btn btn-outline-danger location-btn"
                  id="clearLocationBtn">
            Clear Location
          </button>

          <button type="button"
                  class="btn btn-primary location-btn btn-confirm"
                  data-bs-dismiss="modal">
            Confirm
          </button>
        </div>

      </div>
    </div>
  </div>
</div>

<!-- ================= LOCATION SCRIPT ================= -->
<script>
document.addEventListener('DOMContentLoaded', function () {

  let buyerMap, buyerMarker;

  const locationText  = document.getElementById('currentLocationText');
  const locationInput = document.getElementById('buyer_location_name');
  const radiusInput   = document.getElementById('buyer_radius');
  const radiusValue   = document.getElementById('radiusValue');

  /* ================= LOAD SAVED LOCATION ================= */
  const savedLocation = localStorage.getItem('buyer_location');
  const params = new URLSearchParams(window.location.search);

  if (savedLocation) {
    const data = JSON.parse(savedLocation);
    locationText.textContent = ` ${data.area}`;
    radiusInput.value = data.radius;
    radiusValue.textContent = data.radius;
  }

  /* ================= AUTO APPLY LOCATION FILTER (ALL PAGES) ================= */
  if (savedLocation && !params.has('lat')) {
    const data = JSON.parse(savedLocation);

    const url = new URL(window.location.href);
    url.searchParams.set('lat', data.lat);
    url.searchParams.set('lng', data.lng);
    url.searchParams.set('radius', data.radius);

    window.location.replace(url.toString());
    return;
  }

  /* ================= RADIUS LIVE UPDATE ================= */
  radiusInput.addEventListener('input', function () {
    radiusValue.textContent = this.value;

    const saved = localStorage.getItem('buyer_location');
    if (!saved) return;

    const data = JSON.parse(saved);
    data.radius = this.value;
    localStorage.setItem('buyer_location', JSON.stringify(data));
  });

  /* ================= INIT MAP ================= */
  const modal = document.getElementById('locationModal');

  modal.addEventListener('shown.bs.modal', function () {

    if (!buyerMap) {
      buyerMap = L.map('buyerMap')
        .setView([3.0738, 101.5183], 13);

      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap'
      }).addTo(buyerMap);

      buyerMap.on('click', async function (e) {
        const { lat, lng } = e.latlng;

        if (buyerMarker) buyerMap.removeLayer(buyerMarker);
        buyerMarker = L.marker([lat, lng]).addTo(buyerMap);

        const res = await fetch(
          `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`
        );
        const data = await res.json();

        const address = data.address || {};
        const area =
          address.city ||
          address.town ||
          address.suburb ||
          address.village ||
          address.state ||
          'Unknown';

        locationInput.value = area;

        localStorage.setItem('buyer_location', JSON.stringify({
          lat,
          lng,
          area,
          radius: radiusInput.value
        }));

        locationText.textContent = ` ${area}`;
      });
    }

    buyerMap.invalidateSize();
  });

  /* ================= CONFIRM ================= */
  document.querySelector('.btn-confirm').addEventListener('click', function () {
    const saved = localStorage.getItem('buyer_location');
    if (!saved) return;

    const data = JSON.parse(saved);

    const url = new URL(window.location.href);

    // Update / inject location params
    url.searchParams.set('lat', data.lat);
    url.searchParams.set('lng', data.lng);
    url.searchParams.set('radius', data.radius);

    window.location.href = url.toString();
  });

  /* ================= CLEAR LOCATION ================= */
  document.getElementById('clearLocationBtn').addEventListener('click', function () {
    localStorage.removeItem('buyer_location');
    locationText.textContent = 'Set Location';

    const url = new URL(window.location.href);
    url.searchParams.delete('lat');
    url.searchParams.delete('lng');
    url.searchParams.delete('radius');

    window.location.href = url.toString();
  });

  const el = document.getElementById('emptyLocationText');
  const saved = localStorage.getItem('buyer_location');

  if (el && saved) {
    const data = JSON.parse(saved);
    el.textContent = `of ${data.area}.`;
  }

});
</script>

</body>
</html>
