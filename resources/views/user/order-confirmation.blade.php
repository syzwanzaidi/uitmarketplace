@extends('layouts.app')

@section('content')
<main class="main py-5">

<div class="container">
  <div class="content-area">

    <div class="container d-flex justify-content-center">
      <div class="col-lg-6 col-md-8">

        <div class="card shadow-sm border-0 text-center p-4" data-aos="fade-up">

          {{-- STATUS ICON --}}
          <div class="mb-3">
            @if($order->status === 'paid')
              <i class="bi bi-check-circle-fill text-success" style="font-size:64px;"></i>
            @elseif($order->status === 'failed')
              <i class="bi bi-x-circle-fill text-danger" style="font-size:64px;"></i>
            @else
              <i class="bi bi-clock-fill text-warning" style="font-size:64px;"></i>
            @endif
          </div>

          {{-- TITLE --}}
          <h2 class="fw-bold mb-2">
            @if($order->status === 'paid')
              Payment Successful
            @elseif($order->status === 'failed')
              Payment Failed
            @else
              Payment Processing
            @endif
          </h2>

          <p class="text-muted mb-4">
            Thank you for your order
          </p>

          {{-- ORDER INFO --}}
          <div class="border rounded p-3 mb-4 text-start">

            <div class="d-flex justify-content-between mb-2">
              <span class="text-muted">Order ID</span>
              <strong>#ORDER-{{ $order->id }}</strong>
            </div>

            <div class="d-flex justify-content-between mb-2">
              <span class="text-muted">Total</span>
              <strong>RM {{ number_format($order->total, 2) }}</strong>
            </div>

            <div class="d-flex justify-content-between">
              <span class="text-muted">Status</span>
              <span class="badge 
                {{ $order->status === 'paid'
                    ? 'bg-success'
                    : ($order->status === 'failed'
                        ? 'bg-danger'
                        : 'bg-warning text-dark') }}">
                {{ strtoupper($order->status) }}
              </span>
            </div>

          </div>

          {{-- ACTION --}}
          <a href="{{ route('buyer.orders') }}"
              class="btn btn-dark btn-lg w-100">
            View My Orders
          </a>

        </div>

      </div>
    </div>

  </div>
</div>

</main>
@endsection
