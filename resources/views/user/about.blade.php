@extends('layouts.app')

@section('title', 'About')

@section('content')
<div class="container" style="max-width: 920px">
    <x-page-head title="About UiTMarketplace" subtitle="A Final Year Project at the Faculty of Computer & Mathematical Sciences, UiTM Shah Alam." />

    <div class="surface surface-pad mb-4">
        <p class="mb-3">
            Students buy and sell a lot of things to each other — textbooks after a semester ends, hostel furniture,
            calculators, lab coats. That usually happens in scattered group chats, with no record of who paid what.
        </p>
        <p class="mb-0">
            UiTMarketplace brings it into one place. Sellers are verified with a UiTM email, buyers can see how far
            away an item is, talk to the seller before buying, and pay through ToyyibPay so every order is tracked
            from payment to delivery.
        </p>
    </div>

    <div class="features" style="grid-template-columns: repeat(auto-fit, minmax(240px, 1fr))">
        <div class="surface feature"><div class="ico"><i class="bi bi-patch-check"></i></div><div><h3>Verified sellers</h3><p>Only <code>&#64;uitm.edu.my</code> and <code>&#64;student.uitm.edu.my</code> accounts can list items.</p></div></div>
        <div class="surface feature"><div class="ico"><i class="bi bi-geo-alt"></i></div><div><h3>Location aware</h3><p>Set your area and see items sorted by distance.</p></div></div>
        <div class="surface feature"><div class="ico"><i class="bi bi-chat-dots"></i></div><div><h3>Built-in chat</h3><p>Ask questions and agree on a meetup without sharing your number.</p></div></div>
        <div class="surface feature"><div class="ico"><i class="bi bi-shield-lock"></i></div><div><h3>Tracked payments</h3><p>FPX via ToyyibPay, verified server-side before an order is marked paid.</p></div></div>
    </div>

    @guest
        <div class="text-center mt-5">
            <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Create a free account</a>
            <a href="{{ route('login') }}" class="btn btn-light btn-lg ms-2">Log in</a>
        </div>
    @endguest
</div>
@endsection
