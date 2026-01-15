<div class="row g-4 mb-5">

  <div class="col-md-3 col-sm-6">
    <div class="dashboard-card" style="cursor:pointer"
         onclick="window.location='<?php echo e(route('admin.users')); ?>'">
      <i class="bi bi-people"></i>
      <h4>Total Users</h4>
      <p><?php echo e($totalUsers); ?></p>
    </div>
  </div>

  <div class="col-md-3 col-sm-6">
    <div class="dashboard-card" style="cursor:pointer"
         onclick="window.location='<?php echo e(route('admin.transactions')); ?>'">
      <i class="bi bi-receipt"></i>
      <h4>Total Transactions</h4>
      <p><?php echo e($totalTransactions); ?></p>
    </div>
  </div>

  <div class="col-md-3 col-sm-6">
    <div class="dashboard-card" style="cursor:pointer"
         onclick="window.location='<?php echo e(route('admin.transactions')); ?>'">
      <i class="bi bi-currency-dollar"></i>
      <h4>Total Sales (RM)</h4>
      <p>RM <?php echo e(number_format($totalSalesValue, 2)); ?></p>
    </div>
  </div>

  <div class="col-md-3 col-sm-6">
    <div class="dashboard-card" style="cursor:pointer"
         onclick="window.location='<?php echo e(route('admin.complaints')); ?>'">
      <i class="bi bi-exclamation-circle"></i>
      <h4>Complaints</h4>
      <p><?php echo e($totalComplaints); ?></p>
    </div>
  </div>

</div>
<?php /**PATH C:\fyp\uitmarketplace\resources\views/admin/partials/dashboard-cards.blade.php ENDPATH**/ ?>