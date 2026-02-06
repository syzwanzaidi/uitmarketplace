<?php $__env->startSection('content'); ?>
<main class="main py-5">

<div class="container">
  <div class="content-area">

    <div class="container d-flex justify-content-center">
      <div class="col-lg-6 col-md-8">

        <div class="card shadow-sm border-0 text-center p-4" data-aos="fade-up">

          
          <div class="mb-3">
            <?php if($order->status === 'paid'): ?>
              <i class="bi bi-check-circle-fill text-success" style="font-size:64px;"></i>
            <?php elseif($order->status === 'failed'): ?>
              <i class="bi bi-x-circle-fill text-danger" style="font-size:64px;"></i>
            <?php else: ?>
              <i class="bi bi-clock-fill text-warning" style="font-size:64px;"></i>
            <?php endif; ?>
          </div>

          
          <h2 class="fw-bold mb-2">
            <?php if($order->status === 'paid'): ?>
              Payment Successful
            <?php elseif($order->status === 'failed'): ?>
              Payment Failed
            <?php else: ?>
              Payment Processing
            <?php endif; ?>
          </h2>

          <p class="text-muted mb-4">
            Thank you for your order
          </p>

          
          <div class="border rounded p-3 mb-4 text-start">

            <div class="d-flex justify-content-between mb-2">
              <span class="text-muted">Order ID</span>
              <strong>#ORDER-<?php echo e($order->id); ?></strong>
            </div>

            <div class="d-flex justify-content-between mb-2">
              <span class="text-muted">Total</span>
              <strong>RM <?php echo e(number_format($order->total, 2)); ?></strong>
            </div>

            <div class="d-flex justify-content-between">
              <span class="text-muted">Status</span>
              <span class="badge 
                <?php echo e($order->status === 'paid'
                    ? 'bg-success'
                    : ($order->status === 'failed'
                        ? 'bg-danger'
                        : 'bg-warning text-dark')); ?>">
                <?php echo e(strtoupper($order->status)); ?>

              </span>
            </div>

          </div>

          
          <a href="<?php echo e(route('buyer.orders')); ?>"
              class="btn btn-dark btn-lg w-100">
            View My Orders
          </a>

        </div>

      </div>
    </div>

  </div>
</div>

</main>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\fyp\uitmarketplace\resources\views/user/order-confirmation.blade.php ENDPATH**/ ?>