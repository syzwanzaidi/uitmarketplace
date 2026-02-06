@extends('layouts.app')

@section('content')
<main class="main">

  <!-- Page Title -->
  <div class="page-title light-background">
    <div class="container d-lg-flex justify-content-between align-items-center">
      <h1>Order History</h1>
      <nav class="breadcrumbs">
        <ol>
          <li><a href="{{ route('home') }}">Home</a></li>
          <li class="current">Order History</li>
        </ol>
      </nav>
    </div>
  </div>

  <section class="account section">
    <div class="container">
      <div class="content-area">

        {{-- =====================
           DASHBOARD STATS
           ===================== --}}
        <div class="dashboard-grid mb-5">

          <div class="dashboard-card">
            <i class="bi bi-currency-dollar"></i>
            <h4>Total Sales</h4>
            <p>RM {{ number_format($totalSales, 2) }}</p>
          </div>

          <div class="dashboard-card"
               onclick="window.location='{{ route('listings') }}'">
            <i class="bi bi-box-seam"></i>
            <h4>Active Listings</h4>
            <p>{{ $activeListingsCount }} Items</p>
          </div>

          <div class="dashboard-card"
               onclick="window.location='{{ route('chat') }}'">
            <i class="bi bi-chat-dots"></i>
            <h4>Messages</h4>
            <p>Click to View</p>
          </div>

          <div class="dashboard-card"
               onclick="window.location='{{ route('seller.orders') }}'">
            <i class="bi bi-graph-up"></i>
            <h4>Pending Orders</h4>
            <p>{{ $pendingOrdersCount }}</p>
          </div>

          <div class="dashboard-card active">
            <i class="bi bi-clock-history"></i>
            <h4>Order History</h4>
            <p>{{ $completedOrdersCount }} Completed</p>
          </div>

        </div>

        {{-- =====================
           SEARCH
           ===================== --}}
        <div class="section-header mb-4">
          <form method="GET" action="{{ route('seller.orders.history') }}">
            <div class="search-box">
              <i class="bi bi-search"></i>
              <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search by Order ID..."
                class="form-control">
            </div>
          </form>
        </div>

        {{-- =====================
           ORDERS GRID
           ===================== --}}
        <div class="orders-grid">

          @forelse($orders as $order)
            <div class="order-card">

              {{-- Header --}}
              <div class="order-header">
                <div class="order-id">
                  <span class="label">Order ID:</span>
                  <span class="value">#ORDER-{{ $order->id }}</span>
                </div>

                <div class="order-date">
                  {{ $order->created_at->format('d M Y') }}
                </div>
              </div>

              {{-- Content --}}
              <div class="order-content">

                {{-- Product Images --}}
                <div class="product-grid">
                  @foreach($order->items->take(3) as $item)
                    <img src="{{ $item->product->image }}" alt="Product">
                  @endforeach

                  @if($order->items->count() > 3)
                    <span class="more-items">
                      +{{ $order->items->count() - 3 }}
                    </span>
                  @endif
                </div>

                {{-- Info --}}
                <div class="order-info">
                  <div class="info-row">
                    <span>Status</span>
                    <span class="status delivered">COMPLETED</span>
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

              {{-- Footer --}}
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

            {{-- =====================
               DETAILS MODAL
               ===================== --}}
            <div class="modal fade" id="orderDetails{{ $order->id }}" tabindex="-1">
              <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">

                  <div class="modal-header">
                    <h5 class="modal-title">Order #{{ $order->id }} Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>

                  <div class="modal-body">

                    {{-- Buyer --}}
                    <div class="detail-section mb-4">
                      <h5>Buyer Information</h5>
                      <p>
                        <strong>Name:</strong> {{ $order->buyer->name }} <br>
                        <strong>Email:</strong> {{ $order->buyer->email }} <br>
                        <strong>Phone:</strong> {{ $order->buyer->phone ?? '-' }}
                      </p>
                    </div>

                    {{-- Items --}}
                    <div class="detail-section mb-4">
                      <h5>Items</h5>

                      @foreach($order->items as $item)
                        <div class="d-flex align-items-center mb-3">
                          <img src="{{ $item->product->image }}" width="70" class="rounded me-3">
                          <div>
                            <strong>{{ $item->product->name }}</strong><br>
                            RM {{ number_format($item->price, 2) }}
                          </div>
                        </div>
                      @endforeach
                    </div>

                    {{-- Shipping --}}
                    <div class="detail-section">
                      <h5>Shipping</h5>
                      <p>
                        <strong>Courier:</strong> {{ $order->courier ?? '-' }} <br>
                        <strong>Tracking:</strong> {{ $order->tracking_number ?? '-' }}
                      </p>
                    </div>

                  </div>

                </div>
              </div>
            </div>

          @empty
            <p>No completed orders yet.</p>
          @endforelse

        </div>

        @if($orders->hasPages())
            <section class="category-pagination section">
            <div class="mt-4 d-flex justify-content-center">
                {{ $orders->links() }}
            </div>
            </section>
        @endif

      </div>
    </div>
  </section>

</main>
@endsection
