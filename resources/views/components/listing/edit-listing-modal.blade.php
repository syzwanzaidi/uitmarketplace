<!-- Edit Listing Modal -->
<div class="modal fade" id="editModal{{ $product->id }}" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">

      <form action="{{ route('listings.update', $product->id) }}"
            method="POST" enctype="multipart/form-data">
        @csrf
        @method('PATCH')

        <div class="modal-header">
          <h5 class="modal-title">Edit Listing</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

          <!-- Name -->
          <div class="mb-3">
            <label class="form-label fw-semibold">Product Name</label>
            <input type="text" name="name"
                   class="form-control"
                   value="{{ $product->name }}" required>
          </div>

          <!-- Category -->
          <div class="mb-3">
            <label class="form-label fw-semibold">Category</label>
            <select name="category_id" class="form-select" required>
              @foreach($categories as $cat)
                <option value="{{ $cat->id }}"
                  {{ $product->category_id == $cat->id ? 'selected' : '' }}>
                  {{ $cat->name }}
                </option>
              @endforeach
            </select>
          </div>

          <!-- Price -->
          <div class="mb-3">
            <label class="form-label fw-semibold">Price (RM)</label>
            <input type="number" name="price"
                   class="form-control"
                   value="{{ $product->price }}" required>
          </div>

          <!-- Location (auto) -->
          <div class="mb-3">
            <label class="form-label fw-semibold">Location</label>
            <input type="text"
                   id="edit_location_{{ $product->id }}"
                   name="location"
                   class="form-control"
                   value="{{ $product->location }}"
                   readonly>
          </div>

          <!-- Map -->
          <div class="mb-3">
            <label class="form-label fw-semibold">Pin Location</label>
            <div id="editMap_{{ $product->id }}"
                 style="height:300px;border-radius:8px;"></div>
          </div>

          <!-- Hidden lat/lng -->
          <input type="hidden" name="latitude"
                 id="edit_latitude_{{ $product->id }}">
          <input type="hidden" name="longitude"
                 id="edit_longitude_{{ $product->id }}">

          <!-- Description -->
          <div class="mb-3">
            <label class="form-label fw-semibold">Description</label>
            <textarea name="description"
                      class="form-control">{{ $product->description }}</textarea>
          </div>

          <!-- Image -->
          <div class="mb-3">
            <label class="form-label fw-semibold">Image</label>
            <input type="file" name="image" class="form-control">
          </div>

        </div>

        <div class="modal-footer">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button class="btn btn-primary">Save Changes</button>
        </div>

      </form>
    </div>
  </div>
</div>
