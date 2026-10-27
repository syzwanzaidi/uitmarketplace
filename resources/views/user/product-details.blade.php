@extends('layouts.app')

@section('title', $product->name)

@section('content')
@php
    $seller = $product->user;
    $sold = $product->status === \App\Models\Product::STATUS_SOLD;
    $hidden = $product->status === \App\Models\Product::STATUS_INACTIVE;
@endphp

<div class="container">
    <nav class="crumbs pt-4" aria-label="Breadcrumb">
        <a href="{{ route('home') }}">Home</a>
        <a href="{{ route('browse', ['category' => $product->category?->slug]) }}">{{ $product->category->name ?? 'Browse' }}</a>
        <span aria-current="page" class="text-truncate">{{ $product->name }}</span>
    </nav>

    <div class="row g-4 g-lg-5 mt-0">
        <div class="col-lg-7">
            <div class="gallery-main">
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
            </div>

            <div class="surface surface-pad mt-4">
                <h2 class="h6 mb-3">Description</h2>
                <div class="prose-text">{{ $product->description ?: 'The seller has not added a description.' }}</div>
            </div>

            @if ($product->locationPoint)
                <div class="surface surface-pad mt-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2 class="h6 mb-0">Meetup area</h2>
                        <span class="small text-muted"><i class="bi bi-geo-alt"></i> {{ $product->location }}</span>
                    </div>
                    <div class="map-box" data-static-map data-lat="{{ $product->locationPoint->latitude }}" data-lng="{{ $product->locationPoint->longitude }}"></div>
                    <p class="small text-muted mt-2 mb-0">Approximate area. Agree on an exact spot with the seller in chat.</p>
                </div>
            @endif
        </div>

        <div class="col-lg-5">
            <div class="sticky-col">
                <div class="surface surface-pad">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="small-caps text-brand">{{ $product->category->name ?? 'Other' }}</span>
                        @if ($sold)
                            <x-status-badge tone="danger" label="Sold" />
                        @elseif ($hidden)
                            <x-status-badge tone="muted" label="Hidden from buyers" />
                        @else
                            <x-status-badge tone="success" label="Available" />
                        @endif
                    </div>
                    <h1 class="h3 mb-2">{{ $product->name }}</h1>
                    <div class="detail-price mb-3">RM {{ number_format($product->price, 2) }}</div>

                    <div class="detail-facts mb-4">
                        <div class="fact">
                            <i class="bi bi-geo-alt"></i>
                            <div class="min-w-0">
                                <small>Location</small>
                                <strong class="d-block text-truncate">{{ $product->location ?: 'Campus' }}</strong>
                            </div>
                        </div>
                        <div class="fact">
                            <i class="bi bi-signpost-2"></i>
                            <div>
                                <small>Distance</small>
                                <strong>
                                    @if ($distance !== null)
                                        {{ $distance < 1 ? round($distance * 1000) . ' m' : number_format($distance, 1) . ' km' }}
                                    @else
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#locationModal">Set location</a>
                                    @endif
                                </strong>
                            </div>
                        </div>
                        <div class="fact">
                            <i class="bi bi-calendar3"></i>
                            <div><small>Listed</small><strong>{{ $product->created_at->diffForHumans() }}</strong></div>
                        </div>
                        <div class="fact">
                            <i class="bi bi-box"></i>
                            <div><small>Quantity</small><strong>1 unit</strong></div>
                        </div>
                    </div>

                    @if ($isOwner)
                        <div class="notice mb-3"><i class="bi bi-info-circle"></i><div>This is your listing.</div></div>
                        <a href="{{ route('listings') }}" class="btn btn-primary w-100"><i class="bi bi-pencil"></i> Manage in Seller centre</a>
                    @elseif ($sold || $hidden)
                        <div class="notice warn"><i class="bi bi-exclamation-triangle"></i><div>This item is no longer available.</div></div>
                        <a href="{{ route('browse', ['category' => $product->category?->slug]) }}" class="btn btn-light w-100 mt-3">See similar items</a>
                    @else
                        <div class="d-grid gap-2">
                            <form method="POST" action="{{ route('buy.now', $product) }}">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-lg w-100"><i class="bi bi-lightning-charge"></i> Buy now</button>
                            </form>
                            <div class="d-flex gap-2">
                                @if ($inCart)
                                    <a href="{{ route('cart') }}" class="btn btn-outline-primary flex-grow-1"><i class="bi bi-bag-check"></i> In your cart</a>
                                @else
                                    <form method="POST" action="{{ route('cart.add', $product) }}" class="flex-grow-1">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-primary w-100"><i class="bi bi-bag-plus"></i> Add to cart</button>
                                    </form>
                                @endif
                                <form method="POST" action="{{ route('wishlist.toggle', $product) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-light {{ $inWishlist ? 'text-danger' : '' }}" style="height: 100%" aria-label="{{ $inWishlist ? 'Remove from wishlist' : 'Save to wishlist' }}">
                                        <i class="bi bi-heart{{ $inWishlist ? '-fill' : '' }}"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="surface surface-pad mt-3">
                    <div class="seller-card">
                        <x-avatar :user="$seller" class="avatar-lg" style="--size: 52px" />
                        <div class="flex-grow-1 min-w-0">
                            <div class="small text-muted">Sold by</div>
                            <div class="fw-bold text-truncate">{{ $seller->name }}</div>
                            <div class="small text-muted">
                                <i class="bi bi-patch-check-fill text-brand"></i> UiTM member · {{ $sellerListings }} active {{ Str::plural('listing', $sellerListings) }}
                            </div>
                        </div>
                    </div>
                    @unless ($isOwner)
                        <form method="POST" action="{{ route('chat.start', $product) }}" class="mt-3">
                            @csrf
                            <button type="submit" class="btn btn-light w-100"><i class="bi bi-chat-dots"></i> Chat with seller</button>
                        </form>
                    @endunless
                </div>
            </div>
        </div>
    </div>

    @if ($related->isNotEmpty())
        <section class="section">
            <div class="section-title"><h2>More in {{ $product->category->name }}</h2></div>
            <div class="product-grid">
                @foreach ($related as $item)
                    <x-product-card :product="$item" :saved="$savedIds->contains($item->id)" />
                @endforeach
            </div>
        </section>
    @endif
</div>
@endsection
