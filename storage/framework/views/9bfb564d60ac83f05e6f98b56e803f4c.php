<?php $__env->startSection('content'); ?>
<main class="main">
  <div class="container py-5 text-center">

    <h1 class="mb-3">
      <?php if($order->status === 'paid'): ?>
        Payment Successful
      <?php elseif($order->status === 'failed'): ?>
        Payment Failed
      <?php else: ?>
        Payment Processing
      <?php endif; ?>
    </h1>

    <p>Order ID: <strong>#<?php echo e($order->id); ?></strong></p>

    <p>
      Total:
      <strong>RM<?php echo e(number_format($order->total, 2)); ?></strong>
    </p>

    <p>
      Status:
      <span class="badge 
        <?php echo e($order->status === 'paid' ? 'bg-success' : ($order->status === 'failed' ? 'bg-danger' : 'bg-warning text-dark')); ?>">
        <?php echo e(strtoupper($order->status)); ?>

      </span>
    </p>

    <a href="<?php echo e(route('account.orders')); ?>" class="btn btn-primary mt-4">
      View My Orders
    </a>

  </div>
</main>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\fyp\uitmarketplace\resources\views/user/order-confirmation.blade.php ENDPATH**/ ?>