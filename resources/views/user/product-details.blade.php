@extends('layouts.app')

@section('content')

<main class="main">

    <!-- Page Title -->
    <div class="page-title light-background">
        <div class="container d-lg-flex justify-content-between align-items-center">
            <h1 class="mb-2 mb-lg-0">Product Details</h1>
            <nav class="breadcrumbs">
                <ol>
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li class="current">{{ $product->name }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Product Details Section -->
    <section id="product-details" class="product-details section">
        <div class="container" data-aos="fade-up" data-aos-delay="100">

            <div class="row g-4">

                <!-- LEFT: Product Gallery -->
                <div class="col-lg-7" data-aos="zoom-in" data-aos-delay="150">
                    <div class="product-gallery">

                        <!-- Main Image -->
                        <div class="main-showcase">
                            <div class="image-zoom-container">
                                <img 
                                    src="{{ $product->image }}" 
                                    alt="{{ $product->name }}" 
                                    class="img-fluid main-product-image drift-zoom"
                                    id="main-product-image"
                                >
                            </div>
                        </div>

                        <!-- Thumbnails (only 1 image for now) -->
                        <div class="thumbnail-grid">
                            <div class="thumbnail-wrapper thumbnail-item active">
                                <img src="{{ $product->image }}" class="img-fluid">
                            </div>
                        </div>

                    </div>
                </div>

                <!-- RIGHT: Product Info -->
                <div class="col-lg-5 d-flex" data-aos="fade-left" data-aos-delay="200">

                    <!-- Make product-details a flex column -->
                    <div class="product-details d-flex flex-column w-100" style="min-height: 100%;">

                        <!-- All top content stays inside here -->
                        <div class="flex-grow-1">

                            <!-- Category -->
                            <div class="product-badge-container">
                                <span class="badge-category">
                                    {{ $product->category->name }}
                                </span>
                            </div>

                            <!-- Name -->
                            <h1 class="product-name">{{ $product->name }}</h1>

                            <!-- Pricing -->
                            <div class="pricing-section">
                                <div class="price-display">
                                    <span class="sale-price">
                                        RM {{ number_format($product->price, 2) }}
                                    </span>
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="product-description">
                                <p>{{ $product->description }}</p>
                            </div>

                            <!-- Availability Section -->
                            <div class="availability-status">
                                <div class="stock-indicator">
                                    <i class="bi bi-check-circle-fill"></i>
                                    <span class="stock-text">Available</span>
                                </div>

                                <div class="quantity-left">
                                    Location: <strong>{{ $product->location }}</strong>
                                </div>
                            </div>

                            <!-- Seller -->
                            <p class="mt-3"><strong>Seller:</strong> {{ $product->user->name }}</p>

                        </div> <!-- END flex-grow-1 -->


                        <!-- BUTTONS (stay at bottom & keep full styling) -->
                        <div class="purchase-section mt-4">
                          <div class="action-buttons">
                              <form action="{{ route('cart.add', $product->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn primary-action w-100">
                                    <i class="bi bi-bag-plus"></i>
                                    Add to Cart
                                </button>
                              </form>
                              
                              <button class="btn secondary-action">
                                  <i class="bi bi-lightning"></i>
                                  Buy Now
                              </button>

                              <form action="{{ route('wishlist.add', $product->id) }}" method="POST">
                                @csrf
                                <button class="btn icon-action" title="Add to Wishlist">
                                    <i class="bi bi-heart"></i>
                                </button>
                              </form>
                          </div>

                          <div class="action-buttons">
                            <a href="{{ route('chat.start', $product->id) }}" class="btn secondary-action mt-3 w-100">
                                <i class="bi bi-geo-alt"></i>
                                Arrange Meetup
                            </a>
                          </div>

                        </div>

                    </div>
                </div>

            </div>

        </div>
    </section>

</main>

@endsection
