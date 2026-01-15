

<?php $__env->startSection('content'); ?>
<main class="main">

  <!-- Page Title -->
  <div class="page-title light-background">
    <div class="container d-lg-flex justify-content-between align-items-center">
      <h1 class="mb-2 mb-lg-0">Transactions</h1>
      <nav class="breadcrumbs">
        <ol>
          <li><a href="<?php echo e(route('admin.dashboard')); ?>">Admin</a></li>
          <li class="current">Transactions</li>
        </ol>
      </nav>
    </div>
  </div>

  <section class="section">
    <div class="container">

      
      <?php echo $__env->make('admin.partials.dashboard-cards', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

      
      <form method="GET" class="mb-4">
        <div class="input-group">
          <input
            type="text"
            name="search"
            class="form-control"
            placeholder="Search bill code, payment ref, buyer or seller email"
            value="<?php echo e(request('search')); ?>"
          >
          <button class="btn btn-primary">Search</button>
        </div>
      </form>

      
      <div class="table-responsive">
        <table class="table table-bordered align-middle">
          <thead class="table-light">
            <tr>
              <th>#Order</th>
              <th>Buyer</th>
              <th>Seller</th>
              <th>Total (RM)</th>
              <th>Status</th>
              <th>Bill Code</th>
              <th>Payment Ref</th>
              <th>Paid At</th>
            </tr>
          </thead>

          <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trx): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
              <tr>
                <td><?php echo e($trx->id); ?></td>
                <td><?php echo e($trx->buyer->email ?? '-'); ?></td>
                <td><?php echo e($trx->seller->email ?? '-'); ?></td>
                <td>RM <?php echo e(number_format($trx->total, 2)); ?></td>

                
                <td>
                  <?php if($trx->status === 'completed'): ?>
                    <span class="badge bg-success">Completed</span>
                  <?php elseif($trx->status === 'paid'): ?>
                    <span class="badge bg-primary">Paid</span>
                  <?php elseif($trx->status === 'failed'): ?>
                    <span class="badge bg-danger">Failed</span>
                  <?php else: ?>
                    <span class="badge bg-secondary"><?php echo e(ucfirst($trx->status)); ?></span>
                  <?php endif; ?>
                </td>

                <td><?php echo e($trx->bill_code ?? '-'); ?></td>
                <td><?php echo e($trx->payment_ref ?? '-'); ?></td>

                
                <td>
                  <?php echo e($trx->paid_at
                      ? $trx->paid_at->format('d/m/Y H:i')
                      : $trx->updated_at->format('d/m/Y H:i')); ?>

                </td>
              </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
              <tr>
                <td colspan="8" class="text-center text-muted">
                  No transactions found
                </td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      
      <div class="d-flex justify-content-between align-items-center mt-3">

        <?php if($transactions->onFirstPage()): ?>
          <button class="btn btn-secondary" disabled>Previous</button>
        <?php else: ?>
          <a href="<?php echo e($transactions->previousPageUrl()); ?>" class="btn btn-outline-primary">
            Previous
          </a>
        <?php endif; ?>

        <span class="text-muted">
          Page <?php echo e($transactions->currentPage()); ?> of <?php echo e($transactions->lastPage()); ?>

        </span>

        <?php if($transactions->hasMorePages()): ?>
          <a href="<?php echo e($transactions->nextPageUrl()); ?>" class="btn btn-outline-primary">
            Next
          </a>
        <?php else: ?>
          <button class="btn btn-secondary" disabled>Next</button>
        <?php endif; ?>

      </div>

    </div>
  </section>

</main>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\fyp\uitmarketplace\resources\views/admin/transactions/index.blade.php ENDPATH**/ ?>