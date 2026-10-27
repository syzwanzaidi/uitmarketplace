@extends('layouts.app')

@section('title', 'Payment')

@section('content')
@php
    $total = $orders->sum('total');
    [$icon, $tone, $heading, $text] = match ($status) {
        'paid' => ['check-lg', 'success', 'Payment successful', 'Thank you! The seller has been notified and will ship or arrange a meetup soon.'],
        'failed' => ['x-lg', 'danger', 'Payment failed', 'No money was taken. You can try again from My orders.'],
        default => ['hourglass-split', 'warning', 'Payment processing', 'We are waiting for confirmation from the bank. This page will update when you refresh.'],
    };
@endphp

<div class="container" style="max-width: 640px">
    <div class="surface surface-pad text-center mt-5">
        <div class="mx-auto mb-3 d-grid" style="width:72px;height:72px;border-radius:50%;place-items:center;font-size:2rem;background:var(--{{ $tone }}-50);color:var(--{{ $tone }})">
            <i class="bi bi-{{ $icon }}"></i>
        </div>
        <h1 class="h3 mb-2">{{ $heading }}</h1>
        <p class="text-muted mb-4">{{ $text }}</p>

        <div class="text-start border rounded-3 mb-4">
            @foreach ($orders as $order)
                <div class="row-item">
                    <div class="thumb-stack">
                        @foreach ($order->items->take(3) as $item)
                            <img src="{{ $item->product?->image_url }}" alt="" class="thumb-xs">
                        @endforeach
                    </div>
                    <div class="flex-grow-1 min-w-0">
                        <div class="fw-semibold">#{{ $order->reference }}</div>
                        <div class="small text-muted">{{ $order->items->count() }} {{ Str::plural('item', $order->items->count()) }}</div>
                    </div>
                    <div class="text-end">
                        <div class="fw-bold">RM {{ number_format($order->total, 2) }}</div>
                        <x-status-badge :order="$order" />
                    </div>
                </div>
            @endforeach
            <div class="d-flex justify-content-between px-4 py-3 border-top">
                <span class="fw-semibold">Total</span>
                <strong>RM {{ number_format($total, 2) }}</strong>
            </div>
        </div>

        <div class="d-flex flex-wrap justify-content-center gap-2">
            @if ($status === 'pending')
                <a href="{{ request()->fullUrl() }}" class="btn btn-light"><i class="bi bi-arrow-clockwise"></i> Refresh status</a>
            @endif
            <a href="{{ route('buyer.orders') }}" class="btn btn-primary">View my orders</a>
            <a href="{{ route('browse') }}" class="btn btn-light">Continue shopping</a>
        </div>
    </div>
</div>
@endsection
