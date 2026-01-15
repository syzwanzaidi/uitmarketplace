@extends('layouts.app')

@section('content')
<main class="main">

  <!-- Page Title -->
  <div class="page-title light-background">
    <div class="container d-lg-flex justify-content-between align-items-center">
      <h1 class="mb-2 mb-lg-0">Manage Users</h1>
      <nav class="breadcrumbs">
        <ol>
          <li><a href="{{ route('admin.dashboard') }}">Admin</a></li>
          <li class="current">Users</li>
        </ol>
      </nav>
    </div>
  </div>

  <section class="section">
    <div class="container">

      @include('admin.partials.dashboard-cards')

      <!-- Search -->
      <form method="GET" class="mb-4">
        <div class="input-group">
          <input
            type="text"
            name="search"
            class="form-control"
            placeholder="Search by name or email"
            value="{{ request('search') }}"
          >
          <button class="btn btn-primary">Search</button>
        </div>
      </form>

      <!-- Users Table -->
      <div class="table-responsive">
        <table class="table table-bordered align-middle">
          <thead class="table-light">
            <tr>
              <th>#ID</th>
              <th>Name</th>
              <th>Email</th>
              <th>Membership</th>
              <th>Status</th>
              <th class="text-center">Action</th>
            </tr>
          </thead>

          <tbody>
            @forelse($users as $user)
              <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ ucfirst($user->membership_type) }}</td>

                <!-- STATUS (UPDATED) -->
                <td>
                  @if($user->status === 'suspended')
                    <span class="badge bg-danger">Suspended</span>
                  @else
                    <span class="badge bg-success">Active</span>
                  @endif
                </td>

                <!-- ACTION -->
                <td class="text-center">
                  <form
                    method="POST"
                    action="{{ route('admin.users.toggle', $user->id) }}"
                  >
                    @csrf
                    @method('PATCH')

                    <button
                      type="submit"
                      class="btn btn-sm btn-outline-warning"
                      onclick="return confirm('Change user status?')"
                    >
                      {{ $user->status === 'suspended' ? 'Activate' : 'Suspend' }}
                    </button>
                  </form>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center text-muted">
                  No users found
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-3">

            {{-- Previous --}}
            @if ($users->onFirstPage())
                <button class="btn btn-secondary" disabled>Previous</button>
            @else
                <a href="{{ $users->previousPageUrl() }}"
                class="btn btn-outline-primary">
                Previous
                </a>
            @endif

            <span class="text-muted">
                Page {{ $users->currentPage() }} of {{ $users->lastPage() }}
            </span>

            {{-- Next --}}
            @if ($users->hasMorePages())
                <a href="{{ $users->nextPageUrl() }}"
                class="btn btn-outline-primary">
                Next
                </a>
            @else
                <button class="btn btn-secondary" disabled>Next</button>
            @endif

        </div>

    </div>
  </section>

</main>
@endsection
