@extends('layouts.app')

@section('content')
<main class="main">
  <div class="container py-5">

    <h1 class="mb-4">My Orders</h1>

    @forelse($orders as $order)
      <div class="card mb-3">
        <div class="card-body">

          <h5>Order #{{ $order->id }}</h5>

          <p>
            Total: 
            <strong>RM{{ number_format($order->total, 2) }}</strong>
          </p>

          <p>
            Status:
            @if($order->status === 'paid')
              <span class="badge bg-success">PAID</span>
            @elseif($order->status === 'failed')
              <span class="badge bg-danger">FAILED</span>
            @else
              <span class="badge bg-warning text-dark">PENDING</span>
            @endif
          </p>

          <small class="text-muted">
            {{ $order->created_at->format('d M Y, H:i') }}
          </small>

        </div>
      </div>
    @empty
      <p>No orders found.</p>
    @endforelse

  </div>
</main>
@endsection
