<div class="tab-pane fade <?php echo e(request('tab') === 'wishlist' ? 'show active' : ''); ?>" id="wishlist">

    <div class="section-header" data-aos="fade-up">
        <h2>My Wishlist</h2>
    </div>

    <div class="wishlist-grid">
        
        <?php $__empty_1 = true; $__currentLoopData = $user->wishlist; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

            <div class="wishlist-card">

                <!-- Image -->
                <div class="wishlist-image">
                    <img src="<?php echo e($item->product->image); ?>" 
                         alt="<?php echo e($item->product->name); ?>" 
                         loading="lazy">

                    <!-- Remove button -->
                    <form action="<?php echo e(route('wishlist.remove', $item->product->id)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button class="btn-remove" type="submit" aria-label="Remove from wishlist">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>

                <!-- Content -->
                <div class="wishlist-content">
                    <h4><?php echo e($item->product->name); ?></h4>

                    <div class="product-meta">
                        <div class="price">
                            <span class="current">
                                RM <?php echo e(number_format($item->product->price, 2)); ?>

                            </span>
                        </div>
                    </div>

                    <button type="button" class="btn-add-cart">Add to Cart</button>
                </div>

            </div>

        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

            <p class="text-center mt-4">No items in your wishlist.</p>

        <?php endif; ?>

    </div>

</div>
<?php /**PATH C:\fyp\uitmarketplace\resources\views/components/account/wishlist.blade.php ENDPATH**/ ?>