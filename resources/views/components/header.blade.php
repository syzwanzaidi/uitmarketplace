<header id="header" class="header sticky-top">

@php
  $isAdmin = auth()->check() && auth()->user()->membership_type === 'admin';
@endphp

<!-- ================= ADMIN HEADER ================= -->
@if($isAdmin)

  <div class="main-header">
    <div class="container-fluid container-xl">
      <div class="d-flex py-3 align-items-center justify-content-between">

        <!-- Logo -->
        <a href="{{ route('admin.dashboard') }}" class="logo d-flex align-items-center">
          <h1 class="sitename">UiTMarketplace Admin</h1>
        </a>

        <!-- Admin Actions -->
        <div class="d-flex align-items-center gap-3">

          <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary">
            Dashboard
          </a>

          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline-danger">
              Logout
            </button>
          </form>

        </div>

      </div>
    </div>
  </div>

<!-- ================= USER HEADER ================= -->
@else

  <!-- Main Header -->
  <div class="main-header">
    <div class="container-fluid container-xl">
      <div class="d-flex py-3 align-items-center justify-content-between">

        <!-- Logo -->
        <a href="{{ route('home') }}" class="logo d-flex align-items-center">
          <h1 class="sitename">UiTMarketplace</h1>
        </a>

        <!-- Search -->
        <form class="search-form desktop-search-form" action="#" method="GET">
          <div class="input-group">
            <input type="text" name="q" class="form-control" placeholder="Search for products">
            <button class="btn" type="submit">
              <i class="bi bi-search"></i>
            </button>
          </div>
        </form>

        <!-- Actions -->
        <div class="header-actions d-flex align-items-center justify-content-end">

          <!-- Account -->
          <div class="dropdown account-dropdown">
            <button class="header-action-btn" data-bs-toggle="dropdown">
              <i class="bi bi-person"></i>
            </button>
            <div class="dropdown-menu">

              <div class="dropdown-header">
                @auth
                  <h6>Hi, {{ Auth::user()->name }}</h6>
                  <p class="mb-0">Manage your account</p>
                @else
                  <h6>Welcome to <span class="sitename">UiTMarketplace</span></h6>
                @endauth
              </div>

              <div class="dropdown-body">
                <a class="dropdown-item" href="{{ route('account') }}">My Profile</a>
                <a class="dropdown-item" href="{{ route('buyer.orders') }}">My Orders</a>
                <a class="dropdown-item" href="{{ route('account', ['tab' => 'wishlist']) }}">My Wishlist</a>
              </div>

              <div class="dropdown-footer">
                @auth
                  <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger w-100">Logout</button>
                  </form>
                @else
                  <a href="{{ route('login') }}" class="btn btn-primary w-100 mb-2">Sign In</a>
                  <a href="{{ route('register') }}" class="btn btn-outline-primary w-100">Register</a>
                @endauth
              </div>

            </div>
          </div>

          <!-- Wishlist -->
          <a href="{{ route('account', ['tab' => 'wishlist']) }}" class="header-action-btn d-none d-md-block">
            <i class="bi bi-heart"></i>
          </a>

          <!-- Cart -->
          <a href="{{ route('cart') }}" class="header-action-btn">
            <i class="bi bi-cart3"></i>
          </a>

        </div>
      </div>
    </div>
  </div>

  <!-- Navigation -->
  <div class="header-nav">
    <div class="container-fluid container-xl">
      <nav class="navmenu">
        <ul>
          <li><a href="{{ route('home') }}">Home</a></li>
          <li><a href="{{ route('category') }}">Category</a></li>
          <li><a href="{{ route('checkout') }}">Checkout</a></li>
          <li><a href="{{ route('cart') }}">Cart</a></li>
          <li><a href="{{ route('chat') }}">Chat</a></li>
          <li><a href="{{ route('buyer.orders') }}">My Orders</a></li>

          @auth
            @if(auth()->user()->membership_type === 'uitm')
              <li><a href="{{ route('listings') }}">Listing</a></li>
            @endif
          @endauth

          <li><a href="{{ route('contact') }}">Contact</a></li>
        </ul>
      </nav>
    </div>
  </div>

@endif

</header>
