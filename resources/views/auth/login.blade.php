@extends('layouts.auth')

@section('title', 'Log in')

@section('content')
<h1>Welcome back</h1>
<p class="text-muted mb-4">Log in to continue shopping and selling.</p>

<form method="POST" action="{{ route('login') }}">
    @csrf
    <x-field name="email" label="Email" type="email" required autofocus autocomplete="username" placeholder="you@student.uitm.edu.my" />

    <div class="mb-3">
        <div class="d-flex justify-content-between">
            <label for="f_password" class="form-label">Password</label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="small fw-semibold">Forgot password?</a>
            @endif
        </div>
        <input id="f_password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" required autocomplete="current-password">
        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="form-check mb-4">
        <input class="form-check-input" type="checkbox" name="remember" id="remember">
        <label class="form-check-label" for="remember">Keep me logged in</label>
    </div>

    <button type="submit" class="btn btn-primary btn-lg w-100">Log in</button>
</form>

<p class="text-center text-muted mt-4 mb-0">
    New here? <a href="{{ route('register') }}" class="fw-semibold">Create an account</a>
</p>
@endsection
