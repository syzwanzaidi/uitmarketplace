<?php $__env->startSection('content'); ?>

<main class="main">

  <div class="page-title light-background">
    <div class="container d-lg-flex justify-content-between align-items-center">
      <h1 class="mb-2 mb-lg-0">Cart</h1>
    </div>
  </div>

  <section id="cart" class="cart section">
    <div class="container">

      
      <?php if($grouped->isEmpty()): ?>
        <div class="text-center py-5">
          <h4>Your cart is empty</h4>
          <a href="/" class="btn btn-accent mt-3">Browse Products</a>
        </div>
      <?php endif; ?>

      
      <form action="<?php echo e(route('checkout')); ?>" method="POST" id="checkoutForm">
        <?php echo csrf_field(); ?>

        <?php $__currentLoopData = $grouped; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sellerId => $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <?php $seller = $items->first()->product->user; ?>

          <div class="mb-4 p-3 border rounded shadow-sm">

            
            <h5 class="mb-3 d-flex align-items-center">
              <input type="checkbox"
                     class="form-check-input me-2 seller-checkbox"
                     data-seller="<?php echo e($sellerId); ?>">
              Seller: <?php echo e($seller->name); ?>

            </h5>

            <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cart): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <div class="cart-item mb-3 pb-3 border-bottom">
                <div class="row align-items-center">

                  <div class="col-lg-8 d-flex align-items-center">

                    
                    <input type="checkbox"
                           name="cart_items[]"
                           value="<?php echo e($cart->id); ?>"
                           class="form-check-input me-3 item-checkbox"
                           data-seller="<?php echo e($sellerId); ?>"
                           data-price="<?php echo e($cart->product->price); ?>">

                    <img src="<?php echo e($cart->product->image); ?>" width="80" class="rounded">

                    <div class="ms-3">
                      <h6><?php echo e($cart->product->name); ?></h6>

                      
                      <button type="button"
                              class="btn btn-sm btn-outline-danger mt-2"
                              onclick="submitRemove(<?php echo e($cart->id); ?>)">
                        <i class="bi bi-trash"></i> Remove
                      </button>
                    </div>

                  </div>

                  <div class="col-lg-4 text-end">
                    <strong>RM<?php echo e(number_format($cart->product->price, 2)); ?></strong>
                  </div>

                </div>
              </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

          </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

      </form>
      

      
      <?php if(!$grouped->isEmpty()): ?>
        <div class="cart-summary p-4 border rounded shadow-sm">

          <div class="d-flex justify-content-between">
            <span>Total</span>
            <span class="fw-bold" id="grandTotal">RM0.00</span>
          </div>

          
          <button type="submit"
                  class="btn btn-accent w-100 mt-4"
                  form="checkoutForm">
            Proceed to Checkout
          </button>

          
          <form action="<?php echo e(route('cart.clear')); ?>" method="POST" class="mt-2">
            <?php echo csrf_field(); ?>
            <?php echo method_field('DELETE'); ?>
            <button class="btn btn-outline-danger w-100">
              Clear Cart
            </button>
          </form>

        </div>
      <?php endif; ?>

      
      <?php $__currentLoopData = $grouped; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cart): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <form id="remove-form-<?php echo e($cart->id); ?>"
                action="<?php echo e(route('cart.remove', $cart->id)); ?>"
                method="POST"
                class="d-none">
            <?php echo csrf_field(); ?>
            <?php echo method_field('DELETE'); ?>
          </form>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    </div>
  </section>

</main>


<script>
document.addEventListener('DOMContentLoaded', () => {

  const items = document.querySelectorAll('.item-checkbox');
  const sellers = document.querySelectorAll('.seller-checkbox');
  const total = document.getElementById('grandTotal');

  function updateTotal() {
    let sum = 0;
    items.forEach(i => {
      if (i.checked) sum += parseFloat(i.dataset.price);
    });
    total.textContent = 'RM' + sum.toFixed(2);
  }

  function syncSeller(id) {
    const group = [...items].filter(i => i.dataset.seller === id);
    const seller = document.querySelector(`.seller-checkbox[data-seller="${id}"]`);
    seller.checked = group.every(i => i.checked);
  }

  items.forEach(i => {
    i.addEventListener('change', () => {
      updateTotal();
      syncSeller(i.dataset.seller);
    });
  });

  sellers.forEach(s => {
    s.addEventListener('change', () => {
      items.forEach(i => {
        if (i.dataset.seller === s.dataset.seller) {
          i.checked = s.checked;
        }
      });
      updateTotal();
    });
  });

});

function submitRemove(id) {
  if (confirm('Remove this item from cart?')) {
    document.getElementById('remove-form-' + id).submit();
  }
}
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\fyp\uitmarketplace\resources\views/user/cart.blade.php ENDPATH**/ ?>