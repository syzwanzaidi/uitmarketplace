

<?php $__env->startSection('content'); ?>
<main class="main">

  <!-- Page Title -->
  <div class="page-title light-background">
    <div class="container d-lg-flex justify-content-between align-items-center">
      <h1>My Orders</h1>
      <nav class="breadcrumbs">
        <ol>
          <li><a href="<?php echo e(route('home')); ?>">Home</a></li>
          <li class="current">My Orders</li>
        </ol>
      </nav>
    </div>
  </div>

  <!-- SAME WRAPPER AS ACCOUNT PAGE -->
  <section class="account section">
    <div class="container">
      <div class="content-area">

        
        
        
        <div class="section-header" data-aos="fade-up">
          <form method="GET" action="<?php echo e(route('buyer.orders')); ?>">
            <div class="search-box">
              <i class="bi bi-search"></i>
              <input
                type="text"
                name="search"
                value="<?php echo e(request('search')); ?>"
                placeholder="Search by Order ID..."
                class="form-control">
            </div>
          </form>
        </div>

        
        
        
        <div class="orders-grid">

          <?php
            $statusClasses = [
              'pending'   => 'processing',
              'paid'      => 'processing',
              'shipped'   => 'shipped',
              'completed' => 'delivered',
              'failed'    => 'cancelled',
            ];
          ?>

          <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="order-card" data-aos="fade-up">

              
              <div class="order-header">
                <div class="order-id">
                  <span class="label">Order ID:</span>
                  <span class="value">#ORDER-<?php echo e($order->id); ?></span>

                  <span class="label ms-3">Tracking:</span>
                  <span class="value">
                    <?php if($order->tracking_number): ?>
                      <?php echo e($order->tracking_number); ?>

                      <?php if($order->courier): ?>
                        (<?php echo e($order->courier); ?>)
                      <?php endif; ?>
                    <?php else: ?>
                      -
                    <?php endif; ?>
                  </span>
                </div>

                <div class="order-date">
                  <?php echo e($order->created_at->format('d M Y')); ?>

                </div>
              </div>

              
              <div class="order-content">

                
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

                
                <div class="order-info">
                  <div class="info-row">
                    <span>Status</span>
                    <span class="status <?php echo e($statusClasses[$order->status] ?? 'processing'); ?>">
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

              
              <div class="order-footer">
                <button
                  type="button"
                  class="btn-details"
                  data-bs-toggle="modal"
                  data-bs-target="#orderDetails<?php echo e($order->id); ?>">
                  View Details
                </button>

                
                <?php if($order->status === 'shipped'): ?>
                  <form
                    action="<?php echo e(route('buyer.orders.received', $order->id)); ?>"
                    method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PATCH'); ?>

                    <button class="btn btn-header">
                      Mark as Received
                    </button>
                  </form>
                <?php endif; ?>
              </div>

            </div>

            
            
            
            <div class="modal fade" id="orderDetails<?php echo e($order->id); ?>" tabindex="-1">
              <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">

                  <div class="modal-header">
                    <h5 class="modal-title">Order #<?php echo e($order->id); ?> Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>

                  <div class="modal-body">

                    
                    <div class="detail-section mb-4">
                      <h5>Seller Information</h5>
                      <p>
                        <strong>Name:</strong> <?php echo e($order->seller->name ?? '-'); ?> <br>
                        <strong>Email:</strong> <?php echo e($order->seller->email ?? '-'); ?> <br>
                        <strong>Phone:</strong> <?php echo e($order->seller->phone ?? '-'); ?>

                      </p>
                    </div>

                    
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

                    
                    <div class="detail-section">
                      <h5>Shipping</h5>

                      <p>
                        <strong>Courier:</strong>
                        <?php echo e($order->courier ?? 'Not assigned yet'); ?>

                      </p>

                      <p>
                        <strong>Tracking Number:</strong>
                        <?php echo e($order->tracking_number ?? 'Not available yet'); ?>

                      </p>
                    </div>

                  </div>

                </div>
              </div>
            </div>

          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p>No orders found.</p>
          <?php endif; ?>

        </div>

        <?php if($orders->hasPages()): ?>
          <section class="category-pagination section">
            <div class="mt-4 d-flex justify-content-center">
              <?php echo e($orders->links()); ?>

            </div>
          </section>
        <?php endif; ?>

      </div>
    </div>
  </section>

</main>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\fyp\uitmarketplace\resources\views/user/orders.blade.php ENDPATH**/ ?>