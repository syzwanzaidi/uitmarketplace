@props(['product'])

<div class="col-lg-3 col-md-6">
    <div class="product-item">

        <!-- Product Image Section -->
        <div class="product-image">

            {{-- Optional badge --}}
            @if($product->status === 'sold')
                <div class="product-badge bg-danger">SOLD</div>
            @endif

            <img src="{{ $product->image }}"
                 alt="{{ $product->name }}"
                 class="img-fluid"
                 loading="lazy">



            <!-- VIEW BUTTON -->
            <button class="cart-btn"
                    onclick="goToProduct({{ $product->id }})">
                View
            </button>
        </div>

        <!-- Product Info Section -->
        <div class="product-info">

            <div class="product-category">
                {{ $product->category->name ?? 'Uncategorized' }}
            </div>

            <!-- PRODUCT NAME (FIXED) -->
            <h4 class="product-name">
                <a href="javascript:void(0)"
                   onclick="goToProduct({{ $product->id }})">
                    {{ $product->name }}
                </a>
            </h4>

            <div class="product-price">
                RM {{ number_format($product->price, 2) }}
            </div>

            <!-- LOCATION + DISTANCE -->
            <div class="product-location small text-muted mt-1">
                {{ $product->location }}

                @if(isset($product->distance))
                    • {{ number_format($product->distance, 1) }} km away
                @endif
            </div>

        </div>

    </div>
</div>

<script>
function goToProduct(productId) {
    const saved = localStorage.getItem('buyer_location');

    if (!saved) {
        window.location.href = `/product/${productId}`;
        return;
    }

    const data = JSON.parse(saved);
    window.location.href =
        `/product/${productId}?lat=${data.lat}&lng=${data.lng}`;
}
</script>
