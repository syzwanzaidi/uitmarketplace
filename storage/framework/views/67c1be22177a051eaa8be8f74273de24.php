

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

  <!-- SAME WRAPPER AS ACCOUNT PAGE -->
  <section class="account section">
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

        
        
        
        <div class="section-header" data-aos="fade-up">
          <div class="header-actions">
            <form method="GET" action="<?php echo e(route('seller.orders')); ?>">
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
        </div>

        
        
        
        <div class="orders-grid">

          <?php
            $statusClasses = [
                'paid' => 'processing',
                'shipped' => 'shipped',
                'completed' => 'delivered',
                'failed' => 'cancelled',
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

                      <form action="<?php echo e(route('seller.orders.updateTracking', $order->id)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PATCH'); ?>

                        
                        <label class="form-label">Courier</label>
                        <select name="courier" class="form-select mb-3" required>
                          <option value="">Select courier</option>
                          <?php $__currentLoopData = ['Pos Laju', 'J&T Express', 'NinjaVan', 'GD Express', 'DHL']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $courier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($courier); ?>"
                              <?php echo e($order->courier === $courier ? 'selected' : ''); ?>>
                              <?php echo e($courier); ?>

                            </option>
                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>

                        
                        <label class="form-label">Tracking Number</label>
                        <input
                          type="text"
                          name="tracking_number"
                          class="form-control"
                          value="<?php echo e($order->tracking_number); ?>"
                          placeholder="Enter tracking number"
                          required>

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

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\fyp\uitmarketplace\resources\views/seller/orders.blade.php ENDPATH**/ ?>