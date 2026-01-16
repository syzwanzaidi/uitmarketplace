<?php $__env->startSection('content'); ?>
  
  <main class="main">

    <!-- Hero Section -->
    <section id="hero" class="hero section">

      <div class="hero-container">
        <div class="hero-content">
          <div class="content-wrapper" data-aos="fade-up" data-aos-delay="100">
            <h1 class="hero-title">Discover Amazing Products</h1>
            <p class="hero-description">Explore our curated collection of premium items designed to enhance your lifestyle. From fashion to tech, find everything you need with exclusive deals and fast shipping.</p>
            <div class="hero-actions" data-aos="fade-up" data-aos-delay="200">
              <a href="#products" class="btn-primary">Shop Now</a>
              <a href="#categories" class="btn-secondary">Browse Categories</a>
            </div>
            <div class="features-list" data-aos="fade-up" data-aos-delay="300">
              <div class="feature-item">
                <i class="bi bi-truck"></i>
                <span>Free Shipping</span>
              </div>
              <div class="feature-item">
                <i class="bi bi-award"></i>
                <span>Quality Guarantee</span>
              </div>
              <div class="feature-item">
                <i class="bi bi-headset"></i>
                <span>24/7 Support</span>
              </div>
            </div>
          </div>
        </div>

        <div class="hero-visuals">
          <div class="product-showcase" data-aos="fade-left" data-aos-delay="200">
            <div class="product-card featured">
              <img src="<?php echo e(asset('assets/niceshop/img/product/product-2.webp')); ?>" alt="Featured Product" class="img-fluid">
              <div class="product-badge">Best Seller</div>
              <div class="product-info">
                <h4>Premium Wireless Headphones</h4>
                <div class="price">
                  <span class="sale-price">RM299</span>
                  <span class="original-price">RM399</span>
                </div>
              </div>
            </div>

            <div class="product-grid">
              <div class="product-mini" data-aos="zoom-in" data-aos-delay="400">
                <img src="<?php echo e(asset('assets/niceshop/img/product/product-3.webp')); ?>" alt="Product" class="img-fluid">
                <span class="mini-price">RM89</span>
              </div>
              <div class="product-mini" data-aos="zoom-in" data-aos-delay="500">
                <img src="<?php echo e(asset('assets/niceshop/img/product/product-5.webp')); ?>" alt="Product" class="img-fluid">
                <span class="mini-price">RM149</span>
              </div>
            </div>
          </div>

          <div class="floating-elements">
            <div class="floating-icon cart" data-aos="fade-up" data-aos-delay="600">
              <i class="bi bi-cart3"></i>
              <span class="notification-dot">3</span>
            </div>
            <div class="floating-icon wishlist" data-aos="fade-up" data-aos-delay="700">
              <i class="bi bi-heart"></i>
            </div>
            <div class="floating-icon search" data-aos="fade-up" data-aos-delay="800">
              <i class="bi bi-search"></i>
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

          <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php if (isset($component)) { $__componentOriginalecfc721726b8b5798826c96d529d8b59 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalecfc721726b8b5798826c96d529d8b59 = $attributes; } ?>
<?php $component = App\View\Components\ProductCard::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('product-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\ProductCard::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['product' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($product)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalecfc721726b8b5798826c96d529d8b59)): ?>
<?php $attributes = $__attributesOriginalecfc721726b8b5798826c96d529d8b59; ?>
<?php unset($__attributesOriginalecfc721726b8b5798826c96d529d8b59); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalecfc721726b8b5798826c96d529d8b59)): ?>
<?php $component = $__componentOriginalecfc721726b8b5798826c96d529d8b59; ?>
<?php unset($__componentOriginalecfc721726b8b5798826c96d529d8b59); ?>
<?php endif; ?>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-12">
              <p class="text-center">No products available.</p>
            </div>
          <?php endif; ?>

        </div>
      </div>

    </section>

  </main>

 <?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\fyp\uitmarketplace\resources\views/user/home.blade.php ENDPATH**/ ?>