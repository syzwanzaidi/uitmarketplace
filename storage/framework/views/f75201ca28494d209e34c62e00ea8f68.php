<?php $__env->startSection('content'); ?>

<main class="main">

  <!-- Page Title -->
  <div class="page-title light-background">
    <div class="container d-lg-flex justify-content-between align-items-center">
      <h1 class="mb-2 mb-lg-0">Contact / Complaint</h1>
      <nav class="breadcrumbs">
        <ol>
          <li><a href="<?php echo e(route('home')); ?>">Home</a></li>
          <li class="current">Contact</li>
        </ol>
      </nav>
    </div>
  </div>
  <!-- End Page Title -->

  <!-- Complaint Section -->
  <section id="contact-2" class="contact-2 section">
    <div class="container" data-aos="fade-up" data-aos-delay="100">

      <div class="row justify-content-center">
        <div class="col-lg-10">

          <div class="contact-form-wrapper">
            <h2 class="text-center mb-4">Submit a Complaint</h2>


            <form method="POST" action="<?php echo e(route('contact.store')); ?>">
              <?php echo csrf_field(); ?>

              <div class="row g-3">

                <!-- SUBJECT -->
                <div class="col-md-12">
                  <div class="form-group">
                    <div class="input-with-icon">
                      <i class="bi bi-text-left"></i>
                      <input
                        type="text"
                        class="form-control"
                        name="subject"
                        placeholder="Complaint Subject"
                        value="<?php echo e(old('subject')); ?>"
                        required
                      >
                    </div>
                  </div>
                </div>

                <!-- MESSAGE -->
                <div class="col-12">
                  <div class="form-group">
                    <div class="input-with-icon">
                      <i class="bi bi-chat-dots message-icon"></i>
                      <textarea
                        class="form-control"
                        name="message"
                        placeholder="Describe your issue clearly..."
                        style="height: 180px"
                        required
                      ><?php echo e(old('message')); ?></textarea>
                    </div>
                  </div>
                </div>

                <!-- SUBMIT -->
                <div class="col-12 text-center">
                  <button type="submit" class="btn btn-primary btn-submit px-5">
                    SUBMIT
                  </button>
                </div>

              </div>
            </form>

          </div>
        </div>
      </div>

    </div>
  </section>
  <!-- End Complaint Section -->

</main>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\fyp\uitmarketplace\resources\views/user/contact.blade.php ENDPATH**/ ?>