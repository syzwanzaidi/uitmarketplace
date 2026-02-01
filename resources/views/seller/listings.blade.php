@extends('layouts.app')

@section('content')
<main class="main">

  <!-- Page Title -->
  <div class="page-title light-background">
    <div class="container d-lg-flex justify-content-between align-items-center">
      <h1 class="mb-2 mb-lg-0">My Listings</h1>
      <nav class="breadcrumbs">
        <ol>
          <li><a href="{{ route('home') }}">Home</a></li>
          <li class="current">Listings</li>
        </ol>
      </nav>
    </div>
  </div>

  <!-- Seller Dashboard Section -->
  <section id="seller-dashboard" class="account section">
    <div class="container">

      <div class="content-area">

        <div class="row g-4 mb-5">

          <div class="col-md-3 col-sm-6">
            <div class="dashboard-card">
              <i class="bi bi-currency-dollar"></i>
              <h4>Total Sales</h4>
              <p>RM {{ number_format($totalSales, 2) }}</p>
            </div>
          </div>

          <div class="col-md-3 col-sm-6">
            <div class="dashboard-card"
                style="cursor: pointer"
                onclick="window.location='{{ route('listings') }}'">
              <i class="bi bi-box-seam"></i>
              <h4>Active Listings</h4>
              <p>{{ $activeListingsCount }} Items</p>
            </div>
          </div>

          <div class="col-md-3 col-sm-6">
            <div class="dashboard-card"
                  style="cursor: pointer"
                  onclick="window.location='{{ route('chat') }}'">
              <i class="bi bi-chat-dots"></i>
              <h4>Messages</h4>
              <p>4 New</p>
            </div>
          </div>

          <div class="col-md-3 col-sm-6">
            <div class="dashboard-card"
                  style="cursor: pointer"
                  onclick="window.location='{{ route('seller.orders') }}'">
              <i class="bi bi-graph-up"></i>
              <h4>Pending Orders</h4>
              <p>{{ $pendingOrdersCount }}</p>
            </div>
          </div>

        </div>

        <!-- Add New Listing Button -->
        <div class="d-flex justify-content-between align-items-center mb-4">
          <a href="#" class="btn btn-header"
            data-bs-toggle="modal"
            data-bs-target="#addListingModal">
            <i class="bi bi-plus-lg"></i> Add New Listing
          </a>
        </div>

        <!-- Listings -->
        <div class="listing-items">

          @foreach($products as $product)
          <div class="listing-item mb-4 p-3 rounded shadow-sm">
            <div class="row align-items-center">

              <!-- Image + Title -->
              <div class="col-lg-6 col-12">
                <div class="product-info d-flex align-items-center">
                  <div class="product-image me-3">
                    <img src="{{ $product->image }}" class="img-fluid rounded" width="80">
                  </div>
                  <div class="product-details">
                    <h6 class="product-title mb-1">{{ $product->name }}</h6>
                    <p class="text-muted small mb-0">
                      Category: {{ $product->category->name }}
                    </p>
                  </div>
                </div>
              </div>

              <!-- Price -->
              <div class="col-lg-2 col-6 text-center">
                <span class="fw-bold text-dark">
                  RM {{ number_format($product->price, 2) }}
                </span>
              </div>

              <!-- Status -->
              <div class="col-lg-2 col-6 text-center">
                <form action="{{ route('listings.updateStatus', $product->id) }}" method="POST">
                  @csrf
                  @method('PATCH')
                  <select name="status"
                          class="form-select form-select-sm"
                          onchange="this.form.submit()">
                    <option value="active" {{ $product->status == 'active' ? 'selected' : '' }}>
                      Active
                    </option>
                    <option value="inactive" {{ $product->status == 'inactive' ? 'selected' : '' }}>
                      Inactive
                    </option>
                  </select>
                </form>
              </div>

              <!-- Edit + Delete (UNCHANGED) -->
              <div class="col-lg-2 col-12 text-center mt-3 mt-lg-0">
                
                <!-- Edit Button -->
                <button class="btn btn-sm btn-outline-secondary me-2"
                        data-bs-toggle="modal"
                        data-bs-target="#editModal{{ $product->id }}">
                  <i class="bi bi-pencil"></i>
                </button>

                <!-- Delete -->
                <form action="{{ route('listings.delete', $product->id) }}"
                      method="POST"
                      class="d-inline">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-sm btn-outline-danger"
                          onclick="return confirm('Delete this listing?')">
                    <i class="bi bi-trash"></i>
                  </button>
                </form>

              </div>

            </div>
          </div>

          <!-- Edit Modal -->
          @include('components.listing.edit-listing-modal')

          @endforeach

        </div>

      </div>
    </div>
  </section>
