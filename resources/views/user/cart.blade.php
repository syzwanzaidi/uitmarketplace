@extends('layouts.app')

@section('title', 'Cart')

@section('content')
<div class="container">
    <x-page-head title="Your cart" :subtitle="$grouped->flatten()->count() . ' ' . Str::plural('item', $grouped->flatten()->count())">
        @if ($grouped->isNotEmpty())
            <form method="POST" action="{{ route('cart.clear') }}" data-confirm="Remove every item from your cart?">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-ghost btn-sm text-danger"><i class="bi bi-trash"></i> Clear cart</button>
            </form>
        @endif
    </x-page-head>

    @if ($grouped->isEmpty())
        <div class="surface">
            <x-empty-state icon="bag" title="Your cart is empty">
                Items you add will show up here, grouped by seller.
                <x-slot:actions>
                    <a href="{{ route('browse') }}" class="btn btn-primary">Start browsing</a>
                </x-slot:actions>
            </x-empty-state>
        </div>
    @else
        <div class="row g-4">
            <div class="col-lg-8">
                <form id="cartForm" method="GET" action="{{ route('checkout') }}">
                    <label class="d-flex align-items-center gap-2 mb-3 fw-semibold small">
                        <input type="checkbox" class="form-check-input m-0" data-cart-all> Select all available items
                    </label>

                    @foreach ($grouped as $sellerId => $items)
                        @php($seller = $items->first()->product->user)
                        <div class="surface mb-3">
                            <div class="seller-group-head">
                                <input type="checkbox" class="form-check-input m-0" data-cart-seller="{{ $sellerId }}" aria-label="Select all from {{ $seller->name }}">
                                <x-avatar :user="$seller" class="avatar-sm" />
                                <div class="fw-semibold">{{ $seller->name }}</div>
                                <span class="small text-muted ms-auto">Ships as one order</span>
                            </div>

                            @foreach ($items as $item)
                                @php($available = $item->product->isAvailable())
                                <div class="row-item cart-row {{ $available ? '' : 'is-unavailable' }}">
                                    @if ($available)
                                        <input type="checkbox" name="items[]" value="{{ $item->id }}" class="form-check-input m-0 flex-shrink-0"
                                               data-cart-item data-seller="{{ $sellerId }}" data-price="{{ $item->product->price }}"
                                               aria-label="Select {{ $item->product->name }}" checked>
                                    @else
                                        <input type="checkbox" class="form-check-input m-0 flex-shrink-0" disabled aria-label="Unavailable">
                                    @endif
                                    <a href="{{ route('product.show', $item->product) }}">
                                        <img src="{{ $item->product->image_url }}" alt="" class="thumb-sm">
                                    </a>
                                    <div class="flex-grow-1 min-w-0">
                                        <a href="{{ route('product.show', $item->product) }}" class="fw-semibold text-body d-block text-truncate">{{ $item->product->name }}</a>
                                        <div class="small text-muted">{{ $item->product->category->name ?? '' }}</div>
                                        @unless ($available)
                                            <x-status-badge tone="danger" label="No longer available" class="mt-1" />
                                        @endunless
                                    </div>
                                    <div class="text-end">
                                        <div class="fw-bold mb-1">RM {{ number_format($item->product->price, 2) }}</div>
                                        <button type="submit" form="remove-{{ $item->id }}" class="btn btn-ghost btn-sm text-danger px-2" aria-label="Remove {{ $item->product->name }}">
                                            <i class="bi bi-trash"></i> <span class="d-none d-sm-inline">Remove</span>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </form>

                @foreach ($grouped->flatten() as $item)
                    <form id="remove-{{ $item->id }}" method="POST" action="{{ route('cart.remove', $item) }}" class="d-none">
                        @csrf
                        @method('DELETE')
                    </form>
                @endforeach
            </div>

            <div class="col-lg-4">
                <aside class="surface surface-pad summary">
                    <h2 class="h6 mb-3">Summary</h2>
                    <div class="line"><span>Selected items</span><span data-cart-count>0</span></div>
                    <div class="line"><span>Delivery</span><span>Arranged with seller</span></div>
                    <div class="total"><span class="fw-semibold">Total</span><strong data-cart-total>RM 0.00</strong></div>
                    <button type="submit" form="cartForm" class="btn btn-primary btn-lg w-100 mt-3" data-cart-submit>
                        Checkout <i class="bi bi-arrow-right"></i>
                    </button>
                    <p class="small text-muted text-center mt-3 mb-0"><i class="bi bi-lock"></i> Secure payment with ToyyibPay (FPX)</p>
                </aside>
            </div>
        </div>
    @endif
</div>
@endsection
