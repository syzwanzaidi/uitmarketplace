@php
    $user = auth()->user();
    $location = \App\Http\Controllers\LocationController::current();
@endphp

<header class="site-header">
    <div class="container">
        <div class="bar">
            <a href="{{ $user?->isAdmin() ? route('admin.users') : route('home') }}" class="brand" aria-label="UiTMarketplace home">
                <span class="brand-mark"><i class="bi bi-bag-heart-fill"></i></span>
                <span>UiT<em>Marketplace</em></span>
                @if ($user?->isAdmin())
                    <span class="status status-primary ms-1">Admin</span>
                @endif
            </a>

            @if ($user && ! $user->isAdmin())
                <form action="{{ route('browse') }}" method="GET" class="header-search input-icon d-none d-md-block" role="search">
                    <i class="bi bi-search"></i>
                    <input type="search" name="search" value="{{ request()->routeIs('browse') ? request('search') : '' }}"
                           class="form-control" placeholder="Search textbooks, gadgets, furniture…" aria-label="Search products">
                </form>
            @endif

            <div class="header-actions">
                @if ($user && ! $user->isAdmin())
                    <a href="{{ route('browse') }}" class="icon-link d-md-none" aria-label="Search"><i class="bi bi-search"></i></a>
                    <a href="{{ route('chat.show') }}" class="icon-link {{ request()->routeIs('chat.*') ? 'active' : '' }}" aria-label="Messages">
                        <i class="bi bi-chat-dots"></i>
                        @if ($unreadCount ?? 0)<span class="count">{{ $unreadCount > 99 ? '99+' : $unreadCount }}</span>@endif
                    </a>
                    <a href="{{ route('account', ['tab' => 'wishlist']) }}" class="icon-link d-none d-sm-inline-grid" aria-label="Wishlist">
                        <i class="bi bi-heart"></i>
                    </a>
                    <a href="{{ route('cart') }}" class="icon-link {{ request()->routeIs('cart', 'checkout') ? 'active' : '' }}" aria-label="Cart">
                        <i class="bi bi-bag"></i>
                        @if ($cartCount ?? 0)<span class="count">{{ $cartCount }}</span>@endif
                    </a>
                @endif

                @auth
                    <div class="dropdown ms-1">
                        <button class="btn p-0 border-0" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Account menu">
                            <x-avatar :user="$user" class="avatar-sm" />
                        </button>
                        <div class="dropdown-menu dropdown-menu-end" style="min-width: 240px">
                            <div class="px-2 py-2 mb-1 border-bottom">
                                <div class="fw-bold text-truncate">{{ $user->name }}</div>
                                <div class="small text-muted text-truncate">{{ $user->email }}</div>
                            </div>
                            @if ($user->isAdmin())
                                <a class="dropdown-item" href="{{ route('admin.users') }}"><i class="bi bi-people"></i> Users</a>
                                <a class="dropdown-item" href="{{ route('admin.transactions') }}"><i class="bi bi-receipt"></i> Transactions</a>
                                <a class="dropdown-item" href="{{ route('admin.complaints') }}"><i class="bi bi-flag"></i> Complaints</a>
                            @else
                                <a class="dropdown-item" href="{{ route('account') }}"><i class="bi bi-person"></i> My account</a>
                                <a class="dropdown-item" href="{{ route('buyer.orders') }}"><i class="bi bi-box-seam"></i> My orders</a>
                                <a class="dropdown-item" href="{{ route('account', ['tab' => 'wishlist']) }}"><i class="bi bi-heart"></i> Wishlist</a>
                                @can('sell')
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{ route('listings') }}"><i class="bi bi-shop"></i> Seller centre</a>
                                @endcan
                            @endif
                            <div class="dropdown-divider"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right text-danger"></i> Log out</button>
                            </form>
                        </div>
                    </div>
                @endauth
            </div>
        </div>
    </div>

    @auth
        <nav class="subnav" aria-label="Main">
            <div class="container">
                @if ($user->isAdmin())
                    <a href="{{ route('admin.users') }}" class="{{ request()->routeIs('admin.users') ? 'active' : '' }}"><i class="bi bi-people"></i> Users</a>
                    <a href="{{ route('admin.transactions') }}" class="{{ request()->routeIs('admin.transactions') ? 'active' : '' }}"><i class="bi bi-receipt"></i> Transactions</a>
                    <a href="{{ route('admin.complaints') }}" class="{{ request()->routeIs('admin.complaints') ? 'active' : '' }}"><i class="bi bi-flag"></i> Complaints</a>
                @else
                    <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
                    <a href="{{ route('browse') }}" class="{{ request()->routeIs('browse', 'product.show') ? 'active' : '' }}">Browse</a>
                    <a href="{{ route('buyer.orders') }}" class="{{ request()->routeIs('buyer.orders', 'payment.return') ? 'active' : '' }}">My orders</a>
                    @can('sell')
                        <a href="{{ route('listings') }}" class="{{ request()->routeIs('listings', 'seller.*') ? 'active' : '' }}">Seller centre</a>
                    @endcan
                    <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}">Help</a>

                    <button type="button" class="location-chip {{ $location ? 'is-set' : '' }}" data-bs-toggle="modal" data-bs-target="#locationModal">
                        <i class="bi bi-geo-alt{{ $location ? '-fill' : '' }}"></i>
                        @if ($location)
                            {{ $location['area'] }} · {{ $location['radius'] }} km
                        @else
                            Set location
                        @endif
                    </button>
                @endif
            </div>
        </nav>
    @endauth
</header>
