@extends('layouts.app')

@section('content')

@php
  $user = auth()->user();
  $grandTotal = 0;
@endphp

<main class="main">

  <!-- Page Title -->
  <div class="page-title light-background">
    <div class="container d-lg-flex justify-content-between align-items-center">
      <h1 class="mb-2 mb-lg-0">Checkout</h1>
      <nav class="breadcrumbs">
        <ol>
          <li><a href="{{ route('home') }}">Home</a></li>
          <li class="current">Checkout</li>
        </ol>
      </nav>
    </div>
  </div>

  <!-- Checkout Section -->
  <section id="checkout" class="checkout section">
    <div class="container">

      <div class="row">

        <!-- LEFT: CUSTOMER + ADDRESS -->
        <div class="col-lg-7">

          <!-- SINGLE FORM -->
          <form action="{{ route('orders.store') }}" method="POST" class="checkout-container">
            @csrf

            @foreach($grouped as $items)
              @foreach($items as $cart)
                <input type="hidden" name="cart_items[]" value="{{ $cart->id }}">
              @endforeach
            @endforeach

            <!-- Customer Information -->
            <div class="checkout-section">
              <div class="section-header">
                <div class="section-number">1</div>
                <h3>Customer Information</h3>
              </div>
              <div class="section-content">

                <div class="row">
                  <div class="col-md-6 form-group">
                    <label>Full Name</label>
                    <input type="text" name="name" class="form-control"
                           value="{{ $user->name }}">
                  </div>

                  <div class="col-md-6 form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" class="form-control"
                           value="{{ $user->email }}" readonly>
                  </div>
                </div>

                <div class="form-group">
                  <label>Phone Number</label>
                  <input type="text" name="phone" class="form-control"
                         value="{{ $user->phone }}">
                </div>

              </div>
            </div>

            <!-- Shipping Address -->
            <div class="checkout-section">
              <div class="section-header">
                <div class="section-number">2</div>
                <h3>Shipping Address</h3>
              </div>
              <div class="section-content">

                <div class="form-group">
                  <label>Street Address</label>
                  <input type="text" name="address1" class="form-control"
                         value="{{ $user->address1 }}">
                </div>

                <div class="form-group">
                  <label>Apartment / Unit</label>
                  <input type="text" name="address2" class="form-control"
                         value="{{ $user->address2 }}">
                </div>

                <div class="row">
                  <div class="col-md-4 form-group">
                    <label>City</label>
                    <input type="text" name="city" class="form-control"
                           value="{{ $user->city }}">
                  </div>

                  <div class="col-md-4 form-group">
                    <label>State</label>
                    <input type="text" name="state" class="form-control"
                           value="{{ $user->state }}">
                  </div>

                  <div class="col-md-4 form-group">
                    <label>Postcode</label>
                    <input type="text" name="postcode" class="form-control"
                           value="{{ $user->postcode }}">
                  </div>
                </div>

                <div class="form-group">
                  <label>Country</label>
                  <input type="text" name="country" class="form-control"
                         value="{{ $user->country }}">
                </div>

              </div>
            </div>

            <!-- Place Order -->
            <div class="checkout-section" id="order-review">
              <div class="section-header">
                <div class="section-number">3</div>
                <h3>Review &amp; Place Order</h3>
              </div>
              <div class="section-content">

                <div class="place-order-container">
                  <button type="submit" class="btn btn-primary place-order-btn w-100">
                    <span class="btn-text">Place Order</span>
                    <span class="btn-price">
                      RM{{ number_format($grouped->flatten()->sum(fn($i) => $i->product->price), 2) }}
                    </span>
                  </button>
                </div>

              </div>
            </div>

          </form>
          <!-- ✅ END FORM -->

        </div>

        <!-- RIGHT: ORDER SUMMARY -->
        <div class="col-lg-5">

          <div class="order-summary">
            <div class="order-summary-header">
              <h3>Order Summary</h3>
              <span class="item-count">
                {{ $grouped->flatten()->count() }} Items
              </span>
            </div>

            <div class="order-summary-content">

              <div class="order-items">

                @foreach($grouped as $items)
                  @foreach($items as $cart)
                    @php $grandTotal += $cart->product->price; @endphp

                    <div class="order-item">
                      <div class="order-item-image">
                        <img src="{{ $cart->product->image }}" class="img-fluid">
                      </div>

                      <div class="order-item-details">
                        <h4>{{ $cart->product->name }}</h4>
                        <p class="order-item-variant">
                          Seller: {{ $cart->product->user->name }}
                        </p>
                        <div class="order-item-price">
                          <span class="quantity">1 ×</span>
                          <span class="price">
                            RM{{ number_format($cart->product->price, 2) }}
                          </span>
                        </div>
                      </div>
                    </div>

                  @endforeach
                @endforeach

              </div>

              <div class="order-totals mt-4">
                <div class="order-total d-flex justify-content-between fw-bold">
                  <span>Total</span>
                  <span>RM{{ number_format($grandTotal, 2) }}</span>
                </div>
              </div>

            </div>
          </div>

        </div>

      </div>

    </div>
  </section>

</main>

@endsection
