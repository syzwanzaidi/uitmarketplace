@extends('layouts.auth')

@section('title', 'Confirm password')

@section('content')
<h1>Confirm it's you</h1>
<p class="text-muted mb-4">This is a secure area. Please re-enter your password to continue.</p>

<form method="POST" action="{{ route('password.confirm') }}">
    @csrf
    <x-field name="password" label="Password" type="password" required autofocus autocomplete="current-password" />
    <button type="submit" class="btn btn-primary btn-lg w-100">Confirm</button>
</form>
@endsection
