<div class="tab-pane fade {{ request('tab') === 'wishlist' ? 'show active' : '' }}" id="wishlist">

    <div class="section-header" data-aos="fade-up">
        <h2>My Wishlist</h2>
    </div>

    <div class="wishlist-grid">
        
        @forelse ($user->wishlist as $item)

            <div class="wishlist-card">

                <!-- Image -->
                <div class="wishlist-image">
                    <img src="{{ $item->product->image }}" 
                         alt="{{ $item->product->name }}" 
                         loading="lazy">

                    <!-- Remove button -->
                    <form action="{{ route('wishlist.remove', $item->product->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="btn-remove" type="submit" aria-label="Remove from wishlist">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>

                <!-- Content -->
                <div class="wishlist-content">
                    <h4>{{ $item->product->name }}</h4>

                    <div class="product-meta">
                        <div class="price">
                            <span class="current">
                                RM {{ number_format($item->product->price, 2) }}
                            </span>
                        </div>
                    </div>

                    <button type="button" class="btn-add-cart">Add to Cart</button>
                </div>

            </div>

        @empty

            <p class="text-center mt-4">No items in your wishlist.</p>

        @endforelse

    </div>

</div>
