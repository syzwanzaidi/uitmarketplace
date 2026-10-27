@extends('layouts.app')

@section('title', 'Transactions')

@section('content')
<div class="container">
    <x-page-head title="Transactions" subtitle="Every paid order, verified against ToyyibPay." />

    @include('admin.partials.stats')

    <div class="surface">
        <div class="surface-header">
            <h2>{{ number_format($transactions->total()) }} paid {{ Str::plural('order', $transactions->total()) }}</h2>
            <form method="GET" class="input-icon" style="min-width: 300px">
                <i class="bi bi-search"></i>
                <input type="search" name="search" value="{{ $search }}" class="form-control form-control-sm" placeholder="Bill code, payment ref or email">
            </form>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr><th>Order</th><th>Buyer</th><th>Seller</th><th class="text-end">Amount</th><th>Status</th><th>Bill / Ref</th><th>Paid</th></tr>
                </thead>
                <tbody>
                    @forelse ($transactions as $order)
                        <tr>
                            <td class="fw-semibold text-nowrap">#{{ $order->reference }}</td>
                            <td><div class="small">{{ $order->buyer->name ?? '—' }}</div><div class="small text-muted">{{ $order->buyer->email ?? '' }}</div></td>
                            <td><div class="small">{{ $order->seller->name ?? '—' }}</div><div class="small text-muted">{{ $order->seller->email ?? '' }}</div></td>
                            <td class="text-end fw-bold text-nowrap">RM {{ number_format($order->total, 2) }}</td>
                            <td><x-status-badge :order="$order" /></td>
                            <td class="small"><code>{{ $order->bill_code ?? '—' }}</code><div class="text-muted">{{ $order->payment_ref }}</div></td>
                            <td class="small text-nowrap">{{ ($order->paid_at ?? $order->updated_at)->format('d M Y, g:i A') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-5">No transactions found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4 d-flex justify-content-center">{{ $transactions->links() }}</div>
</div>
@endsection
