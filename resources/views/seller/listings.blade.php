@extends('layouts.app')

@section('title', 'Seller centre')

@section('content')
@use('App\Models\Product')
@php
    $statusTabs = ['' => 'All', Product::STATUS_ACTIVE => 'Active', Product::STATUS_INACTIVE => 'Hidden', Product::STATUS_SOLD => 'Sold'];
    $editingId = old('listing_id');
@endphp

<div class="container">
    <x-page-head title="Seller centre" subtitle="Manage your listings and fulfil orders.">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#listingModal">
            <i class="bi bi-plus-lg"></i> New listing
        </button>
    </x-page-head>

    @include('seller.partials.stats')

    <div class="surface">
        <div class="surface-header flex-wrap">
            <nav class="d-flex gap-1 flex-wrap" aria-label="Filter listings">
                @foreach ($statusTabs as $key => $label)
                    <a href="{{ route('listings', array_filter(['status' => $key, 'search' => $search])) }}"
                       class="pill py-1 {{ ($status ?? '') === $key ? 'active' : '' }}">{{ $label }}</a>
                @endforeach
            </nav>
            <form method="GET" class="input-icon" style="min-width: 220px">
                @if ($status)<input type="hidden" name="status" value="{{ $status }}">@endif
                <i class="bi bi-search"></i>
                <input type="search" name="search" value="{{ $search }}" class="form-control form-control-sm" placeholder="Search your listings">
            </form>
        </div>

        @forelse ($products as $product)
            @php
                $listingJson = [
                    'id' => $product->id,
                    'update_url' => route('listings.update', $product),
                    'name' => $product->name,
                    'category_id' => $product->category_id,
                    'description' => $product->description,
                    'price' => $product->price,
                    'location' => $product->location,
                    'latitude' => $product->locationPoint?->latitude,
                    'longitude' => $product->locationPoint?->longitude,
                    'image_url' => $product->image_url,
                ];
            @endphp
            <div class="row-item flex-wrap">
                <img src="{{ $product->image_url }}" alt="" class="thumb-sm">
                <div class="flex-grow-1 min-w-0" style="flex-basis: 200px">
                    <a href="{{ route('product.show', $product) }}" class="fw-semibold text-body d-block text-truncate">{{ $product->name }}</a>
                    <div class="small text-muted">
                        {{ $product->category->name ?? 'Other' }} · <i class="bi bi-geo-alt"></i> {{ $product->location }} · Listed {{ $product->created_at->format('d M Y') }}
                    </div>
                </div>
                <div class="fw-bold" style="min-width: 100px">RM {{ number_format($product->price, 2) }}</div>

                <div class="d-flex align-items-center gap-2 ms-auto">
                    @if ($product->status === Product::STATUS_SOLD)
                        <x-status-badge tone="primary" label="Sold" />
                    @else
                        <form method="POST" action="{{ route('listings.updateStatus', $product) }}">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="{{ $product->isAvailable() ? Product::STATUS_INACTIVE : Product::STATUS_ACTIVE }}">
                            <button type="submit" class="btn btn-sm {{ $product->isAvailable() ? 'btn-light' : 'btn-outline-primary' }}"
                                    title="{{ $product->isAvailable() ? 'Hide from buyers' : 'Show to buyers' }}">
                                @if ($product->isAvailable())
                                    <i class="bi bi-eye text-success"></i> Active
                                @else
                                    <i class="bi bi-eye-slash"></i> Hidden
                                @endif
                            </button>
                        </form>

                        <button type="button" class="btn btn-light btn-sm btn-icon" aria-label="Edit {{ $product->name }}"
                                data-bs-toggle="modal" data-bs-target="#listingModal"
                                data-listing="{{ json_encode($listingJson) }}">
                            <i class="bi bi-pencil"></i>
                        </button>

                        <form method="POST" action="{{ route('listings.delete', $product) }}" data-confirm="Delete “{{ $product->name }}”? This cannot be undone.">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-ghost btn-sm btn-icon text-danger" aria-label="Delete {{ $product->name }}"><i class="bi bi-trash"></i></button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            @if ($search || $status)
                <x-empty-state icon="search" title="No listings match">Try a different search or filter.</x-empty-state>
            @else
                <x-empty-state icon="tags" title="You haven't listed anything yet">
                    Snap a photo, set a price and pin a meetup spot — that's it.
                    <x-slot:actions>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#listingModal"><i class="bi bi-plus-lg"></i> Create your first listing</button>
                    </x-slot:actions>
                </x-empty-state>
            @endif
        @endforelse
    </div>

    <div class="mt-4 d-flex justify-content-center">{{ $products->links() }}</div>
</div>
@endsection

@push('modals')
<div class="modal fade" id="listingModal" tabindex="-1" aria-labelledby="listingModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <form method="POST" enctype="multipart/form-data" novalidate
                  action="{{ $editingId ? route('listings.update', $editingId) : route('listings.store') }}"
                  data-create-action="{{ route('listings.store') }}"
                  data-keep="{{ $errors->any() ? 1 : 0 }}">
                @csrf
                <input type="hidden" name="_method" value="PATCH" @disabled(! $editingId)>
                <input type="hidden" name="listing_id" value="{{ $editingId }}">

                <div class="modal-header">
                    <h2 class="modal-title" id="listingModalTitle">{{ $editingId ? 'Edit listing' : 'New listing' }}</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <label class="upload mb-1" for="listingImage">
                        <img id="listingPreview" src="" alt="" class="d-none">
                        <span class="ph"><i class="bi bi-image"></i></span>
                        <span>
                            <span class="fw-semibold d-block">Photo</span>
                            <span class="small text-muted">A clear, well-lit photo sells faster. JPG/PNG/WEBP up to 2 MB.</span>
                        </span>
                        <input id="listingImage" type="file" name="image" accept="image/png,image/jpeg,image/webp" class="d-none" data-preview="#listingPreview" @required(! $editingId)>
                    </label>
                    @error('image')<div class="text-danger small">{{ $message }}</div>@enderror

                    <div class="row mt-3">
                        <x-field class="col-md-8" name="name" label="Title" required maxlength="255" placeholder="e.g. Casio fx-570EX calculator" />
                        <x-field class="col-md-4" name="price" label="Price (RM)" type="number" step="0.01" min="1" required placeholder="0.00" />
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="f_category">Category</label>
                        <select id="f_category" name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                            <option value="">Choose a category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <x-field name="description" label="Description" type="textarea" rows="4" maxlength="5000"
                             placeholder="Condition, what's included, reason for selling…" />

                    <div class="d-flex justify-content-between align-items-end mb-2">
                        <label class="form-label mb-0">Meetup location</label>
                        <button type="button" class="btn btn-light btn-sm" data-my-location><i class="bi bi-crosshair"></i> Use my location</button>
                    </div>
                    <div class="map-box mb-2" data-map-picker></div>
                    <input type="hidden" name="latitude" value="{{ old('latitude') }}">
                    <input type="hidden" name="longitude" value="{{ old('longitude') }}">
                    <input name="location" value="{{ old('location') }}" class="form-control @error('location') is-invalid @enderror" readonly placeholder="Tap the map to set the area" aria-label="Location">
                    @error('location')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">{{ $editingId ? 'Save changes' : 'Publish listing' }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endpush