</main>

<!-- ================= ADD LISTING MODAL ================= -->

<div class="modal fade" id="addListingModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content p-3">

      <div class="modal-header">
        <h5 class="modal-title">Add New Listing</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <form action="{{ route('listings.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="modal-body">

          <input name="name" class="form-control mb-3" placeholder="Product name" required>

          <select name="category_id" class="form-select mb-3" required>
            <option disabled selected>Choose category</option>
            @foreach($categories as $category)
              <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
          </select>

          <textarea name="description" class="form-control mb-3"
                    rows="3" placeholder="Description"></textarea>

          <input type="number" name="price"
                 class="form-control mb-3"
                 placeholder="Price (RM)" required>

          <!-- Auto Location -->
          <input type="text"
                 id="add_location"
                 name="location"
                 class="form-control mb-3"
                 placeholder="Click on map to detect location"
                 readonly>

          <!-- Map -->
          <div id="addProductMap"
               style="height:300px;border-radius:8px"></div>

          <input type="hidden" id="add_latitude" name="latitude">
          <input type="hidden" id="add_longitude" name="longitude">

          <input type="file" name="image"
                 class="form-control mt-3"
                 accept="image/*" required>

        </div>

        <div class="modal-footer">
          <button type="button"
                  class="btn btn-secondary"
                  data-bs-dismiss="modal">Cancel</button>
          <button type="submit"
                  class="btn btn-primary">Confirm</button>
        </div>

      </form>
    </div>
  </div>
</div>

<!-- ================= MAP SCRIPTS ================= -->

<script>
document.addEventListener('DOMContentLoaded', function () {

  /* ========== ADD LISTING MAP ========== */
  let addMap, addMarker;

  document.getElementById('addListingModal')
    .addEventListener('shown.bs.modal', function () {

      if (!addMap) {
        addMap = L.map('addProductMap')
          .setView([3.0738, 101.5183], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
          attribution: '&copy; OpenStreetMap'
        }).addTo(addMap);

        addMap.on('click', async function (e) {
          const { lat, lng } = e.latlng;

          if (addMarker) addMap.removeLayer(addMarker);
          addMarker = L.marker([lat, lng]).addTo(addMap);

          document.getElementById('add_latitude').value = lat;
          document.getElementById('add_longitude').value = lng;

          const res = await fetch(
            `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`
          );
          const data = await res.json();

          document.getElementById('add_location').value =
            data.address.city ||
            data.address.town ||
            data.address.suburb ||
            data.address.state ||
            'Unknown';
        });
      }

      addMap.invalidateSize();
  });

  /* ========== EDIT MAPS ========== */
  @foreach($products as $product)

  let editMap{{ $product->id }}, editMarker{{ $product->id }};

  document.getElementById('editModal{{ $product->id }}')
    .addEventListener('shown.bs.modal', function () {

      if (!editMap{{ $product->id }}) {
        editMap{{ $product->id }} =
          L.map('editMap_{{ $product->id }}')
           .setView([3.0738, 101.5183], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
          attribution: '&copy; OpenStreetMap'
        }).addTo(editMap{{ $product->id }});

        editMap{{ $product->id }}.on('click', async function (e) {
          const { lat, lng } = e.latlng;

          if (editMarker{{ $product->id }})
            editMap{{ $product->id }}.removeLayer(editMarker{{ $product->id }});

          editMarker{{ $product->id }} =
            L.marker([lat, lng]).addTo(editMap{{ $product->id }});

          document.getElementById('edit_latitude_{{ $product->id }}').value = lat;
          document.getElementById('edit_longitude_{{ $product->id }}').value = lng;

          const res = await fetch(
            `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`
          );
          const data = await res.json();

          document.getElementById('edit_location_{{ $product->id }}').value =
            data.address.city ||
            data.address.town ||
            data.address.suburb ||
            data.address.state ||
            'Unknown';
        });
      }

      editMap{{ $product->id }}.invalidateSize();
  });

  @endforeach
});
</script>

@endsection
