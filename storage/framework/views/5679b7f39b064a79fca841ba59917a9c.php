

<?php $__env->startSection('content'); ?>
<main class="main">

  <!-- Page Title -->
  <div class="page-title light-background">
    <div class="container d-lg-flex justify-content-between align-items-center">
      <h1 class="mb-2 mb-lg-0">Manage Users</h1>
      <nav class="breadcrumbs">
        <ol>
          <li><a href="<?php echo e(route('admin.dashboard')); ?>">Admin</a></li>
          <li class="current">Users</li>
        </ol>
      </nav>
    </div>
  </div>

  <section class="section">
    <div class="container">

      <?php echo $__env->make('admin.partials.dashboard-cards', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

      <!-- Search -->
      <form method="GET" class="mb-4">
        <div class="input-group">
          <input
            type="text"
            name="search"
            class="form-control"
            placeholder="Search by name or email"
            value="<?php echo e(request('search')); ?>"
          >
          <button class="btn btn-primary">Search</button>
        </div>
      </form>

      <!-- Users Table -->
      <div class="table-responsive">
        <table class="table table-bordered align-middle">
          <thead class="table-light">
            <tr>
              <th>#ID</th>
              <th>Name</th>
              <th>Email</th>
              <th>Membership</th>
              <th>Status</th>
              <th class="text-center">Action</th>
            </tr>
          </thead>

          <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
              <tr>
                <td><?php echo e($user->id); ?></td>
                <td><?php echo e($user->name); ?></td>
                <td><?php echo e($user->email); ?></td>
                <td><?php echo e(ucfirst($user->membership_type)); ?></td>

                <!-- STATUS (UPDATED) -->
                <td>
                  <?php if($user->status === 'suspended'): ?>
                    <span class="badge bg-danger">Suspended</span>
                  <?php else: ?>
                    <span class="badge bg-success">Active</span>
                  <?php endif; ?>
                </td>

                <!-- ACTION -->
                <td class="text-center">
                  <form
                    method="POST"
                    action="<?php echo e(route('admin.users.toggle', $user->id)); ?>"
                  >
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PATCH'); ?>

                    <button
                      type="submit"
                      class="btn btn-sm btn-outline-warning"
                      onclick="return confirm('Change user status?')"
                    >
                      <?php echo e($user->status === 'suspended' ? 'Activate' : 'Suspend'); ?>

                    </button>
                  </form>
                </td>
              </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
              <tr>
                <td colspan="6" class="text-center text-muted">
                  No users found
                </td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-3">

        
        <?php if($users->onFirstPage()): ?>
            <button class="btn btn-secondary" disabled>Previous</button>
        <?php else: ?>
            <a href="<?php echo e($users->previousPageUrl()); ?>"
            class="btn btn-outline-primary">
            Previous
            </a>
        <?php endif; ?>

        <span class="text-muted">
            Page <?php echo e($users->currentPage()); ?> of <?php echo e($users->lastPage()); ?>

        </span>

        
        <?php if($users->hasMorePages()): ?>
            <a href="<?php echo e($users->nextPageUrl()); ?>"
            class="btn btn-outline-primary">
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

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\fyp\uitmarketplace\resources\views/admin/users/index.blade.php ENDPATH**/ ?>