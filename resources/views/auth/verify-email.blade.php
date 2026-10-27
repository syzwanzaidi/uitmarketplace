@extends('layouts.auth')

@section('title', 'Verify your email')

@section('content')
<div class="empty px-0 pt-0 text-start">
    <div class="ico mx-0"><i class="bi bi-envelope-check"></i></div>
</div>
<h1>Check your inbox</h1>
<p class="text-muted mb-4">
    We sent a verification link to <strong class="text-body">{{ auth()->user()->email }}</strong>.
    Click it to activate your account. Can't find it? Check your spam folder.
</p>

@if (session('status') === 'verification-link-sent')
    <div class="notice mb-4"><i class="bi bi-check-circle"></i><div>A new verification link is on its way.</div></div>
@endif

<form method="POST" action="{{ route('verification.send') }}">
    @csrf
    <button type="submit" class="btn btn-primary btn-lg w-100">Resend verification email</button>
</form>

<form method="POST" action="{{ route('logout') }}" class="text-center mt-3">
    @csrf
    <button type="submit" class="btn btn-ghost">Log out</button>
</form>
@endsection
