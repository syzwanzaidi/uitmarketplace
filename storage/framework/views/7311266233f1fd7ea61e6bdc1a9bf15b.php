

<?php $__env->startSection('content'); ?>
<main class="main">

  <!-- Page Title -->
  <div class="page-title light-background">
    <div class="container d-lg-flex justify-content-between align-items-center">
      <h1 class="mb-2 mb-lg-0">My Listings</h1>
      <nav class="breadcrumbs">
        <ol>
          <li><a href="<?php echo e(route('home')); ?>">Home</a></li>
          <li class="current">Listings</li>
        </ol>
      </nav>
    </div>
  </div>

  <!-- Seller Dashboard Section -->
  <section id="seller-dashboard" class="account section">
    <div class="container">

      <div class="content-area">

        <div class="row g-4 mb-5">

          <div class="col-md-3 col-sm-6">
            <div class="dashboard-card">
              <i class="bi bi-currency-dollar"></i>
              <h4>Total Sales</h4>
              <p>RM <?php echo e(number_format($totalSales, 2)); ?></p>
            </div>
          </div>

          <div class="col-md-3 col-sm-6">
            <div class="dashboard-card"
                style="cursor: pointer"
                onclick="window.location='<?php echo e(route('listings')); ?>'">
              <i class="bi bi-box-seam"></i>
              <h4>Active Listings</h4>
              <p><?php echo e($activeListingsCount); ?> Items</p>
            </div>
          </div>

          <div class="col-md-3 col-sm-6">
            <div class="dashboard-card"
                  style="cursor: pointer"
                  onclick="window.location='<?php echo e(route('chat')); ?>'">
              <i class="bi bi-chat-dots"></i>
              <h4>Messages</h4>
              <p>Click to View</p>
            </div>
          </div>

          <div class="col-md-3 col-sm-6">
            <div class="dashboard-card"
                  style="cursor: pointer"
                  onclick="window.location='<?php echo e(route('seller.orders')); ?>'">
              <i class="bi bi-graph-up"></i>
              <h4>Pending Orders</h4>
              <p><?php echo e($pendingOrdersCount); ?></p>
            </div>
          </div>

        </div>

        <!-- Add New Listing Button -->
        <div class="d-flex justify-content-between align-items-center mb-4">

          <!-- LEFT: Add Listing -->
          <a href="#"
            class="btn btn-header"
            data-bs-toggle="modal"
            data-bs-target="#addListingModal">
            <i class="bi bi-plus-lg"></i> Add New Listing
          </a>

          <!-- RIGHT: Search -->
          <form method="GET"
                action="<?php echo e(route('listings')); ?>"
                class="d-flex align-items-center">

            <div class="search-box">
              <i class="bi bi-search"></i>
              <input type="text"
                    name="search"
                    value="<?php echo e(request('search')); ?>"
                    placeholder="Search listings..."
                    class="form-control">
            </div>

          </form>

        </div>

        <!-- Listings -->
        <div class="listing-items">

          <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <div class="listing-item mb-4 p-3 rounded shadow-sm">
            <div class="row align-items-center">

              <!-- Image + Title -->
              <div class="col-lg-6 col-12">
                <div class="product-info d-flex align-items-center">
                  <div class="product-image me-3">
                    <img src="<?php echo e($product->image); ?>" class="img-fluid rounded" width="80">
                  </div>
                  <div class="product-details">
                    <h6 class="product-title mb-1"><?php echo e($product->name); ?></h6>
                    <p class="text-muted small mb-0">
                      Category: <?php echo e($product->category->name); ?>

                    </p>
                  </div>
                </div>
              </div>

              <!-- Price -->
              <div class="col-lg-2 col-6 text-center">
                <span class="fw-bold text-dark">
                  RM <?php echo e(number_format($product->price, 2)); ?>

                </span>
              </div>

              <!-- Status -->
              <div class="col-lg-2 col-6 text-center">
                <form action="<?php echo e(route('listings.updateStatus', $product->id)); ?>" method="POST">
                  <?php echo csrf_field(); ?>
                  <?php echo method_field('PATCH'); ?>
                  <select name="status"
                          class="form-select form-select-sm"
                          onchange="this.form.submit()">
                    <option value="active" <?php echo e($product->status == 'active' ? 'selected' : ''); ?>>
                      Active
                    </option>
                    <option value="inactive" <?php echo e($product->status == 'inactive' ? 'selected' : ''); ?>>
                      Inactive
                    </option>
                  </select>
                </form>
              </div>

              <!-- Edit + Delete (UNCHANGED) -->
              <div class="col-lg-2 col-12 text-center mt-3 mt-lg-0">
                
                <!-- Edit Button -->
                <button class="btn btn-sm btn-outline-secondary me-2"
                        data-bs-toggle="modal"
                        data-bs-target="#editModal<?php echo e($product->id); ?>">
                  <i class="bi bi-pencil"></i>
                </button>

                <!-- Delete -->
                <form action="<?php echo e(route('listings.delete', $product->id)); ?>"
                      method="POST"
                      class="d-inline">
                  <?php echo csrf_field(); ?>
                  <?php echo method_field('DELETE'); ?>
                  <button class="btn btn-sm btn-outline-danger"
                          onclick="return confirm('Delete this listing?')">
                    <i class="bi bi-trash"></i>
                  </button>
                </form>

              </div>

            </div>
          </div>

          <!-- Edit Modal -->
          <?php echo $__env->make('components.listing.edit-listing-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>


        <?php if($products->hasPages()): ?>
          <section class="category-pagination section">
            <div class="mt-4 d-flex justify-content-center">
              <?php echo e($products->links()); ?>

            </div>
          </section>
        <?php endif; ?>

      </div>
    </div>
  </section>
</main>

<!-- ================= ADD LISTING MODAL ================= -->

<div class="modal fade" id="addListingModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content p-3">

      <div class="modal-header">
        <h5 class="modal-title">Add New Listing</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <form action="<?php echo e(route('listings.store')); ?>" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>

        <div class="modal-body">

          <input name="name" class="form-control mb-3" placeholder="Product name" required>

          <select name="category_id" class="form-select mb-3" required>
            <option disabled selected>Choose category</option>
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($category->id); ?>"><?php echo e($category->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </select>

          <textarea name="description" class="form-control mb-3"
                    rows="3" placeholder="Description"></textarea>

          <input type="number" name="price"
                 class="form-control mb-3"
                 placeholder="Price (RM)" required>

          <!-- Auto Location -->
          <input type="text"
                 id="add_location"
                 name="location"
                 class="form-control mb-3"
                 placeholder="Click on map to detect location"
                 readonly>

          <!-- Map -->
          <div id="addProductMap"
               style="height:300px;border-radius:8px"></div>

          <input type="hidden" id="add_latitude" name="latitude">
          <input type="hidden" id="add_longitude" name="longitude">

          <input type="file" name="image"
                 class="form-control mt-3"
                 accept="image/*" required>

        </div>

        <div class="modal-footer">
          <button type="button"
                  class="btn btn-secondary"
                  data-bs-dismiss="modal">Cancel</button>
          <button type="submit"
                  class="btn btn-primary">Confirm</button>
        </div>

      </form>
    </div>
  </div>
</div>

<!-- ================= MAP SCRIPTS ================= -->

<script>
document.addEventListener('DOMContentLoaded', function () {

  /* ========== ADD LISTING MAP ========== */
  let addMap, addMarker;

  document.getElementById('addListingModal')
    .addEventListener('shown.bs.modal', function () {

      if (!addMap) {
        addMap = L.map('addProductMap')
          .setView([3.0738, 101.5183], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
          attribution: '&copy; OpenStreetMap'
        }).addTo(addMap);

        addMap.on('click', async function (e) {
          const { lat, lng } = e.latlng;

          if (addMarker) addMap.removeLayer(addMarker);
          addMarker = L.marker([lat, lng]).addTo(addMap);

          document.getElementById('add_latitude').value = lat;
          document.getElementById('add_longitude').value = lng;

          const res = await fetch(
            `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`
          );
          const data = await res.json();

          document.getElementById('add_location').value =
            data.address.city ||
            data.address.town ||
            data.address.suburb ||
            data.address.state ||
            'Unknown';
        });
      }

      addMap.invalidateSize();
  });

  /* ========== EDIT MAPS ========== */
  <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

  let editMap<?php echo e($product->id); ?>, editMarker<?php echo e($product->id); ?>;

  document.getElementById('editModal<?php echo e($product->id); ?>')
    .addEventListener('shown.bs.modal', function () {

      if (!editMap<?php echo e($product->id); ?>) {
        editMap<?php echo e($product->id); ?> =
          L.map('editMap_<?php echo e($product->id); ?>')
           .setView([3.0738, 101.5183], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
          attribution: '&copy; OpenStreetMap'
        }).addTo(editMap<?php echo e($product->id); ?>);

        editMap<?php echo e($product->id); ?>.on('click', async function (e) {
          const { lat, lng } = e.latlng;

          if (editMarker<?php echo e($product->id); ?>)
            editMap<?php echo e($product->id); ?>.removeLayer(editMarker<?php echo e($product->id); ?>);

          editMarker<?php echo e($product->id); ?> =
            L.marker([lat, lng]).addTo(editMap<?php echo e($product->id); ?>);

          document.getElementById('edit_latitude_<?php echo e($product->id); ?>').value = lat;
          document.getElementById('edit_longitude_<?php echo e($product->id); ?>').value = lng;

          const res = await fetch(
            `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`
          );
          const data = await res.json();

          document.getElementById('edit_location_<?php echo e($product->id); ?>').value =
            data.address.city ||
            data.address.town ||
            data.address.suburb ||
            data.address.state ||
            'Unknown';
        });
      }

      editMap<?php echo e($product->id); ?>.invalidateSize();
  });

  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
});
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\fyp\uitmarketplace\resources\views/seller/listings.blade.php ENDPATH**/ ?>