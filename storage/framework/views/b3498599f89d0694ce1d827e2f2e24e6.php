

<?php $__env->startSection('content'); ?>
<main class="main">

  <div class="page-title light-background">
    <div class="container d-lg-flex justify-content-between align-items-center">
      <h1 class="mb-2 mb-lg-0">Complaints</h1>
      <nav class="breadcrumbs">
        <ol>
          <li><a href="<?php echo e(route('admin.dashboard')); ?>">Admin</a></li>
          <li class="current">Complaints</li>
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
            placeholder="Search subject or user email"
            value="<?php echo e(request('search')); ?>"
          >
          <button class="btn btn-primary">Search</button>
        </div>
      </form>

      
      <div class="table-responsive">
        <table class="table table-bordered align-middle">
          <thead class="table-light">
            <tr>
              <th>#</th>
              <th>User</th>
              <th>Subject</th>
              <th>Message</th>
              <th>Status</th>
              <th>Date</th>
              <th class="text-center">Action</th>
            </tr>
          </thead>

          <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $complaints; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
              <tr>
                <td><?php echo e($c->id); ?></td>
                <td><?php echo e($c->user->email); ?></td>
                <td><?php echo e($c->subject); ?></td>
                <td><?php echo e(\Illuminate\Support\Str::limit($c->message, 50)); ?></td>

                <td>
                  <?php if($c->status === 'resolved'): ?>
                    <span class="badge bg-success">Resolved</span>
                  <?php else: ?>
                    <span class="badge bg-warning text-dark">Open</span>
                  <?php endif; ?>
                </td>

                <td><?php echo e($c->created_at->format('d/m/Y')); ?></td>

                <td class="text-center">
                  <form
                    method="POST"
                    action="<?php echo e(route('admin.complaints.toggle', $c->id)); ?>"
                  >
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PATCH'); ?>
                    <button
                      class="btn btn-sm btn-outline-primary"
                      onclick="return confirm('Change complaint status?')"
                    >
                      <?php echo e($c->status === 'open' ? 'Resolve' : 'Reopen'); ?>

                    </button>
                  </form>
                </td>
              </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
              <tr>
                <td colspan="7" class="text-center text-muted">
                  No complaints found
                </td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      
      <div class="d-flex justify-content-between align-items-center mt-3">

        <?php if($complaints->onFirstPage()): ?>
          <button class="btn btn-secondary" disabled>Previous</button>
        <?php else: ?>
          <a href="<?php echo e($complaints->previousPageUrl()); ?>"
             class="btn btn-outline-primary">
            Previous
          </a>
        <?php endif; ?>

        <span class="text-muted">
          Page <?php echo e($complaints->currentPage()); ?> of <?php echo e($complaints->lastPage()); ?>

        </span>

        <?php if($complaints->hasMorePages()): ?>
          <a href="<?php echo e($complaints->nextPageUrl()); ?>"
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

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\fyp\uitmarketplace\resources\views/admin/complaints/index.blade.php ENDPATH**/ ?>