@extends('layouts.app')

@section('content')
<main class="main">

  <!-- Page Title -->
  <div class="page-title light-background">
    <div class="container d-lg-flex justify-content-between align-items-center">
      <h1>Order Lists</h1>
      <nav class="breadcrumbs">
        <ol>
          <li><a href="{{ route('home') }}">Home</a></li>
          <li class="current">Seller Orders</li>
        </ol>
      </nav>
    </div>
  </div>

  <!-- IMPORTANT: SAME WRAPPER AS ACCOUNT PAGE -->
  <section class="account section">
    <div class="container">

      <div class="content-area">

        <!-- Section Header -->
        <div class="section-header" data-aos="fade-up">
          <div class="header-actions">
            <div class="search-box">
              <i class="bi bi-search"></i>
              <input type="text" placeholder="Search orders...">
            </div>
          </div>
        </div>

        <!-- Orders Grid -->
        <div class="orders-grid">

          @forelse($orders as $order)
            <div class="order-card" data-aos="fade-up">

              <!-- Header -->
              <div class="order-header">
                <div class="order-id">
                  <span class="label">Order ID:</span>
                  <span class="value">#ORDER-{{ $order->id }}</span>
                  <span class="label">Tracking Number:</span>
                  <span class="value">{{ $order->tracking_number ?? '-' }}</span>
                </div>
                <div class="order-date">
                  {{ $order->created_at->format('d M Y') }}
                </div>
              </div>

              <!-- Content -->
              <div class="order-content">

                <!-- Product Images -->
                <div class="product-grid">
                  @foreach($order->items->take(3) as $item)
                    <img 
                      src="{{ $item->product->image }}" 
                      alt="Product"
                      loading="lazy">
                  @endforeach

                  @if($order->items->count() > 3)
                    <span class="more-items">
                      +{{ $order->items->count() - 3 }}
                    </span>
                  @endif
                </div>

                <!-- Order Info -->
                <div class="order-info">
                  <div class="info-row">
                    <span>Status</span>
                    <span class="status processing">
                      {{ strtoupper($order->status) }}
                    </span>
                  </div>
                  <div class="info-row">
                    <span>Items</span>
                    <span>{{ $order->items->count() }} items</span>
                  </div>
                  <div class="info-row">
                    <span>Total</span>
                    <span class="price">
                      RM {{ number_format($order->total, 2) }}
                    </span>
                  </div>
                </div>

              </div>

              <!-- Footer -->
              <div class="order-footer">
                <button 
                  type="button" 
                  class="btn-details"
                  data-bs-toggle="modal"
                  data-bs-target="#orderDetails{{ $order->id }}">
                  View Details
                </button>
              </div>

            </div>

            <!-- ORDER DETAILS MODAL -->
            <div class="modal fade" id="orderDetails{{ $order->id }}" tabindex="-1">
              <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">

                  <div class="modal-header">
                    <h5 class="modal-title">Order #{{ $order->id }} Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>

                  <div class="modal-body">

                    <!-- Buyer Info -->
                    <div class="detail-section mb-4">
                      <h5>Buyer Information</h5>
                      <p>
                        <strong>Name:</strong> {{ $order->buyer->name }} <br>
                        <strong>Email:</strong> {{ $order->buyer->email }} <br>
                        <strong>Phone:</strong> {{ $order->buyer->phone ?? '-' }} <br>
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

                    <!-- Items -->
                    <div class="detail-section mb-4">
                      <h5>Items</h5>

                      @foreach($order->items as $item)
                        <div class="d-flex align-items-center mb-3">
                          <img 
                            src="{{ $item->product->image }}" 
                            width="70"
                            class="rounded me-3">
                          <div class="flex-grow-1">
                            <strong>{{ $item->product->name }}</strong><br>
                            RM {{ number_format($item->price, 2) }}
                          </div>
                        </div>
                      @endforeach
                    </div>

                    <!-- Tracking -->
                    <div class="detail-section">
                      <h5>Shipping</h5>

                      <form action="{{ route('seller.orders.updateTracking', $order->id) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <label class="form-label">Tracking Number</label>
                        <input 
                          type="text"
                          name="tracking_number"
                          class="form-control mb-3"
                          value="{{ $order->tracking_number }}"
                          placeholder="Enter tracking number">

                        <div class="d-flex justify-content-end mt-4">
                            <button class="btn btn-header">
                                Save Tracking
                            </button>
                        </div>
                      </form>

                    </div>

                  </div>

                </div>
              </div>
            </div>

          @empty
            <p>No orders found.</p>
          @endforelse

        </div>

      </div>
    </div>
  </section>

</main>
@endsection
