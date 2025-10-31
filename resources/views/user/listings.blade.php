@extends('layouts.app')

@section('content')
<main class="main">

  <!-- Page Title -->
  <div class="page-title light-background">
    <div class="container d-lg-flex justify-content-between align-items-center">
      <h1 class="mb-2 mb-lg-0">My Listings</h1>
      <nav class="breadcrumbs">
        <ol>
          <li><a href="{{ route('home') }}">Home</a></li>
          <li class="current">Listings</li>
        </ol>
      </nav>
    </div>
  </div>

  <!-- Seller Dashboard Section -->
  <section id="seller-dashboard" class="section">
    <div class="container" data-aos="fade-up" data-aos-delay="100">

      <!-- Stats Cards -->
      <div class="row g-4 mb-5">
        <div class="col-md-3 col-sm-6">
          <div class="dashboard-card">
            <i class="bi bi-currency-dollar"></i>
            <h4>Total Sales</h4>
            <p>RM 12,540</p>
          </div>
        </div>

        <div class="col-md-3 col-sm-6">
          <div class="dashboard-card">
            <i class="bi bi-box-seam"></i>
            <h4>Active Listings</h4>
            <p>8 Items</p>
          </div>
        </div>

        <div class="col-md-3 col-sm-6">
          <div class="dashboard-card">
            <i class="bi bi-chat-dots"></i>
            <h4>Messages</h4>
            <p>4 New</p>
          </div>
        </div>

        <div class="col-md-3 col-sm-6">
          <div class="dashboard-card">
            <i class="bi bi-graph-up"></i>
            <h4>Pending Orders</h4>
            <p>3</p>
          </div>
        </div>
      </div>

      <!-- Add New Listing Button -->
      <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="#" class="btn btn-primary">
          <i class="bi bi-plus-lg"></i> Add New Listing
        </a>
      </div>

      <!-- Listings Table Style -->
      <div class="listing-items">
        <!-- Listing Item -->
        <div class="listing-item mb-4 p-3 rounded shadow-sm">
          <div class="row align-items-center">
            <div class="col-lg-6 col-12">
              <div class="product-info d-flex align-items-center">
                <div class="product-image me-3">
                  <img src="{{ asset('assets/niceshop/img/product/product-1.webp') }}" alt="Product" class="img-fluid rounded" width="80">
                </div>
                <div class="product-details">
                  <h6 class="product-title mb-1">Wireless Headphones</h6>
                  <p class="text-muted small mb-0">Category: Electronics</p>
                </div>
              </div>
            </div>

            <div class="col-lg-2 col-6 text-center">
              <span class="fw-bold text-dark">RM299.00</span>
            </div>

            <div class="col-lg-2 col-6 text-center">
              <select class="form-select form-select-sm">
                <option>Active</option>
                <option>Out of Stock</option>
                <option>Hidden</option>
              </select>
            </div>

            <div class="col-lg-2 col-12 text-center mt-3 mt-lg-0">
              <button class="btn btn-sm btn-outline-secondary me-2">
                <i class="bi bi-pencil"></i>
              </button>
              <button class="btn btn-sm btn-outline-danger">
                <i class="bi bi-trash"></i>
              </button>
            </div>
          </div>
        </div>

        <!-- Listing Item 2 -->
        <div class="listing-item mb-4 p-3 rounded shadow-sm">
          <div class="row align-items-center">
            <div class="col-lg-6 col-12">
              <div class="product-info d-flex align-items-center">
                <div class="product-image me-3">
                  <img src="{{ asset('assets/niceshop/img/product/product-3.webp') }}" alt="Product" class="img-fluid rounded" width="80">
                </div>
                <div class="product-details">
                  <h6 class="product-title mb-1">Leather Handbag</h6>
                  <p class="text-muted small mb-0">Category: Accessories</p>
                </div>
              </div>
            </div>

            <div class="col-lg-2 col-6 text-center">
              <span class="fw-bold text-dark">RM189.00</span>
            </div>

            <div class="col-lg-2 col-6 text-center">
              <select class="form-select form-select-sm">
                <option>Active</option>
                <option>Out of Stock</option>
                <option>Hidden</option>
              </select>
            </div>

            <div class="col-lg-2 col-12 text-center mt-3 mt-lg-0">
              <button class="btn btn-sm btn-outline-secondary me-2">
                <i class="bi bi-pencil"></i>
              </button>
              <button class="btn btn-sm btn-outline-danger">
                <i class="bi bi-trash"></i>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>

@endsection
