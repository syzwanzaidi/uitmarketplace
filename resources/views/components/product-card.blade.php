@props(['product'])

<div class="col-lg-3 col-md-6">
    <div class="product-item">

        <!-- Product Image Section -->
        <div class="product-image">

            {{-- Optional badge (display if exists) --}}
            @if($product->status === 'sold')
            <div class="product-badge bg-danger">SOLD</div>
            @endif

            <img src="{{ $product->image }}" 
                 alt="{{ $product->name }}" 
                 class="img-fluid" 
                 loading="lazy">

            <!-- Action Buttons (same as template) -->
            <div class="product-actions">
                <button class="action-btn wishlist-btn">
                    <i class="bi bi-heart"></i>
                </button>

                <button class="action-btn compare-btn">
                    <i class="bi bi-arrow-left-right"></i>
                </button>

                <button class="action-btn quickview-btn">
                    <i class="bi bi-zoom-in"></i>
                </button>
            </div>

            <button class="cart-btn" onclick="window.location='{{ route('product.show', $product->id) }}'">
                View
            </button>
        </div>

        <!-- Product Info Section -->
        <div class="product-info">
            <div class="product-category">
                {{ $product->category->name ?? 'Uncategorized' }}
            </div>

            <h4 class="product-name">
                <a href="{{ route('product.show', $product->id) }}">
                    {{ $product->name }}
                </a>
            </h4>

            <div class="product-price">
                RM {{ number_format($product->price, 2) }}
            </div>

                {{-- LOCATION + DISTANCE --}}
            <div class="product-location small text-muted mt-1">
                {{ $product->location }}

                @if(isset($product->distance))
                    • {{ number_format($product->distance, 1) }} km away
                @endif
            </div>
        </div>

    </div>
</div>
