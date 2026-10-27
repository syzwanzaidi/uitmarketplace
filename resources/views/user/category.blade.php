@extends('layouts.app')

@section('title', $activeCategory?->name ?? 'Browse')

@section('content')
<div class="container">
    <x-page-head
        :title="$search !== '' ? 'Results for “' . $search . '”' : ($activeCategory?->name ?? 'Browse all items')"
        :subtitle="number_format($products->total()) . ' ' . Str::plural('item', $products->total()) . ($location ? ' within ' . $location['radius'] . ' km of ' . $location['area'] : '')"
        :crumbs="['Home' => route('home'), 'Browse' => null]" />

    <form method="GET" action="{{ route('browse') }}" class="surface surface-pad mb-4">
        <div class="row g-3 align-items-end">
            <div class="col-lg-6">
                <label class="form-label" for="browseSearch">Search</label>
                <div class="input-icon">
                    <i class="bi bi-search"></i>
                    <input id="browseSearch" type="search" name="search" value="{{ $search }}" class="form-control" placeholder="Item name or description">
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <label class="form-label" for="browseCategory">Category</label>
                <select id="browseCategory" name="category" class="form-select" data-autosubmit>
                    <option value="">All categories</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->slug }}" @selected($activeCategory?->is($category))>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-6 col-lg-3">
                <label class="form-label" for="browseSort">Sort by</label>
                <select id="browseSort" name="sort" class="form-select" data-autosubmit>
                    @foreach ($sorts as $key => $label)
                        <option value="{{ $key }}" @selected($sort === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        @if ($search !== '' || $activeCategory || $location)
            <div class="d-flex flex-wrap align-items-center gap-2 mt-3 pt-3 border-top">
                <span class="small-caps me-1">Filters</span>
                @if ($search !== '')
                    <a href="{{ request()->fullUrlWithQuery(['search' => null, 'page' => null]) }}" class="pill py-1">“{{ $search }}” <i class="bi bi-x"></i></a>
                @endif
                @if ($activeCategory)
                    <a href="{{ request()->fullUrlWithQuery(['category' => null, 'page' => null]) }}" class="pill py-1">{{ $activeCategory->name }} <i class="bi bi-x"></i></a>
                @endif
                @if ($location)
                    <button type="button" class="pill py-1" data-bs-toggle="modal" data-bs-target="#locationModal">
                        <i class="bi bi-geo-alt"></i> {{ $location['area'] }} · {{ $location['radius'] }} km
                    </button>
                @endif
                @if ($search !== '' || $activeCategory)
                    <a href="{{ route('browse') }}" class="small fw-semibold ms-auto">Clear all</a>
                @endif
            </div>
        @endif
    </form>

    @if ($products->isEmpty())
        <div class="surface">
            <x-empty-state icon="search" title="No items match">
                @if ($location)
                    Try widening your search radius, or clear some filters.
                @else
                    Try a different keyword or category.
                @endif
                <x-slot:actions>
                    <div class="d-flex justify-content-center gap-2">
                        <a href="{{ route('browse') }}" class="btn btn-light">Clear filters</a>
                        @if ($location)
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#locationModal">Change radius</button>
                        @endif
                    </div>
                </x-slot:actions>
            </x-empty-state>
        </div>
    @else
        <div class="product-grid">
            @foreach ($products as $product)
                <x-product-card :product="$product" :saved="$savedIds->contains($product->id)" />
            @endforeach
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $products->onEachSide(1)->links() }}
        </div>
    @endif
</div>
@endsection
