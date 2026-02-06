

<?php $__env->startSection('content'); ?>
<main class="main">

  <!-- Page Title -->
  <div class="page-title light-background">
    <div class="container d-lg-flex justify-content-between align-items-center">
      <h1>Order History</h1>
      <nav class="breadcrumbs">
        <ol>
          <li><a href="<?php echo e(route('home')); ?>">Home</a></li>
          <li class="current">Order History</li>
        </ol>
      </nav>
    </div>
  </div>

  <section class="account section">
    <div class="container">
      <div class="content-area">

        
        <div class="dashboard-grid mb-5">

          <div class="dashboard-card">
            <i class="bi bi-currency-dollar"></i>
            <h4>Total Sales</h4>
            <p>RM <?php echo e(number_format($totalSales, 2)); ?></p>
          </div>

          <div class="dashboard-card"
               onclick="window.location='<?php echo e(route('listings')); ?>'">
            <i class="bi bi-box-seam"></i>
            <h4>Active Listings</h4>
            <p><?php echo e($activeListingsCount); ?> Items</p>
          </div>

          <div class="dashboard-card"
               onclick="window.location='<?php echo e(route('chat')); ?>'">
            <i class="bi bi-chat-dots"></i>
            <h4>Messages</h4>
            <p>Click to View</p>
          </div>

          <div class="dashboard-card"
               onclick="window.location='<?php echo e(route('seller.orders')); ?>'">
            <i class="bi bi-graph-up"></i>
            <h4>Pending Orders</h4>
            <p><?php echo e($pendingOrdersCount); ?></p>
          </div>

          <div class="dashboard-card active">
            <i class="bi bi-clock-history"></i>
            <h4>Order History</h4>
            <p><?php echo e($completedOrdersCount); ?> Completed</p>
          </div>

        </div>

        
        <div class="section-header mb-4">
          <form method="GET" action="<?php echo e(route('seller.orders.history')); ?>">
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

          <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="order-card">

              
              <div class="order-header">
                <div class="order-id">
                  <span class="label">Order ID:</span>
                  <span class="value">#ORDER-<?php echo e($order->id); ?></span>
                </div>

                <div class="order-date">
                  <?php echo e($order->created_at->format('d M Y')); ?>

                </div>
              </div>

              
              <div class="order-content">

                
                <div class="product-grid">
                  <?php $__currentLoopData = $order->items->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <img src="<?php echo e($item->product->image); ?>" alt="Product">
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
                    <span class="status delivered">COMPLETED</span>
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
                        <strong>Phone:</strong> <?php echo e($order->buyer->phone ?? '-'); ?>

                      </p>
                    </div>

                    
                    <div class="detail-section mb-4">
                      <h5>Items</h5>

                      <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="d-flex align-items-center mb-3">
                          <img src="<?php echo e($item->product->image); ?>" width="70" class="rounded me-3">
                          <div>
                            <strong><?php echo e($item->product->name); ?></strong><br>
                            RM <?php echo e(number_format($item->price, 2)); ?>

                          </div>
                        </div>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    
                    <div class="detail-section">
                      <h5>Shipping</h5>
                      <p>
                        <strong>Courier:</strong> <?php echo e($order->courier ?? '-'); ?> <br>
                        <strong>Tracking:</strong> <?php echo e($order->tracking_number ?? '-'); ?>

                      </p>
                    </div>

                  </div>

                </div>
              </div>
            </div>

          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p>No completed orders yet.</p>
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

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\fyp\uitmarketplace\resources\views/seller/order-history.blade.php ENDPATH**/ ?>