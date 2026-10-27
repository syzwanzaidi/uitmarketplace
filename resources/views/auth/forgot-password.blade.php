@extends('layouts.auth')

@section('title', 'Reset password')

@section('content')
<h1>Forgot your password?</h1>
<p class="text-muted mb-4">Enter your email and we'll send you a link to choose a new one.</p>

<form method="POST" action="{{ route('password.email') }}">
    @csrf
    <x-field name="email" label="Email" type="email" required autofocus autocomplete="username" />
    <button type="submit" class="btn btn-primary btn-lg w-100">Email reset link</button>
</form>

<p class="text-center mt-4 mb-0"><a href="{{ route('login') }}" class="fw-semibold"><i class="bi bi-arrow-left"></i> Back to log in</a></p>
@endsection
