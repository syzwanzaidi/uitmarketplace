

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
  <section id="seller-dashboard" class="section">
    <div class="container" data-aos="fade-up" data-aos-delay="100">

      <!-- Stats Cards -->
      <div class="row g-4 mb-5">
        <div class="col-md-3 col-sm-6">
          <div class="dashboard-card">
            <i class="bi bi-currency-dollar"></i>
            <h4>Total Sales</h4>
            <p>RM <?php echo e(number_format($totalSales, 2)); ?></p>
          </div>
        </div>

        <div class="col-md-3 col-sm-6">
          <div class="dashboard-card">
            <i class="bi bi-box-seam"></i>
            <h4>Active Listings</h4>
            <p> <?php echo e($activeListingsCount); ?> Items </p>
          </div>
        </div>

        <div class="col-md-3 col-sm-6">
          <div class="dashboard-card"
              style="cursor: pointer"
              onclick="window.location='<?php echo e(route('chat')); ?>'">
            <i class="bi bi-chat-dots"></i>
            <h4>Messages</h4>
            <p>4 New</p>
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
        <a href="#" class="btn btn-header" data-bs-toggle="modal" data-bs-target="#addListingModal">
          <i class="bi bi-plus-lg"></i> Add New Listing
        </a>
      </div>

      <!-- Listings Table Style -->
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
                            <p class="text-muted small mb-0">Category: <?php echo e($product->category->name); ?></p>
                        </div>
                    </div>
                </div>

                <!-- Price -->
                <div class="col-lg-2 col-6 text-center">
                    <span class="fw-bold text-dark">RM <?php echo e(number_format($product->price, 2)); ?></span>
                </div>

                <!-- Status Dropdown -->
                <div class="col-lg-2 col-6 text-center">
                    <form action="<?php echo e(route('listings.updateStatus', $product->id)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PATCH'); ?>

                        <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="active" <?php echo e($product->status == 'active' ? 'selected' : ''); ?>>Active</option>
                            <option value="inactive" <?php echo e($product->status == 'inactive' ? 'selected' : ''); ?>>Inactive</option>
                        </select>
                    </form>
                </div>

                <!-- Edit + Delete -->
                <div class="col-lg-2 col-12 text-center mt-3 mt-lg-0">
                    
                    <!-- Edit Button -->
                    <button class="btn btn-sm btn-outline-secondary me-2" 
                            data-bs-toggle="modal"
                            data-bs-target="#editModal<?php echo e($product->id); ?>">
                        <i class="bi bi-pencil"></i>
                    </button>

                    <!-- Delete -->
                    <form action="<?php echo e(route('listings.delete', $product->id)); ?>" method="POST" class="d-inline">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this listing?')">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>

                </div>
            </div>
        </div>

        <!-- Include edit modal -->
        <?php echo $__env->make('components.listing.edit-listing-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

      </div>

    </div>
  </section>
</main>

<!-- Add Listing Modal -->
<div class="modal fade" id="addListingModal" tabindex="-1" aria-labelledby="addListingLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content p-3">

      <div class="modal-header">
        <h5 class="modal-title" id="addListingLabel">Add New Listing</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <form action="<?php echo e(route('listings.store')); ?>" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>

        <div class="modal-body">

          <!-- Product Name -->
          <div class="mb-3">
            <label class="form-label fw-semibold">Product Name</label>
            <input type="text" name="name" class="form-control" required>
          </div>

          <!-- Category -->
          <div class="mb-3">
            <label class="form-label fw-semibold">Category</label>
            <select name="category_id" class="form-select" required>
              <option disabled selected>Choose category</option>
              <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($category->id); ?>"><?php echo e($category->name); ?></option>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
          </div>

          <!-- Description -->
          <div class="mb-3">
            <label class="form-label fw-semibold">Description</label>
            <textarea name="description" class="form-control" rows="3"></textarea>
          </div>

          <!-- Price -->
          <div class="mb-3">
            <label class="form-label fw-semibold">Price (RM)</label>
            <input type="number" name="price" step="0.01" class="form-control" required>
          </div>

          <!-- Location -->
          <div class="mb-3">
            <label class="form-label fw-semibold">Location</label>
            <input type="text" name="location" class="form-control" placeholder="e.g. Shah Alam" required>
          </div>

          <!-- Image Upload -->
          <div class="mb-3">
            <label class="form-label fw-semibold">Product Image</label>
            <input type="file" name="image" class="form-control" accept="image/*" required>
          </div>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Confirm</button>
        </div>

      </form>

    </div>
  </div>
</div>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\fyp\uitmarketplace\resources\views/seller/listings.blade.php ENDPATH**/ ?>