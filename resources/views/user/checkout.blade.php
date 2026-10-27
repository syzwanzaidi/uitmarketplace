@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
@php($count = $grouped->flatten()->count())

<div class="container">
    <x-page-head title="Checkout" subtitle="Confirm your details, then pay securely with FPX."
                 :crumbs="['Cart' => route('cart'), 'Checkout' => null]" />

    <form method="POST" action="{{ route('orders.store') }}" class="row g-4">
        @csrf
        @foreach ($grouped->flatten() as $item)
            <input type="hidden" name="items[]" value="{{ $item->id }}">
        @endforeach

        <div class="col-lg-7">
            <section class="surface surface-pad mb-4">
                <div class="step-title"><span class="num">1</span><h2>Contact</h2></div>
                <div class="row">
                    <x-field class="col-md-6" name="shipping_name" label="Full name" :value="$user->name" required autocomplete="name" />
                    <x-field class="col-md-6" name="shipping_phone" label="Phone number" type="tel" :value="$user->phone" required autocomplete="tel" placeholder="012-345 6789" />
                </div>
                <div class="small text-muted"><i class="bi bi-envelope"></i> Receipt goes to <strong>{{ $user->email }}</strong></div>
            </section>

            <section class="surface surface-pad mb-4">
                <div class="step-title"><span class="num">2</span><h2>Delivery address</h2></div>
                <x-field name="address1" label="Address line 1" :value="$user->address1" required autocomplete="address-line1" placeholder="Street, house or room number" />
                <x-field name="address2" label="Address line 2 (optional)" :value="$user->address2" autocomplete="address-line2" placeholder="College, block, apartment" />
                <div class="row">
                    <x-field class="col-md-4" name="postcode" label="Postcode" :value="$user->postcode" required autocomplete="postal-code" inputmode="numeric" />
                    <x-field class="col-md-4" name="city" label="City" :value="$user->city" required autocomplete="address-level2" />
                    <x-field class="col-md-4" name="state" label="State" :value="$user->state" required autocomplete="address-level1" />
                </div>
                <div class="form-check">
                    <input type="hidden" name="save_address" value="0">
                    <input class="form-check-input" type="checkbox" name="save_address" value="1" id="saveAddress" @checked(old('save_address', ! $user->hasCompleteAddress()))>
                    <label class="form-check-label" for="saveAddress">Save these details to my profile</label>
                </div>
            </section>

            <div class="notice">
                <i class="bi bi-info-circle"></i>
                <div>Items from different sellers become separate orders, but you pay once. Each seller ships or arranges a meetup with you.</div>
            </div>
        </div>

        <div class="col-lg-5">
            <aside class="surface summary">
                <div class="surface-header">
                    <h2>Order summary</h2>
                    <span class="small text-muted">{{ $count }} {{ Str::plural('item', $count) }}</span>
                </div>

                @foreach ($grouped as $items)
                    <div class="px-4 pt-3 small-caps">From {{ $items->first()->product->user->name }}</div>
                    @foreach ($items as $item)
                        <div class="d-flex align-items-center gap-3 px-4 py-2">
                            <img src="{{ $item->product->image_url }}" alt="" class="thumb-xs">
                            <div class="flex-grow-1 min-w-0 small fw-semibold text-truncate">{{ $item->product->name }}</div>
                            <div class="small fw-bold">RM {{ number_format($item->product->price, 2) }}</div>
                        </div>
                    @endforeach
                @endforeach

                <div class="px-4 pb-4 pt-2">
                    <div class="total"><span class="fw-semibold">Total to pay</span><strong>RM {{ number_format($total, 2) }}</strong></div>
                    <button type="submit" class="btn btn-primary btn-lg w-100 mt-3" data-loading="Redirecting to payment…">
                        <i class="bi bi-lock-fill"></i> Pay RM {{ number_format($total, 2) }}
                    </button>
                    <p class="small text-muted text-center mt-3 mb-0">You'll be redirected to ToyyibPay to complete payment.</p>
                </div>
            </aside>
        </div>
    </form>
</div>
@endsection
