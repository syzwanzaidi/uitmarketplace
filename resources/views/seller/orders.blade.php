@extends('layouts.app')

@section('title', 'Seller orders')

@section('content')
@php
    $tabLabels = ['to_ship' => 'To ship', 'shipped' => 'Shipped', 'completed' => 'Completed'];
@endphp

<div class="container">
    <x-page-head title="Seller centre" subtitle="Ship paid orders and add tracking so buyers can follow along." />

    @include('seller.partials.stats')

    <div class="d-flex flex-wrap gap-3 justify-content-between align-items-center mb-3">
        <nav class="seg" aria-label="Order status">
            @foreach ($tabLabels as $key => $label)
                <a href="{{ route('seller.orders', ['tab' => $key]) }}" class="{{ $tab === $key ? 'active' : '' }}">
                    {{ $label }} <span class="n">{{ $counts[$key] }}</span>
                </a>
            @endforeach
        </nav>
        <form method="GET" class="input-icon" style="min-width: 220px">
            <input type="hidden" name="tab" value="{{ $tab }}">
            <i class="bi bi-search"></i>
            <input type="search" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search order no.">
        </form>
    </div>

    @forelse ($orders as $order)
        <article class="surface order-card mb-3">
            <div class="order-top">
                <div class="kv"><small>Order</small><strong>#{{ $order->reference }}</strong></div>
                <div class="kv"><small>Paid</small><strong>{{ $order->paid_at?->format('d M Y') ?? '—' }}</strong></div>
                <div class="kv"><small>Buyer</small><strong>{{ $order->buyer->name ?? '—' }}</strong></div>
                <div class="ms-auto"><x-status-badge :order="$order" /></div>
            </div>

            <div class="row g-0">
                <div class="col-md-7">
                    @foreach ($order->items as $item)
                        <div class="row-item">
                            <img src="{{ $item->product?->image_url }}" alt="" class="thumb-xs">
                            <div class="flex-grow-1 min-w-0 fw-semibold text-truncate">{{ $item->product->name ?? 'Item removed' }}</div>
                            <div class="fw-bold">RM {{ number_format($item->price, 2) }}</div>
                        </div>
                    @endforeach
                </div>
                <div class="col-md-5 border-start-md p-4 small" style="background: #fcfbfe">
                    <div class="small-caps mb-1">Deliver to</div>
                    <div class="fw-semibold">{{ $order->shipping_name ?? $order->buyer->name }}</div>
                    <div class="text-muted">{{ $order->shipping_phone ?? $order->buyer->phone }}</div>
                    <div class="text-muted">{{ $order->shipping_address ?? $order->buyer->full_address ?: 'No address provided' }}</div>
                    @if ($order->tracking_number)
                        <div class="small-caps mt-3 mb-1">Tracking</div>
                        <div><i class="bi bi-truck"></i> {{ $order->courier }} · <span class="fw-semibold user-select-all">{{ $order->tracking_number }}</span></div>
                    @endif
                </div>
            </div>

            <div class="order-actions">
                <span class="me-auto align-self-center">Total <strong class="fs-5 ms-1">RM {{ number_format($order->total, 2) }}</strong></span>
                @if ($order->buyer)
                    <a href="mailto:{{ $order->buyer->email }}" class="btn btn-light btn-sm"><i class="bi bi-envelope"></i> Email buyer</a>
                @endif
                @if ($order->status === \App\Models\Order::STATUS_PAID)
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#ship-{{ $order->id }}"><i class="bi bi-truck"></i> Ship order</button>
                @elseif ($order->status === \App\Models\Order::STATUS_SHIPPED)
                    <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#ship-{{ $order->id }}"><i class="bi bi-pencil"></i> Edit tracking</button>
                @endif
            </div>
        </article>

        @if (in_array($order->status, [\App\Models\Order::STATUS_PAID, \App\Models\Order::STATUS_SHIPPED], true))
            @push('modals')
                <div class="modal fade" id="ship-{{ $order->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <form method="POST" action="{{ route('seller.orders.ship', $order) }}" class="modal-content">
                            @csrf
                            @method('PATCH')
                            <div class="modal-header">
                                <h2 class="modal-title">Ship #{{ $order->reference }}</h2>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label" for="courier-{{ $order->id }}">Courier</label>
                                    <select id="courier-{{ $order->id }}" name="courier" class="form-select" required>
                                        <option value="">Choose courier</option>
                                        @foreach ($couriers as $courier)
                                            <option value="{{ $courier }}" @selected($order->courier === $courier)>{{ $courier }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="form-label" for="tracking-{{ $order->id }}">Tracking number / meetup note</label>
                                    <input id="tracking-{{ $order->id }}" name="tracking_number" class="form-control" value="{{ $order->tracking_number }}" required maxlength="255"
                                           placeholder="e.g. EP123456789MY or “Handed over at Library, 3 PM”">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Mark as shipped</button>
                            </div>
                        </form>
                    </div>
                </div>
            @endpush
        @endif
    @empty
        <div class="surface">
            <x-empty-state icon="receipt" title="Nothing here">
                @if ($tab === 'to_ship')
                    No paid orders are waiting to be shipped. New orders appear here once the buyer pays.
                @else
                    Orders will appear here as they move along.
                @endif
            </x-empty-state>
        </div>
    @endforelse

    <div class="mt-4 d-flex justify-content-center">{{ $orders->links() }}</div>
</div>
@endsection
