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

<!-- Scroll Top -->
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
        <label class="fw-semibold">Radius (km)</label>
        <input type="range"
               id="buyer_radius"
               class="form-range mb-4"
               min="1" max="50" value="10">

        <div class="text-end">
          <button class="btn btn-primary"
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

  let map, marker;

  const locationText = document.getElementById('currentLocationText');
  const locationInput = document.getElementById('buyer_location_name');
  const radiusInput = document.getElementById('buyer_radius');

  // Load saved location
  const saved = localStorage.getItem('buyer_location');
  if (saved && locationText) {
    const data = JSON.parse(saved);
    locationText.textContent = ` ${data.area}`;
  }

  const modal = document.getElementById('locationModal');

  modal.addEventListener('shown.bs.modal', function () {

    if (!map) {
      map = L.map('buyerMap').setView([3.0738, 101.5183], 13);

      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap'
      }).addTo(map);

      map.on('click', async function (e) {
        const { lat, lng } = e.latlng;

        if (marker) map.removeLayer(marker);
        marker = L.marker([lat, lng]).addTo(map);

        try {
          const res = await fetch(
            `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`
          );
          const data = await res.json();

          const address = data.address || {};
          const area =
            address.city ||
            address.town ||
            address.suburb ||
            address.state ||
            'Unknown';

          locationInput.value = area;

          localStorage.setItem('buyer_location', JSON.stringify({
            lat,
            lng,
            area,
            radius: radiusInput.value
          }));

          if (locationText) {
            locationText.textContent = ` ${area}`;
          }

        } catch (err) {
          console.error(err);
        }
      });
    }

    map.invalidateSize();
  });

});
</script>

</body>
</html>
<?php /**PATH C:\fyp\uitmarketplace\resources\views/layouts/app.blade.php ENDPATH**/ ?>