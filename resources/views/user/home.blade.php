@extends('layouts.app')

@section('content')
  
  <main class="main">

    <!-- Hero Section -->
    <section id="hero" class="hero section">

      <div class="hero-container">
        <div class="hero-content">
          <div class="content-wrapper" data-aos="fade-up" data-aos-delay="100">
            <h1 class="hero-title">Discover Product From UiTM Students</h1>
            <p class="hero-description">
              This web application is developed to provide UiTM students with a secure
              platform for buying and selling items within the campus environment.
            </p>
            <div class="hero-actions" data-aos="fade-up" data-aos-delay="200">
              <a href="{{ route('category') }}" class="btn-primary">Shop Now</a>
            </div>

          </div>
        </div>

        <div class="hero-visuals" data-aos="fade-left" data-aos-delay="200">

          <div class="system-visual">

            <div class="system-card">
              <i class="bi bi-shop"></i>
              <h5>Student Marketplace</h5>
              <p>Buy and sell items within UiTM campus</p>
            </div>

            <div class="system-card">
              <i class="bi bi-chat-dots"></i>
              <h5>Direct Chat</h5>
              <p>Communicate with sellers before purchasing</p>
            </div>

            <div class="system-card">
              <i class="bi bi-geo-alt"></i>
              <h5>Location-Based Items</h5>
              <p>View products based on nearby locations</p>
            </div>

            <div class="system-card">
              <i class="bi bi-credit-card"></i>
              <h5>Online Payment</h5>
              <p>Secure payment using ToyyibPay</p>
            </div>

          </div>

        </div>
      </div>

    </section>

    <!-- Best Sellers Section -->
    <section id="best-sellers" class="best-sellers section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Welcome To UiTMarketplace</h2>
        <p>Check out the latest products from UiTM sellers</p>
      </div>

      <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="row g-5">

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

  </main>

 @endsection