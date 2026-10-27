@extends('layouts.auth')

@section('title', 'Create account')

@section('content')
<h1>Create your account</h1>
<p class="text-muted mb-4">Free to join. Takes less than a minute.</p>

<form method="POST" action="{{ route('register') }}">
    @csrf
    <x-field name="name" label="Full name" required autofocus autocomplete="name" />
    <x-field name="email" label="Email" type="email" required autocomplete="username"
             hint="Use your UiTM email (@student.uitm.edu.my or @uitm.edu.my) to be able to sell." />
    <x-field name="password" label="Password" type="password" required autocomplete="new-password" hint="At least 8 characters." />
    <x-field name="password_confirmation" label="Confirm password" type="password" required autocomplete="new-password" />

    <button type="submit" class="btn btn-primary btn-lg w-100 mt-2">Create account</button>
</form>

<p class="text-center text-muted mt-4 mb-0">
    Already have an account? <a href="{{ route('login') }}" class="fw-semibold">Log in</a>
</p>
@endsection
