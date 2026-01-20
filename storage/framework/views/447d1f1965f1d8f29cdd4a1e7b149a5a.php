<!-- Edit Listing Modal -->
<div class="modal fade" id="editModal<?php echo e($product->id); ?>" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">

      <form action="<?php echo e(route('listings.update', $product->id)); ?>"
            method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PATCH'); ?>

        <div class="modal-header">
          <h5 class="modal-title">Edit Listing</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

          <!-- Name -->
          <div class="mb-3">
            <label class="form-label fw-semibold">Product Name</label>
            <input type="text" name="name"
                   class="form-control"
                   value="<?php echo e($product->name); ?>" required>
          </div>

          <!-- Category -->
          <div class="mb-3">
            <label class="form-label fw-semibold">Category</label>
            <select name="category_id" class="form-select" required>
              <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($cat->id); ?>"
                  <?php echo e($product->category_id == $cat->id ? 'selected' : ''); ?>>
                  <?php echo e($cat->name); ?>

                </option>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
          </div>

          <!-- Price -->
          <div class="mb-3">
            <label class="form-label fw-semibold">Price (RM)</label>
            <input type="number" name="price"
                   class="form-control"
                   value="<?php echo e($product->price); ?>" required>
          </div>

          <!-- Location (auto) -->
          <div class="mb-3">
            <label class="form-label fw-semibold">Location</label>
            <input type="text"
                   id="edit_location_<?php echo e($product->id); ?>"
                   name="location"
                   class="form-control"
                   value="<?php echo e($product->location); ?>"
                   readonly>
          </div>

          <!-- Map -->
          <div class="mb-3">
            <label class="form-label fw-semibold">Pin Location</label>
            <div id="editMap_<?php echo e($product->id); ?>"
                 style="height:300px;border-radius:8px;"></div>
          </div>

          <!-- Hidden lat/lng -->
          <input type="hidden" name="latitude"
                 id="edit_latitude_<?php echo e($product->id); ?>">
          <input type="hidden" name="longitude"
                 id="edit_longitude_<?php echo e($product->id); ?>">

          <!-- Description -->
          <div class="mb-3">
            <label class="form-label fw-semibold">Description</label>
            <textarea name="description"
                      class="form-control"><?php echo e($product->description); ?></textarea>
          </div>

          <!-- Image -->
          <div class="mb-3">
            <label class="form-label fw-semibold">Image</label>
            <input type="file" name="image" class="form-control">
          </div>

        </div>

        <div class="modal-footer">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button class="btn btn-primary">Save Changes</button>
        </div>

      </form>
    </div>
  </div>
</div>
<?php /**PATH C:\fyp\uitmarketplace\resources\views/components/listing/edit-listing-modal.blade.php ENDPATH**/ ?>