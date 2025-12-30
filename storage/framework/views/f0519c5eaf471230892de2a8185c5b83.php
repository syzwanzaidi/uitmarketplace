

<?php $__env->startSection('content'); ?>
<main class="main">
  <div class="container py-5">
    <h1>My Orders</h1>

    <p>This page will display all orders made by the user.</p>


    <a href="<?php echo e(route('home')); ?>" class="btn btn-primary mt-3">
      Back to Home
    </a>
  </div>
</main>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\fyp\uitmarketplace\resources\views/user/orders.blade.php ENDPATH**/ ?>