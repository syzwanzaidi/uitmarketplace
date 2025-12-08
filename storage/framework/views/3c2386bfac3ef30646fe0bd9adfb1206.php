<?php $__env->startSection('content'); ?>

<main class="main">

  <div class="page-title light-background">
    <div class="container d-lg-flex justify-content-between align-items-center">
      <h1 class="mb-2 mb-lg-0">Cart</h1>
      <nav class="breadcrumbs">
        <ol>
          <li><a href="<?php echo e(route('home')); ?>">Home</a></li>
          <li class="current">Cart</li>
        </ol>
      </nav>
    </div>
  </div>

  <section id="cart" class="cart section">
    <div class="container">

      
      <?php if($grouped->isEmpty()): ?>
        <div class="text-center py-5">
          <h4>Your cart is empty</h4>
          <a href="/" class="btn btn-accent mt-3">Browse Products</a>
        </div>
      <?php endif; ?>

      <?php $grandTotal = 0; ?>

      <?php $__currentLoopData = $grouped; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sellerId => $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
          $seller = $items->first()->product->user;
          $sellerSubtotal = $items->sum(fn($i) => $i->product->price);
          $grandTotal += $sellerSubtotal;
        ?>

        
        <div class="mb-4 p-3 border rounded shadow-sm">
          <h5 class="mb-3">
            Seller: <?php echo e($seller->name); ?>

          </h5>

          <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cart): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="cart-item mb-3 pb-3 border-bottom">
              <div class="row align-items-center">

                
                <div class="col-lg-8">
                  <div class="product-info d-flex align-items-center">
                    <img src="<?php echo e($cart->product->image); ?>" class="rounded" width="80">

                    <div class="ms-3">
                      <h6 class="product-title"><?php echo e($cart->product->name); ?></h6>
                      <span class="text-muted small">ID: <?php echo e($cart->product_id); ?></span>

                      
                      <form action="<?php echo e(route('cart.remove', $cart->id)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button class="btn btn-sm btn-outline-danger mt-2">
                          <i class="bi bi-trash"></i> Remove
                        </button>
                      </form>
                    </div>
                  </div>
                </div>

                
                <div class="col-lg-4 text-end">
                  <span class="fw-bold text-dark">
                    RM<?php echo e(number_format($cart->product->price, 2)); ?>

                  </span>
                </div>

              </div>
            </div>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


          
          <div class="text-end fw-bold">
            Seller Subtotal: RM<?php echo e(number_format($sellerSubtotal, 2)); ?>

          </div>

        </div>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

      
      <?php if(!$grouped->isEmpty()): ?>
        <div class="cart-summary p-4 border rounded shadow-sm">
          <h4 class="mb-3">Order Summary</h4>

          <div class="d-flex justify-content-between">
            <span>Subtotal</span>
            <span>RM<?php echo e(number_format($grandTotal, 2)); ?></span>
          </div>

          <div class="d-flex justify-content-between mt-2">
            <span>Tax (0%)</span>
            <span>RM0.00</span>
          </div>

          <hr>

          <div class="d-flex justify-content-between fw-bold">
            <span>Total</span>
            <span>RM<?php echo e(number_format($grandTotal, 2)); ?></span>
          </div>

          <a href="#" class="btn btn-accent w-100 mt-4">
            Proceed to Checkout
          </a>

          <a href="/" class="btn btn-link w-100 mt-2">
            <i class="bi bi-arrow-left"></i> Continue Shopping
          </a>
        </div>
      <?php endif; ?>

    </div>
  </section>

</main>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\fyp\uitmarketplace\resources\views/user/cart.blade.php ENDPATH**/ ?>