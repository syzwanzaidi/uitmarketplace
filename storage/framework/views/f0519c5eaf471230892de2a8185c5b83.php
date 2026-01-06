

<?php $__env->startSection('content'); ?>
<main class="main">
  <div class="container py-5">

    <h1 class="mb-4">My Orders</h1>

    <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
      <div class="card mb-3">
        <div class="card-body">

          <h5>Order #<?php echo e($order->id); ?></h5>

          <p>
            Total: 
            <strong>RM<?php echo e(number_format($order->total, 2)); ?></strong>
          </p>

          <p>
            Status:
            <?php if($order->status === 'paid'): ?>
              <span class="badge bg-success">PAID</span>
            <?php elseif($order->status === 'failed'): ?>
              <span class="badge bg-danger">FAILED</span>
            <?php else: ?>
              <span class="badge bg-warning text-dark">PENDING</span>
            <?php endif; ?>
          </p>

          <small class="text-muted">
            <?php echo e($order->created_at->format('d M Y, H:i')); ?>

          </small>

        </div>
      </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
      <p>No orders found.</p>
    <?php endif; ?>

  </div>
</main>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\fyp\uitmarketplace\resources\views/user/orders.blade.php ENDPATH**/ ?>