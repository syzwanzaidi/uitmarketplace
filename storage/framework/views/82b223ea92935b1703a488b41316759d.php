<?php $__env->startSection('content'); ?>

  <main class="main">

    <!-- Page Title -->
    <div class="page-title light-background">
      <div class="container d-lg-flex justify-content-between align-items-center">
        <h1 class="mb-2 mb-lg-0">Account</h1>
        <nav class="breadcrumbs">
          <ol>
            <li><a href="index.html">Home</a></li>
            <li class="current">Account</li>
          </ol>
        </nav>
      </div>
    </div><!-- End Page Title -->

    <!-- Account Section -->
    <section id="account" class="account section">

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <!-- Mobile Menu Toggle -->
        <div class="mobile-menu d-lg-none mb-4">
          <button class="mobile-menu-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#profileMenu">
            <i class="bi bi-grid"></i>
            <span>Menu</span>
          </button>
        </div>

        <div class="row g-4">
          <!-- Profile Menu -->
          <div class="col-lg-3">
            <div class="profile-menu d-lg-block" id="profileMenu">
              <!-- User Info -->
              <div class="user-info" data-aos="fade-right">
                <div class="user-avatar">
                  <img 
                      src="<?php echo e(auth()->user()->profile_photo 
                          ? asset('storage/' . auth()->user()->profile_photo)
                          : asset('assets/img/person/person-f-1.webp')); ?>"
                      alt="Profile"
                      loading="lazy"
                  >
                  <span class="status-badge"><i class="bi bi-shield-check"></i></span>
                </div>
                <h4><?php echo e(auth()->user()->name); ?></h4>
                <div class="user-status">
                  <i class="bi bi-award"></i>
                    <span>
                        <?php echo e(auth()->user()->membership_type === 'uitm' ? 'UiTM Member' : 'Non UiTM Member'); ?>

                    </span>
                </div>
              </div>

              <!-- Navigation Menu -->
              <nav class="menu-nav">
                <ul class="nav flex-column" role="tablist">
                  <li class="nav-item">
                      <a class="nav-link <?php echo e(request('tab') === 'orders' ? 'active' : ''); ?>" 
                        data-bs-toggle="tab" href="#orders">
                          <i class="bi bi-box-seam"></i>
                          <span>My Orders</span>
                      </a>
                  </li>

                  <li class="nav-item">
                      <a class="nav-link <?php echo e(request('tab') === 'wishlist' ? 'active' : ''); ?>" 
                        data-bs-toggle="tab" href="#wishlist">
                          <i class="bi bi-heart"></i>
                          <span>Wishlist</span>
                      </a>
                  </li>

                  <li class="nav-item">
                      <a class="nav-link <?php echo e(request('tab') === 'wallet' ? 'active' : ''); ?>" 
                        data-bs-toggle="tab" href="#wallet">
                          <i class="bi bi-wallet2"></i>
                          <span>Payment Methods</span>
                      </a>
                  </li>

                  <li class="nav-item">
                      <a class="nav-link <?php echo e(request('tab') === 'reviews' ? 'active' : ''); ?>" 
                        data-bs-toggle="tab" href="#reviews">
                          <i class="bi bi-star"></i>
                          <span>My Reviews</span>
                      </a>
                  </li>

                  <li class="nav-item">
                      <a class="nav-link <?php echo e(request('tab') === 'settings' ? 'active' : ''); ?>" 
                        data-bs-toggle="tab" href="#settings">
                          <i class="bi bi-gear"></i>
                          <span>Account Settings</span>
                      </a>
                  </li>
                </ul>

                <div class="menu-footer">
                  <a href="#" class="help-link">
                    <i class="bi bi-question-circle"></i>
                    <span>Help Center</span>
                  </a>
                  <a href="#" class="logout-link">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Log Out</span>
                  </a>
                </div>
              </nav>
            </div>
          </div>

          <!-- Content Area -->
          <div class="col-lg-9">
            <div class="content-area">
              <div class="tab-content">

                <?php if (isset($component)) { $__componentOriginal3215be63b39e035bd9aa8d7ae83732e3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3215be63b39e035bd9aa8d7ae83732e3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.account.order','data' => ['user' => auth()->user()]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('account.order'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['user' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(auth()->user())]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3215be63b39e035bd9aa8d7ae83732e3)): ?>
<?php $attributes = $__attributesOriginal3215be63b39e035bd9aa8d7ae83732e3; ?>
<?php unset($__attributesOriginal3215be63b39e035bd9aa8d7ae83732e3); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3215be63b39e035bd9aa8d7ae83732e3)): ?>
<?php $component = $__componentOriginal3215be63b39e035bd9aa8d7ae83732e3; ?>
<?php unset($__componentOriginal3215be63b39e035bd9aa8d7ae83732e3); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal5d188d44de8bce3289f99477457e4fcf = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5d188d44de8bce3289f99477457e4fcf = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.account.wishlist','data' => ['user' => auth()->user()]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('account.wishlist'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['user' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(auth()->user())]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5d188d44de8bce3289f99477457e4fcf)): ?>
<?php $attributes = $__attributesOriginal5d188d44de8bce3289f99477457e4fcf; ?>
<?php unset($__attributesOriginal5d188d44de8bce3289f99477457e4fcf); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5d188d44de8bce3289f99477457e4fcf)): ?>
<?php $component = $__componentOriginal5d188d44de8bce3289f99477457e4fcf; ?>
<?php unset($__componentOriginal5d188d44de8bce3289f99477457e4fcf); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginalb4780cbaac098cd3f0a86073fa61f16e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb4780cbaac098cd3f0a86073fa61f16e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.account.payment','data' => ['user' => auth()->user()]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('account.payment'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['user' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(auth()->user())]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb4780cbaac098cd3f0a86073fa61f16e)): ?>
<?php $attributes = $__attributesOriginalb4780cbaac098cd3f0a86073fa61f16e; ?>
<?php unset($__attributesOriginalb4780cbaac098cd3f0a86073fa61f16e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb4780cbaac098cd3f0a86073fa61f16e)): ?>
<?php $component = $__componentOriginalb4780cbaac098cd3f0a86073fa61f16e; ?>
<?php unset($__componentOriginalb4780cbaac098cd3f0a86073fa61f16e); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal89813a17ecc8d4c1f217ffe4834c5943 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal89813a17ecc8d4c1f217ffe4834c5943 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.account.review','data' => ['user' => auth()->user()]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('account.review'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['user' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(auth()->user())]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal89813a17ecc8d4c1f217ffe4834c5943)): ?>
<?php $attributes = $__attributesOriginal89813a17ecc8d4c1f217ffe4834c5943; ?>
<?php unset($__attributesOriginal89813a17ecc8d4c1f217ffe4834c5943); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal89813a17ecc8d4c1f217ffe4834c5943)): ?>
<?php $component = $__componentOriginal89813a17ecc8d4c1f217ffe4834c5943; ?>
<?php unset($__componentOriginal89813a17ecc8d4c1f217ffe4834c5943); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal273aec448e6b241871767399c92fee33 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal273aec448e6b241871767399c92fee33 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.account.personal-info','data' => ['user' => auth()->user()]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('account.personal-info'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['user' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(auth()->user())]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal273aec448e6b241871767399c92fee33)): ?>
<?php $attributes = $__attributesOriginal273aec448e6b241871767399c92fee33; ?>
<?php unset($__attributesOriginal273aec448e6b241871767399c92fee33); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal273aec448e6b241871767399c92fee33)): ?>
<?php $component = $__componentOriginal273aec448e6b241871767399c92fee33; ?>
<?php unset($__componentOriginal273aec448e6b241871767399c92fee33); ?>
<?php endif; ?>

              </div>
            </div>
          </div>
        </div>

      </div>

    </section><!-- /Account Section -->

  </main>

  <?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\fyp\uitmarketplace\resources\views/user/account.blade.php ENDPATH**/ ?>