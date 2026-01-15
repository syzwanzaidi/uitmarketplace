@extends('layouts.app')

@section('content')
<main class="main">

  <!-- Page Title -->
  <div class="page-title light-background">
    <div class="container d-lg-flex justify-content-between align-items-center">
      <h1 class="mb-2 mb-lg-0">Transactions</h1>
      <nav class="breadcrumbs">
        <ol>
          <li><a href="{{ route('admin.dashboard') }}">Admin</a></li>
          <li class="current">Transactions</li>
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
            placeholder="Search bill code, payment ref, buyer or seller email"
            value="{{ request('search') }}"
          >
          <button class="btn btn-primary">Search</button>
        </div>
      </form>

      {{-- Transactions Table --}}
      <div class="table-responsive">
        <table class="table table-bordered align-middle">
          <thead class="table-light">
            <tr>
              <th>#Order</th>
              <th>Buyer</th>
              <th>Seller</th>
              <th>Total (RM)</th>
              <th>Status</th>
              <th>Bill Code</th>
              <th>Payment Ref</th>
              <th>Paid At</th>
            </tr>
          </thead>

          <tbody>
            @forelse($transactions as $trx)
              <tr>
                <td>{{ $trx->id }}</td>
                <td>{{ $trx->buyer->email ?? '-' }}</td>
                <td>{{ $trx->seller->email ?? '-' }}</td>
                <td>RM {{ number_format($trx->total, 2) }}</td>

                {{-- Status --}}
                <td>
                  @if($trx->status === 'completed')
                    <span class="badge bg-success">Completed</span>
                  @elseif($trx->status === 'paid')
                    <span class="badge bg-primary">Paid</span>
                  @elseif($trx->status === 'failed')
                    <span class="badge bg-danger">Failed</span>
                  @else
                    <span class="badge bg-secondary">{{ ucfirst($trx->status) }}</span>
                  @endif
                </td>

                <td>{{ $trx->bill_code ?? '-' }}</td>
                <td>{{ $trx->payment_ref ?? '-' }}</td>

                {{-- Paid At (fallback safe) --}}
                <td>
                  {{ $trx->paid_at
                      ? $trx->paid_at->format('d/m/Y H:i')
                      : $trx->updated_at->format('d/m/Y H:i') }}
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="8" class="text-center text-muted">
                  No transactions found
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      {{-- Pagination --}}
      <div class="d-flex justify-content-between align-items-center mt-3">

        @if ($transactions->onFirstPage())
          <button class="btn btn-secondary" disabled>Previous</button>
        @else
          <a href="{{ $transactions->previousPageUrl() }}" class="btn btn-outline-primary">
            Previous
          </a>
        @endif

        <span class="text-muted">
          Page {{ $transactions->currentPage() }} of {{ $transactions->lastPage() }}
        </span>

        @if ($transactions->hasMorePages())
          <a href="{{ $transactions->nextPageUrl() }}" class="btn btn-outline-primary">
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
