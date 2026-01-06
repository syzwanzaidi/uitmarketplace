@extends('layouts.app')

@section('content')
<main class="main">
  <div class="container py-5 text-center">

    <h1 class="mb-3">
      @if($order->status === 'paid')
        Payment Successful
      @elseif($order->status === 'failed')
        Payment Failed
      @else
        Payment Processing
      @endif
    </h1>

    <p>Order ID: <strong>#{{ $order->id }}</strong></p>

    <p>
      Total:
      <strong>RM{{ number_format($order->total, 2) }}</strong>
    </p>

    <p>
      Status:
      <span class="badge 
        {{ $order->status === 'paid' ? 'bg-success' : ($order->status === 'failed' ? 'bg-danger' : 'bg-warning text-dark') }}">
        {{ strtoupper($order->status) }}
      </span>
    </p>

    <a href="{{ route('account.orders') }}" class="btn btn-primary mt-4">
      View My Orders
    </a>

  </div>
</main>
@endsection
