<header id="header" class="header sticky-top">
  <!-- Main Header -->
  <div class="main-header">
    <div class="container-fluid container-xl">
      <div class="d-flex py-3 align-items-center justify-content-between">

        <!-- Logo -->
        <a href="<?php echo e(route('home')); ?>" class="logo d-flex align-items-center">
          <h1 class="sitename">UiTMarketplace</h1>
        </a>

        <!-- Search -->
        <form class="search-form desktop-search-form" action="#" method="GET">
          <div class="input-group">
            <input type="text" name="q" class="form-control" placeholder="Search for products">
            <button class="btn" type="submit">
              <i class="bi bi-search"></i>
            </button>
          </div>
        </form>

        <!-- Actions -->
        <div class="header-actions d-flex align-items-center justify-content-end">

          <!-- Mobile Search Toggle -->
          <button class="header-action-btn mobile-search-toggle d-xl-none" type="button" data-bs-toggle="collapse" data-bs-target="#mobileSearch" aria-expanded="false" aria-controls="mobileSearch">
            <i class="bi bi-search"></i>
          </button>

          <!-- Account -->
          <div class="dropdown account-dropdown">
            <button class="header-action-btn" data-bs-toggle="dropdown">
              <i class="bi bi-person"></i>
            </button>
            <div class="dropdown-menu">
              <div class="dropdown-header">
                <?php if(auth()->guard()->check()): ?>
                  <h6>Hi, <?php echo e(Auth::user()->name); ?></h6>
                  <p class="mb-0">Manage your account</p>
                <?php else: ?>
                  <h6>Welcome to <span class="sitename">UiTMarketplace</span></h6>
                  <p class="mb-0">Access account &amp; manage orders</p>
                <?php endif; ?>
              </div>

              <div class="dropdown-body">
                <a class="dropdown-item d-flex align-items-center" href="<?php echo e(route('account')); ?>">
                  <i class="bi bi-person-circle me-2"></i>
                  <span>My Profile</span>
                </a>
                <a class="dropdown-item d-flex align-items-center" action="#" method="GET">
                  <i class="bi bi-bag-check me-2"></i>
                  <span>My Orders</span>
                </a>
                <a class="dropdown-item d-flex align-items-center" href="<?php echo e(route('account', ['tab' => 'wishlist'])); ?>">
                  <i class="bi bi-heart me-2"></i>
                  <span>My Wishlist</span>
                </a>
                <a class="dropdown-item d-flex align-items-center" action="#" method="GET">
                  <i class="bi bi-gear me-2"></i>
                  <span>Settings</span>
                </a>
              </div>

              <div class="dropdown-footer">
                <?php if(auth()->guard()->check()): ?>
                  <form method="POST" action="<?php echo e(route('logout')); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-outline-danger w-100">Logout</button>
                  </form>
                <?php else: ?>
                  <a href="<?php echo e(route('login')); ?>" class="btn btn-primary w-100 mb-2">Sign In</a>
                  <a href="<?php echo e(route('register')); ?>" class="btn btn-outline-primary w-100">Register</a>
                <?php endif; ?>
              </div>
            </div>
          </div>

          <!-- Wishlist -->
          <a href="<?php echo e(route('account', ['tab' => 'wishlist'])); ?>" class="header-action-btn d-none d-md-block">
            <i class="bi bi-heart"></i>
            <span class="badge">0</span>
          </a>

          <!-- Cart -->
          <a href="<?php echo e(route('cart')); ?>" class="header-action-btn">
            <i class="bi bi-cart3"></i>
            <span class="badge">3</span>
          </a>

          <!-- Mobile Navigation Toggle -->
          <i class="mobile-nav-toggle d-xl-none bi bi-list me-0"></i>

        </div>
      </div>
    </div>
  </div>

    <!-- Navigation -->
    <div class="header-nav">
        <div class="container-fluid container-xl position-relative">
        <nav id="navmenu" class="navmenu">
            <ul>
            <li><a href="<?php echo e(route('home')); ?>" class="active">Home</a></li>
            <li><a href="<?php echo e(route('category')); ?>">Category</a></li>
            <li><a href="<?php echo e(route('checkout')); ?>">Checkout</a></li>
            <li><a href="<?php echo e(route('about')); ?>">About</a></li>
            <li><a href="<?php echo e(route('cart')); ?>">Cart</a></li>
            <li><a href="<?php echo e(route('chat')); ?>">Chat</a></li>
            <?php if(auth()->check() && auth()->user()->membership_type === 'uitm'): ?>
                <li><a href="<?php echo e(route('listings')); ?>">Listing</a></li>
            <?php endif; ?>

            

            

            <li><a href="<?php echo e(route('contact')); ?>">Contact</a></li>

            <!-- Move Set Location far right -->
            <div class="nav-extra ms-auto">
                <a href="#" data-bs-toggle="modal" data-bs-target="#locationModal" class="nav-link">
                <i class="bi bi-geo-alt me-1"></i> Set Location
                </a>
            </div>

            </ul>            
        </nav>

        </div>
    </div>
</header>
<?php /**PATH C:\fyp\uitmarketplace\resources\views/components/header.blade.php ENDPATH**/ ?>