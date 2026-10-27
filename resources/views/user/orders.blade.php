@extends('layouts.app')

@section('title', 'My orders')

@section('content')
@use('App\Models\Order')
@php
    $tabs = [
        'all' => 'All',
        Order::STATUS_PENDING => 'To pay',
        Order::STATUS_PAID => 'To ship',
        Order::STATUS_SHIPPED => 'To receive',
        Order::STATUS_COMPLETED => 'Completed',
        Order::STATUS_CANCELLED => 'Cancelled',
    ];
    $progress = [Order::STATUS_PAID => 1, Order::STATUS_SHIPPED => 2, Order::STATUS_COMPLETED => 3];
@endphp

<div class="container">
    <x-page-head title="My orders" subtitle="Track purchases, confirm deliveries and retry payments." />

    <div class="d-flex flex-wrap gap-3 justify-content-between align-items-center mb-4">
        <nav class="seg" aria-label="Order status">
            @foreach ($tabs as $key => $label)
                <a href="{{ route('buyer.orders', ['status' => $key === 'all' ? null : $key]) }}" class="{{ $tab === $key ? 'active' : '' }}">{{ $label }}</a>
            @endforeach
        </nav>
        <form method="GET" class="input-icon" style="min-width: 240px">
            <input type="hidden" name="status" value="{{ $tab === 'all' ? '' : $tab }}">
            <i class="bi bi-search"></i>
            <input type="search" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search order no.">
        </form>
    </div>

    @forelse ($orders as $order)
        <article class="surface order-card mb-3">
            <div class="order-top">
                <div class="kv"><small>Order</small><strong>#{{ $order->reference }}</strong></div>
                <div class="kv"><small>Placed</small><strong>{{ $order->created_at->format('d M Y') }}</strong></div>
                <div class="kv"><small>Seller</small><strong>{{ $order->seller->name ?? '—' }}</strong></div>
                <div class="ms-auto"><x-status-badge :order="$order" /></div>
            </div>

            @foreach ($order->items as $item)
                <div class="row-item">
                    <img src="{{ $item->product?->image_url }}" alt="" class="thumb-sm">
                    <div class="flex-grow-1 min-w-0">
                        @if ($item->product)
                            <a href="{{ route('product.show', $item->product) }}" class="fw-semibold text-body d-block text-truncate">{{ $item->product->name }}</a>
                        @else
                            <span class="fw-semibold text-muted">Item removed</span>
                        @endif
                    </div>
                    <div class="fw-bold">RM {{ number_format($item->price, 2) }}</div>
                </div>
            @endforeach

            @isset($progress[$order->status])
                <div class="px-4 pb-3">
                    <ol class="steps">
                        <li class="done">Paid</li>
                        <li class="{{ $progress[$order->status] >= 2 ? 'done' : '' }}">Shipped</li>
                        <li class="{{ $progress[$order->status] >= 3 ? 'done' : '' }}">Received</li>
                    </ol>
                    @if ($order->tracking_number)
                        <div class="small text-muted text-center mt-3">
                            <i class="bi bi-truck"></i> {{ $order->courier }} · <span class="fw-semibold text-body user-select-all">{{ $order->tracking_number }}</span>
                        </div>
                    @endif
                </div>
            @endisset

            <div class="order-actions">
                <span class="me-auto align-self-center">Total <strong class="fs-5 ms-1">RM {{ number_format($order->total, 2) }}</strong></span>

                <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#order-{{ $order->id }}">Details</button>

                @if ($order->status === Order::STATUS_PENDING)
                    <form method="POST" action="{{ route('orders.cancel', $order) }}" data-confirm="Cancel this unpaid order?">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn btn-ghost btn-sm text-danger">Cancel</button>
                    </form>
                    <form method="POST" action="{{ route('orders.pay', $order) }}">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-sm" data-loading="Redirecting…"><i class="bi bi-credit-card"></i> Pay now</button>
                    </form>
                @elseif ($order->status === Order::STATUS_SHIPPED)
                    <form method="POST" action="{{ route('buyer.orders.received', $order) }}" data-confirm="Confirm you have received this order? This completes the order.">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-check2-circle"></i> I've received it</button>
                    </form>
                @endif
            </div>
        </article>

        @push('modals')
            <div class="modal fade" id="order-{{ $order->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2 class="modal-title">Order #{{ $order->reference }}</h2>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="small-caps mb-2">Seller</div>
                            <p class="mb-4">
                                <strong>{{ $order->seller->name ?? '—' }}</strong><br>
                                <span class="text-muted">{{ $order->seller->email ?? '' }}</span>
                                @if ($order->seller?->phone)<br><span class="text-muted">{{ $order->seller->phone }}</span>@endif
                            </p>

                            <div class="small-caps mb-2">Deliver to</div>
                            <p class="mb-4">
                                <strong>{{ $order->shipping_name ?? $order->buyer->name }}</strong>
                                @if ($order->shipping_phone) · {{ $order->shipping_phone }} @endif<br>
                                <span class="text-muted">{{ $order->shipping_address ?? $order->buyer->full_address ?: '—' }}</span>
                            </p>

                            <div class="small-caps mb-2">Timeline</div>
                            <ul class="list-unstyled small mb-0">
                                <li class="mb-1"><i class="bi bi-dot"></i> Placed {{ $order->created_at->format('d M Y, g:i A') }}</li>
                                @if ($order->paid_at)<li class="mb-1"><i class="bi bi-dot"></i> Paid {{ $order->paid_at->format('d M Y, g:i A') }} @if ($order->payment_ref)<span class="text-muted">(ref {{ $order->payment_ref }})</span>@endif</li>@endif
                                @if ($order->shipped_at)<li class="mb-1"><i class="bi bi-dot"></i> Shipped {{ $order->shipped_at->format('d M Y, g:i A') }}</li>@endif
                                @if ($order->completed_at)<li><i class="bi bi-dot"></i> Completed {{ $order->completed_at->format('d M Y, g:i A') }}</li>@endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endpush
    @empty
        <div class="surface">
            <x-empty-state icon="box-seam" title="{{ request('search') ? 'No matching orders' : 'No orders here yet' }}">
                {{ request('search') ? 'Check the order number and try again.' : 'When you buy something, you can track it here.' }}
                <x-slot:actions>
                    <a href="{{ route('browse') }}" class="btn btn-primary">Browse items</a>
                </x-slot:actions>
            </x-empty-state>
        </div>
    @endforelse

    <div class="mt-4 d-flex justify-content-center">{{ $orders->links() }}</div>
</div>
@endsection
