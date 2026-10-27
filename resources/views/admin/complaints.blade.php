@extends('layouts.app')

@section('title', 'Complaints')

@section('content')
<div class="container">
    <x-page-head title="Complaints" subtitle="Reports submitted by members through the Help page." />

    @include('admin.partials.stats')

    <div class="surface">
        <div class="surface-header flex-wrap">
            <nav class="d-flex gap-1" aria-label="Filter complaints">
                @foreach (['' => 'All', 'open' => 'Open', 'resolved' => 'Resolved'] as $key => $label)
                    <a href="{{ route('admin.complaints', array_filter(['status' => $key, 'search' => $search])) }}" class="pill py-1 {{ ($status ?? '') === $key ? 'active' : '' }}">{{ $label }}</a>
                @endforeach
            </nav>
            <form method="GET" class="input-icon" style="min-width: 260px">
                @if ($status)<input type="hidden" name="status" value="{{ $status }}">@endif
                <i class="bi bi-search"></i>
                <input type="search" name="search" value="{{ $search }}" class="form-control form-control-sm" placeholder="Subject or email">
            </form>
        </div>

        @forelse ($complaints as $complaint)
            <div class="row-item align-items-start flex-wrap">
                <x-avatar :user="$complaint->user" class="avatar-sm" />
                <div class="flex-grow-1 min-w-0" style="flex-basis: 260px">
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                        <span class="fw-semibold">{{ $complaint->subject }}</span>
                        <x-status-badge :tone="$complaint->status === 'resolved' ? 'success' : 'warning'" :label="ucfirst($complaint->status)" />
                    </div>
                    <div class="small text-muted mb-2">
                        {{ $complaint->user->name ?? 'Deleted user' }} · {{ $complaint->user->email ?? '' }} · {{ $complaint->created_at->format('d M Y, g:i A') }}
                    </div>
                    <div class="prose-text small">{{ $complaint->message }}</div>
                </div>
                <form method="POST" action="{{ route('admin.complaints.toggle', $complaint) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-sm {{ $complaint->status === 'open' ? 'btn-primary' : 'btn-light' }}">
                        {{ $complaint->status === 'open' ? 'Mark resolved' : 'Reopen' }}
                    </button>
                </form>
            </div>
        @empty
            <x-empty-state icon="flag" title="No complaints">Nothing to review right now.</x-empty-state>
        @endforelse
    </div>

    <div class="mt-4 d-flex justify-content-center">{{ $complaints->links() }}</div>
</div>
@endsection
