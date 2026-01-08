

<?php $__env->startSection('content'); ?>
<main class="main">

  <!-- Page Title -->
  <div class="page-title light-background">
    <div class="container d-lg-flex justify-content-between align-items-center">
      <h1>Order Lists</h1>
      <nav class="breadcrumbs">
        <ol>
          <li><a href="<?php echo e(route('home')); ?>">Home</a></li>
          <li class="current">Seller Orders</li>
        </ol>
      </nav>
    </div>
  </div>

  <!-- IMPORTANT: SAME WRAPPER AS ACCOUNT PAGE -->
  <section class="account section">
    <div class="container">

      <div class="content-area">

        <!-- Section Header -->
        <div class="section-header" data-aos="fade-up">
          <div class="header-actions">
            <div class="search-box">
              <i class="bi bi-search"></i>
              <input type="text" placeholder="Search orders...">
            </div>
          </div>
        </div>

        <!-- Orders Grid -->
        <div class="orders-grid">

          <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="order-card" data-aos="fade-up">

              <!-- Header -->
              <div class="order-header">
                <div class="order-id">
                  <span class="label">Order ID:</span>
                  <span class="value">#ORDER-<?php echo e($order->id); ?></span>
                  <span class="label">Tracking Number:</span>
                  <span class="value"><?php echo e($order->tracking_number ?? '-'); ?></span>
                </div>
                <div class="order-date">
                  <?php echo e($order->created_at->format('d M Y')); ?>

                </div>
              </div>

              <!-- Content -->
              <div class="order-content">

                <!-- Product Images -->
                <div class="product-grid">
                  <?php $__currentLoopData = $order->items->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <img 
                      src="<?php echo e($item->product->image); ?>" 
                      alt="Product"
                      loading="lazy">
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                  <?php if($order->items->count() > 3): ?>
                    <span class="more-items">
                      +<?php echo e($order->items->count() - 3); ?>

                    </span>
                  <?php endif; ?>
                </div>

                <!-- Order Info -->
                <div class="order-info">
                  <div class="info-row">
                    <span>Status</span>
                    <span class="status processing">
                      <?php echo e(strtoupper($order->status)); ?>

                    </span>
                  </div>
                  <div class="info-row">
                    <span>Items</span>
                    <span><?php echo e($order->items->count()); ?> items</span>
                  </div>
                  <div class="info-row">
                    <span>Total</span>
                    <span class="price">
                      RM <?php echo e(number_format($order->total, 2)); ?>

                    </span>
                  </div>
                </div>

              </div>

              <!-- Footer -->
              <div class="order-footer">
                <button 
                  type="button" 
                  class="btn-details"
                  data-bs-toggle="modal"
                  data-bs-target="#orderDetails<?php echo e($order->id); ?>">
                  View Details
                </button>
              </div>

            </div>

            <!-- ORDER DETAILS MODAL -->
            <div class="modal fade" id="orderDetails<?php echo e($order->id); ?>" tabindex="-1">
              <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">

                  <div class="modal-header">
                    <h5 class="modal-title">Order #<?php echo e($order->id); ?> Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>

                  <div class="modal-body">

                    <!-- Buyer Info -->
                    <div class="detail-section mb-4">
                      <h5>Buyer Information</h5>
                      <p>
                        <strong>Name:</strong> <?php echo e($order->buyer->name); ?> <br>
                        <strong>Email:</strong> <?php echo e($order->buyer->email); ?> <br>
                        <strong>Phone:</strong> <?php echo e($order->buyer->phone ?? '-'); ?> <br>
                        <strong>Address:</strong>
                            <?php echo e(collect([
                                $order->buyer->address1,
                                $order->buyer->address2,
                                $order->buyer->postcode,
                                $order->buyer->city,
                                $order->buyer->state,
                                $order->buyer->country,
                            ])->filter()->implode(', ')); ?>

                      </p>
                    </div>

                    <!-- Items -->
                    <div class="detail-section mb-4">
                      <h5>Items</h5>

                      <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="d-flex align-items-center mb-3">
                          <img 
                            src="<?php echo e($item->product->image); ?>" 
                            width="70"
                            class="rounded me-3">
                          <div class="flex-grow-1">
                            <strong><?php echo e($item->product->name); ?></strong><br>
                            RM <?php echo e(number_format($item->price, 2)); ?>

                          </div>
                        </div>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    <!-- Tracking -->
                    <div class="detail-section">
                      <h5>Shipping</h5>

                      <form action="<?php echo e(route('seller.orders.updateTracking', $order->id)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PATCH'); ?>

                        <label class="form-label">Tracking Number</label>
                        <input 
                          type="text"
                          name="tracking_number"
                          class="form-control mb-3"
                          value="<?php echo e($order->tracking_number); ?>"
                          placeholder="Enter tracking number">

                        <div class="d-flex justify-content-end mt-4">
                            <button class="btn btn-header">
                                Save Tracking
                            </button>
                        </div>
                      </form>

                    </div>

                  </div>

                </div>
              </div>
            </div>

          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p>No orders found.</p>
          <?php endif; ?>

        </div>

      </div>
    </div>
  </section>

</main>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\fyp\uitmarketplace\resources\views/seller/orders.blade.php ENDPATH**/ ?>