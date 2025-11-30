@props(['product'])

<div class="col-lg-3 col-md-6">
    <div class="product-item">

        <!-- Product Image Section -->
        <div class="product-image">

            {{-- Optional badge (display if exists) --}}
            @if($product->status == 'active')
                <div class="product-badge">New</div>
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
        </div>

    </div>
</div>
