@extends('layouts.app')

@section('content')
<main class="main">
  <div class="container py-5">
    <h1>My Orders</h1>

    <p>This page will display all orders made by the user.</p>


    <a href="{{ route('home') }}" class="btn btn-primary mt-3">
      Back to Home
    </a>
  </div>
</main>
@endsection
