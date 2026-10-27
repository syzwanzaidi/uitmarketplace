<div class="stats mb-4">
    <a href="{{ route('admin.users') }}" class="surface stat">
        <div class="ico"><i class="bi bi-people"></i></div>
        <div><small>Members</small><strong>{{ number_format($stats['users']) }}</strong></div>
    </a>
    <a href="{{ route('admin.transactions') }}" class="surface stat">
        <div class="ico blue"><i class="bi bi-receipt"></i></div>
        <div><small>Paid orders</small><strong>{{ number_format($stats['transactions']) }}</strong></div>
    </a>
    <a href="{{ route('admin.transactions') }}" class="surface stat">
        <div class="ico gold"><i class="bi bi-cash-stack"></i></div>
        <div><small>Sales value</small><strong>RM {{ number_format($stats['sales'], 2) }}</strong></div>
    </a>
    <a href="{{ route('admin.complaints', ['status' => 'open']) }}" class="surface stat">
        <div class="ico {{ $stats['openComplaints'] ? '' : 'green' }}" @if ($stats['openComplaints']) style="background:var(--danger-50);color:var(--danger)" @endif><i class="bi bi-flag"></i></div>
        <div><small>Open complaints</small><strong>{{ $stats['openComplaints'] }}</strong></div>
    </a>
</div>
