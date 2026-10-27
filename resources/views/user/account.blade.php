@extends('layouts.app')

@section('title', 'My account')

@section('content')
<div class="container">
    <x-page-head title="My account" />

    <div class="row g-4">
        <aside class="col-lg-3">
            <div class="surface surface-pad text-center mb-3">
                <x-avatar :user="$user" class="avatar-xl mx-auto mb-3" />
                <div class="fw-bold">{{ $user->name }}</div>
                <div class="small text-muted text-truncate mb-2">{{ $user->email }}</div>
                <x-status-badge :tone="$user->canSell() ? 'primary' : 'muted'" :label="$user->membership_label" />
            </div>
            <nav class="surface p-2 side-nav">
                <a href="{{ route('account') }}" class="{{ $tab === 'profile' ? 'active' : '' }}"><i class="bi bi-person"></i> Profile</a>
                <a href="{{ route('account', ['tab' => 'wishlist']) }}" class="{{ $tab === 'wishlist' ? 'active' : '' }}">
                    <i class="bi bi-heart"></i> Wishlist <span class="ms-auto small text-muted">{{ $wishlist->count() }}</span>
                </a>
                <a href="{{ route('account', ['tab' => 'security']) }}" class="{{ $tab === 'security' ? 'active' : '' }}"><i class="bi bi-shield-lock"></i> Password</a>
                <a href="{{ route('buyer.orders') }}"><i class="bi bi-box-seam"></i> My orders</a>
                @can('sell')
                    <a href="{{ route('listings') }}"><i class="bi bi-shop"></i> Seller centre</a>
                @endcan
            </nav>
        </aside>

        <div class="col-lg-9">
            @if ($tab === 'profile')
                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="surface">
                    @csrf
                    @method('PATCH')
                    <div class="surface-header"><h2>Profile details</h2></div>
                    <div class="surface-pad">
                        <label class="upload mb-4" for="profilePhoto">
                            <img id="avatarPreview" src="{{ $user->avatar_url }}" alt="" class="{{ $user->avatar_url ? '' : 'd-none' }}" style="border-radius:50%">
                            <span class="ph {{ $user->avatar_url ? 'd-none' : '' }}" style="border-radius:50%"><i class="bi bi-camera"></i></span>
                            <span>
                                <span class="fw-semibold d-block">Profile photo</span>
                                <span class="small text-muted">JPG, PNG or WEBP, up to 2 MB</span>
                            </span>
                            <input id="profilePhoto" type="file" name="profile_photo" accept="image/png,image/jpeg,image/webp" class="d-none" data-preview="#avatarPreview">
                        </label>
                        @error('profile_photo')<div class="text-danger small mb-3">{{ $message }}</div>@enderror

                        <div class="row">
                            <x-field class="col-md-6" name="name" label="Full name" :value="$user->name" required />
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="f_email">Email</label>
                                <input id="f_email" class="form-control" value="{{ $user->email }}" disabled>
                                <div class="form-text">Your email can't be changed.</div>
                            </div>
                            <x-field class="col-md-6" name="phone" label="Phone" type="tel" :value="$user->phone" placeholder="012-345 6789" />
                        </div>

                        <div class="divider"></div>
                        <h3 class="h6 mb-3">Default delivery address</h3>
                        <x-field name="address1" label="Address line 1" :value="$user->address1" autocomplete="address-line1" />
                        <x-field name="address2" label="Address line 2 (optional)" :value="$user->address2" autocomplete="address-line2" />
                        <div class="row">
                            <x-field class="col-md-3" name="postcode" label="Postcode" :value="$user->postcode" inputmode="numeric" />
                            <x-field class="col-md-3" name="city" label="City" :value="$user->city" />
                            <x-field class="col-md-3" name="state" label="State" :value="$user->state" />
                            <x-field class="col-md-3" name="country" label="Country" :value="$user->country ?? 'Malaysia'" />
                        </div>
                    </div>
                    <div class="d-flex justify-content-end px-4 pb-4">
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>

            @elseif ($tab === 'wishlist')
                <div class="surface">
                    <div class="surface-header"><h2>Wishlist</h2></div>
                    @forelse ($wishlist as $saved)
                        @php($product = $saved->product)
                        <div class="row-item">
                            <a href="{{ route('product.show', $product) }}"><img src="{{ $product->image_url }}" alt="" class="thumb-sm"></a>
                            <div class="flex-grow-1 min-w-0">
                                <a href="{{ route('product.show', $product) }}" class="fw-semibold text-body d-block text-truncate">{{ $product->name }}</a>
                                <div class="small text-muted">{{ $product->category->name ?? '' }}</div>
                                <div class="fw-bold mt-1">RM {{ number_format($product->price, 2) }}</div>
                            </div>
                            <div class="d-flex flex-wrap justify-content-end gap-2">
                                @if ($product->isAvailable())
                                    <form method="POST" action="{{ route('cart.add', $product) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-primary btn-sm"><i class="bi bi-bag-plus"></i> <span class="d-none d-sm-inline">Add to cart</span></button>
                                    </form>
                                @else
                                    <x-status-badge tone="danger" label="Unavailable" class="align-self-center" />
                                @endif
                                <form method="POST" action="{{ route('wishlist.toggle', $product) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-ghost btn-sm text-danger" aria-label="Remove from wishlist"><i class="bi bi-x-lg"></i></button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <x-empty-state icon="heart" title="No saved items">
                            Tap the heart on any listing to keep track of it here.
                            <x-slot:actions><a href="{{ route('browse') }}" class="btn btn-primary">Browse items</a></x-slot:actions>
                        </x-empty-state>
                    @endforelse
                </div>

            @else
                <form method="POST" action="{{ route('password.update') }}" class="surface" style="max-width: 560px">
                    @csrf
                    @method('PUT')
                    <div class="surface-header"><h2>Change password</h2></div>
                    <div class="surface-pad">
                        <x-field name="current_password" label="Current password" type="password" bag="updatePassword" required autocomplete="current-password" />
                        <x-field name="password" label="New password" type="password" bag="updatePassword" required autocomplete="new-password" hint="At least 8 characters." />
                        <x-field name="password_confirmation" label="Confirm new password" type="password" bag="updatePassword" required autocomplete="new-password" />
                        <button type="submit" class="btn btn-primary">Update password</button>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection
