@extends('layouts.app')

@section('content')

<main class="main">

  {{-- PAGE TITLE --}}
  <div class="page-title light-background">
    <div class="container d-lg-flex justify-content-between align-items-center">
      <h1 class="mb-2 mb-lg-0">Cart</h1>
    </div>
  </div>

  <section id="cart" class="cart section">
    <div class="container">

      {{-- =====================
           EMPTY CART
           ===================== --}}
      @if($grouped->isEmpty())
        <div class="d-flex flex-column align-items-center justify-content-center py-5 text-center">
          <i class="bi bi-cart-x" style="font-size:64px;color:#ccc;"></i>

          <h4 class="mt-3">Your cart is empty</h4>
          <p class="text-muted mb-4">
            Looks like you haven’t added anything yet
          </p>

          <a href="{{ route('home') }}" class="btn btn-dark px-4">
            Browse Products
          </a>
        </div>
      @endif

      {{-- =====================
           CHECKOUT FORM
           ===================== --}}
      <form action="{{ route('checkout') }}" method="POST" id="checkoutForm">
        @csrf

        @foreach($grouped as $sellerId => $items)
          @php $seller = $items->first()->product->user; @endphp

          <div class="mb-4 p-4 border rounded-3 shadow-sm bg-white">

            {{-- SELLER HEADER --}}
            <div class="d-flex align-items-center mb-3">
              <input type="checkbox"
                     class="form-check-input me-2 seller-checkbox"
                     data-seller="{{ $sellerId }}">
              <h5 class="mb-0 fw-semibold">
                Seller: {{ $seller->name }}
              </h5>
            </div>

            {{-- ITEMS --}}
            @foreach($items as $cart)
              <div class="cart-item mb-3 pb-3 border-bottom">
                <div class="row align-items-center g-3">

                  {{-- LEFT --}}
                  <div class="col-lg-8 d-flex align-items-center">

                    <input type="checkbox"
                           name="cart_items[]"
                           value="{{ $cart->id }}"
                           class="form-check-input me-3 item-checkbox"
                           data-seller="{{ $sellerId }}"
                           data-price="{{ $cart->product->price }}">

                    <img src="{{ $cart->product->image }}"
                         width="80"
                         height="80"
                         class="rounded object-fit-cover">

                    <div class="ms-3">
                      <h6 class="mb-1">{{ $cart->product->name }}</h6>

                      <button type="button"
                              class="btn btn-sm btn-outline-danger mt-2"
                              onclick="submitRemove({{ $cart->id }})">
                        <i class="bi bi-trash"></i> Remove
                      </button>
                    </div>

                  </div>

                  {{-- RIGHT --}}
                  <div class="col-lg-4 text-end">
                    <div class="fw-semibold fs-6">
                      RM{{ number_format($cart->product->price, 2) }}
                    </div>
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
        <div class="cart-summary p-4 border rounded-3 shadow-sm bg-white">

          <div class="d-flex justify-content-between fs-5 mb-3">
            <span>Total</span>
            <span class="fw-bold" id="grandTotal">RM0.00</span>
          </div>

          <button type="submit"
                  class="btn btn-dark w-100 py-2"
                  form="checkoutForm">
            Proceed to Checkout
          </button>

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
           HIDDEN REMOVE FORMS
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
     JS (UNCHANGED)
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
