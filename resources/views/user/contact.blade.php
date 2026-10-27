@extends('layouts.app')

@section('title', 'Help')

@section('content')
<div class="container">
    <x-page-head title="Help & reports" subtitle="Problem with an order, a seller or a listing? Tell the admin team." />

    <div class="row g-4">
        <div class="col-lg-7">
            <form method="POST" action="{{ route('contact.store') }}" class="surface surface-pad">
                @csrf
                <x-field name="subject" label="Subject" required maxlength="255" placeholder="e.g. Seller has not shipped ORDER-12" />
                <x-field name="message" label="What happened?" type="textarea" rows="7" required maxlength="5000"
                         placeholder="Include order numbers, item names and dates so we can help faster." />
                <button type="submit" class="btn btn-primary"><i class="bi bi-send"></i> Send report</button>
            </form>
        </div>

        <div class="col-lg-5">
            <div class="surface">
                <div class="surface-header"><h2>Your recent reports</h2></div>
                @forelse ($complaints as $complaint)
                    <div class="row-item align-items-start">
                        <div class="flex-grow-1 min-w-0">
                            <div class="fw-semibold text-truncate">{{ $complaint->subject }}</div>
                            <div class="small text-muted">{{ $complaint->created_at->format('d M Y') }}</div>
                        </div>
                        <x-status-badge :tone="$complaint->status === 'resolved' ? 'success' : 'warning'" :label="ucfirst($complaint->status)" />
                    </div>
                @empty
                    <p class="text-muted small px-4 py-4 mb-0">You haven't sent any reports.</p>
                @endforelse
            </div>

            <div class="surface surface-pad mt-3">
                <h2 class="h6">Staying safe</h2>
                <ul class="small text-muted ps-3 mb-0">
                    <li class="mb-1">Meet in busy, public spots on campus.</li>
                    <li class="mb-1">Pay through UiTMarketplace so your order is tracked.</li>
                    <li>Only mark an order as received once you have the item.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
