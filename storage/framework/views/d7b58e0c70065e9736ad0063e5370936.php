<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>UiTMarketplace</title>

  <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

  <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>

  <!-- Vendor CSS -->
  <link href="<?php echo e(asset('assets/niceshop/vendor/bootstrap/css/bootstrap.min.css')); ?>" rel="stylesheet">
  <link href="<?php echo e(asset('assets/niceshop/vendor/bootstrap-icons/bootstrap-icons.css')); ?>" rel="stylesheet">
  <link href="<?php echo e(asset('assets/niceshop/vendor/aos/aos.css')); ?>" rel="stylesheet">
  <link href="<?php echo e(asset('assets/niceshop/css/main.css')); ?>" rel="stylesheet">

  <!-- Leaflet -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
</head>

<body class="index-page">

<?php if (isset($component)) { $__componentOriginalfd1f218809a441e923395fcbf03e4272 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalfd1f218809a441e923395fcbf03e4272 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.header','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalfd1f218809a441e923395fcbf03e4272)): ?>
<?php $attributes = $__attributesOriginalfd1f218809a441e923395fcbf03e4272; ?>
<?php unset($__attributesOriginalfd1f218809a441e923395fcbf03e4272); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalfd1f218809a441e923395fcbf03e4272)): ?>
<?php $component = $__componentOriginalfd1f218809a441e923395fcbf03e4272; ?>
<?php unset($__componentOriginalfd1f218809a441e923395fcbf03e4272); ?>
<?php endif; ?>

<main class="main">
  <?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show mt-3">
      <?php echo e(session('success')); ?>

      <button class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php endif; ?>

  <?php echo $__env->yieldContent('content'); ?>
</main>

<?php if (isset($component)) { $__componentOriginal8a8716efb3c62a45938aca52e78e0322 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8a8716efb3c62a45938aca52e78e0322 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.footer','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('footer'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8a8716efb3c62a45938aca52e78e0322)): ?>
<?php $attributes = $__attributesOriginal8a8716efb3c62a45938aca52e78e0322; ?>
<?php unset($__attributesOriginal8a8716efb3c62a45938aca52e78e0322); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8a8716efb3c62a45938aca52e78e0322)): ?>
<?php $component = $__componentOriginal8a8716efb3c62a45938aca52e78e0322; ?>
<?php unset($__componentOriginal8a8716efb3c62a45938aca52e78e0322); ?>
<?php endif; ?>

<a href="#" id="scroll-top" class="scroll-top">
  <i class="bi bi-arrow-up-short"></i>
</a>

<script src="<?php echo e(asset('assets/niceshop/vendor/bootstrap/js/bootstrap.bundle.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/niceshop/vendor/aos/aos.js')); ?>"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="<?php echo e(asset('assets/niceshop/js/main.js')); ?>"></script>

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

  /* ================= AUTO APPLY FILTER ON HOME ================= */
  if (window.location.pathname === '/' && savedLocation && !params.has('lat')) {
    const data = JSON.parse(savedLocation);
    window.location.replace(
      `/?lat=${data.lat}&lng=${data.lng}&radius=${data.radius}`
    );
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
    window.location.href = `/?lat=${data.lat}&lng=${data.lng}&radius=${data.radius}`;
  });

  /* ================= CLEAR LOCATION ================= */
  document.getElementById('clearLocationBtn').addEventListener('click', function () {
    localStorage.removeItem('buyer_location');
    locationText.textContent = 'Set Location';
    window.location.href = '/';
  });

});
</script>

</body>
</html>
<?php /**PATH C:\fyp\uitmarketplace\resources\views/layouts/app.blade.php ENDPATH**/ ?>