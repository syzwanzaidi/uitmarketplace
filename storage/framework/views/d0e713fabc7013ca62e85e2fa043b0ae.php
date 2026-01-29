<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['product']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['product']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="col-lg-3 col-md-6">
    <div class="product-item">

        <!-- Product Image Section -->
        <div class="product-image">

            
            <?php if($product->status === 'sold'): ?>
                <div class="product-badge bg-danger">SOLD</div>
            <?php endif; ?>

            <img src="<?php echo e($product->image); ?>"
                 alt="<?php echo e($product->name); ?>"
                 class="img-fluid"
                 loading="lazy">

            <!-- Action Buttons -->
            <div class="product-actions">
                <button class="action-btn wishlist-btn">
                    <i class="bi bi-heart"></i>
                </button>

                <button class="action-btn compare-btn">
                    <i class="bi bi-arrow-left-right"></i>
                </button>

                <button class="action-btn quickview-btn">
                    <i class="bi bi-zoom-in"></i>
                </button>
            </div>

            <!-- VIEW BUTTON -->
            <button class="cart-btn"
                    onclick="goToProduct(<?php echo e($product->id); ?>)">
                View
            </button>
        </div>

        <!-- Product Info Section -->
        <div class="product-info">

            <div class="product-category">
                <?php echo e($product->category->name ?? 'Uncategorized'); ?>

            </div>

            <!-- PRODUCT NAME (FIXED) -->
            <h4 class="product-name">
                <a href="javascript:void(0)"
                   onclick="goToProduct(<?php echo e($product->id); ?>)">
                    <?php echo e($product->name); ?>

                </a>
            </h4>

            <div class="product-price">
                RM <?php echo e(number_format($product->price, 2)); ?>

            </div>

            <!-- LOCATION + DISTANCE -->
            <div class="product-location small text-muted mt-1">
                <?php echo e($product->location); ?>


                <?php if(isset($product->distance)): ?>
                    • <?php echo e(number_format($product->distance, 1)); ?> km away
                <?php endif; ?>
            </div>

        </div>

    </div>
</div>

<script>
function goToProduct(productId) {
    const saved = localStorage.getItem('buyer_location');

    if (!saved) {
        window.location.href = `/product/${productId}`;
        return;
    }

    const data = JSON.parse(saved);
    window.location.href =
        `/product/${productId}?lat=${data.lat}&lng=${data.lng}`;
}
</script>
<?php /**PATH C:\fyp\uitmarketplace\resources\views/components/product-card.blade.php ENDPATH**/ ?>