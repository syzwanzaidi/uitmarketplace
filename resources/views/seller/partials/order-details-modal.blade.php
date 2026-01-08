<div class="modal fade" id="orderDetails{{ $order->id }}" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Order Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <p><strong>Buyer:</strong> {{ $order->buyer->name ?? '-' }}</p>
        <p><strong>Email:</strong> {{ $order->buyer->email ?? '-' }}</p>
        <p><strong>Total:</strong> RM {{ number_format($order->total, 2) }}</p>
        <p>
           <strong>Address:</strong>
            {{ collect([
                $order->buyer->address1,
                $order->buyer->address2,
                $order->buyer->postcode,
                $order->buyer->city,
                $order->buyer->state,
                $order->buyer->country,
            ])->filter()->implode(', ') }}
        </p>
      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
