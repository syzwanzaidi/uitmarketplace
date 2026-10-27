<div class="stats mb-4">
    <div class="surface stat">
        <div class="ico gold"><i class="bi bi-wallet2"></i></div>
        <div><small>Total sales</small><strong>RM {{ number_format($stats['sales'], 2) }}</strong></div>
    </div>
    <a href="{{ route('listings', ['status' => 'active']) }}" class="surface stat">
        <div class="ico"><i class="bi bi-tags"></i></div>
        <div><small>Active listings</small><strong>{{ $stats['active'] }}</strong></div>
    </a>
    <a href="{{ route('seller.orders', ['tab' => 'to_ship']) }}" class="surface stat">
        <div class="ico blue"><i class="bi bi-truck"></i></div>
        <div><small>To ship</small><strong>{{ $stats['toShip'] }}</strong></div>
    </a>
    <a href="{{ route('seller.orders', ['tab' => 'completed']) }}" class="surface stat">
        <div class="ico green"><i class="bi bi-check2-circle"></i></div>
        <div><small>Completed</small><strong>{{ $stats['completed'] }}</strong></div>
    </a>
</div>

<nav class="seg mb-4" aria-label="Seller centre">
    <a href="{{ route('listings') }}" class="{{ request()->routeIs('listings') ? 'active' : '' }}"><i class="bi bi-grid"></i> My listings</a>
    <a href="{{ route('seller.orders') }}" class="{{ request()->routeIs('seller.orders') ? 'active' : '' }}">
        <i class="bi bi-receipt"></i> Orders
        @if ($stats['toShip'])<span class="n">{{ $stats['toShip'] }}</span>@endif
    </a>
    <a href="{{ route('chat.show') }}"><i class="bi bi-chat-dots"></i> Messages</a>
</nav>
