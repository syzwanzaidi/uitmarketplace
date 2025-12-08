@extends('layouts.app')

@section('content')

<main class="main">

  <div class="page-title light-background">
    <div class="container d-lg-flex justify-content-between align-items-center">
      <h1 class="mb-2 mb-lg-0">Cart</h1>
      <nav class="breadcrumbs">
        <ol>
          <li><a href="{{ route('home') }}">Home</a></li>
          <li class="current">Cart</li>
        </ol>
      </nav>
    </div>
  </div>

  <section id="cart" class="cart section">
    <div class="container">

      {{-- Empty cart --}}
      @if($grouped->isEmpty())
        <div class="text-center py-5">
          <h4>Your cart is empty</h4>
          <a href="/" class="btn btn-accent mt-3">Browse Products</a>
        </div>
      @endif

      @php $grandTotal = 0; @endphp

      @foreach($grouped as $sellerId => $items)
        @php
          $seller = $items->first()->product->user;
          $sellerSubtotal = $items->sum(fn($i) => $i->product->price);
          $grandTotal += $sellerSubtotal;
        @endphp

        {{-- Seller Section --}}
        <div class="mb-4 p-3 border rounded shadow-sm">
          <h5 class="mb-3">
            Seller: {{ $seller->name }}
          </h5>

          @foreach($items as $cart)
            <div class="cart-item mb-3 pb-3 border-bottom">
              <div class="row align-items-center">

                {{-- Product Info --}}
                <div class="col-lg-8">
                  <div class="product-info d-flex align-items-center">
                    <img src="{{ $cart->product->image }}" class="rounded" width="80">

                    <div class="ms-3">
                      <h6 class="product-title">{{ $cart->product->name }}</h6>
                      <span class="text-muted small">ID: {{ $cart->product_id }}</span>

                      {{-- Remove Button --}}
                      <form action="{{ route('cart.remove', $cart->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger mt-2">
                          <i class="bi bi-trash"></i> Remove
                        </button>
                      </form>
                    </div>
                  </div>
                </div>

                {{-- Price (only one line) --}}
                <div class="col-lg-4 text-end">
                  <span class="fw-bold text-dark">
                    RM{{ number_format($cart->product->price, 2) }}
                  </span>
                </div>

              </div>
            </div>
          @endforeach


          {{-- Seller Subtotal --}}
          <div class="text-end fw-bold">
            Seller Subtotal: RM{{ number_format($sellerSubtotal, 2) }}
          </div>

        </div>
      @endforeach

      {{-- Grand Total Section --}}
      @if(!$grouped->isEmpty())
        <div class="cart-summary p-4 border rounded shadow-sm">
          <h4 class="mb-3">Order Summary</h4>

          <div class="d-flex justify-content-between">
            <span>Subtotal</span>
            <span>RM{{ number_format($grandTotal, 2) }}</span>
          </div>

          <div class="d-flex justify-content-between mt-2">
            <span>Tax (0%)</span>
            <span>RM0.00</span>
          </div>

          <hr>

          <div class="d-flex justify-content-between fw-bold">
            <span>Total</span>
            <span>RM{{ number_format($grandTotal, 2) }}</span>
          </div>

          <a href="#" class="btn btn-accent w-100 mt-4">
            Proceed to Checkout
          </a>

          <a href="/" class="btn btn-link w-100 mt-2">
            <i class="bi bi-arrow-left"></i> Continue Shopping
          </a>
        </div>
      @endif

    </div>
  </section>

</main>

@endsection
