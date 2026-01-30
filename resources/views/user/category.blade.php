@extends('layouts.app')
@section('content')

<main class="main">

  <!-- Page Title -->
  <div class="page-title light-background">
    <div class="container d-lg-flex justify-content-between align-items-center">
      <h1 class="mb-2 mb-lg-0">Browse Products</h1>
      <nav class="breadcrumbs">
        <ol>
          <li><a href="{{ route('home') }}">Home</a></li>
          <li class="current">Category</li>
        </ol>
      </nav>
    </div>
  </div>

  <div class="container">

    {{-- ================= FILTER BAR ================= --}}
    <section class="category-header section">
      <form method="GET" action="{{ route('category') }}">
        <div class="filter-container mb-4">
          <div class="row g-3 align-items-end">

            {{-- SEARCH --}}
            <div class="col-md-5">
              <label class="form-label">Search Products</label>
              <div class="input-group">
                <input
                  type="text"
                  name="search"
                  value="{{ $search }}"
                  class="form-control"
                  placeholder="Search for products..."
                >
                <button class="btn search-btn">
                  <i class="bi bi-search"></i>
                </button>
              </div>
            </div>

            {{-- CATEGORY --}}
            <div class="col-md-3">
              <label class="form-label">Category</label>
              <select name="category" class="form-select" onchange="this.form.submit()">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                  <option value="{{ $cat->slug }}" {{ $categorySlug === $cat->slug ? 'selected' : '' }}>
                    {{ $cat->name }}
                  </option>
                @endforeach
              </select>
            </div>

            {{-- SORT --}}
            <div class="col-md-3">
              <label class="form-label">Sort By</label>
              <select name="sort" class="form-select" onchange="this.form.submit()">
                <option value="">Latest</option>
                <option value="price_asc" {{ $sort === 'price_asc' ? 'selected' : '' }}>
                  Price: Low to High
                </option>
                <option value="price_desc" {{ $sort === 'price_desc' ? 'selected' : '' }}>
                  Price: High to Low
                </option>
              </select>
            </div>

          </div>

          {{-- ACTIVE FILTERS --}}
          @if($search || $categorySlug || $sort)
            <div class="active-filters mt-3">
              <span class="active-filter-label">Active Filters:</span>
              <div class="filter-tags">
                @if($search)
                  <span class="filter-tag">"{{ $search }}"</span>
                @endif
                @if($categorySlug)
                  <span class="filter-tag">{{ ucfirst($categorySlug) }}</span>
                @endif
                @if($sort)
                  <span class="filter-tag">
                    {{ $sort === 'price_asc' ? 'Price ↑' : 'Price ↓' }}
                  </span>
                @endif
                <a href="{{ route('category') }}" class="clear-all-btn">Clear All</a>
              </div>
            </div>
          @endif

        </div>
      </form>
    </section>

    {{-- ================= PRODUCT GRID ================= --}}
    <!-- Best Sellers Section -->
    <section id="best-sellers" class="best-sellers section">

    <div class="container" data-aos="fade-up" data-aos-delay="100">
      <div class="row g-4">

        @forelse($products as $product)
          <x-product-card :product="$product" />
        @empty

          @if(request()->has('lat'))
            <div class="col-12 text-center py-5">
              <i class="bi bi-geo-alt fs-1 text-muted mb-3 d-block"></i>

              <h5 class="mb-2">
                No items found within
                {{ request('radius', 10) }} km
              </h5>

              <p class="text-muted mb-4" id="emptyLocationText">
                of your selected location.
              </p>

              <button
                class="btn btn-outline-primary"
                data-bs-toggle="modal"
                data-bs-target="#locationModal">
                Change Location / Radius
              </button>
            </div>
          @else
            <div class="col-12 text-center py-5">
              <p class="text-muted">No products available at the moment.</p>
            </div>
          @endif

        @endforelse

      </div>
    </div>

  </section>

    {{-- ================= PAGINATION ================= --}}
    @if($products->hasPages())
      <section class="category-pagination section">
        <div class="d-flex justify-content-center">
          {{ $products->withQueryString()->links() }}
        </div>
      </section>
    @endif

  </div>

</main>

@endsection
