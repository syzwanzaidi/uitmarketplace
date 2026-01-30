<header id="header" class="header sticky-top">

<?php
  $isAdmin = auth()->check() && auth()->user()->membership_type === 'admin';
?>

<!-- ================= ADMIN HEADER ================= -->
<?php if($isAdmin): ?>

  <div class="main-header">
    <div class="container-fluid container-xl">
      <div class="d-flex py-3 align-items-center justify-content-between">

        <!-- Logo -->
        <a href="<?php echo e(route('admin.dashboard')); ?>" class="logo d-flex align-items-center">
          <h1 class="sitename">UiTMarketplace Admin</h1>
        </a>

        <!-- Admin Actions -->
        <div class="d-flex align-items-center gap-3">

          <a href="<?php echo e(route('admin.dashboard')); ?>" class="btn btn-outline-primary">
            Dashboard
          </a>

          <form method="POST" action="<?php echo e(route('logout')); ?>">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn btn-outline-danger">
              Logout
            </button>
          </form>

        </div>

      </div>
    </div>
  </div>

<!-- ================= USER HEADER ================= -->
<?php else: ?>

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
                <?php endif; ?>
              </div>

              <div class="dropdown-body">
                <a class="dropdown-item" href="<?php echo e(route('account')); ?>">My Profile</a>
                <a class="dropdown-item" href="<?php echo e(route('buyer.orders')); ?>">My Orders</a>
                <a class="dropdown-item" href="<?php echo e(route('account', ['tab' => 'wishlist'])); ?>">My Wishlist</a>
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
          </a>

          <!-- Cart -->
          <a href="<?php echo e(route('cart')); ?>" class="header-action-btn">
            <i class="bi bi-cart3"></i>
          </a>

        </div>
      </div>
    </div>
  </div>

  <!-- Navigation -->
  <div class="header-nav">
    <div class="container-fluid container-xl">
      <nav class="navmenu">
        <ul>
          <li><a href="<?php echo e(route('home')); ?>">Home</a></li>
          <li><a href="<?php echo e(route('category')); ?>">Browse</a></li>
          <li><a href="<?php echo e(route('cart')); ?>">Cart</a></li>
          <li><a href="<?php echo e(route('chat')); ?>">Chat</a></li>
          <li><a href="<?php echo e(route('buyer.orders')); ?>">My Orders</a></li>

          <?php if(auth()->guard()->check()): ?>
            <?php if(auth()->user()->membership_type === 'uitm'): ?>
              <li><a href="<?php echo e(route('listings')); ?>">Listing</a></li>
            <?php endif; ?>
          <?php endif; ?>

          <li><a href="<?php echo e(route('contact')); ?>">Contact</a></li>

          <div class="nav-extra ms-auto">
            <a href="#"
              id="setLocationBtn"
              data-bs-toggle="modal"
              data-bs-target="#locationModal"
              class="nav-link"> 
              <i class="bi bi-geo-alt me-1"></i>
              <span id="currentLocationText">Set Location</span>
            </a>
          </div>
        </ul>
      </nav>
    </div>
  </div>

<?php endif; ?>

</header>
<?php /**PATH C:\fyp\uitmarketplace\resources\views/components/header.blade.php ENDPATH**/ ?>