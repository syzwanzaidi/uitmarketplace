@extends('layouts.app')

@section('title', 'Users')

@section('content')
<div class="container">
    <x-page-head title="Users" subtitle="Suspended members are signed out and cannot log back in." />

    @include('admin.partials.stats')

    <div class="surface">
        <div class="surface-header">
            <h2>{{ number_format($users->total()) }} {{ Str::plural('member', $users->total()) }}</h2>
            <form method="GET" class="input-icon" style="min-width: 260px">
                <i class="bi bi-search"></i>
                <input type="search" name="search" value="{{ $search }}" class="form-control form-control-sm" placeholder="Search name or email">
            </form>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr><th>Member</th><th>Type</th><th>Listings</th><th>Joined</th><th>Status</th><th class="text-end">Action</th></tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <x-avatar :user="$user" class="avatar-sm" />
                                    <div class="min-w-0">
                                        <div class="fw-semibold">{{ $user->name }}</div>
                                        <div class="small text-muted">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td><x-status-badge :tone="$user->canSell() ? 'primary' : 'muted'" :label="$user->membership_label" /></td>
                            <td>{{ $user->products_count }}</td>
                            <td class="text-nowrap">{{ $user->created_at?->format('d M Y') }}</td>
                            <td>
                                @if ($user->isSuspended())
                                    <x-status-badge tone="danger" label="Suspended" />
                                @else
                                    <x-status-badge tone="success" label="Active" />
                                @endif
                            </td>
                            <td class="text-end">
                                <form method="POST" action="{{ route('admin.users.toggle', $user) }}"
                                      data-confirm="{{ $user->isSuspended() ? 'Reactivate' : 'Suspend' }} {{ $user->name }}?">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm {{ $user->isSuspended() ? 'btn-outline-primary' : 'btn-outline-danger' }}">
                                        {{ $user->isSuspended() ? 'Reactivate' : 'Suspend' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-5">No members found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4 d-flex justify-content-center">{{ $users->links() }}</div>
</div>
@endsection
