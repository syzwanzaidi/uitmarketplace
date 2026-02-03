@extends('layouts.app')

@section('content')

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
                      src="{{ auth()->user()->profile_photo 
                          ? asset('storage/' . auth()->user()->profile_photo)
                          : asset('assets/img/person/person-f-1.webp') }}"
                      alt="Profile"
                      loading="lazy"
                  >
                  <span class="status-badge"><i class="bi bi-shield-check"></i></span>
                </div>
                <h4>{{ auth()->user()->name }}</h4>
                <div class="user-status">
                  <i class="bi bi-award"></i>
                    <span>
                        {{ auth()->user()->membership_type === 'uitm' ? 'UiTM Member' : 'Non UiTM Member' }}
                    </span>
                </div>
              </div>

              <!-- Navigation Menu -->
              <nav class="menu-nav">
                <ul class="nav flex-column" role="tablist">


                  <li class="nav-item">
                      <a class="nav-link {{ request('tab') === 'wishlist' ? 'active' : '' }}" 
                        data-bs-toggle="tab" href="#wishlist">
                          <i class="bi bi-heart"></i>
                          <span>Wishlist</span>
                      </a>
                  </li>

                  <li class="nav-item">
                      <a class="nav-link {{ request('tab') === 'settings' ? 'active' : '' }}" 
                        data-bs-toggle="tab" href="#settings">
                          <i class="bi bi-gear"></i>
                          <span>Account Settings</span>
                      </a>
                  </li>
                </ul>

                <div class="menu-footer">
                    <form method="POST" action="{{ route('logout') }}" class="logout-form">
                        @csrf
                        <a href="#" class="logout-link"
                          onclick="event.preventDefault(); this.closest('form').submit();">
                            <i class="bi bi-box-arrow-right"></i>
                            <span>Log Out</span>
                        </a>
                    </form>
                </div>

              </nav>
            </div>
          </div>

          <!-- Content Area -->
          <div class="col-lg-9">
            <div class="content-area">
              <div class="tab-content">

                <x-account.wishlist :user="auth()->user()" />
                <x-account.personal-info :user="auth()->user()" />

              </div>
            </div>
          </div>
        </div>

      </div>

    </section><!-- /Account Section -->

  </main>

  @endsection
