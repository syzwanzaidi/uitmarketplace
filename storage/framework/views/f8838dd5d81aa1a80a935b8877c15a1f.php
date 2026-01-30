<?php $__env->startSection('content'); ?>

<main class="main">

  <!-- Page Title -->
  <div class="page-title light-background">
    <div class="container d-lg-flex justify-content-between align-items-center">
      <h1 class="mb-2 mb-lg-0">Browse Products</h1>
      <nav class="breadcrumbs">
        <ol>
          <li><a href="<?php echo e(route('home')); ?>">Home</a></li>
          <li class="current">Category</li>
        </ol>
      </nav>
    </div>
  </div>

  <div class="container">

    
    <section class="category-header section">
      <form method="GET" action="<?php echo e(route('category')); ?>">
        <div class="filter-container mb-4">
          <div class="row g-3 align-items-end">

            
            <div class="col-md-5">
              <label class="form-label">Search Products</label>
              <div class="input-group">
                <input
                  type="text"
                  name="search"
                  value="<?php echo e($search); ?>"
                  class="form-control"
                  placeholder="Search for products..."
                >
                <button class="btn search-btn">
                  <i class="bi bi-search"></i>
                </button>
              </div>
            </div>

            
            <div class="col-md-3">
              <label class="form-label">Category</label>
              <select name="category" class="form-select" onchange="this.form.submit()">
                <option value="">All Categories</option>
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <option value="<?php echo e($cat->slug); ?>" <?php echo e($categorySlug === $cat->slug ? 'selected' : ''); ?>>
                    <?php echo e($cat->name); ?>

                  </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </select>
            </div>

            
            <div class="col-md-3">
              <label class="form-label">Sort By</label>
              <select name="sort" class="form-select" onchange="this.form.submit()">
                <option value="">Latest</option>
                <option value="price_asc" <?php echo e($sort === 'price_asc' ? 'selected' : ''); ?>>
                  Price: Low to High
                </option>
                <option value="price_desc" <?php echo e($sort === 'price_desc' ? 'selected' : ''); ?>>
                  Price: High to Low
                </option>
              </select>
            </div>

          </div>

          
          <?php if($search || $categorySlug || $sort): ?>
            <div class="active-filters mt-3">
              <span class="active-filter-label">Active Filters:</span>
              <div class="filter-tags">
                <?php if($search): ?>
                  <span class="filter-tag">"<?php echo e($search); ?>"</span>
                <?php endif; ?>
                <?php if($categorySlug): ?>
                  <span class="filter-tag"><?php echo e(ucfirst($categorySlug)); ?></span>
                <?php endif; ?>
                <?php if($sort): ?>
                  <span class="filter-tag">
                    <?php echo e($sort === 'price_asc' ? 'Price ↑' : 'Price ↓'); ?>

                  </span>
                <?php endif; ?>
                <a href="<?php echo e(route('category')); ?>" class="clear-all-btn">Clear All</a>
              </div>
            </div>
          <?php endif; ?>

        </div>
      </form>
    </section>

    
    <!-- Best Sellers Section -->
    <section id="best-sellers" class="best-sellers section">

    <div class="container" data-aos="fade-up" data-aos-delay="100">
      <div class="row g-4">

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

          <?php if(request()->has('lat')): ?>
            <div class="col-12 text-center py-5">
              <i class="bi bi-geo-alt fs-1 text-muted mb-3 d-block"></i>

              <h5 class="mb-2">
                No items found within
                <?php echo e(request('radius', 10)); ?> km
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
          <?php else: ?>
            <div class="col-12 text-center py-5">
              <p class="text-muted">No products available at the moment.</p>
            </div>
          <?php endif; ?>

        <?php endif; ?>

      </div>
    </div>

  </section>

    
    <?php if($products->hasPages()): ?>
      <section class="category-pagination section">
        <div class="d-flex justify-content-center">
          <?php echo e($products->withQueryString()->links()); ?>

        </div>
      </section>
    <?php endif; ?>

  </div>

</main>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\fyp\uitmarketplace\resources\views/user/category.blade.php ENDPATH**/ ?>