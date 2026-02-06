<?php $__env->startSection('content'); ?>

<?php
  $user = auth()->user();
  $grandTotal = 0;
?>

<main class="main">

  <!-- Page Title -->
  <div class="page-title light-background">
    <div class="container d-lg-flex justify-content-between align-items-center">
      <h1 class="mb-2 mb-lg-0">Checkout</h1>
      <nav class="breadcrumbs">
        <ol>
          <li><a href="<?php echo e(route('home')); ?>">Home</a></li>
          <li class="current">Checkout</li>
        </ol>
      </nav>
    </div>
  </div>

  <!-- Checkout Section -->
  <section id="checkout" class="checkout section">
    <div class="container">

      <div class="row">

        <!-- LEFT: CUSTOMER + ADDRESS -->
        <div class="col-lg-7">

          <!-- SINGLE FORM -->
          <form action="<?php echo e(route('orders.store')); ?>" method="POST" class="checkout-container">
            <?php echo csrf_field(); ?>

            <?php $__currentLoopData = $grouped; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cart): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <input type="hidden" name="cart_items[]" value="<?php echo e($cart->id); ?>">
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <!-- Customer Information -->
            <div class="checkout-section">
              <div class="section-header">
                <div class="section-number">1</div>
                <h3>Customer Information</h3>
              </div>
              <div class="section-content">

                <div class="row">
                  <div class="col-md-6 form-group">
                    <label>Full Name</label>
                    <input type="text" name="name" class="form-control"
                           value="<?php echo e($user->name); ?>">
                  </div>

                  <div class="col-md-6 form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" class="form-control"
                           value="<?php echo e($user->email); ?>" readonly>
                  </div>
                </div>

                <div class="form-group">
                  <label>Phone Number</label>
                  <input type="text" name="phone" class="form-control"
                         value="<?php echo e($user->phone); ?>">
                </div>

              </div>
            </div>

            <!-- Shipping Address -->
            <div class="checkout-section">
              <div class="section-header">
                <div class="section-number">2</div>
                <h3>Shipping Address</h3>
              </div>
              <div class="section-content">

                <div class="form-group">
                  <label>Street Address</label>
                  <input type="text" name="address1" class="form-control"
                         value="<?php echo e($user->address1); ?>">
                </div>

                <div class="form-group">
                  <label>Apartment / Unit</label>
                  <input type="text" name="address2" class="form-control"
                         value="<?php echo e($user->address2); ?>">
                </div>

                <div class="row">
                  <div class="col-md-4 form-group">
                    <label>City</label>
                    <input type="text" name="city" class="form-control"
                           value="<?php echo e($user->city); ?>">
                  </div>

                  <div class="col-md-4 form-group">
                    <label>State</label>
                    <input type="text" name="state" class="form-control"
                           value="<?php echo e($user->state); ?>">
                  </div>

                  <div class="col-md-4 form-group">
                    <label>Postcode</label>
                    <input type="text" name="postcode" class="form-control"
                           value="<?php echo e($user->postcode); ?>">
                  </div>
                </div>

                <div class="form-group">
                  <label>Country</label>
                  <input type="text" name="country" class="form-control"
                         value="<?php echo e($user->country); ?>">
                </div>

              </div>
            </div>

            <!-- Place Order -->
            <div class="checkout-section" id="order-review">
              <div class="section-header">
                <div class="section-number">3</div>
                <h3>Review &amp; Place Order</h3>
              </div>
              <div class="section-content">

                <div class="place-order-container">
                  <button type="submit" class="btn btn-primary place-order-btn w-100">
                    <span class="btn-text">Place Order</span>
                    <span class="btn-price">
                      RM<?php echo e(number_format($grouped->flatten()->sum(fn($i) => $i->product->price), 2)); ?>

                    </span>
                  </button>
                </div>

              </div>
            </div>

          </form>
          <!-- ✅ END FORM -->

        </div>

        <!-- RIGHT: ORDER SUMMARY -->
        <div class="col-lg-5">

          <div class="order-summary">
            <div class="order-summary-header">
              <h3>Order Summary</h3>
              <span class="item-count">
                <?php echo e($grouped->flatten()->count()); ?> Items
              </span>
            </div>

            <div class="order-summary-content">

              <div class="order-items">

                <?php $__currentLoopData = $grouped; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cart): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $grandTotal += $cart->product->price; ?>

                    <div class="order-item">
                      <div class="order-item-image">
                        <img src="<?php echo e($cart->product->image); ?>" class="img-fluid">
                      </div>

                      <div class="order-item-details">
                        <h4><?php echo e($cart->product->name); ?></h4>
                        <p class="order-item-variant">
                          Seller: <?php echo e($cart->product->user->name); ?>

                        </p>
                        <div class="order-item-price">
                          <span class="quantity">1 ×</span>
                          <span class="price">
                            RM<?php echo e(number_format($cart->product->price, 2)); ?>

                          </span>
                        </div>
                      </div>
                    </div>

                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

              </div>

              <div class="order-totals mt-4">
                <div class="order-total d-flex justify-content-between fw-bold">
                  <span>Total</span>
                  <span>RM<?php echo e(number_format($grandTotal, 2)); ?></span>
                </div>
              </div>

            </div>
          </div>

        </div>

      </div>

    </div>
  </section>

</main>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\fyp\uitmarketplace\resources\views/user/checkout.blade.php ENDPATH**/ ?>