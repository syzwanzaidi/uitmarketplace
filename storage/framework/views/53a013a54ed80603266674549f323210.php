<div class="modal fade" id="orderDetails<?php echo e($order->id); ?>" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Order Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <p><strong>Buyer:</strong> <?php echo e($order->buyer->name ?? '-'); ?></p>
        <p><strong>Email:</strong> <?php echo e($order->buyer->email ?? '-'); ?></p>
        <p><strong>Total:</strong> RM <?php echo e(number_format($order->total, 2)); ?></p>
      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<?php /**PATH C:\fyp\uitmarketplace\resources\views/seller/partials/order-details-modal.blade.php ENDPATH**/ ?>