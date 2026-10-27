@extends('layouts.auth')

@section('title', 'Choose a new password')

@section('content')
<h1>Choose a new password</h1>
<p class="text-muted mb-4">Make it something you haven't used before.</p>

<form method="POST" action="{{ route('password.store') }}">
    @csrf
    <input type="hidden" name="token" value="{{ $request->route('token') }}">
    <x-field name="email" label="Email" type="email" :value="$request->email" required autocomplete="username" />
    <x-field name="password" label="New password" type="password" required autofocus autocomplete="new-password" />
    <x-field name="password_confirmation" label="Confirm new password" type="password" required autocomplete="new-password" />
    <button type="submit" class="btn btn-primary btn-lg w-100">Reset password</button>
</form>
@endsection
