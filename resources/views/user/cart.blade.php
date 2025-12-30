@extends('layouts.app')

@section('content')

<main class="main">

  <div class="page-title light-background">
    <div class="container d-lg-flex justify-content-between align-items-center">
      <h1 class="mb-2 mb-lg-0">Cart</h1>
    </div>
  </div>

  <section id="cart" class="cart section">
    <div class="container">

      {{-- EMPTY CART --}}
      @if($grouped->isEmpty())
        <div class="text-center py-5">
          <h4>Your cart is empty</h4>
          <a href="/" class="btn btn-accent mt-3">Browse Products</a>
        </div>
      @endif

      {{-- =====================
           CHECKOUT FORM (CHECKBOXES ONLY)
           ===================== --}}
      <form action="{{ route('checkout') }}" method="POST" id="checkoutForm">
        @csrf

        @foreach($grouped as $sellerId => $items)
          @php $seller = $items->first()->product->user; @endphp

          <div class="mb-4 p-3 border rounded shadow-sm">

            {{-- SELLER CHECKBOX --}}
            <h5 class="mb-3 d-flex align-items-center">
              <input type="checkbox"
                     class="form-check-input me-2 seller-checkbox"
                     data-seller="{{ $sellerId }}">
              Seller: {{ $seller->name }}
            </h5>

            @foreach($items as $cart)
              <div class="cart-item mb-3 pb-3 border-bottom">
                <div class="row align-items-center">

                  <div class="col-lg-8 d-flex align-items-center">

                    {{-- ITEM CHECKBOX --}}
                    <input type="checkbox"
                           name="cart_items[]"
                           value="{{ $cart->id }}"
                           class="form-check-input me-3 item-checkbox"
                           data-seller="{{ $sellerId }}"
                           data-price="{{ $cart->product->price }}">

                    <img src="{{ $cart->product->image }}" width="80" class="rounded">

                    <div class="ms-3">
                      <h6>{{ $cart->product->name }}</h6>

                      {{-- REMOVE BUTTON (NO FORM HERE) --}}
                      <button type="button"
                              class="btn btn-sm btn-outline-danger mt-2"
                              onclick="submitRemove({{ $cart->id }})">
                        <i class="bi bi-trash"></i> Remove
                      </button>
                    </div>

                  </div>

                  <div class="col-lg-4 text-end">
                    <strong>RM{{ number_format($cart->product->price, 2) }}</strong>
                  </div>

                </div>
              </div>
            @endforeach

          </div>
        @endforeach

      </form>
      {{-- ===== END CHECKOUT FORM ===== --}}

      {{-- =====================
           ORDER SUMMARY
           ===================== --}}
      @if(!$grouped->isEmpty())
        <div class="cart-summary p-4 border rounded shadow-sm">

          <div class="d-flex justify-content-between">
            <span>Total</span>
            <span class="fw-bold" id="grandTotal">RM0.00</span>
          </div>

          {{-- CHECKOUT BUTTON --}}
          <button type="submit"
                  class="btn btn-accent w-100 mt-4"
                  form="checkoutForm">
            Proceed to Checkout
          </button>

          {{-- CLEAR CART --}}
          <form action="{{ route('cart.clear') }}" method="POST" class="mt-2">
            @csrf
            @method('DELETE')
            <button class="btn btn-outline-danger w-100">
              Clear Cart
            </button>
          </form>

        </div>
      @endif

      {{-- =====================
           HIDDEN REMOVE FORMS (OUTSIDE CHECKOUT FORM)
           ===================== --}}
      @foreach($grouped as $items)
        @foreach($items as $cart)
          <form id="remove-form-{{ $cart->id }}"
                action="{{ route('cart.remove', $cart->id) }}"
                method="POST"
                class="d-none">
            @csrf
            @method('DELETE')
          </form>
        @endforeach
      @endforeach

    </div>
  </section>

</main>

{{-- =====================
     JS
     ===================== --}}
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

@endsection
