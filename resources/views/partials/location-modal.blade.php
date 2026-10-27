@php($location = \App\Http\Controllers\LocationController::current())

<div class="modal fade" id="locationModal" tabindex="-1" aria-labelledby="locationModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form method="POST" action="{{ route('location.update') }}" data-location-form>
                @csrf
                <div class="modal-header">
                    <div>
                        <h2 class="modal-title" id="locationModalTitle">Where are you?</h2>
                        <p class="small text-muted mb-0">We'll show items near you, sorted by distance.</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex flex-wrap gap-2 align-items-center mb-3">
                        <button type="button" class="btn btn-light btn-sm" data-my-location>
                            <i class="bi bi-crosshair"></i> Use my current location
                        </button>
                        <span class="small text-muted">or tap anywhere on the map</span>
                    </div>

                    <div class="map-box tall mb-3" data-map></div>

                    <input type="hidden" name="lat" value="{{ $location['lat'] ?? '' }}">
                    <input type="hidden" name="lng" value="{{ $location['lng'] ?? '' }}">

                    <div class="row g-3 align-items-end">
                        <div class="col-md-6">
                            <label class="form-label" for="locArea">Area</label>
                            <input id="locArea" name="area" class="form-control" value="{{ $location['area'] ?? '' }}" placeholder="Pick a point on the map" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="locRadius">Search radius: <span data-radius-label>{{ $location['radius'] ?? 10 }}</span> km</label>
                            <input id="locRadius" type="range" name="radius" class="form-range" min="1" max="50" value="{{ $location['radius'] ?? 10 }}">
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    @if ($location)
                        <button type="submit" form="clearLocationForm" class="btn btn-ghost text-danger">
                            <i class="bi bi-x-circle"></i> Clear location
                        </button>
                    @else
                        <span></span>
                    @endif
                    <button type="submit" class="btn btn-primary">Show nearby items</button>
                </div>
            </form>
            <form method="POST" action="{{ route('location.clear') }}" id="clearLocationForm" class="d-none">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>
</div>
