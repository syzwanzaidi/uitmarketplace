@extends('layouts.app')

@section('content')
<main class="main">

  <div class="page-title light-background">
    <div class="container d-lg-flex justify-content-between align-items-center">
      <h1 class="mb-2 mb-lg-0">Complaints</h1>
      <nav class="breadcrumbs">
        <ol>
          <li><a href="{{ route('admin.dashboard') }}">Admin</a></li>
          <li class="current">Complaints</li>
        </ol>
      </nav>
    </div>
  </div>

  <section class="section">
    <div class="container">

      {{-- Dashboard Cards --}}
      @include('admin.partials.dashboard-cards')

      {{-- Search --}}
      <form method="GET" class="mb-4">
        <div class="input-group">
          <input
            type="text"
            name="search"
            class="form-control"
            placeholder="Search subject or user email"
            value="{{ request('search') }}"
          >
          <button class="btn btn-primary">Search</button>
        </div>
      </form>

      {{-- Complaints Table --}}
      <div class="table-responsive">
        <table class="table table-bordered align-middle">
          <thead class="table-light">
            <tr>
              <th>#</th>
              <th>User</th>
              <th>Subject</th>
              <th>Message</th>
              <th>Status</th>
              <th>Date</th>
              <th class="text-center">Action</th>
            </tr>
          </thead>

          <tbody>
            @forelse($complaints as $c)
              <tr>
                <td>{{ $c->id }}</td>
                <td>{{ $c->user->email }}</td>
                <td>{{ $c->subject }}</td>
                <td>{{ \Illuminate\Support\Str::limit($c->message, 50) }}</td>

                <td>
                  @if($c->status === 'resolved')
                    <span class="badge bg-success">Resolved</span>
                  @else
                    <span class="badge bg-warning text-dark">Open</span>
                  @endif
                </td>

                <td>{{ $c->created_at->format('d/m/Y') }}</td>

                <td class="text-center">
                  <form
                    method="POST"
                    action="{{ route('admin.complaints.toggle', $c->id) }}"
                  >
                    @csrf
                    @method('PATCH')
                    <button
                      class="btn btn-sm btn-outline-primary"
                      onclick="return confirm('Change complaint status?')"
                    >
                      {{ $c->status === 'open' ? 'Resolve' : 'Reopen' }}
                    </button>
                  </form>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="text-center text-muted">
                  No complaints found
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      {{-- Pagination (SAME STYLE AS USERS) --}}
      <div class="d-flex justify-content-between align-items-center mt-3">

        @if ($complaints->onFirstPage())
          <button class="btn btn-secondary" disabled>Previous</button>
        @else
          <a href="{{ $complaints->previousPageUrl() }}"
             class="btn btn-outline-primary">
            Previous
          </a>
        @endif

        <span class="text-muted">
          Page {{ $complaints->currentPage() }} of {{ $complaints->lastPage() }}
        </span>

        @if ($complaints->hasMorePages())
          <a href="{{ $complaints->nextPageUrl() }}"
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
