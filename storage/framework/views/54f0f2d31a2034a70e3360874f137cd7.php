<?php $__env->startSection('content'); ?>
<main class="main">

  <!-- Page Title -->
  <div class="page-title light-background">
    <div class="container d-lg-flex justify-content-between align-items-center">
      <h1 class="mb-2 mb-lg-0">Admin Dashboard</h1>
      <nav class="breadcrumbs">
        <ol>
          <li>Admin</li>
          <li class="current">Dashboard</li>
        </ol>
      </nav>
    </div>
  </div>

  <!-- Admin Dashboard Section -->
  <section class="section">
    <div class="container" data-aos="fade-up" data-aos-delay="100">

      <!-- Stats Cards -->
      <div class="row g-4 mb-5">

        <!-- Total Users -->
        <div class="col-md-3 col-sm-6">
          <div class="dashboard-card"
               style="cursor:pointer"
               onclick="window.location='<?php echo e(route('admin.users')); ?>'">
            <i class="bi bi-people"></i>
            <h4>Total Users</h4>
            <p><?php echo e($totalUsers); ?></p>
          </div>
        </div>

        <!-- Total Transactions -->
        <div class="col-md-3 col-sm-6">
          <div class="dashboard-card"
               style="cursor:pointer"
               >
            <i class="bi bi-receipt"></i>
            <h4>Total Transactions</h4>
            <p><?php echo e($totalTransactions); ?></p>
          </div>
        </div>

        <!-- Total Sales Value -->
        <div class="col-md-3 col-sm-6">
          <div class="dashboard-card"
               style="cursor:pointer"
               >
            <i class="bi bi-currency-dollar"></i>
            <h4>Total Sales (RM)</h4>
            <p>RM <?php echo e(number_format($totalSalesValue, 2)); ?></p>
          </div>
        </div>

        <!-- Complaints -->
        <div class="col-md-3 col-sm-6">
          <div class="dashboard-card"
               style="cursor:pointer"
               >
            <i class="bi bi-exclamation-circle"></i>
            <h4>Complaints</h4>
            <p><?php echo e($totalComplaints); ?></p>
          </div>
        </div>

      </div>

    </div>
  </section>

</main>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\fyp\uitmarketplace\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>