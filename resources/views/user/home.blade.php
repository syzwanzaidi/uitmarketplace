@extends('layouts.app')

@section('title', 'Home')

@section('content')
<div class="container">

    <section class="hero">
        <span class="eyebrow"><i class="bi bi-shield-check"></i> Only verified UiTM students can sell</span>
        <h1>Pre-loved finds from your campus.</h1>
        <p class="mb-4">Textbooks, gadgets, hostel essentials and more — from students near you. Chat, meet up, and pay securely.</p>

        <form action="{{ route('browse') }}" method="GET" class="hero-search input-icon">
            <i class="bi bi-search"></i>
            <input type="search" name="search" class="form-control" placeholder="What are you looking for?" aria-label="Search products">
            <button class="btn btn-accent" type="submit">Search</button>
        </form>
    </section>

    <section class="features">
        <div class="surface feature">
            <div class="ico"><i class="bi bi-mortarboard"></i></div>
            <div><h3>Students only</h3><p>Sellers are verified with a UiTM email.</p></div>
        </div>
        <div class="surface feature">
            <div class="ico"><i class="bi bi-chat-heart"></i></div>
            <div><h3>Chat first</h3><p>Ask questions and arrange a meetup.</p></div>
        </div>
        <div class="surface feature">
            <div class="ico"><i class="bi bi-geo-alt"></i></div>
            <div><h3>Near you</h3><p>Filter listings by distance from you.</p></div>
        </div>
        <div class="surface feature">
            <div class="ico"><i class="bi bi-credit-card-2-front"></i></div>
            <div><h3>Secure payment</h3><p>FPX online banking via ToyyibPay.</p></div>
        </div>
    </section>

    @if ($categories->isNotEmpty())
        <section class="section pb-0">
            <div class="section-title">
                <div><h2>Shop by category</h2></div>
            </div>
            <div class="pill-row">
                @foreach ($categories as $category)
                    <a href="{{ route('browse', ['category' => $category->slug]) }}" class="pill">
                        {{ $category->name }} <span class="n">{{ $category->products_count }}</span>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    <section class="section">
        <div class="section-title">
            <div>
                <h2>{{ $location ? 'Near ' . $location['area'] : 'Just listed' }}</h2>
                <p>{{ $location ? "Within {$location['radius']} km of you, closest first." : 'The newest items from UiTM sellers.' }}</p>
            </div>
            <a href="{{ route('browse') }}" class="btn btn-light btn-sm">View all <i class="bi bi-arrow-right"></i></a>
        </div>

        @if ($products->isEmpty())
            <div class="surface">
                @if ($location)
                    <x-empty-state icon="geo-alt" title="Nothing within {{ $location['radius'] }} km yet">
                        Try a bigger radius or a different area.
                        <x-slot:actions>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#locationModal">Change location</button>
                        </x-slot:actions>
                    </x-empty-state>
                @else
                    <x-empty-state icon="bag" title="No listings yet">
                        Be the first to sell something to your fellow students.
                        @can('sell')
                            <x-slot:actions>
                                <a href="{{ route('listings') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Create a listing</a>
                            </x-slot:actions>
                        @endcan
                    </x-empty-state>
                @endif
            </div>
        @else
            <div class="product-grid">
                @foreach ($products as $product)
                    <x-product-card :product="$product" :saved="$savedIds->contains($product->id)" />
                @endforeach
            </div>
        @endif
    </section>

    @can('sell')
        <section class="surface surface-pad d-flex flex-wrap align-items-center justify-content-between gap-3" style="background: var(--accent-50); border-color: #f4dfae">
            <div>
                <h2 class="h5 mb-1">Got something you don't use anymore?</h2>
                <p class="mb-0 text-muted">List it in under a minute and reach students around campus.</p>
            </div>
            <a href="{{ route('listings') }}" class="btn btn-accent"><i class="bi bi-plus-lg"></i> Sell an item</a>
        </section>
    @endcan
</div>
@endsection
