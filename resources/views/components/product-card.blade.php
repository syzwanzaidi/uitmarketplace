@props(['product', 'saved' => false])

<article class="product-card">
    <div class="thumb">
        @if ($product->status === \App\Models\Product::STATUS_SOLD)
            <span class="ribbon">Sold</span>
        @endif
        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" loading="lazy">
        @if ($product->user_id !== auth()->id())
            <form method="POST" action="{{ route('wishlist.toggle', $product) }}">
                @csrf
                <button type="submit" class="fav {{ $saved ? 'on' : '' }}" aria-label="{{ $saved ? 'Remove from wishlist' : 'Save to wishlist' }}">
                    <i class="bi bi-heart{{ $saved ? '-fill' : '' }}"></i>
                </button>
            </form>
        @endif
    </div>
    <div class="body">
        <div class="cat">{{ $product->category->name ?? 'Other' }}</div>
        <a href="{{ route('product.show', $product) }}" class="name">{{ $product->name }}</a>
        <div class="price">RM {{ number_format($product->price, 2) }}</div>
        <div class="meta">
            <i class="bi bi-geo-alt"></i>
            <span>
                {{ $product->location ?: 'Campus' }}
                @isset($product->distance)
                    · {{ $product->distance < 1 ? round($product->distance * 1000) . ' m' : number_format($product->distance, 1) . ' km' }}
                @endisset
            </span>
        </div>
    </div>
</article>
