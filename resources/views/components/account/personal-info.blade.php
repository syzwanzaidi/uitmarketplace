                <!-- Settings Tab -->
                <div class="tab-pane fade {{ request('tab') === 'settings' ? 'show active' : '' }}" id="settings">
                  <div class="section-header" data-aos="fade-up">
                    <h2>Account Settings</h2>
                  </div>

                  <div class="settings-content">
                    <!-- Personal Information -->
                    <div class="settings-section" data-aos="fade-up">
                      <h3>Personal Information</h3>

                      <form action="{{ route('profile.update') }}" method="POST" class="php-email-form settings-form" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        <div class="row g-3">

                          <!-- First Name -->
                          <div class="col-md-12">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ auth()->user()->name }}" required>
                          </div>


                          <!-- Email -->
                          <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" value="{{ auth()->user()->email }}" disabled>
                          </div>

                          <!-- Phone -->
                          <div class="col-md-6">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="tel" class="form-control" id="phone" name="phone" value="{{ auth()->user()->phone }}" required>
                          </div>

                          <!-- Address Line 1 -->
                          <div class="col-md-12">
                            <label for="address1" class="form-label">Address Line 1</label>
                            <input type="text" class="form-control" id="address1" name="address1" placeholder="Street / House No." value="{{ auth()->user()->address1 }}" required>
                          </div>

                          <!-- Address Line 2 -->
                          <div class="col-md-12">
                            <label for="address2" class="form-label">Address Line 2 (Optional)</label>
                            <input type="text" class="form-control" id="address2" name="address2" placeholder="Apartment / Unit / Building" value="{{ auth()->user()->address2 }}">
                          </div>

                          <!-- Postcode -->
                          <div class="col-md-3">
                            <label for="postcode" class="form-label">Postcode</label>
                            <input type="text" class="form-control" id="postcode" name="postcode" value="{{ auth()->user()->postcode }}" required>
                          </div>

                          <!-- City -->
                          <div class="col-md-5">
                            <label for="city" class="form-label">City</label>
                            <input type="text" class="form-control" id="city" name="city" value="{{ auth()->user()->city }}" required>
                          </div>

                          <!-- State -->
                          <div class="col-md-4">
                            <label for="state" class="form-label">State</label>
                            <input type="text" class="form-control" id="state" name="state" value="{{ auth()->user()->state }}" required>
                          </div>

                          <!-- Country -->
                          <div class="col-md-12">
                            <label for="country" class="form-label">Country</label>
                            <input type="text" class="form-control" id="country" name="country" value="{{ auth()->user()->address1 ?? 'Malaysia' }}" required>
                          </div>

                          <div class="col-md-12 mb-3">
                            <label class="form-label">Profile Photo</label>
                            <input type="file" name="profile_photo" class="form-control">

                          </div>

                        </div>

                        <!-- Save -->
                        <div class="form-buttons">
                          <button type="submit" class="btn-save">Save Changes</button>
                        </div>

                        <div class="loading">Loading</div>
                        <div class="error-message"></div>
                        <div class="sent-message">Your changes have been saved successfully!</div>
                      </form>
                    </div>

                  </div>
                </div>