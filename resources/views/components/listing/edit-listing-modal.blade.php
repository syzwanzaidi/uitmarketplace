<div class="modal fade" id="editModal{{ $product->id }}" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
          <form action="{{ route('listings.update', $product->id) }}" method="POST" enctype="multipart/form-data">
              @csrf
              @method('PATCH')

              <div class="modal-header">
                  <h5 class="modal-title">Edit Listing</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>

              <div class="modal-body">

                  <label class="form-label fw-semibold">Name</label>
                  <input type="text" name="name" class="form-control mb-3" value="{{ $product->name }}" required>

                  <label class="form-label fw-semibold">Category</label>
                  <select name="category_id" class="form-select mb-3" required>
                      @foreach($categories as $cat)
                          <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>
                              {{ $cat->name }}
                          </option>
                      @endforeach
                  </select>

                  <label class="form-label fw-semibold">Price</label>
                  <input type="number" name="price" class="form-control mb-3" value="{{ $product->price }}" required>

                  <label class="form-label fw-semibold">Location</label>
                  <input type="text" name="location" class="form-control mb-3" value="{{ $product->location }}" required>

                  <label class="form-label fw-semibold">Description</label>
                  <textarea name="description" class="form-control mb-3">{{ $product->description }}</textarea>

                  <label class="form-label fw-semibold">Image</label>
                  <input type="file" name="image" class="form-control mb-3">

              </div>

              <div class="modal-footer">
                  <button type="submit" class="btn btn-primary">Save Changes</button>
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              </div>

          </form>
      </div>
  </div>
</div>
